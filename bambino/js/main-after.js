ready(function(){
	GuestBook.init();
	Cloud.init();
	window[ "Updater" ] = Updater;
	window[ "Updater" ].start();
	var imgs = $_( "div.photo-item a img" );
	function load( ind ){
		if ( imgs[ ind ] ){
			imgs[ ind ].src = imgs[ ind ].alt; ind++;
			setTimeout( function(  ){ return load( ind ); }, 150 );
		}		
	}
	load( 0 );
});