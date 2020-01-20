<?php

namespace Application\Model;

class CompraModel
{
    private $pdo;

    function __construct()
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=api', 'root', '');
        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION );
    }

    public function inserir()
    {
        $sth = $this->PDO->prepare("INSERT INTO compras (campo1, campo2) VALUES (:campo1, :campo2");
        $sth ->bindValue('campo1', $this->campo1);
        $sth ->bindValue('campo2', $this->campo2);        

        $sth->execute();

        //Retorna o id inserido
        return $this->PDO->lastInsertId(); 
    }
}