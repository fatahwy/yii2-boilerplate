<?php

$env = require __DIR__ . '/env.php';

return [
    'class' => 'yii\db\Connection',
    'charset' => 'utf8',

    'dsn' => 'mysql:host=' . $env['db_host'] . ';dbname=' . $env['db_name'],
    'username' => $env['db_username'],
    'password' => $env['db_password'],

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
