<?php

use Phalcon\Config as Config;

require __DIR__ . '/../config/config.php';
$config = new Config($settings);
require __DIR__ . '/../config/loader.php';

require_once __DIR__ . '/../../vendor/autoload.php';

header("Content-Type: text/html; charset=ISO-8859-1");
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, access-token');
header('Access-Control-Allow-Methods: GET, POST, DELETE');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    $http_origin = $_SERVER['HTTP_ORIGIN'];

    header("Access-Control-Allow-Origin: $http_origin");
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$debug = new \Phalcon\Debug();
$debug->listen();

$di = new \Phalcon\DI\FactoryDefault();

//Set up the database service
$di->set('db', function () use ($config) {
    $conn = array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->database,
        "options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        )
    );

    try {
        $q = new \Phalcon\Db\Adapter\Pdo\Mysql($conn);
    } catch (PDOException $e) {
        echo json_encode([
            'message' => 'Database error.'
        ]);
        exit;
    }

    return $q;
});

$di->set('config', $config);

$app = new Phalcon\Mvc\Micro($di);

$di->set('input', function () {
    $Request = new Phalcon\Http\Request();
    if ($input = $Request->getJsonRawBody(true)) {
        return $input;
    }

    return null;
});

require __DIR__ . "/../routes.php";

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->setContentType("application/json")->sendHeaders();
    echo json_encode([
        'message' => 'Endpoint not found.'
    ]);
});

$app->handle();
