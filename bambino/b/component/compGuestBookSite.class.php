<?php
	class compGuestBookSite extends componentBase{
	
		protected $_paramsValid = array( "count" => -1,
									   "type" => array( "n" => "integer",
														"nick" => "string",
														"email" => "string",
														"date" => "string",
														"msg" => "string",
														"admin_answer" => "string",
														"admin_answer_date" => "string" )
									  );
		
		public function __construct( $params ){
			//print_r($params); die;
			if ( !$this->_isValidParams( $params ) ){		
				return false;
			}else{
				$this->_isValid = true;
				$this->_params = $params;
				return true;
			}
		}

		
		public function getTree($attr=NULL){
			if ( $this->_isValid ){
				
				$t = "item.template.php";
				$content = "";
				for ( $i = 0; is_array( $this->_params ) && $i < count( $this->_params ); $i++ ){
					//if ( $i == 0 ) $content .= "<input type=\"hidden\" id=\"__L\" value=\"{$this->_params[$i]["n"]}\"/>";
					$saltAndPepper = $this->_params[ $i ];					
					$saltAndPepper[ "admin_answer" ] = strlen( $this->_params[ $i ][ "admin_answer" ] ) > 0 ? $this->_compileTemplate( "admin.template.php", array( "date" => $this->_params[ $i ][ "admin_answer_date" ],
																																									"answer" => $this->_params[ $i ][ "admin_answer" ] ) ) : "";
					$content .= $this->_compileTemplate( $t, $saltAndPepper );
				}
				$tree = XMLDocument::buildTree( $content, XMLDocument::BUILD_MODE_FROM_TEXT );
			}else{				
				$tree = new xmlTree();
			}
			
			return $tree;
		}
	}
?>