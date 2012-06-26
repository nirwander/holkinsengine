<?php

	class textImg extends Error{
		
		private static $_instance = NULL;
		
		private static $_font = NULL;
		private static $_padding = NULL;
		private static $_transparent = NULL;
		private static $_backgroundColor = NULL;
		
		private function __construct(){}
		private function __clone(){}
		
		public static function getInstance(){
			if ( !isset( self::$_instance) ){
				self::$_instance = new self;
				self::_init();
			}
			return self::$_instance;
		}
		
		private static function _init(){
			self::$_font = Config::$textImg[ "font" ];
			self::$_padding = Config::$textImg[ "padding" ];
			self::$_transparent = Config::$textImg[ "transparent" ];
			self::$_backgroundColor = Config::$textImg[ "backgroundColor" ];
			if ( !isset( self::$_font["family"] ) || !isset( self::$_font["size"] ) || !isset( self::$_font["color"] ) ){
				self::$_insatnce = NULL;
				self::raiseException( ERR_TEXTIMG_FONT_ISNT_SET );
			}else if ( !isset( self::$_font["color"]["red"] ) || !isset( self::$_font["color"]["green"] ) || !isset( self::$_font["color"]["blue"] ) ){
				self::$_instance = NULL;
				self::raiseException( ERR_TEXTIMG_FONTCOLOR_ISNT_SET );
			}else if ( !isset( self::$_padding ) ){
				self::$_instance = NULL;
				self::raiseException( ERR_TEXTIMG_PADDING_ISNT_SET );
			}else if ( !isset( self::$_transparent ) ){
				self::$_instance = NULL;
				self::raiseException( ERR_TEXTIMG_TRANSPARENT_ISNT_SET );
			}else if ( !isset( self::$_backgroundColor["red"] ) || !isset( self::$_backgroundColor["green"] ) || !isset( self::$_backgroundColor["blue"] ) ){
				self::$_instance = NULL;
				self::raiseException( ERR_TEXTIMG_BGCOLOR_ISNT_SET );
			}
		}
		
		public static function writeDown( $txt, $filename ){
		
			if ( !( $image = self::_createTTFImage( $txt ) ) ) return false;
			/* else */		
			try{
				imagePNG( $image, $filename );
			}catch( Exception $e ){
				self::raiseException( ERR_TEXTIMG_CANNOT_SAVE, $e );
				imagedestroy($image);
				return NULL;
			}
			imagedestroy($image);
			return true;
		}
		
		protected static function _createTTFImage( $txt ){
			if ( !self::getInstance() ) return NULL;
			/* else */
			
			$bounds = ImageTTFBBox( self::$_font["size"], 0, self::$_font["family"], "W" );

			$fontHeight = abs( $bounds[7] - $bounds[1] );

			$bounds = ImageTTFBBox( self::$_font["size"], 0, self::$_font["family"], $txt );

			$width = abs( $bounds[4] - $bounds[6] );
			$height = abs( $bounds[7] - $bounds[1] );
			$offsetY = $fontHeight;
			$offsetX = 0;

			
			$image = imagecreate( $width + ( self::$_padding * 2 ) + 1, $height + ( self::$_padding * 2 ) + 1 );
			$background = ImageColorAllocate( $image, self::$_backgroundColor["color"]["red"],
													  self::$_backgroundColor["color"]["green"],
													  self::$_backgroundColor["color"]["blue"] );
			$foreground = ImageColorAllocate( $image, self::$_font["color"]["red"],
													  self::$_font["color"]["green"],
													  self::$_font["color"]["blue"] );
		
			if ( self::$_transparent ) ImageColorTransparent( $image, $background );
			ImageInterlace( $image, false );
		
			// render the image
			ImageTTFText( $image, self::$_font["size"],
								  0,
								  $offsetX + self::$_padding,
								  $offsetY + self::$_padding,
								  $foreground,
								  self::$_font["family"],
								  $txt );// print_r( self::$_font );
			return $image;
		}
		
		public static function writeDownSplitText( $filename, $family, $firstTxt, $secondTxt, $firstPartColor, $secondPartColor, $backgroundColor ){
			self::getInstance();
			self::$_padding = 0;
			self::$_transparent = 1;
			self::$_backgroundColor = $backgroundColor;
			self::$_font = array( "family" => $family, "size" => 15, "color" => $firstPartColor );
			
			if ( !( $imageFirst = self::_createTTFImage( $firstTxt ) ) ) return false;
			/* else */		
			self::$_font = array( "family" => $family, "size" => 15, "color" => $secondPartColor );
			if ( !( $imageSecond = self::_createTTFImage( $secondTxt ) ) ) return false;
			/* else */
			
			$imageRes = imagecreate( imagesx( $imageFirst ) + imagesx( $imageSecond ), imagesy( $imageFirst ) );
			imagecolorallocate( $imageRes, $backgroundColor[ "red" ], $backgroundColor[ "green" ], $backgroundColor[ "blue" ] );
			imagecopy ( $imageRes, $imageFirst, 0, 0, 0, 0, imagesx( $imageFirst ), imagesy( $imageFirst ) );
			imagecopy ( $imageRes, $imageSecond, imagesx( $imageFirst ), 0, 0, 0, imagesx( $imageSecond ), imagesy( $imageSecond ) );

			$image = $imageRes;

			try{
				imagePNG( $image, $filename );
			}catch( Exception $e ){
				self::raiseException( ERR_TEXTIMG_CANNOT_SAVE, $e );
				imagedestroy($image);
				return NULL;
			}
			imagedestroy($image);
			return true;			
		}	

	}	
?>