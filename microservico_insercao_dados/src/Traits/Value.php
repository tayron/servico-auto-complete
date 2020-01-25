<?php
namespace Application\Traits;

use Application\Factory\ElasticFactory;

trait Value 
{	
    protected function getListSaleByStore()
    {
        global $app;			
        $params = json_decode($app->request->getBody(), true);
        $eventList = $params['events'];
        
        $listSaleByStore = [];
        foreach ($eventList as $event) {

            if ($event['event'] == 'comprou') {
                $idTransaction = $this->getProductTransactionId($event['custom_data']);
                $storeName = $this->getStoreName($event['custom_data']);

                $listSaleByStore[$idTransaction]['name'] = $storeName;
                $listSaleByStore[$idTransaction]['revenue'] = $event['revenue'];
                $listSaleByStore[$idTransaction]['date'] = $event['timestamp'];
            }				

            if ($event['event'] == 'comprou-produto') {
                $productName = $this->getProductName($event['custom_data']);
                $idTransaction = $this->getProductTransactionId($event['custom_data']);
                $productPrice = $this->getProductPrice($event['custom_data']);

                $listSaleByStore[$idTransaction]['products'][] = [
                    'name' => $productName,
                    'price' => $productPrice,
                    'date' => $event['timestamp']
                ];
            }
        }

        return $listSaleByStore;
    }

    protected function getStoreName($listData)
    {
        $name = '';
        foreach ($listData as $data)
        {
            if ($data['key'] == 'store_name') {
                $name = $data['value'];
            }
        }

        return $name;
    }

    protected function getProductName($listData)
    {
        $name = '';
        foreach ($listData as $data)
        {
            if ($data['key'] == 'product_name') {
                $name = $data['value'];
            }
        }

        return $name;
    }

    protected function getProductTransactionId($listData)
    {
        $name = '';
        foreach ($listData as $data)
        {
            if ($data['key'] == 'transaction_id') {
                $name = $data['value'];
            }
        }

        return $name;
    }
    
    protected function getProductPrice($listData)
    {
        $name = '';
        foreach ($listData as $data)
        {
            if ($data['key'] == 'product_price') {
                $name = $data['value'];
            }
        }

        return $name;
    }

    public function setDataToElasticsearch()
    {        
        $elasticsearch = new ElasticFactory('autocomplete');
        $elasticsearch->setType('sales');
        
        $data = [
            'name'=>'Waldemar Neto',
            'age'=>24,
            'email'=>'waldemarnt@outlook.com',
            'born'=>'1990/01/23'
        ];
        
        $elasticsearch->setData($data);
        $elasticsearch->create();        
    }
}