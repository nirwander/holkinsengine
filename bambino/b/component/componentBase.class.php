<?php
	abstract class componentBase extends Error{
	
		protected $_params = NULL;
		protected $_isValid = false;
		protected $_attr = NULL;
		protected $_templ_cache = array();
	
		abstract public function getTree( $attr=NULL );
	
		protected function _isValidParams( $params=NULL ){

			if ( $params === NULL ) return true;
			if ( !isset( $this->_paramsValid ) && $params === NULL ) return true;
			/* else */
			if ( $this->_paramsValid[ "count" ] != -1 ){
				$length = $this->_paramsValid[ "count" ];
			}else{
				if ( isset( $params[0] ) ){
					$length = count( $params );
				}else{				
					
					$params = $params == 0 ? array() : $params;
					if ( count( array_values( $params ) ) == 0 )
						return true;
					else{
						return false;
					}
				}
			}
			for ( $i = 0; $i < $length; $i++ ){					
				
				if ( !$this->_isValidParam( $params[ $i ] ) ){
					return false;
				}
			}
			return true;
		}
		
		protected function _isValidParam( $target, $pattern=NULL ){
			
			if ( !$this->_isValidMyParamTemplate() ) return false;
			/* else */
			if ( !is_array( $target ) ){
				$this->raiseException( ERR_COMP_WRONG_STRUCTURE );
				return false;
			}
			$pattern = $pattern == NULL ? $this->_paramsValid[ "type" ] : $pattern;

			if ( count( array_diff_key( $pattern, $target ) ) +
				 count( array_diff_key( $target, $pattern ) ) > 0 ){
					return false;
			}
			/* else */
			//print_r( $target ); echo "<br/>-----<br/>";
			foreach( $target as $k => $v ){
			
				$_type = is_array( $pattern[ $k ] ) ? "array" : $pattern[ $k ];
				//echo "k=$k\n";
				/*if ( $k == "thumb" ){
					if ( is_null( $target[ $k ] ) ) echo "notstring=$k";
				}*/
				switch ( $_type ){
					case "string" : if ( !is_string( $target[ $k ] ) && !is_null( $target[ $k ] ) ){ return false; } break;
					case "integer" : if ( !is_integer( $target[ $k ] ) && !is_null( $target[ $k ] ) ){ return false; } break;
					case "float" : if ( !is_float( $target[ $k ] ) ) return false; break;
					case "array" : 
								   if ( isset( $pattern[ $k ][ 0 ] ) ){ 
										if ( is_array( $pattern[ $k ][ 0 ] ) ){ 
											$this->raiseException( ERR_COMP_PARAM_TEMPL_BAD_ARRAY, $pattern[ $k ] );											
											return false;
										}
										/* else */
										if ( !in_array( $target[ $k ], $pattern[ $k ] ) ){
											return false;
										}
								   }else{
								   		if ( !is_array( $target[ $k ] ) ) return false;
								   		/* else */
										for ( $ii = 0; $ii < count( $target[ $k ] ); $ii++ ){
											if ( !$this->_isValidParam( $target[ $k ][ $ii ], $pattern[ $k ] ) ){												
												return false; 
											}
										}
										//print_r( $k ); die;
								   }; break;							   
					default : $this->raiseException( ERR_COMP_UNKNOWN_PARAM_TYPE, $pattern[ $k ] );
				}
			}
			return true;
		}
		
		protected function _isValidMyParamTemplate(){
			if ( !isset( $this->_paramsValid[ "count" ] ) || !is_integer( $this->_paramsValid[ "count" ] ) ){
				$this->raiseException( ERR_COMP_PARAM_TEMPL_COUNT );
				return false;
			}
			/* else */
			if ( !isset( $this->_paramsValid[ "type" ] ) || !is_array( $this->_paramsValid[ "type" ] ) || isset( $this->_paramsValid[ "type" ][0] ) ){
				$this->raiseException( ERR_COMP_PARAM_TEMPL_TYPE );
				return false;
			}
			/* else */
			return true;
		}
		protected function _compileTemplate( $template, $saltAndPepper, $cache=false ){
			if ( isset( $this->_templ_cache[ $template ] ) ){
				$template = $this->_templ_cache[ $template ];				
			}else{
				$template_name = $template;
				$template = Filesystem::getFileContent( __ROOT_PATH.DS."component".DS.get_class( $this ).DS.$template );
				if ( $cache ) $this->_templ_cache[ $template_name ] = $template;
			}
			
			if ( !is_array( $saltAndPepper ) || isset( $saltAndPepper[0] ) || !is_string( $template ) ) return NULL;
			/* else */
			$pattern = array_keys( $saltAndPepper );
			for ( $i = 0; $i < count( $pattern ); $i++ )
				$pattern[ $i ] = "/%".$pattern[ $i ]."%/";
				
			$replacement = array_values( $saltAndPepper );
			return preg_replace( $pattern, $replacement, $template );
		}
		
		protected function _assignAttr( $str ){
			if ( $str == NULL ) return NULL;
			/* else */
			$str .= ";";
			$attributes = explode( "\";", $str );
			for ( $i = 0; $i < count( $attributes ) - 1; $i++ ){
				if ( $i == 0 ) $this->_attr = array();
				$attr = explode( "=\"", $attributes[ $i ], 2 );
				$this->_attr[ $attr[ 0 ] ] = $attr[ 1 ];
			}
			return $this->_attr;
		}
	}
?>