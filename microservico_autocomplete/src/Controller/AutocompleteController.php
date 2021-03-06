<?php
namespace Application\Controller;

use Exception;
use Application\Factory\ElasticFactory;

class AutocompleteController
{	
	public function get()
    {
		$saleId = null;

		try {
			global $app;			
			$event = $app->request()->get('event');			

			if (strlen($event) < 2 ) {
				return $app->render( 'default.php', ["message" => []], 200);
			}
			
			$elasticsearch = new ElasticFactory('autocomplete');			
			$elasticsearch->setType('sales');
			$elasticsearch->setSize(100);

			$matchQuery=[
				'wildcard'=> [
					'event.keyword'=> "$event*"
				]
			];

			$matchData = $elasticsearch->find($matchQuery);

			$listData = [];		
			if(isset($matchData['hits']['hits'])){
				foreach ($matchData['hits']['hits'] as $key => $hit) {
					array_push($listData, $hit['_source']);
				}
			}
			
			return $app->render( 'default.php', [
				"message" => $listData
			], 200);

		} catch (Exception $ex) {

			return $app->render( 'default.php', [
				"message" => $ex->getMessage()
			], 400); 
		}
	}

}