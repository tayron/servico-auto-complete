<?php

namespace Application\Model;

use Application\Exceptions\InsertDataException;
use Application\Exceptions\InvalidDataException;
use Application\Exceptions\DeleteDataException;

use Exception;
use DateTime;

class SaleModel extends AppModel
{
    private $id;
    private $transactionId;
    private $storeName;
    private $date;
    private $revenue = 0.00;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }    

    public function setTransactionId($transactionId)
    {
        if (!$transactionId) {
            throw new InvalidDataException('ID da transação deve ser informado');
        }

        $this->transactionId = $transactionId;
    }
    
    public function setStoreName($storeName)
    {
        if (!$storeName) {
            throw new InvalidDataException('Nome da loja deve ser informado');
        }

        $this->storeName = $storeName;
    }

    public function setDate(DateTime $date)
    {
        if (!$date) {
            throw new InvalidDataException('Data da compra na loja deve ser informado');
        }

        $this->date = $date;
    }    

    public function setRevenue(float $revenue)
    {
        if (!$revenue) {
            throw new InvalidDataException('Valor da compra na loja deve ser informado');
        }

        $this->revenue = $revenue;
    } 

    public function create()
    {
        try {
            $sql = "INSERT INTO sales (transaction_id, store_name, date, revenue) VALUES "
                . "(:transaction_id, :store_name, :date, :revenue)";

            $sth = $this->pdo->prepare($sql);
            $sth ->bindValue('transaction_id', $this->transactionId);
            $sth ->bindValue('store_name', $this->storeName);
            $sth ->bindValue('date', $this->date->format('Y-m-d H:i:s'));
            $sth ->bindValue('revenue', $this->revenue);
    
            if( !$sth->execute() ) {
                throw new InsertDataException('Não foi possível inserir os dados da compra na loja ' . $this->storeName);
            }
    
            $this->setId($this->pdo->lastInsertId());
            return $this;
        } catch (Exception $ex) {
            Throw new InsertDataException($ex->getMessage());
        }
    }

    public function delete($id)
    {
        $idStore = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        
        if (!$idStore) {
            throw new InvalidDataException('Id da compra da loja não foi informao');
        }

        $sql = "DELETE FROM sales WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth ->bindValue('id', $idStore);

        if( !$sth->execute() ) {
            throw new DeleteDataException('Não foi possível excluir os dados da compra na loja de id ' . $this->id);
        }        
    }
}