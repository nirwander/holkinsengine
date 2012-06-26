<?php
	class viewUnsetMe extends viewBase{
		public function compose( $res ){
			if ( is_array( $res ) ){
				$this->_params[ "characterList" ] = $res;				
				$res = (string)$this->_compileComponent( "characterList" );
				$res .= iconv( "cp1251", "utf-8", '<div class="unset-me"><a href="?unsetMe">Я затупил, хочу выбрать другой костюм</a></div>' );
				
				return "{ err_code : 0, txt : '$res' }";
			}else return "{ err_code : -1 }";
		}
	}
?>