<?php
namespace Application\Controller;

use Exception;
use Application\Exceptions\FalhaAoInsetirDadosException;
use Application\Model\CompraModel;

class CompraController{

	public function inserir()
    {
		try {
			global $app;			
			$parametrosAPi = json_decode($app->request->getBody(), true);
	
			$compraModel = new CompraModel();
			$compraModel->setCampo1($parametrosAPi['campo1']);
			$compraModel->setCampo2($parametrosAPi['campo2']);
			$idRegistroGerado = $this->compraModel->inserir();
			
			$app->render( 'default.php', [
				"data" => ['id' => $idRegistroGerado]
			], 200); 
			
		} catch(FalhaAoInsetirDadosException $ex) {
			$app->render( 'default.php', [
				"mensagem" => $ex->getMessage()
			], 400); 

		} catch (Exception $ex) {
			$app->render( 'default.php', [
				"mensagem" => $ex->getMessage()
			], 400); 
		}

	}
}

