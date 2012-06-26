var dialog = function(){
	var choosen = 0;
	var choosenName = "";
	var initialized = false;
	var _character = -1;
	var _mode = "set";
	var _posted_n = -1;
	var _onChooseGuest = function(){
		var id = this.href.split( "#" );
		id = id[ 1 ].split( "-" ); id = id[ 1 ];
		choosen = id;
		choosenName = this.innerHTML;
		$_( "#__menu div div a" ).bind( "className", "" );
		this.className = "active";		
		return false;
	};
	var _onResponse = function( resp ){
		e = resp.response.err_code;
		if ( e == 0 ){
			var p_ = window[ "currentCh" ].parent || window[ "currentCh" ].parentNode;
			var p = p_.parent || p_.parentNode;
			p.className = "costume-item busy";
			window[ "currentCh" ].href = "#guest-" + choosen;
			window[ "currentCh" ].className = "pink";			
			window[ "currentCh" ].innerHTML = choosenName;			
			var el = document.createElement( "span" );
			el.innerHTML = "выбрал&nbsp;";
			p_.insertBefore( el, window[ "currentCh" ] );
			//p_.innerHTML = "выбрал" + p_.innerHTML;
			alert( "Ты свой выбор сделал ." );			
			dialog.hide();
		}else{
			var str = "";
			switch( e ){
				case -1: str = "Не верный ID. Не пытайся шутить. Без глупостей."; break;
				case -2: str = "Позиция уже занята"; break;
				case -3: str = "Ты уже выбрал. Извени."; break;
			}
			alert( str );
		}
	}
	var _onResponseUnset = function( resp ){		
		e = resp.response.err_code;
		if ( e == 0 ){
			alert( "Начинай сначала." );
			$_( "div.frame-costumes" )[ 0 ].innerHTML = resp.response.txt; 

			$_( "div.costume-item div a.free" ).bind( "onclick", window[ "onChoose" ] );
			$_( "div.unset-me a" ).bind( "onclick", window[ "onUnsetMe" ] );

			dialog.hide();						
		}else{
			alert( "Не верный ID. Не пытайся шутить. Без глупостей." );
		}
		
	}
	var _onSubmit = function(){
		if ( choosen < 1 ){
			alert( "Выбери сперва себя" );
			return false;
		}
		/* else */
		$_( "#__form_guest_n" ).value = choosen;
		var inputs = $_( "#__menu form div input" );
		var p = "character=" + _character + "&";
		for ( var i = 0; i < inputs.length; i++ ) p += inputs[ i ].name + "=" + inputs[ i ].value + "&";
		if ( _mode == "set" ){
			ajax( { url : "?identity", method : "POST", params : p, callback : _onResponse } );
		}else{
			ajax( { url : "?unsetme", method : "POST", params : p, callback : _onResponseUnset } );
			_posted_n = choosen;
		}
		return false;
	};
	return {
		init : function(){
			$_( "#__menu div div a" ).bind( "onclick", _onChooseGuest );			
			$_( "#dialog_submit" ).bind( "onclick", _onSubmit );
			$_( "#__menu div div a" ).bind( "className", "" );
			$_( "#__shadow" ).bind( "onclick", this.hide );
			initialized = true;
		},
		show : function( id ){		
			if ( !initialized ) dialog.init();
			_character = id;
			_mode = "set";
			$_( "#__shadow" ).styleProperty( "display" ).setValue( "block" );
			$_( "#__menu" ).styleProperty( "display" ).setValue( "block" );
		},
		showForUnset : function(){
			if ( !initialized ) dialog.init();
			_mode = "unset";
			$_( "#__shadow" ).styleProperty( "display" ).setValue( "block" );
			$_( "#__menu" ).styleProperty( "display" ).setValue( "block" );						
		},
		hide : function(){
			if ( !initialized ) dialog.init();
			$_( "#__shadow" ).styleProperty( "display" ).setValue( "none" );
			$_( "#__menu" ).styleProperty( "display" ).setValue( "none" );
		}
		
	};
}();