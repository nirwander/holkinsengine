<?php
	class Tree extends Error{

		protected $_root = NULL;

		protected $_current = NULL;
		
		protected $_pos = NULL;

		public function __construct(){
			$this->_root = new Node('ROOT');
			$this->_current = & $this->_root;
		}

		public function addChild( &$node, $setCurrent = false ){
			
			$this->_current->addChild( $node );
			if ( $setCurrent ) {
				$this->_current = &$node;
			}
		}

		public function getParent()
		{
			$this->_current = &$this->_current->getParent();
		}

		public function reset()
		{
			$this->_current = &$this->_root;
			$this->_pos = NULL;
		}
		
		public function &getCurrent(){
			return $this->_current;
		}
		
		public function &nextElement( ){
			if ( $this->_pos === NULL ){
				$this->_pos = &$this->_root;
				return $this->_pos;
			}		
			/* else */
			if ( $this->_pos->hasChilds() ){
				$childs = $this->_pos->getChilds();
				foreach( $childs as $k => $v ){
					$this->_pos = &$childs[ $k ];
					break;
				}
				//print_r( $this->_pos );
				return $this->_pos;
			}				
			/* else */
			$res = $this->_pos->getNextSibling();
			if ( $this->_pos->getTagName() == "component:css" ){
				//print_r( $this->_pos->getParent() );
				//if ( $res === NULL ) echo 65436;
				//echo "next:".$res->getTagName();
			}
			if ( $res !== NULL ){			
				$this->_pos = $res;
				return $this->_pos;
			}else{
				$daddyNextSibling = $this->_pos->getParent()->getNextSibling();
				//echo $daddyNextSibling->getTagName()."<br/>";
				while ( $daddyNextSibling == NULL ){
					if ( ( $this->_pos = $this->_pos->getParent() ) === NULL ) return NULL;
					/* else */
					$daddyNextSibling = $this->_pos->getNextSibling();
					//echo $daddyNextSibling->getTagName()."<br/>";
				}
				$this->_pos = &$daddyNextSibling;
				return $this->_pos;
			}
			
		}
		

	}
?>
