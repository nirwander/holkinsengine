<?php
	class ctrlAddMsg extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){
			
			/*$resp = reCaptcha::recaptcha_check_answer ( Request::getVar( "recaptcha_challenge_field", "POST" ),
														Request::getVar( "recaptcha_response_field", "POST" ) );*/
			$gb = mGuestBook::getInstance();			
			if ( $gb->add() ){
				//$res = 0;				
				$res = $gb->getPage( array( "nick", "email", "date", "msg", "admin_answer", "admin_answer_date" ), 1 );

			}else{
				$_SESSION[ "gb.msg.add" ] = $gb->getLastError(  );
				$res = -1;
			}			
			
			Response::setContent( $this->_view->compose( $res ) );
			return true;
		}
	}
?>