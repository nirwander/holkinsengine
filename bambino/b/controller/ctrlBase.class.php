<?php
	abstract class ctrlBase extends Error implements IObservable{
		
		protected $_params = NULL;
		protected $_model = NULL;
		protected $_view = NULL;
		protected $_observers = array(  );
		
		public function __construct(){
			$this->_params = Registry::get( "controllerParams" );
			if ( !isset( $this->_params ) ){
				$this->raiseException( ERR_CTRL_PARAMS_ARE_NOT_SET );
				return NULL;
			}
			$viewPath = __ROOT_PATH.DS."view".DS.Registry::get( "viewName" ).DS."view".Registry::get( "viewName" ).".class.php";
			$viewName = Class_routines::loadClass( $viewPath );
			$this->_view = new $viewName;
			$this->_turnPluginsOn();
			/*echo "<pre>";			
				print_r( $this->_observers ); die;
			echo "</pre>";*/
			return true;
		}
		protected function _setView( $viewName ){
			$viewPath = __ROOT_PATH.DS."view".DS.$viewName.DS."view".$viewName.".class.php";
			$viewName = Class_routines::loadClass( $viewPath );
			$this->_view = NULL;
			$this->_view = new $viewName;
		}
		
		abstract public function process();
		
		//protected function _load
		
		protected function _turnPluginsOn(){
			if ( strlen( trim( Config::PLUGINS ) ) < 1 ) return;
			/* else */
			$plugins = explode( ",", Config::PLUGINS );
			for ( $i = 0; $i < count( $plugins ); $i++ ){
				$pluginName = Class_routines::loadClass( __ROOT_PATH.DS."plugin".DS."plg".$plugins[ $i ].".class.php" );
				$plugin = new $pluginName;
				//print_r( $plugin );
				foreach( $plugin->whatEventWannaListen() as $eventType ){
					$this->addObserver( $plugin, $eventType );
				}
			}
		}

		public function addObserver( IObserver $objObserver, $strEventType ){
			if ( !isset( $this->_observers[ $strEventType ] ) )
				$this->_observers[ $strEventType ] = array();
			$this->_observers[ $strEventType ][] = $objObserver;
		}

		public function fireEvent( $strEventType, $params ){
			if( is_array( $this->_observers[ $strEventType ] ) ){
				foreach ( $this->_observers[ $strEventType ] as $objObserver ){
					$objObserver->notify( $this, $strEventType, $params );
				}
			}			
		}

	}
?>