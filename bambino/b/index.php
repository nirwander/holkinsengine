<?php
	define( "__EXEC", 1 );
	define( "__DEBUG", 1 );
	define( "DS", "\\" );
	define( "__ROOT_PATH", dirname(__FILE__) );
	define( "__REQ_TEMPL_DIR", __ROOT_PATH.DS.requestTemplates );
	
	require_once __ROOT_PATH.DS."error.class.php";
	require_once __ROOT_PATH.DS."router.class.php";
	require_once __ROOT_PATH.DS."request.class.php";
	require_once __ROOT_PATH.DS."filesystem.class.php";
	require_once __ROOT_PATH.DS."class_routines.class.php";
	require_once __ROOT_PATH.DS."registry.class.php";
	require_once __ROOT_PATH.DS."response.class.php";
	require_once __ROOT_PATH.DS."dbMysql.class.php";
	require_once __ROOT_PATH.DS."config.class.php";
	require_once __ROOT_PATH.DS."Auth.class.php";
	require_once __ROOT_PATH.DS."text.class.php";

	require_once __REQ_TEMPL_DIR.DS."request_template.class.php";
	
	require_once __ROOT_PATH.DS."IObservable.interface.php";
	require_once __ROOT_PATH.DS."IObserver.interface.php";

	require_once __ROOT_PATH.DS."plugin".DS."pluginBase.class.php";
	require_once __ROOT_PATH.DS."controller".DS."ctrlBase.class.php";

	require_once __ROOT_PATH.DS."model".DS."model.class.php";
	require_once __ROOT_PATH.DS."model".DS."modelUser.class.php";
	require_once __ROOT_PATH.DS."model".DS."modelBase.class.php";
	require_once __ROOT_PATH.DS."model".DS."mGuestBook.class.php";
	
	require_once __ROOT_PATH.DS."view".DS."viewBase.class.php";
	require_once __ROOT_PATH.DS."view".DS."viewPlugin.class.php";

	require_once __ROOT_PATH.DS."node.class.php";
	require_once __ROOT_PATH.DS."tree.class.php";
	require_once __ROOT_PATH.DS."xmlNode.class.php";
	require_once __ROOT_PATH.DS."xmlTree.class.php";
	require_once __ROOT_PATH.DS."xmldocument.class.php";

	require_once __ROOT_PATH.DS."component".DS."componentBase.class.php";

	$router = Router::getInstance();

	if ( !$controller = $router->getController() ) die;
	
	if ( $controller->process() ){		
		echo Response::getInstance();
	}
	
?>