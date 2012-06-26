<?php
	class viewBase extends Error{
		
		protected static $_instance = NULL;
		protected static $_docTree = NULL;
		protected static $_fragments = array();
		protected static $_params = NULL;
		protected $_template = "default";

		public function __construct(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = $this;
			}
			return self::$_instance;
		}

		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		

		public function compose( $params=NULL ){
			
			$this->_params = $params;
		
			$this->_buildDocTree();
			
			$this->_replaceFragmentWithComponents();
			
			//print_r( $this->_docTree );
			
			$this->_compileComponents();
			
			return $this->_docTree ;
			//return stripslashes( $this->_docTree );
		}
		
		protected function _buildDocTree(){
			$xmlPath = __ROOT_PATH.DS."template".DS.$this->_template.DS."index.php";
			$this->_docTree = XMLDocument::buildTree( $xmlPath );
			return $this->_docTree;
		}
		
		protected function &_buildFragTree( $fragmentName ){
			//$xmlPath = __ROOT_PATH.DS."view".DS.Registry::get( "viewName" ).DS."fragments".DS.$fragmentName.".xml";
			$xmlPath = __ROOT_PATH.DS."view".DS.substr( get_class( $this ), 4 ).DS."fragments".DS.$fragmentName.".xml";
			$compTree = XMLDocument::buildTree( $xmlPath );
			return $compTree;			
		}
		
		protected function _replaceFragmentWithComponents(){
			$forRemove = array();
			while ( ( $elem = $this->_docTree->nextElement() ) !== NULL ){
				if ( preg_match( "/^fragment:([a-z_0-9]+)$/i", $elem->getTagName(), $matches ) ){
					$_compTree = $this->_buildFragTree( $matches[1] );					
					$this->_fragments[] = $_compTree;
					$_compTree->reset();
					$childs = $_compTree->getCurrent()->getChilds();
					
					foreach ( $childs as $hash => $node ){
						$_childs = $childs[ $node->getHash() ]; break;	
					}
					$__childs = $_childs->getChilds();
					
					foreach( $__childs as $hash => $node ){
						if ( !$lastElem )
							$lastElem = $elem->insertAfterMe( $node );
						else
							$lastElem = $lastElem->insertAfterMe( $node );
					}
					$lastElem = NULL;
					$forRemove[] = $elem;
				}			
			}
			foreach( $forRemove as $k => $v )
				$forRemove[ $k ]->unsetMe();	
			return $this->_docTree;
		}
		
		protected function _compileComponents(){
		
			$this->_docTree->reset();

			//echo $this->_docTree; die;
			while ( ( $elem = $this->_docTree->nextElement() ) !== NULL ){
				//echo $elem->getTagName()."\n";
				if ( preg_match( "/^component:([a-z_0-9]+)$/i", $elem->getTagName(), $matches ) ){
					//if ( $matches[1] )
					$_compTree = $this->_compileComponent( $matches[1], $elem->attrToString( ";" ) );

					$this->_component[] = $_compTree;
					if ( $_compTree == NULL ){
						 $this->raiseException( ERR_VIEW_COMPONENT_COMPILE, $matches[1] );
							//die;
						 return false;
					}
					
					$childs = $_compTree->getCurrent()->getChilds();
					foreach( $childs as $hash => $node ){		
						if ( !$lastElem )
							$lastElem = $elem->insertAfterMe( $node );
						else
							$lastElem = $lastElem->insertAfterMe( $childs[ $hash ] );
						//$lastElem = $node;
					}

					$lastElem = NULL;
					
					$forRemove[] = $elem;					
				}
			}	

			if ( is_array( $forRemove ) ){
				foreach( $forRemove as $k => $v ){
					$forRemove[ $k ]->unsetMe();
				}
			}			
		}
		
		protected function _compileComponent( $name, $attr=NULL ){			
			$compPath = __ROOT_PATH.DS."component".DS."comp".ucfirst( $name ).".class.php";
			$className = Class_routines::loadClass( $compPath );		
			if ( $className != NULL ){
				$comp = new $className( $this->_params === NULL ? NULL : $this->_params[ $name ] );
				//if ( $name == "siteCatalogRightPanel" ) die( "1" );
				$t = $comp->getTree( $attr );
				return $t;
			}else{
				 $this->raiseException( ERR_VIEW_COMPONENT_NOTFOUND, $name );
				 return false;				
			}
		}
		
	}
?>