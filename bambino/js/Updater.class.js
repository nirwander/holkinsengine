var Updater = function(){
	var _observers = [];
	var	 _interval = 5000;
	var _cursor = 0;
	var _preprocess = function( resp ){
		 _observers[ _cursor ].o.process( resp );
		 _cursor++;
		 _exec(  );
	};
	var _exec = function(){		
		if ( _cursor == _observers.length ){
			_cursor = 0;
			setTimeout( _exec, _interval );
		}else{
			var p = "";
			if ( _observers[ _cursor ].o.getUpdateArg.constructor == Function ) p = _observers[ _cursor ].o.getUpdateArg();
			ajax( { url : _observers[ _cursor ].u, method : "post", params : p, callback :_preprocess } );		
		}
	};
	return {
		start : function(){
			if ( _observers.length > 0 )
				setTimeout( _exec, _interval );
		},
		subscribe : function( obj, url, dyn ){
			if ( obj.constructor == Object && url.constructor == String ){
				_observers[ _observers.length ] = { o : obj, u : url };
			}else{
				throw "Updater::subscribe: bad args";
				return false;
			}
		}
	};
}();