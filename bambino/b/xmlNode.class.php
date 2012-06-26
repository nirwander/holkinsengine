<?php
	class xmlNode extends Node{

		protected $_tagName = NULL;
		protected $_attributes = NULL;
		protected $_isEmpty = false;
		protected $_text = NULL;
		
		public function __construct( $tagName ){
			$this->_tagName = $tagName;
			parent::__construct();
		}
		
		public function getTagName(){
			return $this->_tagName;
		}
		
		public function setAttribute( $key, $value ){
			if ( !isset( $this->_attributes ) ){
				$this->_attributes = array();
			}
			$this->_attributes[ $key ] = $value;
		}

		public function getAttribute( $key ){
			if ( !isset( $this->_attributes ) || !isset( $this->_attributes[ $key ] ) ){
				return NULL;
			}
			/* else */
			return $this->_attributes[ $key ];
		}
		
		public function hasAttributes(){
			return count( $this->_attributes ) > 0 ? true : false;
		}
		
		public function attrToString( $delimiter=" "){
			$res = array();
			if ( $this->_attributes === NULL ) return NULL;
			/* else */
			foreach ( $this->_attributes as $k => $v )
				$res[] = $k."=\"".$v."\"";
			return join( $delimiter, $res );
		}
		
		public function markAsEmpty(){
			$this->_isEmpty = true;
		}
		
		public function isEmpty(){
			return $this->_isEmpty;
		}
		
		public function text( $txt ){
			if ( is_string( $txt ) && $this->_tagName == "#text" ){
				$this->_text = $txt;
			}
		}
		
		public function getText(){
			return isset( $this->_text ) ? $this->_text : false;
		}
		
		public function &insertAfterMe( &$newElem ){
		
			if ( !( $newElem instanceof Node ) ){				
				return NULL;
			}
			/* else */			
			if ( ( $daddy = $this->getParent() ) !== NULL ){

				$childs = $daddy->getChilds();

				$buf = array();
				foreach( $childs as $hash => $node ){				
					$buf[] = $childs[ $node->getHash() ];
					$node->unsetMe();
				}

				for( $i = 0; $i < count( $buf ); $i++ ){
					
					$daddy->addChild( $buf[ $i ] );
					if ( $buf[ $i ]->getHash() == $this->getHash() )
						$daddy->addChild( $newElem );
				}
				return $newElem;
			}else{
				
			}
		}
		
	}
?>