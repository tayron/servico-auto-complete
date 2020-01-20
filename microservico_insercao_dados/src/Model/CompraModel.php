<?php

namespace Application\Model;

use Application\Exceptions\FalhaAoInsetirDadosException;
use Exception;

class CompraModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new \PDO('mysql:host=mysql_escrita;dbname=compras', 'root', 'root');
        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION );
    }

    public function inserir()
    {
        try {
            $sth = $this->pdo->prepare("INSERT INTO compras (campo1, campo2) VALUES (:campo1, :campo2");
            $sth ->bindValue('campo1', $this->campo1);
            $sth ->bindValue('campo2', $this->campo2);        
    
            $sth->execute();
    
            //Retorna o id inserido
            return $this->PDO->lastInsertId(); 
        } catch (Exception $ex) {
            Throw new FalhaAoInsetirDadosException($ex->getMessage());
        }
    }
}