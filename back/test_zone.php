<?php
namespace xyz;

use sgk\back\ZoneDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$coordinates = [
    [1, 2],
    [3, 4],
    [5, 6]
];
$zone = ZoneDBAdaptor::create('Storage', 'SERVICE', json_encode($coordinates));
$zone = ZoneDBAdaptor::read($zone->getId());
$zone->setName('StorageX');
$zone = ZoneDBAdaptor::update($zone);
ZoneDBAdaptor::delete($zone);
$allZones = ZoneDBAdaptor::readAll();

foreach ($allZones as $zone) {
    var_dump($zone);
}
