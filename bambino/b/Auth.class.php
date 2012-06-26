<?php
	class Auth extends Error{

		private static $_instance = NULL;
		private static $_sessionPath = NULL;
		
		private function __construct(){}
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		
		private static function _safetySessionStart(){
			if ( session_id() == "" ){
				session_name( "SID" );
				$path = pathinfo( $_SERVER[ "PHP_SELF" ] );
				self::$_sessionPath = $path[ "dirname" ];					
				session_set_cookie_params( Config::MAX_TIME_SESSION_STANDALONE, self::$_sessionPath );				
				session_start();
			}		
		}
		
		public static function letHimGo(){
			self::_safetySessionStart();
			if ( isset( $_SESSION[ "admin-auth" ] ) && $_SESSION[ "admin-auth" ] == "ok" ){
				if ( isset( $_SESSION[ "last-refreshed" ] ) && time() - $_SESSION[ "last-refreshed" ] < Config::MAX_TIME_SESSION_STANDALONE ){
					$_SESSION[ "last-refreshed" ] = time();		
					setcookie( session_name(), session_id(), $_SESSION[ "last-refreshed" ] + Config::MAX_TIME_SESSION_STANDALONE, self::$_sessionPath );
					return true;
				}else{
					self::unregisterHim();
					return false;
				}
			}else{
				return false;
			}
		}
		
		public static function registerHim(){			
			self::_safetySessionStart();
			$_SESSION[ "admin-auth" ] = "ok";
			$_SESSION[ "last-refreshed" ] = time();
		}
		public static function unregisterHim(){
			self::_safetySessionStart();
			session_unset();
			if ( isset( $_COOKIE[ session_name() ] ) ) {
			   setcookie( session_name(), '', time() - 42000, self::$_sessionPath );
			}
		}
	}
?>