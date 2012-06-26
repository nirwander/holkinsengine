<?php
	class dbMysql extends Error{
		
		private static $_instance = NULL;
		
		private static $_link = NULL;
		private static $_isValid = NULL;
		
		const TYPE_INT = 0;
		const TYPE_VARCHAR = 1;
		
		private function __construct(){
			self::$_isValid = self::connect();
		}
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		
		private static function connect(){
			
			$connectionStruct = Config::$mysqlConnection;
			if ( $connectionStruct !== NULL ){
				self::$_link = @mysql_connect( $connectionStruct["server"], $connectionStruct["user"], $connectionStruct["pswd"] );
				if ( !self::$_link ) {
				    self::raiseException( ERR_MYSQL_CONNECTION_STRING, mysql_error() );
				    return NULL;
				}
				if ( !@mysql_select_db( $connectionStruct["database"], self::$_link ) ){
				    self::raiseException( ERR_MYSQL_SELECT_DB, mysql_error() );
				    return NULL;
				}
				return true;		
			}else{
			    self::raiseException( ERR_MYSQL_DONT_SEE_CONNECTION_STRING, mysql_error() );
				return NULL;
			}
		}		
		
		public static function fireSQL($sql){
			if ( self::$_isValid ){				
				$result = @mysql_query( "SET NAMES cp1251", self::$_link );
				$result = @mysql_query( $sql, self::$_link );
				if ( !$result ){
					self::raiseException( ERR_MYSQL_INVALID_QUERY, mysql_error() );
					return false;
				}
				/* else */
				if ( @mysql_num_rows($result) === FALSE ){					
					return mysql_affected_rows(  ) || $result;
				}
				/* else */
				$res = NULL;
				$ind = -1;
				$fields = mysql_num_fields( $result );
				while ( $row = mysql_fetch_array( $result, MYSQL_BOTH ) ){
					$res = !isset( $res ) ? array() : $res;
					$k_num = -1;
					$ind++;
					foreach( $row as $k => $v ){						
						if ( !is_integer( $k ) ){

							$k_num++;
							$type =  mysql_field_type( $result, $k_num );
							switch ( $type ){
								case "int": $res[ $ind ][ $k ] = ( integer )( $v ); break;
								case "real": $res[ $ind ][ $k ] = ( float )( $v ); break;
								default: $res[ $ind ][ $k ] = $v;
							}
						}
					}
				}
				mysql_free_result( $result );				
				return $res;
			}else{
				//self::raiseException( ERR_MYSQL_INVALID_QUERY, mysql_error() );
				return NULL;
			}
		}
		
	}
?>