<?php
namespace xyz;

use sgk\back\UserDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$user = UserDBAdaptor::create('Jackie Chan', 'EMPLOYEE', 10, 30);
$user = UserDBAdaptor::read($user->getId());
$user->setName('Leonardo Di Caprio');
$user = UserDBAdaptor::update($user);
UserDBAdaptor::delete($user);
$allUsers = UserDBAdaptor::readAll();

foreach ($allUsers as $user) {
    var_dump($user);
}
