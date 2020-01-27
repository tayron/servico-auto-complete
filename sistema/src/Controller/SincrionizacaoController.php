<?php
namespace Application\Controller;

use Exception;

class SincrionizacaoController
{
	public function sincronizar()
    {			
		try {
			$url = 'https://storage.googleapis.com/dito-questions/events.json';
			$urlApi = 'http://localhost/sales';

			$conteudo = file_get_contents($url);

			$cr = curl_init();
			curl_setopt($cr, CURLOPT_URL, $urlApi);
			curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cr, CURLOPT_POST, TRUE);			
			curl_setopt($cr, CURLOPT_POSTFIELDS, $conteudo);

			$retorno = curl_exec($cr);			
			curl_close($cr);			
			
			echo $retorno;

		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}
}