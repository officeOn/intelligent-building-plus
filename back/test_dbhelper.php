<?php
namespace xyz;

use sgk\back\DBHelper;

require_once __DIR__ . '/vendor/autoload.php';

var_dump(DBHelper::query('SELECT * FROM Zone;')->fetch());
