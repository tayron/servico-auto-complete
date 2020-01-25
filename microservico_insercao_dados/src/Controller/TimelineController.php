<?php
namespace Application\Controller;

use Application\Model\ProductModel;
use Application\Model\SaleModel;
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

			return $app->render( 'default.php', [
				"message" => $listaTimeline
			], 200);

		} catch (Exception $ex) {
			$app->render( 'default.php', [
				"message" => $ex->getMessage()
			], 400); 
		}
	}
}