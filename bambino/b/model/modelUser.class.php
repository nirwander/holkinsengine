<?php
	class modelUser extends Error{
		
		private static $_instance = NULL;
		
		private static $_db = NULL;		
		
		private function __construct(){
			self::$_db = dbMysql::getInstance();
		}
		
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		
		public static function loginIsOK( $params ){
			self::getInstance();
			if ( self::$_db && isset( $params[ "login" ] ) && isset( $params[ "pswd" ] ) ){	
				$sql = "select name, pswd from user";
				$res = self::$_db->fireSQL( $sql );
				for ( $i = 0; $i < count( $res ); $i++ ){
					if ( $res[ $i ][ "name" ] == $params[ "login" ] && $res[ $i ][ "pswd" ] == md5( $params[ "pswd" ] ) ){
						return true;
					}
				}
				/* else */
				return false;

			}
			return NULL;
		}	
	}
?>