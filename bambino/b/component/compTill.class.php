<?php
	class compTill extends componentBase{
	
		public function __construct( $params ){
			if ( !$this->_isValidParams( $params ) ){				
				return false;
			}else{
				$this->_params = $params;
				return true;
			}
		}

		
		public function getTree($attr=NULL){
			$delta = ( mktime( 19, 0, 0, 10, 1, 2011 ) - time() );
			$day = (int)( $delta / ( 60 * 60 * 24 ) );
			$hour = "X";
			$minute = "X";
			$saltAndPepper = array( "day" => $day, "hour" => $hour, "minute" => $minute );
			$content = $this->_compileTemplate( "item.template.php", $saltAndPepper );
			$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			return $tree;
		}
	}
?>