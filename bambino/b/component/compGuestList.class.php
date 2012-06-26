<?php
	class compGuestList extends componentBase{
	
		protected $_paramsValid = array( "count" => -1,
									   "type" => array( "n" => "integer",
														"guest" => "string" )
									  );
		
		public function __construct( $params ){
			//print_r($params); die;
			if ( !$this->_isValidParams( $params ) ){		
				return false;
			}else{
				$this->_isValid = true;
				$this->_params = $params;
				return true;
			}
		}

		
		public function getTree($attr=NULL){
			//print_r( $this->_params ); die;
			if ( $this->_isValid ){
				$content = "";
				if ( count( $this->_params ) % 2 != 0 ) $this->_params[] = array( "n" => "", "guest" => "" );
				for ( $i = 0; is_array( $this->_params ) && $i < count( $this->_params ); $i = $i + 2 ){
					$saltAndPepper = array( "guest1" => $this->_params[ $i ][ "guest" ],
											"guest2" => $this->_params[ $i + 1 ][ "guest" ],
											"n1" => $this->_params[ $i ][ "n" ],
											"n2" => $this->_params[ $i + 1 ][ "n" ] );
					$content .= $this->_compileTemplate( "row.template.php", $saltAndPepper );
								
				}
					//echo $content; die;
				$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			}
			
			return $tree;
		}
	}
?>