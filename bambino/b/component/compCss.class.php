<?php
	class compCss extends componentBase{
	
		protected $_paramsValid = NULL;
		
		public function __construct( $params=NULL ){
			if ( !$this->_isValidParams( $params ) ){				
				return false;
			}else{
				$this->_params = $params;
				$this->_isValid = true;
				return true;
			}
		}

		
		public function getTree($attr=NULL){
			if ( $this->_isValid ){
				$cssPath = __ROOT_PATH.DS."template".DS."default".DS."css".DS;
				$styleSheets = Filesystem::getFilesFromDir( $cssPath, "css" );
				if ( $attr !== NULL ){
					if ( $this->_assignAttr( $attr ) !== NULL ){
						$styleSheets = $this->_attr;
						if ( isset( $styleSheets[ "href" ] ) )
							$styleSheets = explode( ",", ereg_replace( " ", "",  $styleSheets[ "href" ] ) );
							$p = $this->_assignAttr( $attr );
							if ( isset( $p[ "combat" ] ) && $p[ "combat" ] ){								
								$cssPath = Config::SITE_DIR."/"."css"."/";
							}
							for ( $i = 0; $i < count( $styleSheets ); $i++ )
								$styleSheets[ $i ] = Filesystem::absoluteUrl( $cssPath.$styleSheets[ $i ] );
							//print_r( $styleSheets ); die;
					}
				}

				$tree = new xmlTree();
				for ( $i = 0; $i < count( $styleSheets ); $i++ ){
					$url = Filesystem::absoluteUrl( $styleSheets[ $i ] );
					if ( !$url ) return NULL;
					/* else */
					$cssLink = new xmlNode( "link" );
					$cssLink->markAsEmpty();
					$cssLink->setAttribute( "rel", "STYLESHEET" );
					$cssLink->setAttribute( "href", $url );
					$cssLink->setAttribute( "type", "text/css" );
					
					$tree->addChild( $cssLink );
				}			
				//echo $tree; die;
				return $tree;
			}
		}
	}
?>