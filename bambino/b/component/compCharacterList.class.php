<?php
	class compCharacterList extends componentBase{
	
		protected $_paramsValid = array( "count" => -1,
									   "type" => array( "n" => "integer",
														"title" => "string",
														"guestN" => "integer",
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
				for ( $i = 0; is_array( $this->_params ) && $i < count( $this->_params ); $i++ ){
					$saltAndPepper = $this->_params[ $i ];
					$saltAndPepper[ "title" ] = htmlspecialchars( $saltAndPepper[ "title" ], ENT_QUOTES );
					$saltAndPepper[ "guest" ] = htmlspecialchars( $saltAndPepper[ "guest" ], ENT_QUOTES );
					$content .= $saltAndPepper[ "guestN" ] > 0 ? $this->_compileTemplate( "item-busy.template.php", $saltAndPepper ) : $this->_compileTemplate( "item.template.php", $saltAndPepper );
								
				}
					//echo $content; die;
				$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			}
			
			return $tree;
		}
	}
?>