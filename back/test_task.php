<?php
namespace xyz;

use sgk\back\TaskDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$task = TaskDBAdaptor::create(3, 1, 3, 'CLEAN', 'PENDING_APPROVAL');
$task = TaskDBAdaptor::read($task->getId());
$task->setStatus('APPROVED');
$task = TaskDBAdaptor::update($task);
TaskDBAdaptor::delete($task);
$allTasks = TaskDBAdaptor::readAll();

foreach ($allTasks as $task) {
    var_dump($task);
}
