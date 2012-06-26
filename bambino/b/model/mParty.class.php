<?php
	class mParty extends modelBase{

		protected static $_instance = NULL;
		
		protected function __construct(){
			self::$_db = dbMysql::getInstance();
			if ( !self::$_db ) return NULL; else return $this;
		}
		
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		protected function _ident( $guest, $id ){
			$id = md5( $this->asInteger( $id ) );			
			$sql = "select 1 res from b_guest where n = {$this->asInteger($guest)} and id = '$id'";
			$res = self::$_db->fireSQL( $sql );			
			return $res[ 0 ][ "res" ] > 0;
		}
		public function getCharacters(){
			$sql = "select n, title, who guestN, ( select name from b_guest where n = who ) guest from b_character";
			return self::$_db->fireSQL( $sql );
		}
		public function getGuests(){
			$sql = "select n, name guest from b_guest";
			return self::$_db->fireSQL( $sql );
		}		
		public function chooseCharacter( $guest, $id, $character ){
			$id = md5( $this->asInteger( $id ) );			
			$sql = "select 1 res from b_guest where n = {$this->asInteger($guest)} and id = '$id'";
			$res = self::$_db->fireSQL( $sql );			
			if ( $res[ 0 ][ "res" ] > 0 ){
				$sql = "select 1 res from b_character where who = {$this->asInteger($guest)}";
				$res = self::$_db->fireSQL( $sql );
				if ( $res[ 0 ][ "res" ] < 1 ){
					$sql = "update b_character set who = {$this->asInteger($guest)} where n = {$this->asInteger($character)} and not exists(select null from b_guest where n = who)";
					$res = $this->_modify( $sql );
					return (integer)$res > 0 ? 0 : -2;
				}else return -3;
			} else return -1;
		}
		public function unsetMe(){
			$who = Request::getVarAsInteger( "guest", "POST" );
			$id = Request::getVarAsInteger( "id", "POST" );
			if ( $this->_ident( $who, $id ) )
				return $this->_modify( "update b_character set who = -1 where who = $who" );
			else return false;
		}
		
	}
?>