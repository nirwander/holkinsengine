<?php
	class compFotki extends componentBase{
	
		
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
			if ( $this->_isValid ){
				$fotki = Filesystem::getFilesFromDir( "img/photo", "jpg" );
				$content = "";				
				for ( $i = 0; is_array( $fotki ) && $i < count( $fotki ); $i++ ){
					$saltAndPepper = array( "src" => $fotki[ $i ], "class" => "v" );
					$content .= $this->_compileTemplate( "item.template.php", $saltAndPepper );
				}

				$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			}
			
			return $tree;
		}
	}
?>