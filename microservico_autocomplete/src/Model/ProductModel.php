<?php

namespace Application\Model;

use Application\Exceptions\InsertDataException;
use Application\Exceptions\InvalidDataException;
use Exception;
use DateTime;

class ProductModel extends AppModel
{
    private $id;
    private $saleId;
    private $name;
    private $date;
    private $price = 0.00;

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setSaleId($saleId)
    {
        if (!$saleId) {
            throw new InvalidDataException('Id da compra deve ser informado');
        }

        $this->saleId = $saleId;
    }

    public function setName($name)
    {
        if (!$name) {
            throw new InvalidDataException('Nome do produto deve ser informado');
        }

        $this->name = $name;
    }

    public function setDate(DateTime $date)
    {
        if (!$date) {
            throw new InvalidDataException('Data da compra do produto deve ser informado');
        }

        $this->date = $date;
    }    

    public function setPrice(float $price)
    {
        if (!$price) {
            throw new InvalidDataException('Valor da compra do produto deve ser informado');
        }

        $this->price = $price;
    } 

    public function create()
    {
        try {
            $sql = "INSERT INTO products (sales_id, name, price, date) VALUES "
                . "(:sales_id, :name, :price, :date)";

            $sth = $this->pdo->prepare($sql);
            $sth ->bindValue('sales_id', $this->saleId);
            $sth ->bindValue('name', $this->name);
            $sth ->bindValue('price', $this->price);
            $sth ->bindValue('date', $this->date->format('Y-m-d H:i:s'));            
    
            if( !$sth->execute() ) {
                throw new InsertDataException('Não foi possível inserir os dados da compra do produto ' . $this->name);
            }
    
            $this->setId($this->pdo->lastInsertId());
            return $this;
        } catch (Exception $ex) {
            Throw new InsertDataException($ex->getMessage());
        }
    }
}