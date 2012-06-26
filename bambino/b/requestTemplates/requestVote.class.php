<?php
	class requestVote extends Request_template{	

		//Parameters, that controller selection is depended on
		protected $_vote = NULL;

		//Parameters, that would be passed to controller to rule the Model or/and the View
		
		public function getCtrlName(){
			return "Vote";
		}
		
	}

?>