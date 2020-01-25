<?php

namespace Application\Model;

class TimeLineModel extends AppModel
{
    public function getTimeline()
    {        
        $sql = "SELECT * from sales INNER JOIN products ON (products.sales_id = sales.id)  "
            . "order by sales.date DESC";

        $sth = $this->pdo->query($sql);
        $listData = $sth->fetchAll();

        $listaTimeline = [];
        foreach ($listData as $data) {                

            $id = $data['transaction_id'];

            $listaTimeline[$id]["timestamp" ] = $data['date'];
            $listaTimeline[$id]["revenue"] = $data['revenue'];
            $listaTimeline[$id]["transaction_id"] = $id;
            $listaTimeline[$id]["store_name"] = $data['store_name'];

            $listaTimeline[$id]['products'][] = [
                'name' => $data['name'],
                'price' => $data['price']
            ];      
        }

        return $listaTimeline;
    }
}