<?php
	class Text extends Error{
		private static $_trans = array(	"à"=>"a","á"=>"b","â"=>"v","ã"=>"g","ä"=>"d","å"=>"e",
										"¸"=>"yo","æ"=>"j","ç"=>"z","è"=>"i","é"=>"i","ê"=>"k","ë"=>"l",
										"ì"=>"m","í"=>"n","î"=>"o","ï"=>"p","ð"=>"r","ñ"=>"s","ò"=>"t",
										"ó"=>"y","ô"=>"f","õ"=>"h","ö"=>"c","÷"=>"ch",
										"ø"=>"sh","ù"=>"sh","û"=>"i","ý"=>"e","þ"=>"u","ÿ"=>"ya",
										"À"=>"A","Á"=>"B","Â"=>"V","Ã"=>"G","Ä"=>"D","Å"=>"E",
										"¨"=>"Yo","Æ"=>"J","Ç"=>"Z","È"=>"I","É"=>"I","Ê"=>"K",
										"Ë"=>"L","Ì"=>"M","Í"=>"N","Î"=>"O","Ï"=>"P",
										"Ð"=>"R","Ñ"=>"S","Ò"=>"T","Ó"=>"Y","Ô"=>"F",
										"Õ"=>"H","Ö"=>"C","×"=>"Ch","Ø"=>"Sh","Ù"=>"Sh",
										"Û"=>"I","Ý"=>"E","Þ"=>"U","ß"=>"Ya",
										"ü"=>"","Ü"=>"","ú"=>"","Ú"=>"" );

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