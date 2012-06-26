var Cloud = function(){
	var _onMOver = function( e ){
		$_( "div.people div.item div.who" ).styleProperty( "display" ).setValue( "none" );
		var el = this.childNodes;
		el[ 0 ].style.display = "block";
	};
	var _onMOut = function( e ){
		$_( "div.people div.item div.who" ).styleProperty( "display" ).setValue( "none" );
	};
	var _bindEmAll = function(){
		$_( "div.people div.item" ).bind( "onmouseover", _onMOver );
		$_( "div.people div.item" ).bind( "onmouseout", _onMOut );
	};
	return {
		init : function(){
					$_( "div.people div.item div.who" ).styleProperty( "display" ).setValue( "none" );
					_bindEmAll();
				}
	};
}();