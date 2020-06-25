<?php

use Rev\Controllers\AutoController;
use Rev\Controllers\MakeController;
use Rev\Controllers\ModelController;
use Rev\Controllers\ProjectController;
use Rev\Controllers\TagController;
use Rev\Controllers\UploadController;
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
        'class' => TagController::class,
        'prefix' => '/',
        'methods' =>
            [
                'post' =>
                    [
                        'video-autos/{id:[0-9]+}/tags' =>'addToVideoAuto',
                    ],
                'delete' =>
                    [
                        'video-autos/{videoAutoId:[0-9]+}/tags' =>'deleteAllFromVideoAuto',
                        'video-autos/{videoAutoId:[0-9]+}/tags/{tagId:[0-9]+}' =>'deleteFromVideoAuto',
                    ],
            ],
    ],
    [
        'class' => UploadController::class,
        'prefix' => '/uploads',
        'methods' =>
            [
                'post' =>
                    [
                        '/' =>'create',
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
                'put' =>
                    [
                        '/{id:[0-9]+}' =>'update',
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
