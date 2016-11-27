<?php
namespace xyz;

use sgk\back\CleanableCleaningToolDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$cleanableCleaningTool = CleanableCleaningToolDBAdaptor::create(1, 'CUCUMBER');
$cleanableCleaningTool = CleanableCleaningToolDBAdaptor::read($cleanableCleaningTool->getId());
CleanableCleaningToolDBAdaptor::delete($cleanableCleaningTool);
$allCleanableCleaningTools = CleanableCleaningToolDBAdaptor::readAll(1);

foreach ($allCleanableCleaningTools as $cleanableCleaningTool) {
    var_dump($cleanableCleaningTool);
}
