<?php
	class RoadMap extends Error{
		
		protected static $_attr = array( "url", "hasPage", "ctrl" );
		
		private function __construct(){}
		private function __clone(){}
		
		public static function get(  )
		{			
			$rmFile = preg_replace( "/\/\*([^x00]*)\*?\/?\*\//U", "", Filesystem::getFileContent( Config::ROADMAP ) );			
			$roadMap = JSON::decode( $rmFile, true );
			$tmpl = self::$_attr;
			$cnt = 0; $good = is_array( $roadMap );
				//print_r( $roadMap ); die;
			for ( $i = 0; $good && is_array( $roadMap ) && $i < count( $roadMap ); $i++ )
			{
				$el = $roadMap[ $i ];
				foreach ( $el as $k => $v )
					if ( ( $pos = array_search( $k, $tmpl ) ) !== FALSE )
					{
						$tmpl[ $pos ] = "asnfcjrwpht984fh9prewgf84fds";
						$cnt++;
					}
				$good = ( $cnt == count( self::$_attr ) );
			}
			if ( $good )
			{
				return $roadMap;
			}
			else
			{
				self::raiseException( ERR_ROADMAP );
				return NULL;
			}
		}		
	}
?>