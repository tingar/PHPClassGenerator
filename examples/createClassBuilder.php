#!/usr/bin/php
<?

require_once '../src/ClassBuilderNode.php';
require_once '../src/ClassBuilderField.php';
require_once '../src/ClassBuilderMethod.php';
require_once '../src/ClassBuilder.php';

$cl = new ClassBuilder('ClassBuilder');

$c1->setPackage('PHPClassBuilder');
$cl->setComment('Builds a class, which can be exported as code with the {@link __toString()} function.');

$cl->setSuperClass('ClassBuilderNode');

$cl->addConstant('ACCESS_PUBLIC', 'public');
$cl->addConstant('ACCESS_PROTECTED', 'protected');
$cl->addConstant('ACCESS_PRIVATE', 'private');

$f = new ClassBuilderField('$_constants');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('array()');
$cl->addField($f);

$f = new ClassBuilderField('$_fields');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('array()');
$cl->addField($f);

$f = new ClassBuilderField('$_methods');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('array()');
$cl->addField($f);

$f = new ClassBuilderField('$_abstract');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('false');
$cl->addField($f);

$f = new ClassBuilderField('$_final');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('false');
$cl->addField($f);

$f = new ClassBuilderField('$_superclass');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('null');
$cl->addField($f);

$f = new ClassBuilderField('$_interfaces');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('array()');
$cl->addField($f);

$m = new ClassBuilderMethod('addConstant');
$m->addArg('string', '$name');
$m->addArg('string', '$value');
$m->setCode('$this->_constants[$name] = $value;');
$cl->addMethod($m);

$m = new ClassBuilderMethod('addField');
$m->addArg('ClassBuilderField', '$field');
$m->setCode('$this->_fields[$field->getName()] = $field;');
$cl->addMethod($m);

$m = new ClassBuilderMethod('addMethod');
$m->addArg('ClassBuilderMethod', '$method');
$m->setCode('$this->_methods[$method->getName()] = $method;');
$cl->addMethod($m);

$m = new ClassBuilderMethod('setPackage');
$m->addArg('string', '$package');
$m->setCode('$this->setPHPDoc(\'package\', $package);');
$cl->addMethod($m);

$m = new ClassBuilderMethod('setSuperClass');
$m->addArg('string', '$superclass');
$m->setCode('$this->_superclass = $superclass;');
$cl->addMethod($m);

// the code for the __toString method
$code = '$classSpec = $this->getComment();

if ($this->_abstract) {
	$classSpec .= \'abstract \';
}

$classSpec .= "class {$this->getName()}";
if ($this->_superclass) {
	$classSpec .= " extends {$this->_superclass}";
}
foreach($this->_interfaces as $interface) {
	$classSpec .= "\n\timplements $interface";
}

$classSpec .= ($this->_interfaces ? "\n" : " ")."{\n\n";

foreach($this->_constants as $name => $value) {
	if (!ctype_digit($value)) {
		$value = "\'{$value}\'";
	}
	$classSpec .= "\tconst {$name} = {$value};\n";
}
if ($this->_constants) $classSpec .= "\n";

foreach($this->_fields as $field) {
	foreach(explode("\n", $field->__toString()) as $line) {
		if ($line) {
			$classSpec .= "\t{$line}\n";
		} else {
			$classSpec .= "\n";
		}
	}
}

foreach($this->_methods as $method) {
	foreach(explode("\n", $method->__toString()) as $line) {
		if ($line) {
			$classSpec .= "\t{$line}\n";
		} else {
			$classSpec .= "\n";
		}
	}
}

return $classSpec . "}\n";';

$m = new ClassBuilderMethod('__toString');
$m->setType('string');
$m->setCode($code);
$cl->addMethod($m);

echo $cl;