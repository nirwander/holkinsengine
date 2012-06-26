<?php
	class ctrlWannaNewMsgs extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){
			
			$gb = mGuestBook::getInstance();
			
			$newMsgs = $gb->getSince( array( "n", "nick", "email", "date", "msg", "admin_answer", "admin_answer_date" ),
									  Request::getVarAsInteger( "lastN", "POST" ) );
			
			Response::setContent( $this->_view->compose( $newMsgs ) );
			return true;
		}
	}
?>