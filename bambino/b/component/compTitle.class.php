<?php
	class compTitle extends componentBase{
	
		protected $_paramsValid = array( "count" => 1,
									   "type" => array( "title" => "string" )
									  );
		
		public function __construct( $params ){
			if ( !$this->_isValidParams( $params ) ){				
				return false;
			}else{
				$this->_params = $params;
				return true;
			}
		}

		
		public function getTree($attr=NULL){
			if ( isset( $this->_params ) ){
				
				$tree = new xmlTree();

				$text = new xmlNode( "#text" );
				$this->_params[0]["title"] = iconv( "windows-1251", "UTF-8", $this->_params[0]["title"] );
				$text->text( $this->_params[0]["title"] );
				
				$title = new xmlNode( "title" );
				$title->addChild( $text );

				$tree->addChild( $title );			
				
				return $tree;
			}
		}
	}
?>