ready(function(){
	window[ "onChoose" ] = function( e ){
		var id = this.href.split( "#" );
		id = id[ 1 ].split( "-" ); id = id[ 1 ];
		window[ "currentCh" ] = this;
		dialog.show( id );
		return false;
	};
	window[ "onUnsetMe" ] = function( e ){
		dialog.showForUnset();
		return false;
	};
	$_( "div.costume-item div a.free" ).bind( "onclick", window[ "onChoose" ] );
	$_( "div.unset-me a" ).bind( "onclick", window[ "onUnsetMe" ] );
	GuestBook.init();
	Voter.init();
	window[ "Updater" ] = Updater;
	window[ "Updater" ].start();
});