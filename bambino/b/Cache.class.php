<?php
	class Cache extends Error{
	
		const MODE_COMPONENT = 1;		
		const MODE_CLEAR_ALL = 1;
		
		protected static $_t = NULL;		
		
		protected static function _getDir(){ return Config::CACHE_DIR.DS."cache"; }
		
		protected static function _exists( $key ){
			return file_exists( Config::CACHE_DIR.DS."cache".DS.$key.".cache.php" );
		}
		public static function get( $key, $callback_args, $mode=Cache::MODE_COMPONENT ){
			//self::$_t = microtime ();
			switch( $mode ){
				case Cache::MODE_COMPONENT : return self::getComp( $key, $callback_args ); break;
				default : return NULL;
			}
		}
		public static function getComp( $key, $callback_args ){
			if ( self::_exists( $key ) ){
				$c = Filesystem::getFileContent( Config::CACHE_DIR.DS."cache".DS.$key.".cache.php" );
				//echo ( microtime () - self::$_t )."<br/>";
				return unserialize( $c );
			}else{
				$filename = Config::CACHE_DIR.DS."cache".DS.$key.".cache.php";
				$content = call_user_func( $callback_args );
				//echo ( microtime () - self::$_t )."<br/>";
				Filesystem::saveToFile( $filename, serialize( $content ) );				
				return $content;
			}
		}
		public static function clear( $mode=Cache::MODE_CLEAR_ALL ){
			if ( $mode == Cache::MODE_CLEAR_ALL ){
				$c = Filesystem::getPHPScripts( self::_getDir() );
			}
			for ( $i = 0; is_array( $c ) && $i < count( $c ); $i++ ){				
				if ( preg_match( "/^(.+)\.cache\.php$/", $c[ $i ] ) )
					unlink( $c[ $i ] );
			}
		}
	}
?>