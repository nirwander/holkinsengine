<?php
	class Class_routines extends Error{

		public static function loadDir( $dir ){
			if ( !$dir ) return NULL;
			/* else */
			$res = array();
			$script = Filesystem::getPHPScripts( __REQ_TEMPL_DIR );
			for ( $i = 0; $i < count( $script ); $i++ ){
				$parts = pathinfo( $script[$i] );
				$fileParts = explode( ".", $parts["basename"] );
				if ( count( $fileParts ) == 3 && strtolower( $fileParts[ 1 ] ) == "class" ){
					require_once $script[$i];
					if ( class_exists( $fileParts[ 0 ] ) ){
						$res[] = $fileParts[ 0 ];
					}else{
						self::raiseException( ERR_ITS_NOT_CLASS, $script[$i] );
						return NULL;
					}
				}
			}
			return $res;
		}
		
		public static function loadClass( $filename ){
			if ( !file_exists( $filename ) ){
				self::raiseException( ERR_FILE_NOT_EXISTS, $filename );
				return NULL;
			}
			/* else */
			require_once $filename;
			$parts = pathinfo( $filename );
			$fileParts = explode( ".", $parts["basename"] );
			if ( class_exists( $fileParts[ 0 ] ) ){
				return $fileParts[ 0 ];
			}else{
				self::raiseException( ERR_ITS_NOT_CLASS,  $fileParts[ 0 ] );
				return NULL;
			}			
		}
		
		public static function filterByParent( $classes, $daddy ){
			if ( !class_exists( $daddy ) || !is_array( $classes ) ) return NULL;
			/* else */
			$res = array();
			for ( $i = 0; $i < count( $classes ); $i++ ){
				if ( !class_exists( $classes[$i] ) ) return NULL;
				/* else */
				if ( get_parent_class( $classes[$i] ) == $daddy ){
					$res[] = $classes[$i];
				}
			}
			return $res;
		}
	}
?>