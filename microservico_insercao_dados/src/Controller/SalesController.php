<?php
namespace Application\Controller;

use Exception;
use Application\Exceptions\InvalidDataException;
use Application\Exceptions\InsertDataException;
use Application\Model\ProductModel;
use Application\Model\SaleModel;
use Application\Traits\Value;
use DateTime;

class SalesController
{
	use Value;

	public function insert()
    {
		$this->setDataToElasticsearch();
		
		$saleId = null;

		try {
			global $app;						
			$listSaleByStore = $this->getListSaleByStore();
			
			foreach ($listSaleByStore as $transactionId => $store) {				
				$storeName = $store['name'];
				$revenue = $store['revenue'];
				$saleDate = new DateTime($store['date']);
	
				$saleId = $this->insertStoreSale($transactionId, $storeName, $revenue, $saleDate);
	
				foreach ($store['products'] as $product) {
					$productName = $product['name'];
					$productPrice = $product['price'];
					$productDate = new DateTime($product['date']);
		
					$this->insertProductSale($saleId, $productName, $productPrice, $productDate);
				}
			}
			
			$app->render( 'default.php', [
				"message" => 'success'
			], 200);

		} catch (InvalidDataException | InsertDataException | DeleteDataException $ex) {
			// Possível problema com fluxo normal do Microserviço.
			// Pode indicar que formato dos dados enviados na requisição está fora do padrão.
			// Pode implementar qual tipo de erro avisar setor de TI ou outros interessados.
			$this->removeStorageSaleCreated($saleId);			

			$app->render( 'default.php', [
				"message" => $ex->getMessage()
			], 400); 

		} catch (Exception $ex) {
			$this->removeStorageSaleCreated($saleId);			

			$app->render( 'default.php', [
				"message" => $ex->getMessage()
			], 400); 
		}
	}

	private function insertStoreSale($transactionId, $storeName, $revenue, $saleDate)
	{
		$saleModel = new SaleModel();
		$saleModel->setTransactionId($transactionId);
		$saleModel->setStoreName($storeName);
		$saleModel->setRevenue($revenue);
		$saleModel->setDate($saleDate);
		$saleModel->create();

		return $saleModel->getId();
	}

	private function insertProductSale($saleId, $productName, $productPrice, $productDate)
	{
		$productModel = new ProductModel();
		$productModel->setSaleId($saleId);
		$productModel->setName($productName);
		$productModel->setPrice($productPrice);
		$productModel->setDate($productDate);
		$productModel->create();
	}

	private function removeStorageSaleCreated($saleId)
	{
		if ($saleId) {
			$saleModel = new SaleModel();
			$saleModel->delete($saleId);
		}
	}
}

