<?php
	class compErrorDsc extends componentBase{
	
		protected $_paramsValid = array( "count" => 1,
										"type" => array( "dsc" => "string" )
										);
		
		public function __construct( $params ){

			if ( !$this->_isValidParams( $params ) ){				
				return false;
			}else{
				$this->_params = $params;
				return true;
			}
		}

		
		public function getTree( $attr=NULL ){
			if ( isset( $this->_params ) ){
				$tree = new xmlTree();

				$text = new xmlNode( "#text" );
				$text->text( $this->_params[ 0 ][ "dsc" ] );
				
				$div = new xmlNode( "div" );
				$div->addChild( $text );
				$div->setAttribute( "class", "error-dsc" );

				$tree->addChild( $div );
				//echo $tree; die;
				return XMLDocument::buildTree( "<div class=\"error-dsc\">{$this->_params[ 0 ][ "dsc" ]}</div>", XMLDocument::BUILD_MODE_FROM_TEXT );
				//return $tree;
			}else{
				return new xmlTree();
			}
		}
	}
?>