<?php
namespace xyz;

use sgk\back\UnitDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$coordinates = [1,2];
$unit = UnitDBAdaptor::create('Teapot', 1, json_encode($coordinates));
$unit = UnitDBAdaptor::read($unit->getId());
$unit->setName('Coffee Machine');
$unit = UnitDBAdaptor::update($unit);
//UnitDBAdaptor::delete($unit);
$allUnits = UnitDBAdaptor::readAll(1);

foreach ($allUnits as $unit) {
    var_dump($unit);
}

var_dump(UnitDBAdaptor::getUnitHealth(1));
