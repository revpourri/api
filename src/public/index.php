<?php

use Phalcon\Config as Config;
use Phalcon\Http\Request;

require __DIR__ . '/../config/config.php';
$config = new Config($settings);
require __DIR__ . '/../config/loader.php';
require __DIR__ . '/../config/auth.php';

require_once __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] ?? null) {
    header("Content-Type: text/html; charset=ISO-8859-1");
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, DELETE');

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $http_origin = $_SERVER['HTTP_ORIGIN'];

        header("Access-Control-Allow-Origin: $http_origin");
    }

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }
}

$debug = new \Phalcon\Debug();
$debug->listen();

$di = new \Phalcon\DI\FactoryDefault();

//Set up the database service
$di->set(
    'db', function () use ($config) {
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
            echo json_encode(
                [
                'message' => 'Database error.'
                ]
            );
            exit;
        }

        return $q;
    }
);

$di->set('config', $config);

$app = new Phalcon\Mvc\Micro($di);

$di->set(
    'input', function () {
        $Request = new Phalcon\Http\Request();
        if ($input = $Request->getJsonRawBody(true)) {
            return $input;
        }

        return null;
    }
);

$app->before(
    function () use ($app, $whitelisted, $config) {
        $handler = $app->getActiveHandler();

        // If whitelisted, you may pass
        foreach ($whitelisted as $l) {
            if ($handler[0] instanceof $l['class']) {
                if (in_array($handler[1], $l['actions'])) {
                    return true;
                }
            }
        }

        if ($config->env == 'testing') {
            return true;
        }

        // Check for auth bearer
        $request = new Request();
        $headers = $request->getHeaders();

        if (isset($headers['Authorization'])) {
            if ($headers['Authorization'] == 'Bearer ' . $config->key) {
                return true;
            }
        }

        $app->response->setStatusCode(403)->setContentType("application/json")->sendHeaders();
        exit;
    }
);

$routes = include __DIR__ . "/../routes.php";

foreach ($routes ?? [] as $route) {
    $collection = new Phalcon\Mvc\Micro\Collection();
    $collection->setHandler($route['class'], true);
    $collection->setPrefix($route['prefix']);

    foreach ($route['methods'] as $verb => $methods) {
        foreach ($methods as $endpoint => $action) {
            $collection->$verb($endpoint, $action);
        }
    }

    $app->mount($collection);
}

$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, "Not Found")->setContentType("application/json")->sendHeaders();
    }
);

if (isset($_SERVER["REQUEST_URI"])) {
    $app->handle($_SERVER["REQUEST_URI"]);
} else {
    return $app;
}
