<?php
namespace controllers;


class Compra{

	private $PDO;

	function __construct()
    {
		$this->PDO = new \PDO('mysql:host=localhost;dbname=api', 'root', ''); //Conexão
		$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
	}

	public function inserir()
    {
		global $app;
		$dados = json_decode($app->request->getBody(), true);
		$dados = (sizeof($dados)==0)? $_POST : $dados;
		$keys = array_keys($dados); //Paga as chaves do array
		// O uso de prepare e bindValue é importante para se evitar SQL Injection

		$sth = $this->PDO->prepare("INSERT INTO pessoa (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
		foreach ($dados as $key => $value) {
			$sth ->bindValue(':'.$key,$value);
		}
		$sth->execute();

		//Retorna o id inserido
		$app->render('default.php',["data"=>['id'=>$this->PDO->lastInsertId()]],200); 
	}
}

