<?php
namespace xyz;

require_once __DIR__ . '/vendor/autoload.php';

$cleaningToolName = str_replace('_', ' ', 'CLEANING_TOOL_NAME');
$cleaningToolName = strtolower($cleaningToolName);
$cleaningToolName = ucfirst($cleaningToolName);

echo $cleaningToolName . PHP_EOL;
