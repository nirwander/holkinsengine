var Voter = function(){
	var _onResponse = function( resp ){
		if ( resp.response.err_code == 0 ){
			var p = window[ "__vote_target" ].parent || window[ "__vote_target" ].parentNode;
			var c = p.childNodes;
			
			for ( var i = 0; i < c.length; i++ )
				if ( c[ i ].tagName && c[ i ].tagName.toLowerCase() == "span" ) c[ i ].innerHTML = parseInt( c[ i ].innerHTML ) + 1;
			document.cookie = "voter=yes; path=/; expires=01-Jan-2012 00:00:00 GMT";
		}else{
			alert( "Чё-то какая-то хрнь произошла - попробуй ещё раз." );
		}
	};
	var _isDuble = function(){
		return document.cookie.indexOf( "voter=yes" ) != -1;
	}
	var _onSubmit = function( e ){		
		/*if ( _isDuble() ){
			alert( "Ты уже свое отголосовал" );
			return false;
		}*/
		var inputs = $_( "div.frame-vote form p input" );
		var val = 0;		
		for ( var i = 0; i < inputs.length; i++ )
			if ( inputs[ i ].checked ){				
				val = inputs[ i ].value;
				window[ "__vote_target" ] = inputs[ i ];
			}
		ajax( { method : "post", url : "?vote", params : "target=" + val, callback : _onResponse } );
		return false;
	};
	return {
		init : function(){
			$_( "a.voter" ).bind( "onclick", _onSubmit );
		}		
	};
}();