<?php
	class viewIdentity extends viewBase{
		public function compose( $res ){
			$res = (integer)$res;
			return "{ err_code : $res }";			
		}		
	}
?>