<?php
	class compJs extends componentBase{
	
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

		
		public function getTree( $attr=NULL ){
			if ( $this->_isValid ){
				$jsPath = __ROOT_PATH.DS."template".DS."default".DS."js".DS;
				$scripts = Filesystem::getFilesFromDir( $jsPath, "js" );
				if ( $attr !== NULL ){
					if ( ( $scripts = $this->_assignAttr( $attr ) ) !== NULL ){
						$p = $this->_assignAttr( $attr );
						if ( isset( $scripts[ "src" ] ) )
							$scripts = explode( ",", ereg_replace( " ", "",  $scripts[ "src" ] ) );
							if ( isset( $p[ "combat" ] ) && $p[ "combat" ] )
								$jsPath = Config::SITE_DIR."/"."js"."/";
							for ( $i = 0; $i < count( $scripts ); $i++ )
								$scripts[ $i ] = Filesystem::absoluteUrl( $jsPath.$scripts[ $i ] );
					}
				}
				$tree = new xmlTree();

				for ( $i = 0; $i < count( $scripts ); $i++ ){
					$url = Filesystem::absoluteUrl( $scripts[ $i ] );
					if ( !$url ) return NULL;
					/* else */
										
					$script = new xmlNode( "script" );
					$script->setAttribute( "src", $url );
					$script->setAttribute( "type", "text/javascript" );
					
					$tree->addChild( $script );
				}
				if ( $main !== NULL ){
					$script = new xmlNode( "script" );
					$script->setAttribute( "src", $main );
					$script->setAttribute( "type", "text/javascript" );
					
					$tree->addChild( $script );
				}
				return $tree;
			}
		}
	}
?>