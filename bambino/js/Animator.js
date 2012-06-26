var Animator = function(){
	var _items = [];
	var _queue = [];
	var _steps1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
	var _steps2 = [0, 0, 0, 0, 0, 0, 6, 5, 4, 12, 12, 11, 10, 10, 9, 8, 7, 3, 2, 1];
	var _steps3 = [ 1, 2, 3, 7, 8, 9, 10, 10, 11, 12, 12, 4, 5, 6, 0, 0, 0, 0, 0, 0 ];
	var _isStopped = true;
	var _pos = 0;
	var _rate = 40;
	
	var _isValid = function( obj ){
		if ( !obj.elem ) return false;
		/* else */
		if ( obj.property.constructor != String ) return false;
		/* else */
		if ( obj.units.constructor != String ) return false;
		/* else */
		if ( obj.to.constructor != Number ) return false;
		/* else */
		if ( obj.from.constructor != Number ) return false;
		/* else */
		return true;
	};
	var _setValue = function( i ){
		if ( $_.browser.msie && _items[ i ].property == "opacity" ){
			//document.body.innerHTML += "_setValue: " + _items[ i ].currValue * 100 + "<br/>";
			//var oAlpha = _items[ i ].elem.filters[ 'DXImageTransform.Microsoft.alpha' ] || _items[ i ].elem.filters.alpha;
			//if ( oAlpha && oAlpha.opacity ){
				//alert( _items[ i ].currValue );
				_items[ i ].elem.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+( ( _items[ i ].currValue * 100 ).toString() )+")";				
				//oAlpha.opacity = ( _items[ i ].currValue * 99 ).toString();
			//}else _items[ i ].elem.style.filter += "progid:DXImageTransform.Microsoft.Alpha(opacity="+( ( _items[ i ].currValue * 100 ).toString() )+")";				
		}else{
			//console.log( _items[ i ].elem );
			if ( _items[ i ].property == "opacity" )
				_items[ i ].elem.style[ _items[ i ].property ] = ( _items[ i ].currValue ).toString() + _items[ i ].units;
			else _items[ i ].elem.style[ _items[ i ].property ] = ( Math.round( _items[ i ].currValue ) ).toString() + _items[ i ].units;
		}
	};
	var _pushItem = function( obj ){
		_items[ _items.length ] = obj
		_items[ _items.length - 1 ].elem = $_( _items[ _items.length - 1 ].elem );
		_items[ _items.length - 1 ].startValue = _items[ _items.length - 1 ].from;
		_items[ _items.length - 1 ].currValue = _items[ _items.length - 1 ].from;
		_items[ _items.length - 1 ].endValue = _items[ _items.length - 1 ].to;
		_items[ _items.length - 1 ].cursor = 0;
		
		_items[ _items.length - 1 ].property = _items[ _items.length - 1 ].property.replace( /\-(\w)/g, function( strMatch, p1 ){ return p1.toUpperCase(); } );
		_setValue( _items.length - 1 );				
	};
	
	var _execute = function(){
		var i;
		var buf = [];
		for ( i = 0; _items && i < _items.length; i++){		
			if ( _items[ i ].cursor == _steps1.length ){
				if ( _items[ i ].callback && _items[ i ].callback.constructor == Function ){					
					_items[ i ].callback.apply( _items[ i ].elem, [] );
				}
			}else{
				buf[ buf.length ] = _items[ i ];
			}
		}
		_items = buf;
		if ( !_items || _items.length == 0 ){
			_reset();
			return;
		}		
		
		var newValue;		
		for ( i = 0; i < _items.length; i++ ){
			_items[ i ].currValue += _steps1[ _items[ i ].cursor ] * .01 * ( _items[ i ].endValue - _items[ i ].startValue );	
			//document.body.innerHTML += _items[ i ].currValue + "<br/>";
			_setValue( i );
			_items[ i ].cursor++;
		}				
		
		setTimeout( arguments.callee, _rate );
	}
	var _reset = function(){
		_items = [];
		_pos = -1;
		_isStopped = true;
	}
	
	return { init : function( obj ){
						if ( _isValid( obj ) ){
							//_items[ _items.length ] = obj;
							_pushItem( obj );
							return true;
						}else{
							return false;
						}
					},
			 queue : function( obj ){
						if ( _isValid( obj ) ){
							_queue[ _queue.length ] = obj;
							return true;
						}else{
							return false;
						}					
					},
			 play : function(){				
						if ( _items.length > 0 ){						
							if ( _isStopped ){
								_isStopped = false;
								_execute();
							}
						}
					}
	};
}();