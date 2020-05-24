<?php

use Rev\Controllers\AutoController;
use Rev\Controllers\MakeController;
use Rev\Controllers\ModelController;
use Rev\Controllers\ProjectController;
use Rev\Controllers\UploaderController;
use Rev\Controllers\VideoController;

$whitelisted = [
    [
        'class' => AutoController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
    [
        'class' => MakeController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
    [
        'class' => ModelController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
    [
        'class' => ProjectController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
    [
        'class' => UploaderController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
    [
        'class' => VideoController::class,
        'actions' => [
            'get',
            'search',
        ],
    ],
];