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
	require_once __ROOT_PATH.DS."textImg.class.php";
	require_once __ROOT_PATH.DS."Auth.class.php";
	require_once __ROOT_PATH.DS."text.class.php";
	require_once __ROOT_PATH.DS."helpers".DS."photoManager.class.php";

	require_once __REQ_TEMPL_DIR.DS."request_template.class.php";
	
	require_once __ROOT_PATH.DS."IObservable.interface.php";
	require_once __ROOT_PATH.DS."IObserver.interface.php";

	require_once __ROOT_PATH.DS."plugin".DS."pluginBase.class.php";
	require_once __ROOT_PATH.DS."controller".DS."ctrlBase.class.php";

	require_once __ROOT_PATH.DS."model".DS."modelBase.class.php";
	require_once __ROOT_PATH.DS."model".DS."mCatalog.class.php";

	require_once __ROOT_PATH.DS."view".DS."viewBase.class.php";
	require_once __ROOT_PATH.DS."view".DS."viewPlugin.class.php";

	require_once __ROOT_PATH.DS."node.class.php";
	require_once __ROOT_PATH.DS."tree.class.php";
	require_once __ROOT_PATH.DS."xmlNode.class.php";
	require_once __ROOT_PATH.DS."xmlTree.class.php";
	require_once __ROOT_PATH.DS."xmldocument.class.php";

	require_once __ROOT_PATH.DS."component".DS."componentBase.class.php";
	
	$ctg = mCatalog::getInstance();
	//if ( $ctg->removeItem( 6 ) ){ die( "Yes" );	}

	/*if ( $ctg->setCursor( 9 ) ){		
		$ctg->addItem( "asdasasdas", "asdsad" );
	}
	die;*/
	$tel = "+7-(902)-505-5174";
	preg_match_all( "|([0-9]){1}|", $tel, $out );
	print_r( count( $out[ 1 ] ) );
	//$digits = tel.match( ( new RegExp( "([0-9]){1}", "g" ) ) );
	//return ( new RegExp( "^([0-9\-+\(\)]*)$" ) ).test( tel ) && digits && digits.length == 11;
	die;
	echo "<pre>";		$ctg->buildTree();	echo "</pre>"; die;
	if ( $ctg->setCursor( 1 ) ){
		/*if ( $ctg->applyFilters( array( 1, 2 ) ) ){
			echo "Yes";
		}*/
		$ctg->addItem( "new9", "href12" );
		//if ( $ctg->chngDaddy( 1 ) ){			echo "Yes";		}else echo $ctg->getLastError();
	}
	die;
	if ( $ctg->setCursor( 7 ) ){
		if ( $ctg->addItem( "new7", "href1" ) ){
			echo "Yes";
		}else{
			echo $ctg->getLastError();
		}
	}else{
		echo $ctg->getLastError();
	}
	
?>