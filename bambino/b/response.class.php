<?php
	class Response extends Error{
		
		private static $_instance = NULL;
		
		private static $_headers = array();
		
		private static $_docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
		
		private static $_content = NULL;

		private function __contruct(){}
		
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
				//self::setHeader( "Cache-Control", "no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
			}
			return self::$_instance;
		}
		
		public static function asText(){
			self::getInstance();
			if ( isset( self::$_instance ) ){
				if ( Request::getHeader( "X-Powered-By" ) == "XMLHTTPRequestHola" )
					header( "Content-type: application/json" );
				for ( $i = 0; $i < count( self::$_headers ); $i++ ){
					header( self::$_headers[ $i ][ "key" ].": ".self::$_headers[ $i ][ "value" ] );
				}
				if ( Request::getHeader( "X-Powered-By" ) != "XMLHTTPRequestHola" ){
					echo self::$_docType;
				}else{
					self::$_content = iconv("windows-1251", "UTF-8", self::$_content );
				}					
				//echo self::$_content; die;
				//self::$_content = iconv( "UTF-8", "windows-1251", self::$_content );
				self::$_content = iconv( "UTF-8", "windows-1251", self::$_content );				
				return ( string )self::$_content;
			}else{
				return NULL;
			}
		}
		
		public function __toString(){
			self::getInstance();
			return self::asText();
		}
		
		public static function setContent( $cont ){
			self::getInstance();			
			self::$_content = $cont;
		}
		public static function setHeader( $header, $value ){
			self::getInstance();
			self::$_headers[ $header ] = $value;
		}
		
	}
?>