<?php

$loader = require 'vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/.env');

$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

$app->any('/', function() use ($app){
    $app->render( 'default.php', [
        "message" => 'API ativa'
    ], 200);	
});

$app->post('/sales/', function() use ($app){
	(new Application\Controller\SalesController($app))->insert();
});

$app->get('/sales/timeline', function() use ($app){
	(new Application\Controller\TimelineController($app))->get();
});

$app->get('/sales/sincronizacao', function() use ($app){
	(new Application\Controller\SincrionizacaoController($app))->sincronizar();
});

$app->run();