<?php
	class Router extends Error{
	
		private static $_instance;
		
		private function __construct(){}

		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance ) ){
				self::$_instance = new self;
			}
			return self::$_instance;
		}
		

		
		//admin/type=modify&target=gallery&action=add&params[title]=newOne&params[type]=gallery&params[ord]=end
		public static function getController(){
			self::getInstance();
			if ( !__REQ_TEMPL_DIR ){
				self::raiseException( ERR_REQ_TEMPL_DIR_NOT_SET );
				return NULL;
			}
			/* else */			
			if ( ( $classes = Class_routines::loadDir( __REQ_TEMPL_DIR ) ) !== NULL ){
				$arr = Class_routines::filterByParent( $classes, "Request_template" );
				$res = NULL;
				//echo "<pre>"; print_r( $arr ); echo "</pre>";
				for ( $i = 0; $i < count( $arr ); $i++ ){
					$obj = NULL;
					$obj = new $arr[$i];
					$resNew = $obj->checkRequest( Request::getAllVar() );
					if ( $resNew == NULL ) continue;
					/* else */
					if ( $res == NULL ){
						$res = $resNew;
						$reqName = $arr[$i];
					}else{
						self::raiseException( ERR_MORE_THAN_ONE_ROUTE );
						return NULL;
					}
				}
				if ( $res === NULL ){					
					self::raiseException( ERR_ROUTER_NO_ROUTE_FOUND, Request::getAllVar() );
					return NULL;					
				}
				modelOrder::_safetySessionStart();				
				$ctrl = Class_routines::loadClass( __ROOT_PATH.DS."controller".DS."ctrl".$res[ "controller" ].".class.php" );
				Registry::set( "requestTemplate", ( new $reqName ) );
				Registry::set( "controllerParams", $res[ "__params" ] );
				Registry::set( "viewName", $res[ "controller" ] );
				Registry::set( "controller", ( new $ctrl ) );
				return Registry::get( "controller" );
			}else{
				self::raiseException( ERR_CANNOT_LOAD_REQ_TEMPL );
			}
		}
		public function getController2()
		{
			$route = pathinfo( Request::getVar( "route9sd3jdk3", "GET" )."/1.1" );
			unset( $_GET[ "route9sd3jdk3" ] );
			$route = $route[ "dirname" ];
			$route = preg_replace( "/index\.php$/i", "", $route );
			$route = preg_replace( "/\/$/", "", $route );			
			if ( ( $rm = RoadMap::get() ) != NULL )
			{
				$found = false; $i = 0;
				$matches = array();
				for ( ; !$found && $i < count( $rm ); $i++ )
				{
					$found = ( preg_match_all( "/^".addcslashes( $rm[ $i ][ "url" ], "/" )."$/", $route, $matches ) );
				}

				if ( $found && $rm[ $i-1 ][ "hasPage" ] != 0 )
				{
					$name = ucfirst( $rm[ $i-1 ][ "ctrl" ] );
					$ctrl = Class_routines::loadClass( __ROOT_PATH.DS."controller".DS."ctrl".$name.".class.php" );
					Registry::set( "controllerParams", array( "route" => $route, "XHROnly" => $rm[ $i-1 ][ "XHROnly" ] ) );
					Registry::set( "viewName", $name );
					Registry::set( "controller", ( new $ctrl ) );
					Registry::set( "route:params", $matches );
					return Registry::get( "controller" );
				}
				else
				{
					self::raiseException( !$found ? ERR_ROUTER_NO_ROUTE_FOUND : ERR_ROUTE_IS_NOT_LEAF, $route );
					return NULL;					
				}
			}
		}		
	}
?>