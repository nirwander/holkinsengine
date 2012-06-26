<?php
	class Text extends Error{
		private static $_trans = array(	"�"=>"a","�"=>"b","�"=>"v","�"=>"g","�"=>"d","�"=>"e",
										"�"=>"yo","�"=>"j","�"=>"z","�"=>"i","�"=>"i","�"=>"k","�"=>"l",
										"�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r","�"=>"s","�"=>"t",
										"�"=>"y","�"=>"f","�"=>"h","�"=>"c","�"=>"ch",
										"�"=>"sh","�"=>"sh","�"=>"i","�"=>"e","�"=>"u","�"=>"ya",
										"�"=>"A","�"=>"B","�"=>"V","�"=>"G","�"=>"D","�"=>"E",
										"�"=>"Yo","�"=>"J","�"=>"Z","�"=>"I","�"=>"I","�"=>"K",
										"�"=>"L","�"=>"M","�"=>"N","�"=>"O","�"=>"P",
										"�"=>"R","�"=>"S","�"=>"T","�"=>"Y","�"=>"F",
										"�"=>"H","�"=>"C","�"=>"Ch","�"=>"Sh","�"=>"Sh",
										"�"=>"I","�"=>"E","�"=>"U","�"=>"Ya",
										"�"=>"","�"=>"","�"=>"","�"=>"" );

		public static function toTranslit( $str ){
			if ( is_string( $str ) ){
				return strtr( $str, self::$_trans );
			}else if ( is_array( $str ) ){
				$res = array();
				foreach( $str as $k => $v ){
					$res[ $k ] = strtr( $v, self::$_trans );
				}
				return $res;
			}else{
				return NULL;
			}
		}
		
		public static function toCorrectUrl( $str ){
			if ( !is_string( $str ) ) return NULL;
			/* else */	
			$str = strtolower( trim( $str ) );
			$str = self::toTranslit( $str );
			$res = "";
			for ( $i = 0; $i < strlen( $str ); $i++ ){
				if ( ctype_alnum( $str{ $i } )  ){
					$res .= $str{ $i };
				}else if ( $str{ $i } == "-" || $str{ $i } == "_" || $str{ $i } == "+" ){
					$res .= $str{ $i };
				}else if ( $str{ $i } == " " ){
					$res .= "-";
				}
			}
			return $res;
		}
	}
?>