<?php
namespace Application\Controller;

use Application\Model\TimeLineModel;
use Exception;

class TimelineController
{
	public function get()
    {			
		try {
			global $app;
			
			$timelineModel = new TimeLineModel();
			$listaTimeline = $timelineModel->getTimeline();

			$listaRetorno['timeline'] = [];

			foreach( $listaTimeline  as $dado) {
				array_push($listaRetorno['timeline'], $dado);
			}

			return $app->render( 'default.php', [
				"message" => $listaRetorno
			], 200);

		} catch (Exception $ex) {
			$app->render( 'default.php', [
				"message" => $ex->getMessage()
			], 400); 
		}
	}
}