<?php
	abstract class pluginBase extends Error implements IObserver{
	
		protected $_observable = NULL;
		protected $_pluginView = NULL;
		protected $_views = array();

		public function notify( IObservable $objSource, $strEventType, $params ){
			if ( !is_string( $strEventType ) ){
				$this->raiseException( ERR_PLUGIN_BASE_BAD_EVENT_TYPE, $strEventType );
				return false;
			}
			/* else */
			if ( method_exists( $this, "_".$strEventType ) ){
				$this->_observable = $objSource;
				if ( is_array( $params ) && !isset( $params[ 0 ] ) ){
					//print_r( $params );
					$_params = array();
					foreach( $params as $param )
						$_params[] = $param;
				}else{					
					$_params = $params;
				}				
				return call_user_func_array( array( $this, "_".$strEventType ), $_params );
			}
		}
		
		public function whatEventWannaListen(){
			$res = array();
			$className = get_class( $this );
			$methods = get_class_methods( $className );
			foreach( $methods as $method ){
				if ( substr( $method, 0, 3 ) == "_on" )
					$res[] = substr( $method, 1 );
			}
			return $res;
		}
			
		protected function _loadView( $template ){
			//if ( !isset( $this->_view ) ){
				if ( !is_string( $template ) ){
					$this->raiseException( ERR_PLUGIN_BASE_BAD_VIEW_TEMPLATE, $template );
					return NULL;
				}
				/* else */
				$this->_pluginView = new viewPlugin( $template );				
			//}
			return $this->_pluginView;
		}
		protected function _getView( $viewName ){
			if ( is_string( $viewName ) ){
				if ( !in_array( $viewName, $this->_views ) ){
					$viewPath = __ROOT_PATH.DS."view".DS.$viewName.DS."view".$viewName.".class.php";
					if ( ( $viewName = Class_routines::loadClass( $viewPath ) ) !== NULL )
						$this->_views[ $viewName ] = new $viewName;
					else return NULL;
				}
				return $this->_views[ $viewName ];
			}else{
				$this->raiseException( ERR_PLUGIN_BASE_BAD_VIEW_NAME, $viewName );
				return NULL;
			}			
		}

	}
?>