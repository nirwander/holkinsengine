<?php
	class ctrlIdentity extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){

			$params = array();
			$params[ "title" ] = array( array( "title" => "Привет!" ) );
			
			$party = mParty::getInstance();
			
			$res = $party->chooseCharacter( Request::getVarAsInteger( "guest", "POST" ),
											Request::getVarAsInteger( "id", "POST" ),
											Request::getVarAsInteger( "character", "POST" ) );
			
			$c = $this->_view->compose( $res );

			Response::setContent( $c );
			return true;
		}
	}
?>