<?php
	class viewPlugin extends viewBase{

		public function __construct( $template=NULL ){
			if ( isset( $template ) && is_string( $template ) ){
				$this->_template = $template;
			}
			return parent::__construct();
		}

	}
?>