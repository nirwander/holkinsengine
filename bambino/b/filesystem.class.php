<?php
	class Filesystem extends Error{
		
		private static function _getFilesInDir( $dir, $ext = NULL ){
			$res = array();
			if ( $handle = opendir( $dir ) ) {
				while ( false !== ( $file = readdir( $handle ) ) ) {
					if ( $file != "." && $file != ".." &&  is_file( $dir.DS.$file ) ) {
						$pathParts = pathinfo( $dir.DS.$file );
						if ( $ext && strtolower( $pathParts["extension"] ) == strtolower( $ext ) ){
							$res[] = ( substr( $dir, -1 ) == DS ? substr( $dir, 0, -1 ).DS.$file : $dir.DS.$file );
						}else if ( !$ext ){
							$res[] = ( substr( $dir, -1 ) == DS ? substr( $dir, 0, -1 ).DS.$file : $dir.DS.$file );
						}
					}
				}
				closedir($handle);
			}else{
				self::raiseException( ERR_CANNOT_OPEN_DIR, $dir );
				return NULL;
			}
			return $res;
		} 
		
		public static function getPHPScripts( $dir ){
			return self::_getFilesInDir( $dir, "php" );
		}
		
		public static function getFileContent( $filename ){
			if ( ( $handle = fopen($filename, "r") ) != false ){
				if ( ( $contents = fread( $handle, filesize( $filename ) ) ) != false ){
					fclose($handle);			
					return $contents;
				}else{
					self::raiseException( ERR_FS_FILE_READ, $filename );
					return NULL;					
				}
			}else{
				self::raiseException( ERR_FS_FILE_OPEN, $filename );
				return NULL;
			}
		}
		public static function saveToFile( $filename, $content ){
			
			@mkdir( dirname( $filename ) );
			if ( ( $handle = fopen($filename, "w") ) != false ){
				return fwrite( $handle, $content );
			}else{
				self::raiseException( ERR_FS_FILE_OPEN, $filename );
				return NULL;
			}			
		}
		
		public static function getFilesFromDir( $dir, $ext = NULL ){
			return self::_getFilesInDir( $dir, $ext );
		}
		
		public static function removeDir( $dirname ){
			if ( is_dir( $dirname ) )
				$dir_handle = opendir( $dirname );
			if ( !$dir_handle ) return false;
			/* else */
			while( $file = readdir( $dir_handle ) ){
				if ( $file != "." && $file != ".."){
					if ( !is_dir( $dirname."/".$file ) ){
						unlink( $dirname."/".$file );
					}else{
						self::removeDir( $dirname.'/'.$file );
					}
				}
			}
			closedir( $dir_handle );
			@rmdir( $dirname );
			return true;
		}
    

		
		public static function absoluteUrl( $relative ) {
			
			$absolute = "http://".$_SERVER["HTTP_HOST"].dirname( $_SERVER["PHP_SELF"] );
			
	        $p = parse_url( $relative );
	        if ( $p["scheme"] && strlen( $p[ "scheme" ] ) > 1 ) return $relative;
	        /* else */
			if ( strlen( $p[ "scheme" ] ) == 1 ){
				$pos = strpos( dirname( $relative ), __ROOT_PATH );
				if ( $pos != 0 ){
					self::raiseException( ERR_FS_INVALID_INPUT_PATH, $relative );
					return false;
				}
				$len = strlen( __ROOT_PATH );
				return self::absoluteUrl( str_replace( "\\", "/", substr( $relative, $len + 1 ) ) );
			}
	        extract( parse_url( $absolute ) );
	        
	        $path = dirname( $_SERVER["PHP_SELF"] ); 
			
	        if($relative{0} == '/') {
	            $cparts = array_filter(explode("/", $relative));
	        }
	        else {
	            $aparts = array_filter(explode("/", $path));
	            $rparts = array_filter(explode("/", $relative));
	            $cparts = array_merge($aparts, $rparts);				
				
	            foreach($cparts as $i => $part) {
					//echo "Part: ".$part."<br/>";
	                if( $part == '.' ) {
	                    $cparts[$i] = false;
	                }else if( $part == '..' ){
						$j = $i;
						$cparts[ $j ] = false;
						while ( !$cparts[ $j ] ){
							//echo $cparts[ $j ]."<br/>";							
							$cparts[ $j-- ] = false;
						}
						$cparts[ $j ] = false;
	                }
	            }
	            $cparts = array_filter($cparts);
	        }
	        $path = implode("/", $cparts);
	        $url = "";
	        if($scheme) {
	            $url = "$scheme://";
	        }
	        if($user) {
	            $url .= "$user";
	            if($pass) {
	                $url .= ":$pass";
	            }
	            $url .= "@";
	        }
	        if($host) {
	            $url .= "$host/";
	        }
	        $url .= $path;
	        return $url;
    	}		
		
	}
?>