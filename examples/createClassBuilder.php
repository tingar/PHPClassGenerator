#!/usr/bin/php
<?

require_once '../src/ClassBuilderNode.php';
require_once '../src/ClassBuilderField.php';
require_once '../src/ClassBuilderMethod.php';
require_once '../src/ClassBuilder.php';

$cl = new ClassBuilder('ClassBuilder');

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
$f->setDefault('false');
$cl->addField($f);

$f = new ClassBuilderField('$_interfaces');
$f->setAccess(ClassBuilder::ACCESS_PROTECTED);
$f->setDefault('false');
$cl->addField($f);

$m = new ClassBuilderMethod('addField');
$m->addArg('ClassBuilderField', '$field');
$cl->addMethod($m);

$m = new ClassBuilderMethod('addMethod');
$m->addArg('ClassBuilderMethod', '$method');

echo $cl;