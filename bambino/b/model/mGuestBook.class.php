<?php
	class mGuestBook extends modelBase{		
		
		const GB_MSG_MIN_LENGTH = 2;
		const GB_MSG_MAX_LENGTH = 2048;
		const GB_MSG_PER_PAGE = 1000;
		protected static $_instance = NULL;
		
		protected function __construct(){			
			self::$_db = dbMysql::getInstance();
			if ( !self::$_db ) return NULL;
			else return $this;
		}
		
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}

		public function getAll( Array $fields ){
			$fields = implode( ", ", $fields );
			return self::$_db->fireSQL( "select $fields from b_guestbook order by date desc" );
		}		
		public function getPage( Array $fields, $page=1 ){
			/*if ( in_array( "date", $fields ) )
				$fields[ "date" ] = "date_format( date, '%Y.%m.%d&nbsp;&nbsp;&nbsp;%H:%i' ) date";*/
			if ( in_array( "admin_answer_date", $fields ) )
				$fields[ "admin_answer_date" ] = "date_format( admin_answer_date, '%Y.%m.%d&nbsp;&nbsp;&nbsp;%H:%i' ) admin_answer_date";

				$fields = implode( ", ", $fields );
			$offset = self::GB_MSG_PER_PAGE * ( $page - 1 );
			//echo $fields;
			return self::$_db->fireSQL( "select $fields from b_guestbook order by date desc limit $offset, ".self::GB_MSG_PER_PAGE );
		}
		
		public function delete( $n ){
			return $this->_modify( "delete from b_guestbook where n = {$this->asInteger( $n )}" );
		}
		public function add(){						
			$m = trim( Request::getVar( "gb[msg]", "POST" ) );
			$n = trim( Request::getVar( "gb[nick]", "POST" ) );
			$m = iconv( "utf-8", "cp1251", $m );
			$n = iconv( "utf-8", "cp1251", $n );			
			if ( GB_MSG_MIN_LENGTH - 1 < strlen( $m ) && strlen( $m ) < self::GB_MSG_MAX_LENGTH + 1
			  && strlen( $n ) > 0 && strlen( $n ) < 40 ){
					
					$sql = "insert into b_guestbook( up, msg, date, email, nick, ip ) 
							values( -1, {$this->asString( $m )}, sysdate(), '', {$this->asString( $n )}, '{$_SERVER[ "REMOTE_ADDR" ]}' )";
					$res = $this->_modify( $sql );
					return (bool)$res;
			}else{
				$this->_saveError( array( "code" => 999, "dsc" => "Слишком длинное сообщение или не верно указан ник, а ещё возможно вы не верно ввели текст с каритнки" ) );
				return false;				
			}
		}
		public function getSince( Array $fields, $lastN ){
			$fields = implode( ", ", $fields );
			return self::$_db->fireSQL( "select $fields from b_guestbook where n > {$this->asInteger($lastN)} order by date desc" );
		}
	}
?>