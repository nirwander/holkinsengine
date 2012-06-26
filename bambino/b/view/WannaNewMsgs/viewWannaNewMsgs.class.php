<?php
	class viewWannaNewMsgs extends viewBase{
		public function compose( $msgs ){
			if ( is_array( $msgs ) ){
				$this->_params[ "guestBookSite" ] = $msgs;
				$res = (string)$this->_compileComponent( "guestBookSite" );
				return "{ err_code : 0, txt : '$res' }";				
			}else return "{ err_code : -1 }";
		}
	}
?>