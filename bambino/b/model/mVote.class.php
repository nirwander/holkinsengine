<?php
	class mVote extends modelBase{

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
		
		public function getAll(){
			$sql = "select n, name, rate from b_vote";			
			return self::$_db->fireSQL( $sql );
		}				
		public function rateUp(  ){
			$target = Request::getVarAsInteger( "target", "POST" );
			return self::_modify( "update b_vote set rate = rate + 1 where n = {$this->asInteger($target)}" );
		}
	
	}
?>