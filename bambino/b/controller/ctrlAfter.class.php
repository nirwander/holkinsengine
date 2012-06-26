<?php
	class ctrlAfter extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){

			$params = array();
			$params[ "title" ] = array( array( "title" => "Kirillka-after-party!" ) );
			
			$gb = mGuestBook::getInstance();			
			$params[ "guestBookSite" ] = $gb->getPage( array( "n", "nick", "email", "date", "msg", "admin_answer", "admin_answer_date" ), 1 );
			
			$c = $this->_view->compose( $params );

			Response::setContent( $c );
			return true;
		}
	}
?>