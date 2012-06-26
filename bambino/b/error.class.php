<?php
	class Error{
	
		const OK = "Everything is OK";
		const ERR_CANNOT_LOAD_REQ_TEMPL = "Cannot load request templates for check GET- or POST-parameters";
		const ERR_REQ_TEMPL_DIR_NOT_SET = "Constant __REQ_TEMPL_DIR is not set in executable(main) script";
		const ERR_CANNOT_OPEN_DIR = "Cannot open directory or it doesn't exist";
		const ERR_FILE_NOT_EXISTS = "Cannot open directory or it doesn't exist";
		const ERR_ITS_NOT_CLASS = "Such file does not class-defenition-file or it's invalid";
		const ERR_BAD_REQPARAM_NOTEXISTS = "Some of GET- or POST-parameter are not set";
		const ERR_BAD_REQPARAM_VALUE = "Value of some of GET- or POST-parameter is not allowed";
		const ERR_BAD_REQPARAM_TYPE = "Some of GET- or POST-parameter type is not right";
		const ERR_REQPARAM_NOTENOUGH = "No params in request_template class";
		const ERR_MORE_THAN_ONE_ROUTE = "We have more than one route for combination of parameters";
		const ERR_CTRL_PARAMS_ARE_NOT_SET = "Controller cannot process without input params. Check Router::getController set controller params";
		const ERR_MYSQL_CONNECTION_STRING = "Cannot connect to Mysql, check connection string returned by Config::mysqlConnection";
		const ERR_MYSQL_SELECT_DB = "Cannot select db after successful connection";
		const ERR_MYSQL_DONT_SEE_CONNECTION_STRING = "Don't see anything in Config::mysqlConnection";
		const ERR_MYSQL_INVALID_QUERY = "Invalid query";
		const ERR_MODEL_ADD_GALLERY_INVALID_TYPE = "Model: there is no such gallery type in DB in dictionary";
		const ERR_MODEL_ADD_GALLERY_INVALID_ORD = "Model: bad order value ( less or greater min or max+1, correspondetly)";
		const ERR_MODEL_CANNOT_INSERT = "Model: Cannot insert new gallery item";
		const ERR_ROUTER_NO_ROUTE_FOUND = "Router: No route found for request";
		const ERR_MODEL_NOT_ENOUGH_PARAMS = "Model: Not enough params, or some of them are empty";
		const ERR_FS_FILE_READ = "Filesystem: cannot read file";
		const ERR_FS_FILE_OPEN = "Filesystem: cannot open file";
		const ERR_TEXTIMG_FONT_ISNT_SET = "textImg: check config for font is set";
		const ERR_TEXTIMG_FONTCOLOR_ISNT_SET = "textImg: check config for font-color is set";
		const ERR_TEXTIMG_PADDING_ISNT_SET = "textImg: check config for padding is set";				
		const ERR_TEXTIMG_TRANSPARENT_ISNT_SET = "textImg: check config for transparent is set";				
		const ERR_TEXTIMG_BGCOLOR_ISNT_SET = "textImg: check config for background-color is set";				
		const ERR_TEXTIMG_CANNOT_SAVE = "textImg: cannot save generated img to file";				
		const ERR_NODE_INVALID_DADDY = "Node: invalid parent";				
		const ERR_NODE_ALREADY_HAS_PARENT = "Node: already has parent";				
		const ERR_TREE_CHILD_IS_ALREADY_IN = "Tree: child is already in";				
		const ERR_TREE_NOT_MY_CHILD = "Tree: not a child";				
		const ERR_VIEW_XML_TEMPLATE_IS_INVALID = "View: site xml-template is invalid, check inside";				
		const ERR_XML_INVALID_XML = "XML: xml-content is invalid, check inside";
		const ERR_COMP_UNKNOWN_PARAM_TYPE = "Component: unknown param type in component's input template";
		const ERR_COMP_PARAM_TEMPL_BAD_ARRAY = "Component: in param template array cannot have integer keys or maybe too much nested not associative arrays";
		const ERR_COMP_PARAM_TEMPL_COUNT = "Component: in param template 'count' is not set or it's no integer";
		const ERR_COMP_PARAM_TEMPL_BAD_TYPE = "Component: in param template 'type' is not set or it's not proper array";
		const ERR_COMP_WRONG_STRUCTURE = "Component: param, that is passed to component has wrong structure";
		const ERR_FS_INVALID_INPUT_PATH = "Filesystem: cannot convert url to absolute";
		const ERR_VIEW_COMPONENT_COMPILE = "View: component compilation returned NULL tree";	
		const ERR_PHOTO_MANAGER_BAD_DIR = " Photo manager: bad initial dir";
		const ERR_PLUGIN_BASE_BAD_EVENT_TYPE = "PLUGIN_BASE: event type must be a string";
		const ERR_PLUGIN_BASE_BAD_VIEW_TEMPLATE = "PLUGIN_BASE: template should be a string";
		const ERR_PLUGIN_BASE_BAD_VIEW_NAME = "PLUGIN_BASE: view name must be string name of existing view class";
		const ERR_MODEL_BASE_EXISTS_TYPE_MISMATCH = "MODEL_BASE: modelBase::_exists thrown type mismatch";
		const ERR_VIEW_COMPONENT_NOTFOUND = "VIEW_BASE: no valid class found corresponding to givem name.";


		public static function raiseException( $err, $dsc=NULL ){
			if ( defined( "__DEBUG" ) ){
				$trace = debug_backtrace();
				echo "<hr>";
				echo 'Exception: '.$trace[1]["class"]."::".$trace[1]["function"]."<br/>";
				echo "file: ".$trace[1]["file"].", ";
				echo "line: ".$trace[1]["line"]."<br/>";
					echo '----->Exception: '.$trace[0]["class"]."::".$trace[0]["function"]."<br/>";
					echo "----->file: ".$trace[0]["file"].", ";
					echo "line: ".$trace[0]["line"]."<br/>";
				if ( defined( "Error::".$err ) ){
					echo "<b>Description: </b>";
					echo constant( "Error::".$err )."<br/>";
					if ( $dsc ){
						echo "<b>Details:</b> ";
						if ( is_array( $dsc ) || is_object( $dsc ) ){
							print_r( $dsc ); echo "<br/>";
						}else{
							echo $dsc ."<br/>";
						}
					}
				}else{
					if ( $dsc ){
						echo "<b>Custom description: </b>";
						echo $dsc."<br/>";						
					}
				}
			}
		}
	}
?>