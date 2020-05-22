<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs([
    $config->application->controllersDir,
    $config->application->pluginsDir,
    $config->application->libraryDir,
    $config->application->modelsDir
]);

$loader->registerNamespaces(
    [
        'Rev\Controllers' => './controllers/',
        'Rev\Models'      => './models/',
    ]
);

$loader->register();
