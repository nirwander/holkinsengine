<?php
	class ctrlUnsetMe extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){
					
			$p = mParty::getInstance();
			if ( $p->unsetMe() ){				
				$res = $p->getCharacters();
			}else $res = -1;
			
			Response::setContent( $this->_view->compose( $res ) );
			return true;
		}
	}
?>