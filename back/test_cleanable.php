<?php
namespace xyz;

use sgk\back\CleanableDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

//$cleanable = CleanableDBAdaptor::create('Teapot', 1, 60);
$cleanable = CleanableDBAdaptor::read(1);
$cleanable->setName('Tolchok');
$cleanable = CleanableDBAdaptor::update($cleanable);
//CleanableDBAdaptor::delete($cleanable);
$allCleanables = CleanableDBAdaptor::readAll(1);

foreach ($allCleanables as $cleanable) {
    var_dump($cleanable);
}
