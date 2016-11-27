<?php
namespace xyz;

use sgk\back\RefillableDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$refillable = RefillableDBAdaptor::create('Coffee Bean Jr', 1, 33, 100, 'COFFEE_BEANS');
$refillable = RefillableDBAdaptor::read($refillable->getId());
$refillable->setName('Coffee Bean Jar');
$refillable = RefillableDBAdaptor::update($refillable);
//RefillableDBAdaptor::delete($refillable);
$allRefillables = RefillableDBAdaptor::readAll(1);

foreach ($allRefillables as $refillable) {
    var_dump($refillable);
}
