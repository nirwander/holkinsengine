<?php
	class compVoteList extends componentBase{
	
		protected $_paramsValid = array( "count" => -1,
									   "type" => array( "n" => "integer",
														"name" => "string",
														"rate" => "integer" )
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
					$saltAndPepper[ "checked" ] = $i ==0  ? "checked=\"\"" : "";
					$content .= $this->_compileTemplate( "item.template.php", $saltAndPepper );
								
				}
					//echo $content; die;
				$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			}
			
			return $tree;
		}
	}
?>