<?php

$loader = require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

$app->any('/', function() use ($app){
    $app->render( 'default.php', [
        "message" => 'API ativa'
    ], 200);	
});

$app->any('/sales/', function() use ($app){
	(new Application\Controller\SalesController($app))->insert();
});

$app->run();