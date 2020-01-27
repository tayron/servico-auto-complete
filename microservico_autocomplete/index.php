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

$app->get('/autocomplete/', function() use ($app){
	(new Application\Controller\AutocompleteController($app))->get();
});

$app->run();