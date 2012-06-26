<?php
	//XMLDocument::buildTree( $filename, XMLDocument::BUILD_MODE_FROM_FILE );
	class XMLDocument extends Error{
		
		const BUILD_MODE_FROM_FILE = 0;
		const BUILD_MODE_FROM_TEXT = 1;
		
		
				
		public static function &buildTree( $source, $mode = XMLDocument::BUILD_MODE_FROM_FILE, $ns=NULL ){
			if ( $mode == XMLDocument::BUILD_MODE_FROM_FILE ){
				if ( ( $content = Filesystem::getFileContent( $source ) ) === NULL )
					return NULL;
			}else{
				if ( $source == "" || $source == NULL ) return new xmlTree();
				$content = $ns == NULL ? "<ROOT>".$source."</ROOT>" : "<ROOT xmlns:$ns=\".\">".$source."</ROOT>";
			}
			/*$content = ereg_replace( "&amp;", "&", $content );
			$content = ereg_replace( "&", "&amp;", $content );*/
			//echo $content;
			$content = preg_replace( "/&amp;/", "&", $content );
			$content = preg_replace( "/&/", "&amp;", $content );			

			$content = iconv( "windows-1251", "UTF-8", $content );
			$reader = new XMLReader();
			if ( $reader->XML( $content ) ){
				$_docTree = NULL;
				$_docTree = new xmlTree();
				self::_appendChilds( $_docTree->getCurrent(), $reader, 0 );
				$reader = NULL;
				return $_docTree;
			}else{
				self::raiseException( ERR_XML_INVALID_XML, $content );
				return NULL;
			}
		}
		private static function _appendChilds( &$node /*parent*/ , &$reader, $depth ){
			while ( $reader->read() ){
				//echo $reader->name.", ".$reader->isEmptyElement.", ".$reader->nodeType."<br/>";
				if ( $reader->nodeType == XMLReader::ELEMENT ){
					$element = $node->addChild( new xmlNode( $reader->name ) );
					$element = self::_setAttr( $element, $reader );
					//$node->addChild( $element );
					if ( !$reader->isEmptyElement ){
						self::_appendChilds( $element, $reader, 0 );
					}else{
						$element->markAsEmpty();
					}
				}else if ( $reader->nodeType == XMLReader::DOC_TYPE ){
					/*$element = $node->addChild( new xmlNode( $reader->name ) );
					$element = self::_setAttr( $element, $reader );
					$element->markAsEmpty();*/
				}else if ( $reader->nodeType == XMLReader::TEXT ){
					$element = $node->addChild( new xmlNode( $reader->name ) );
					$element->text( $reader->value );
					
				}else if ( $reader->nodeType == XMLReader::END_ELEMENT ){
					break;
				}else if ( $reader->nodeType == XMLReader::ENTITY_REF ){
					
				}
			}
		}
		
		private static function &_setAttr( &$elem, &$reader_ ){
			if ( $reader_->hasAttributes ) {
				while ( $reader_->moveToNextAttribute() ) {
					//$elem->setAttribute( $reader_->name, html_entity_decode( $reader_->value ) );
					$elem->setAttribute( $reader_->name, ( $reader_->value ) );
				}
			}			
			$reader_->moveToElement();
			return $elem;
		}
						
	}
?>