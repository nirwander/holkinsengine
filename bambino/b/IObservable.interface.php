<?php
	interface IObservable{

	  public function addObserver( IObserver $objObserver, $strEventType );

	  public function fireEvent( $strEventType, $params );

	}
?>