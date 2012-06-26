<?php
	interface IObserver{

	  public function notify( IObservable $objSource, $strEventType, $params );

	}
?>