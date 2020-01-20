<?php
namespace Application\Controller;

use Exception;
use Application\Controller\Model\CompraModel;

class Compra{

	public function inserir()
    {
		try {
			global $app;
			$dados = json_decode($app->request->getBody(), true);
			$parametrosAPi = (sizeof($dados)==0)? $_POST : $dados;
	
			$compraModel = new CompraModel();
			$compraModel->setCampo1($parametrosAPi['campo1']);
			$compraModel->setCampo2($parametrosAPi['campo2']);
			$idRegistroGerado = $this->compraModel->inserir();
			
			$app->render( 'default.php', [
				"data" => ['id' => $idRegistroGerado]
			], 200); 
		} catc (Exception $ex) {
			$app->render( 'default.php', [
				"mensagem" => $ex->getMessage()
			], 400); 
		}

	}
}

