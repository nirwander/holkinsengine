<?php
	class ctrlMain extends ctrlBase{
		
		public function __construct(){
			return parent::__construct();
		}
		
		public function process(){

			$params = array();
			$params[ "title" ] = array( array( "title" => "Привет!" ) );
			
			$party = mParty::getInstance();			
			$params[ "characterList" ] = $party->getCharacters();
			$params[ "guestList" ] = $party->getGuests();

			$gb = mGuestBook::getInstance();			
			$params[ "guestBookSite" ] = $gb->getPage( array( "n", "nick", "email", "date", "msg", "admin_answer", "admin_answer_date" ), 1 );
			
			$vote = mVote::getInstance();
			$params[ "voteList" ] = $vote->getAll();

			$c = $this->_view->compose( $params );

			Response::setContent( $c );
			return true;
		}
	}
?>