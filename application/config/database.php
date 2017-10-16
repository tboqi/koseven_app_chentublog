<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
    'default' => array(
        'type' => 'PDO',
        'connection' => array(
            'dsn' => 'mysql:host=10.0.75.1;dbname=chentublog',
            'username' => 'root',
            'password' => 'root',
            'persistent' => FALSE,
        ),
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => FALSE,
    ),
);
