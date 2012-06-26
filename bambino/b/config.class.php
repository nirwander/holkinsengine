<?php
	class Config extends Error{
		
		const PHOTO_DIR = "images";
		const MAX_TIME_SESSION_STANDALONE = 600;
		const PLUGINS = "";
		const SITE_DIR = "./";
		const LIB_DIR = "b/";
		const PREVIEW_DIR = "preview";
		const CACHE_DIR = "b/";
		
		public static $mysqlConnection = array( "server" => "localhost",
												"user" => "eddUser",
												"pswd" => "hUDBUzDNBjqBHqje", //eoC]XqZ9R#&h
												"database" => "eddison" );
	}
?>