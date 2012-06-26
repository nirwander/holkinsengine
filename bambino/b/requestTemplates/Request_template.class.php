<?php
	 abstract class Request_template extends Error{
	 
		abstract public function getCtrlName();
		
			
		public function checkRequest( $params ){
		
			//print_r( $params );
		
			$vars = get_object_vars( $this );			
			//print_r( $vars );
			$res = "";
			$parameters = array();			
			foreach ( $vars as $k => $v ){
				$k_ = substr( $k, 1 );
				$cursor = $vars[ $k ];
				if ( array_key_exists( $k_, $params ) ){
					if ( is_array( $cursor ) && is_string( $cursor[0] ) ){
						$exists = false;
						for ( $i = 0; $i < count( $cursor ); $i++ ){
							if ( $cursor[$i] == $params[$k_] ){
								$exists = true;
								$res .= ucfirst( strtolower( $params[ $k_ ] ) );
							}
						}
						if ( !$exists ){
							//$this->raiseException( ERR_BAD_REQPARAM_VALUE, $params[ $k_ ] );							
							return NULL;				
						}
						
					}else{
						$exists = false;
						if ( $cursor == $params[$k_] ){
							$exists = true;
							$res .= ucfirst( strtolower( $k_ ) );
						}
						if ( !$exists ){
							//$this->raiseException( ERR_BAD_REQPARAM_VALUE, $params[ $k_ ] );							
							return NULL;				
						}

						$parameters[ $k_ ] = $params[ $k_ ];
					}
				}else{
					//$this->raiseException( ERR_BAD_REQPARAM_NOTEXISTS, $k_ );
					return NULL;				
				}
			}
						
			if ( $res == "" ){				
				//$this->raiseException( ERR_REQPARAM_NOTENOUGH, $k_ );
				if ( count( $vars ) >= 1 || count( $params ) >= 1 ) return NULL;				
			}
			//$res = array( "controller" => $res, "__params" => $parameters );
			$res = array( "controller" => $this->getCtrlName(), "__params" => $parameters );
			return $res;
		}
		
		public function getRequestString( $params, $nested=NULL ){
			if ( $nested === NULL ){
				if ( $this->checkRequest( $params ) === NULL ){
					echo 123;
					return NULL;
				}
				$res = "";
				foreach( $params as $k => $v ){
					if ( !is_array( $v ) ){
						$res .= $k."=".$v."&amp;";
					}else{
						$res .= $k.$this->getRequestString( $v, true );
					}
				}
				return $res;
			}
			/* else */
			foreach( $params as $k => $v ){
				if ( !is_array( $v ) ){
					$res .= "[".$k."]=".$v."&amp;";
				}else{
					//$res .= $k.getRequestString( $v, $res );
				}
			}
			return $res;
		}
	}
?>