<?php
//Autoload
$loader = require 'vendor/autoload.php';

//Instanciando objeto
$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

//nova compra
$app->get('/compras/', function() use ($app){
	(new Application\Controller\CompraController($app))->inserir();
});

//Rodando aplicação
$app->run();