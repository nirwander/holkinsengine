<?php
	class viewAddMsg extends viewBase{
		public function compose( $res ){
			if ( is_array( $res ) ){
				$this->_params[ "guestBookSite" ] = $res;
				$res = (string)$this->_compileComponent( "guestBookSite" );
				return "{ err_code : 0, txt : '$res' }";
			}else{
				return "{ err_code : -1 }";
			}
		}
	}
?>