<?php

namespace Application\Model;
use \PDO;

class AppModel
{
    protected $pdo;

    function __construct()
    {
        $this->pdo = new PDO('mysql:host=mysql;dbname=projeto', 'root', 'root');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAME'utf8'");

    }
}