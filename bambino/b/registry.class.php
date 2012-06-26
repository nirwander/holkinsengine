<?php
	final class Registry extends Error{
		private static $_entries = array();
		
		private function __construct(){}
		private function __clone(){}
		
		private static function exists( $name ){
			return array_key_exists( $name, self::$_entries );
		}
		
		public static function set( $name, $obj ){
			//if ( !self::exists( $name ) ){
				self::$_entries[ $name ] = $obj;
			//}
		}

		public static function get( $name ){
			if ( self::exists( $name ) ){
				return self::$_entries[ $name ];
			}else{
				return NULL;
			}
		}
	}
?>