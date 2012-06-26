<?php
	class xmlTree extends Tree{	
		
		public function __construct(){
			$this->_root = new xmlNode( 'ROOT' );
			$this->_current = & $this->_root;
		}


		public function __toString( &$node=NULL ){
			$res = "";
			
			if ( is_null( $node ) ){
				$this->reset();
				$node = &$this->getCurrent();				
			}
			$childs = $node->getChilds();
			if ( $node->getTagName() != 'ROOT' ){
				if ( $node->getTagName() != 'html' ){
					$res .= "<".$node->getTagName().( $node->hasAttributes() ? " ".$node->attrToString() : "" ).($node->isEmpty() ? "/" : "").">";
				}else{
					$res .= "<".$node->getTagName()." xmlns=\"http://www.w3.org/1999/xhtml\">";
				}
			}
			foreach ( $childs as $node_ ){
				/* Whether node is simple text */
				if ( $node_->getTagName() == "#text" ){				
					$res .= $node_->getText();
				}else{
					if ( $node_->hasChilds() ){
						$res .= $this->__toString( $node_ );
					}else{
						$res .= "<".$node_->getTagName().( $node_->hasAttributes() ? " ".$node_->attrToString() : "" ).($node_->isEmpty() ? "/" : "").">";
						if ( !$node_->isEmpty() )
							$res .= "</".$node_->getTagName().">";
					}
				}
			}
			if ( !$node->isEmpty() && $node->getTagName() != 'ROOT' )
				$res .= "</".$node->getTagName().">";
			return $res;
		}
	}
?>