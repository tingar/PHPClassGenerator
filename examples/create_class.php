#!/usr/bin/php
<?

require_once '../src/ClassBuilderNode.php';
require_once '../src/ClassBuilderField.php';
require_once '../src/ClassBuilderMethod.php';
require_once '../src/ClassBuilder.php';

$cl = new ClassBuilder('myClass');

$m = new ClassBuilderMethod('doStuff');

$m->addArg('int', '$foo');

$cl->addMethod($m);

$f = new ClassBuilderField('$datum');
$cl->addField($f);

echo $cl;
