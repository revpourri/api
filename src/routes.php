<?php

use Rev\Controllers\AutoController;
use Rev\Controllers\MakeController;
use Rev\Controllers\ModelController;
use Rev\Controllers\ProjectController;
use Rev\Controllers\UploaderController;
use Rev\Controllers\VideoController;

return [
    [
        'class' => AutoController::class,
        'prefix' => '/autos',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
                'post' =>
                    [
                        '/' =>'create',
                    ],
                'put' =>
                    [
                        '/{id:[0-9]+}' =>'update',
                    ],
                'delete' =>
                    [
                        '/{id:[0-9]+}' =>'delete',
                    ],
            ],
    ],
    [
        'class' => MakeController::class,
        'prefix' => '/makes',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
            ],
    ],
    [
        'class' => ModelController::class,
        'prefix' => '/models',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
            ],
    ],
    [
        'class' => ProjectController::class,
        'prefix' => '/projects',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
                'post' =>
                    [
                        '/' =>'create',
                    ],
                'put' =>
                    [
                        '/{id:[0-9]+}' =>'update',
                    ],
                'delete' =>
                    [
                        '/{id:[0-9]+}' =>'delete',
                    ],
            ],
    ],
    [
        'class' => UploaderController::class,
        'prefix' => '/uploaders',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
                'post' =>
                    [
                        '/' =>'create',
                    ],
            ],
    ],
    [
        'class' => VideoController::class,
        'prefix' => '/videos',
        'methods' =>
            [
                'get' =>
                    [
                        '/' =>'search',
                        '/{id:[0-9]+}' =>'get',
                    ],
                'post' =>
                    [
                        '/' =>'create',
                    ],
                'put' =>
                    [
                        '/{id:[0-9]+}' =>'update',
                    ],
                'delete' =>
                    [
                        '/{id:[0-9]+}' =>'delete',
                    ],
            ],
    ],
];
