<?php

$VideoCollection = new Phalcon\Mvc\Micro\Collection();
$VideoCollection->setHandler(new Rev\Controllers\VideoController());
$VideoCollection->setPrefix ('/videos');
$VideoCollection->get('/{id}','get');
$VideoCollection->post('/','create');
$VideoCollection->put('/{id}','update');
$VideoCollection->delete('/{id}','delete');
$VideoCollection->get('/','search');
$app->mount($VideoCollection);

$ProjectCollection = new Phalcon\Mvc\Micro\Collection();
$ProjectCollection->setHandler(new Rev\Controllers\ProjectController());
$ProjectCollection->setPrefix ('/projects');
$ProjectCollection->get('/{id}','get');
$ProjectCollection->post('/','create');
$ProjectCollection->put('/{id}','update');
$ProjectCollection->delete('/{id}','delete');
$ProjectCollection->get('/','search');
$app->mount($ProjectCollection);

$MakeCollection = new Phalcon\Mvc\Micro\Collection();
$MakeCollection->setHandler(new Rev\Controllers\MakeController());
$MakeCollection->setPrefix ('/makes');
$MakeCollection->get('/{id:[0-9]+}','get');
$MakeCollection->get('/','search');
$app->mount($MakeCollection);

$ModelCollection = new Phalcon\Mvc\Micro\Collection();
$ModelCollection->setHandler(new Rev\Controllers\ModelController());
$ModelCollection->setPrefix ('/models');
$ModelCollection->get('/{id}','get');
$ModelCollection->get('/','search');
$app->mount($ModelCollection);

$AutoCollection = new Phalcon\Mvc\Micro\Collection();
$AutoCollection->setHandler(new Rev\Controllers\AutoController());
$AutoCollection->setPrefix ('/autos');
$AutoCollection->get('/{id}','get');
$AutoCollection->post('/','create');
$AutoCollection->get('/','search');
$app->mount($AutoCollection);

$UploaderCollection = new Phalcon\Mvc\Micro\Collection();
$UploaderCollection->setHandler(new Rev\Controllers\UploaderController());
$UploaderCollection->setPrefix ('/uploaders');
$UploaderCollection->get('/{id}','get');
$UploaderCollection->post('/','create');
$UploaderCollection->get('/','search');
$app->mount($UploaderCollection);

$app->notFound(function () use ($app) {
	returnResponse($app, 404, null, 'Endpoint not found');
});