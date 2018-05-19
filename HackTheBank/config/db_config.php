<?php

require_once __DIR__ . '/../db/MysqlDatabaseProvider.php';

$dbConfig = array(
    'provider' => new MysqlDatabaseProvider(),
    'host' => 'localhost',
    'database' => 'k0_secphp',
    'user' => 'k0_secphp',
    'password' => 'luneta1',
    'events' => array(
        'onConnect' => 'SET NAMES gbk'
    )
);