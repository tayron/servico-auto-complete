<?php
//Autoload
$loader = require 'vendor/autoload.php';

//Instanciando objeto
$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

//nova pessoa
$app->post('/compras/', function() use ($app){
	(new \controllers\Compra($app))->inserir();
});

//Rodando aplicação
$app->run();
