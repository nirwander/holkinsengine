<?php
	class Request extends Error{
		
		private static $_instance = NULL;
		private static $GET = NULL;
		private static $POST = NULL;
		private static $SERVER = NULL;
		private static $REQUEST = NULL;
		private static $FILES = NULL;
		private static $_method = NULL;
		private static $_args = array();
		private static $_headers = array();
		
		private function __construct(){
		
			self::$_method = strtoupper( $_SERVER['REQUEST_METHOD'] );
			self::$GET = $_GET;
			self::$POST = $_POST;
			self::$SERVER = $_SERVER;
			self::$REQUEST = $_REQUEST;
			self::$FILES = $_FILES;
			
			self::$_args[ "GET" ] = self::$GET;
			self::$_args[ "POST" ] = self::$POST;
			
			//$headers = apache_request_headers();

			foreach( $_SERVER as $i => $val ){
				$name = str_replace( array( 'HTTP_', '_' ), array( '', '-' ), $i );
				self::$_headers[ strtolower( $name ) ] = $val;
			}
			
		}
		private function __clone(){}
		
		public static function getInstance(){
			if ( !self::$_instance ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		
		public static function getMethod(){
			self::getInstance();
			return self::$_method;

		}
		
		
		public static function getVar( $name, $type="GET" ){
			self::getInstance();
			if ( substr_count( $name, "[" ) != substr_count( $name, "]" ) ) return NULL;
			/* else */
			$name = str_replace( "]", "", $name );
			$_name = explode( "[", $name );
			if ( count( $_name ) > 1 ){
				
				$_arg = self::$_args[ $type ][ $_name[ 0 ] ];
				if ( !isset( $_arg ) ) return NULL;				
				/* else */				
				for ( $i = 1; $i < count( $_name ); $i++ ){
					if ( isset( $_arg[ $_name[ $i ] ] ) ){
						$_arg = $_arg[ $_name[ $i ] ];
					}else{
						return NULL;
					}
				}
				return $_arg;
			}else{
				return isset( self::$_args[ $type ][ $name ] ) ? self::$_args[ $type ][ $name ] : NULL;
			}
			
		}
		
		public static function getVarAsInteger( $name, $type="GET" ){
			self::getInstance();
			$var = self::getVar( $name, $type );		
			if ( $var === NULL ) return NULL;
			/* else */			
			if ( preg_match( "/^([\-]{0,1})([0-9]+)$/", $var ) ){
				return (integer)$var;
			}else{
				return NULL;
			}
		}
		
		public static function getAllVar( $type="GET" ){
			self::getInstance();
			return isset( self::$_args[ $type ] ) ? self::$_args[ $type ] : NULL;

		}
		
		public static function getHeader( $header ){
			self::getInstance();
			return isset( self::$_headers[ $header ] ) ? self::$_headers[ $header ] : ( isset( self::$_headers[ strtolower( $header ) ] ) ? self::$_headers[ strtolower( $header ) ] : NULL );
		}
		public static function getFiles(){
			return isset( self::$FILES ) ? self::$FILES : NULL;
		}		
		
	}
?>