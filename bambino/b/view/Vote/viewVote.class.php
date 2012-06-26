<?php
	class viewVote extends viewBase{
		public function compose( $res ){
			if ( $res ){				
				return "{ err_code : 0 }";
			}else{
				return "{ err_code : -1 }";
			}
		}
	}
?>