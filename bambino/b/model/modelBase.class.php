<?php
	class modelBase extends Error{
		
		protected static $_instance = NULL;
		
		protected static $_db = NULL;
		protected static $_lastError = NULL;
		
		protected function __construct(){
			self::$_db = dbMysql::getInstance();
			if ( !self::$_db ) return NULL;
		}
		protected function _getFieldTypes( $table ){
			print_r( self::$_db->fireSQL( "show columns from $table" ) );
		}
		protected function asString( $str, $quots=true ){		
			if ( $quots ) return is_string( $str ) ? "'".trim( htmlspecialchars( $str, ENT_QUOTES ) )."'" : "''";
			else return is_string( $str ) ? trim( htmlspecialchars( $str, ENT_QUOTES ) ) : "";
			//return is_string( $str ) ? "'".htmlentities( $str, ENT_QUOTES, "cp1251" )."'" : 'NULL';
		}
		protected function asInteger( $value ){
			return is_integer( $value ) ? $value : ( preg_match( "/^([0-9]+)$/", $value ) ? $value : 'NULL' );
		}
		protected function _exists( $sql ){
			$res = self::$_db->fireSQL( $sql );			
			return is_array( $res );
		}
		protected function _modify( $sql ){
			$res = self::$_db->fireSQL( $sql );
			return (integer)$res;
		}
		protected function _getSingle( $sql ){
			$res = self::$_db->fireSQL( $sql );
			if ( is_array( $res ) && count( $res ) == 1 ){
				$cnt = 0;
				$res_ = NULL;
				foreach( $res[ 0 ] as $k => $v ){
					$cnt++;
					$res_ = $cnt == 1 ? $v : $res_;
				}
				return $res_;
			}else return NULL;
		}
		protected function _saveError( $err ){
			if ( is_array( $err ) && isset( $err[ "code" ] ) && isset( $err[ "dsc" ] ) ){
				$this->_lastError = $err;
			}
		}
		public function getLastError( $onlyDsc=true ){
			return $onlyDsc ? $this->_lastError[ "dsc" ] : $this->_lastError;
		}
	}
?>
