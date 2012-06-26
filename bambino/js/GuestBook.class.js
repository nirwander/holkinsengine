var GuestBook = function(){
	var _lastN = 0;
	var _onResponse = function( resp ){
		if ( resp.response.err_code == 0 ){
			//$_( "div.lenta" )[ 0 ].innerHTML = resp.response.txt;
		}else{
			alert( "Какой-то затуп произошел - пробуй, пока не получится" );
		}
	};
	var _onSubmit = function( e ){		
		p = "gb[nick]=" + encodeURIComponent( $_( "#__gb_nick" ).value ) + "&" + "gb[msg]=" + encodeURIComponent( $_( "#__gb_msg" ).value );		
		ajax( { method : "POST", url : "?addMsg", params : p, callback : _onResponse } );
		return false;
	};
	var _setLastN = function(){
		_lastN = $_( "div.lenta div.msg input" )[ 0 ].value;
	};
	var _slideNew = function(){
		var lastN = $_( "div.lenta div.msg input" )[ 0 ].value;
		var msgs = $_( "div.lenta div.msg" );
		var height = 0;
		for ( var i = 0; i < lastN - _lastN; i++ ){
			Animator.init( { elem : msgs[ i ], property : "height", from : 0, to : parseInt( msgs[ i ].offsetHeight ), units : "px",
							 callback : function(){ this.style.backgroundColor = "transparent"; } } );
			Animator.init( { elem : msgs[ i ], property : "margin-bottom", from : 0, to : 20, units : "px" } );
			msgs[ i ].style.backgroundColor = "#71E942";			
		}
		Animator.play();
	};
	return {
		init : function(){			
			$_( "#__gbSubmit" ).bind( "onclick", _onSubmit );
			_setLastN();
			if ( window[ "Updater" ] ){
				window[ "Updater" ].subscribe( GuestBook, "?wannaNewMsgs" );
			}
		},
		process : function( resp ){
			var r = resp.response;
			//console.log( r );
			if ( r && r.err_code == 0 ){
				$_( "div.lenta" )[ 0 ].innerHTML = r.txt + $_( "div.lenta" )[ 0 ].innerHTML;
				_slideNew();
				_setLastN();
			}
		},
		getUpdateArg : function(){
			return "lastN=" + _lastN;
		}
	};
}();