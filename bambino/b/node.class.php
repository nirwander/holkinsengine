<?php

	class Node extends Error{

		protected $_daddy = NULL;
		protected $_hash = NULL;

		protected $_childs = array();
		

		public function __construct(){
			$this->_hash = spl_object_hash( $this );
		}

		public function &addChild( &$child ){
			if ( $child instanceof Node ){
				$child->setParent( $this );
				return $child;
			}
		}

		public function setParent( &$parent ){
			if ( $parent instanceof Node || is_null( $parent ) ){

				if ( !is_null( $this->_daddy ) ){
					unset( $this->_daddy->_childs[ $this->getHash() ] );
				}
				if ( !is_null( $parent ) ){
					$parent->_childs[ $this->getHash() ] = & $this;
				}
				$this->_daddy = & $parent;
			}
		}

		public function &getChilds(){
			return $this->_childs;
		}

		public function &getParent(){
			return $this->_daddy;
		}

		public function hasChilds(){
			return count( $this->_childs );
		}

		public function hasParent(){
			return $this->getParent() != null;
		}
		
		public function unsetMe(){
			if ( ( $this->getParent() ) !== NULL ){
				if ( $this->getParent()->removeChild( $this ) ){
					$this->_daddy = NULL;
				}
			}	
		}
		
		public function removeChild( $child ){
				$childs = $this->getChilds();
				$newArr = array();
				foreach( $childs as $hash => $node ){
					if ( $node->getHash() != $child->getHash() ){
						$newArr[ $node->getHash() ] = $childs[ $node->getHash() ];
					}else{
						$exists = true;
					}
				}
				if ( $exists ){
					$this->_childs = $newArr;
					return true;
				}
				/* else */
				$this->raiseException( ERR_TEST, $child );
				return false;						
		}	

		
		public function &getNextSibling(){
			
			$daddy = $this->getParent();
			if ( !$daddy ) return NULL;
			/* else */
			$res = NULL;
			$childs = $daddy->getChilds();
			foreach( $childs as $hash => $child ){
				
				if ( $next ){
					$res = $childs[ $child->getHash() ];
					break;
				}
				if ( $this->getHash() == $child->getHash() ){
					$next = true;
				}
			}
			return $res;
		}
		
		public function getHash(){
			return $this->_hash;
		}
		
	}

?>