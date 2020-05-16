<?php

$settings = [
    "env" => getenv('ENV') ?: 'development',
    "app" => [
        "name" => getenv('APP_NAME') ?: 'Revpourri',
        "method" => getenv('APP_URL_METHOD') ?: 'http',
        "api" => getenv('APP_URL_HOST') ?: 'api.revpourri.local',
    ],
    "application" => [
        "controllersDir" => __DIR__ . "/../../controllers/",
        "modelsDir"      => __DIR__ . "/../../models/",
        "viewsDir"       => __DIR__ . "/../../views/",
        "pluginsDir"     => __DIR__ . "/../../plugins/",
        "libraryDir"     => __DIR__ . "/../../library/",
        "baseUri"        => __DIR__ . "/../../",
    ],
    "database" => [
        "adapter"  => "Mysql",
        "host"     => getenv('DB_HOST') ?: 'db',
        "username" => getenv('DB_USERNAME') ?: 'root',
        "password" => getenv('DB_PASS') ?: 'pass',
        "database"   => getenv('DB_DATABASE') ?: 'rev_dev',
    ],
];
