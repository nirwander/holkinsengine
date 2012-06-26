<?php
	class ctrlVote extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){
			
			$v = mVote::getInstance();
			if ( $v->rateUp( ) ){
				$res = 1;
			}else{
				$res = 0;
			}
			Response::setContent( $this->_view->compose( $res ) );
			return true;
		}
	}
?>