<?php
	class requestOrder extends Request_template{

		//Parameters, that controller selection is depended on
		protected $_action = array( "order" );
		//Parameters, that would be passed to controller to rule the Model or/and the View
		
		public function getCtrlName(){
			return "Order";
		}
		
	}

?>