<?php
	class Model extends Error{
	
		const DIC_MENU_ITEM_TYPE = 1;
		
		private static $_instance = NULL;
		
		private static $_db = NULL;
		private static $_lastError = NULL;
		
		private function __construct(){}
		
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}

		//admin/type=modify&target=gallery&action=add&params[title]=newOne&params[type]=gallery&params[ord]=end		
		public static function addGallery( $params ){		
			
			self::$_db = dbMysql::getInstance();
			
			if ( !( $params["params"]["title"] && $params["params"]["type"] && $params["params"]["ord"]
				 && strlen( $params["params"]["title"] ) > 0 && strlen( $params["params"]["type"] ) > 0 && strlen( $params["params"]["title"] ) > 0 ) )
			{
				self::raiseException( ERR_MODEL_NOT_ENOUGH_PARAMS, $params["params"] );
				return;
			}
			
			if ( !self::$_db ) return NULL;
			if ( self::_exists( "gallerySections", "title", $params["params"]["title"] ) ){
				echo "Title is already exist";
				self::$_lastError = 1;//MODEL_GALLERY_ALREADY_EXISTS;
				return;
			}else{
				$type = self::_getDicCodeByTerm( self::DIC_MENU_ITEM_TYPE, $params["params"]["type"] );
				if ( !$type ){
					self::raiseException( ERR_MODEL_ADD_GALLERY_INVALID_TYPE, $params["params"]["type"] );
					return;
				}
				/* else */
				$ord = self::_getOrd( $params["params"]["ord"] );
				if ( $ord  == -1 ){					
					self::raiseException( ERR_MODEL_ADD_GALLERY_INVALID_ORD, $params["params"]["ord"] );
					return;
				}
				/* else */
				return self::_insertItem( $params["params"]["title"], $type, $ord );
			}
		}	
		
		public static function getMenuWithOneActive( $params ){
			//title, type, active, href, id, imgSrc
			self::$_db = dbMysql::getInstance();
			if ( self::$_db ){	
				if ( self::_exists( "gallerySections", "href", $params[ "params" ][ "name" ] ) ){
					$sql = "select request as \"href\", html_id as \"id\", imgSrc,";
					$sql .= " ( select term from dic_data where up = ".self::DIC_MENU_ITEM_TYPE." and code = type ) as \"type\",";
					$sql .= " case when href = '".$params[ "params" ][ "name" ]."' then 'true' else 'false' end as \"active\"";
					$sql .= " from gallerySections order by type, ord";
					$res = self::$_db->fireSQL( $sql );

					$vars = Request::getAllVar();
					for ( $i = 0; $i < count( $res ); $i++ ){
						//$vars[ "params" ][ "name" ] = $res[ $i ][ "href" ];
						//$vars[ "show" ] = "gallery";
						$res[ $i ][ "href" ] = $_SERVER[ "PHP_SELF" ]."?".$res[ $i ][ "href" ];//Registry::get( "requestTemplate" )->getRequestString( $vars );
					}
					//print_r( $res ); die;
					return $res;
				}
			}
			return NULL;
		}
		
		public static function getValueByKey( $key, $type=NULL ){
			$keyValTable = array( "pageAttrValueText" /*, "pageAttrValueVarchar"*/ );
			$resCnt = 0;
			if ( $type === NULL ){
				for ( $i = 0; $i < count( $keyValTable ); $i++ ){
					if ( self::_exists( $keyValTable[ $i ], "key$", $key ) ){
						$resCnt++;
						$table = $keyValTable[ $i ];
					}
				}
				if ( $resCnt == 0 ){
					self::raiseException( ERR_MODEL_VAL_BY_KEY_NOT_FOUND, $key );
					return NULL;
				}else if ( $resCnt > 1 ){
					self::raiseException( ERR_MODEL_VAL_BY_KEY_MORE_THAN_ONE_TABLE, $key );
					return NULL;					
				}else{
					$sql = "select value from ".$table." where key$ = '".$key."'";
					$res = self::$_db->fireSQL( $sql );
					if ( count( $res ) > 1 ){
						self::raiseException( ERR_MODEL_VAL_BY_KEY_MORE_THAN_ONE_FOUND, $key );
						return NULL;											
					}
					/* else */					
					return $res[ 0 ][ "value" ];
				}			
			}	
			return NULL;		
		}
		
		private static function _insertItem( $title, $type, $ord ){
			$sqlInsert = "insert into gallerySections(title, type, ord) value( '".$title."', ".$type.", ".$ord." )";
			$sqlUpdate = "update gallerySections set ord = ord + 1 where ord >= ".$ord." and title != '".$title."'";
			if ( self::$_db->fireSQL( $sqlInsert ) > 0 ){
				self::$_db->fireSQL( $sqlUpdate );
				echo "Success";
				return true;
			}else{
				self::raiseException( ERR_MODEL_CANNOT_INSERT );
				return false;
			}
		}

		
		private static function _getOrd( $ord ){
			$ord = preg_match( "/^[0-9]*$/", $params["params"]["ord"] ) ? (int)$ord : $ord;
			$sql = "";
			if ( is_string( $ord ) ){
				switch( $ord ){
					case "end" : $sql = "select max(ord) + 1 ord from gallerySections"; break;
					case "begin" : $sql = "select 0 ord"; break;
					default : $sql = "select -1 ord";
				}
			}else if ( is_integer( $ord ) ){
				
				$sql = "select case when exists( select 1 from gallerySections where type = 1 group by type having min(ord) <= ".$ord." and ".$ord." <= max(ord) + 1 ) ";
				$sql .= "then ".$ord." else -1 end ord";
			}else{				
				$sql = "select -1 ord";
			}
			$res = self::$_db->fireSQL( $sql );
			return $res[0]["ord"];			
		}
		
		
		private static function _exists( $table, $field, $value ){
			if ( strpos( $field, '\'' ) || strpos( $field, '"' ) || strpos( $field, 'insert ' ) || strpos( $field, 'delete ' ) || strpos( $field, 'update ' ) || strpos( $field, 'select ' ) ) return false;
			if ( strpos( $value, '\'' ) || strpos( $value, '"' ) || strpos( $value, 'insert ' ) || strpos( $value, 'delete ' ) || strpos( $value, 'update ' ) || strpos( $value, 'select ' ) ) return false;
			/* else */
			$sql = "select 1 from ".$table." where ".$field." = ".( is_string( $value ) ? "'".$value."'" : $value );
			$res = self::$_db->fireSQL( $sql );
			return is_array( $res );
		}
		
		private static function _getDicCodeByTerm( $dicN, $dicDataTerm ){
			$sql = "select code from dic_data where up = ".$dicN." and term = '".$dicDataTerm."'";
			$res = self::$_db->fireSQL( $sql );
			return is_array( $res ) && count( $res ) == 1 ? $res[0]["code"] : NULL;
		}	
		
		public static function getLastError(){
			return self::$_lastError;
		}
		
	}
?>