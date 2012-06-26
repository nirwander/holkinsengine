
function $_(obj){
 
  this.version = "1.0";
  
	//var reg_ = /^([a-z]*[#\.]{0,1}[_]*[a-z]+[0-9\-]*[a-z]*[\s]*)*$/gi;
	var reg_ = /^([a-z]*[#\.]{0,1}[_a-z]+[a-zA-Z0-9\-]*[\s]*)*$/gi;
//  if (!reg_.test(obj)) return null;
  
	function inheritMembers(parent_){
		for (var k in parent_){
			if (!(k in this)){
				this[k] = parent_[k];
			}
		}
	}

  function appendMembers(daughters){
  	
  	if (daughters == null || daughters.length == 0) return null;
  	/* else */
		if (daughters.constructor == Array){
			for (var i = 0; i < daughters.length; i++)
				inheritMembers.call(daughters[i], $_.prototype );			  
		}
		inheritMembers.call(daughters, $_.prototype );
		return daughters;
	}
  
	function objectsBySelector(selector){
		
		if (selector == null) return null;
		/* else */
						
		parent_ = this.daddy ? this.daddy : document;		
		var i = 0; 
				
		if ((ind = selector.indexOf("#")) != -1){
			if (ind == 0){
				return parent_.getElementById(selector.substring(1));
			}else{
				return parent_.getElementById(selector.substring(ind + 1));
			}

		}else if ((ind = selector.indexOf(".")) != -1){
			if (ind == 0){
				
				elements = document.getElementsByTagName("*");
				res_ = [];
				for (i = 0; i < elements.length; i++){
					//if (elements[i].className == selector.substring(1)) HERE
					if ( elements[i].className.indexOf( selector.substring(1) ) != -1 )
					  res_ = res_.concat( elements[i] );
				}
				return res_.length == 0 ? null : res_;
			}else{
				if ((elements = parent_.getElementsByTagName(selector.substring(0, ind))) == null)
					return null;
				/* else */
				res_ = [];
				for (i = 0; i < elements.length; i++)
					if ( elements[i].className.indexOf( selector.substring(ind + 1) ) != -1 )
					  res_ = res_.concat(elements[i]);
				return res_.length == 0 ? null : res_;
			}		
		}else{ 
			if ((elements = parent_.getElementsByTagName(selector)) == null)
				return null;
			/* else */
			res_ = [];
			for (i = 0; i < elements.length; i++)
				res_ = res_.concat(elements[i]);
			return res_.length == 0 ? null : res_;
		}
		return null;
	}
	
	function getFirstItemInPath(path){		
		//reItem = /[a-z]*[#\.]{0,1}[_]*[a-z]+[0-9\-]*[a-z]*/gi;
		reItem = /[a-z]*[#\.]{0,1}[_a-z]+[a-zA-Z0-9\-]*/gi;

    matches = path.match(reItem);
		if (!matches) return "hui";
    /* else */
		return matches[0];
	}
	
	$_.browser = {
		safari: /webkit/.test(navigator.userAgent.toLowerCase()),
		opera: /opera/.test(navigator.userAgent.toLowerCase()),
		msie: /msie/.test(navigator.userAgent.toLowerCase()) && !/opera/.test(navigator.userAgent.toLowerCase()),
		mozilla: /mozilla/.test(navigator.userAgent.toLowerCase()) && !/compatible/.test(navigator.userAgent.toLowerCase())
	};

	$_.script = {  
		add : function(script_text){			
				var scr_ = document.createElement("script");
				scr_.text = script_text;				
				var el = $_("head")[0].appendChild(scr_);
				//for (var k in el)
				//  alert(k + "||" + el[k]);
		}
	};
	
	$_.css = {
		setProperty : function(elem, prop, value){
						
												if (elem.constructor ==  String){
													
													var sheets = document.styleSheets;
									
													var cssRules_ = sheets[0].cssRules ? "cssRules" : "rules"; 
													var selectors = [];
													for (var i = 0; i < sheets.length; i++){														
														for (var j = 0; j < sheets[i][cssRules_].length; j++){
															rule_ = sheets[i][cssRules_][j];
															if (rule_.selectorText.substring( rule_.selectorText.length - elem.length ).toLowerCase() == elem.toLowerCase()){
																selectors.push(	rule_ );
															}
														}					
													}													
													return $_.css.modifyRule(elem, prop, value, selectors);
												}
											},
		modifyRule : function(elem, prop, value, rules_){
												if (rules_.length > 0){
													for (var i = 0; i < rules_.length; i++){
														try{
															rules_[i].style[ prop ] = value;
														}catch( e ){ /*alert(e);*/ }
													}
													return true;
												}else{
													$_.css.addDynamicRule(elem, "{}");
													return $_.css.setProperty(elem, prop, value);
																										
												}
											},
		dynamicStyleSheet : function(){																

																var sheets = document.styleSheets;
																
																for (var i = 0; i < sheets.length; i++){
																	if (sheets[i][ sheets[i].ownerNode ? "ownerNode" : "owningElement" ].id == "_cssSheetForDynamicRules")
																		return sheets[i];
																}

																var sheet = document.createElement("style");
																sheet.type = "text/css"; sheet.media = "screen";
																sheet.id = "_cssSheetForDynamicRules";
																
																if (sheet.styleSheet) sheet.styleSheet.cssText = "";// IE method
																else sheet.appendChild( document.createTextNode("") );// others
																
																$_("head")[0].appendChild( sheet );
																return $_.css.dynamicStyleSheet();
												
															},
		addDynamicRule : function(selector, stringRules){
												var dynCSS = $_.css.dynamicStyleSheet();
												var rulesCount = dynCSS[ dynCSS.cssRules ? "cssRules" : "rules" ].length;
												rulesCount = rulesCount == 0 ? 0 : rulesCount - 1;
												
												if (dynCSS.insertRule){
														dynCSS.insertRule( selector + "{" + stringRules + "}", rulesCount );
												}else if (dynCSS.addRule){														
														dynCSS.addRule( selector, stringRules, rulesCount );
												}
												return true;
										 },
		removeDynamicRule : function( ruleSelector ){
														var dynCSS = $_.css.dynamicStyleSheet();
														var dynRules = dynCSS[ dynCSS.cssRules ? "cssRules" : "rules" ];
														for (var i = 0; i < dynRules.length; i++){
																//if ( i == 10 ) alert( dynRules[i].selectorText + "|" + ruleSelector );
																if ( dynRules[i].selectorText.toLowerCase() == ruleSelector.toLowerCase() ){
																		if ( dynCSS.deleteRule ){
																				dynCSS.deleteRule(i);
																		}else{
																				dynCSS.removeRule(i);
																		}
																}
														}
												}		
	};
	if (obj == document){
			return $_("html");
	}else	if (obj.constructor == String){
		if (obj.search(reg_) == -1) return null;
		/* else */		
		if ((el = getFirstItemInPath(obj)) == null) return null;
		/* else */
		if ((buf = objectsBySelector(el)) == null) return null;
		return $_({ daddy : buf,
				         path : obj.substring(el.length + 1) });

	}else if (obj.constructor == Object && obj.daddy){		
		
		sel = getFirstItemInPath(obj.path);
		if (sel == "hui")
			return appendMembers(obj.daddy);

		/* else */
		
		var res = [];

		if (obj.daddy.constructor == Array){
			for (var i = 0; i < obj.daddy.length; i++){
				buf = objectsBySelector.call( {daddy : obj.daddy[i]} , sel);
				if (buf != null)
					res = res.concat( buf );
			}
		}else{
			if ((buf = objectsBySelector.call( {daddy : obj.daddy} , sel)) != null)
				res = res.concat( buf );
		}
		
		return $_( {daddy : res,
							   path : obj.path.substring(sel.length + 1)} );		

	}else if (obj.tagName){ /* Object HTML Element */

		return appendMembers( obj );

	}else if (obj.constructor == Array || obj[0].tagName /* HTML Collections */){
	  //[Object, Object]
		var res = [];
		for (var i = 0; i < obj.length; i++){
			res = res.concat( $_(obj[i]) );
		}
		return appendMembers( res );
			
	}else{
		/*var str = "";
		for ( var k in obj )
			str += "obj[" + k + "]=" + obj[k] + "<br/>";
		$_("#pageLeftContent").tegzd(str);*/
		alert( obj.constructor );
		return null;
	}
}

$_.prototype.tegzd = function(txt){ //alert(this.constructor);
	if (this.constructor == Array){
		for (var i = 0; i < this.length; i++){
			this[i].innerHTML = txt;
		}
	}else {
		this.innerHTML = txt;
	}
}

$_.prototype.bind = function(ev, func){ //alert(this.constructor);
	if (this.constructor == Array){
		for (var i = 0; i <this.length; i++){
			this[i][ev] = func;
		}
	}else {
		this[ev] = func;
	}
}
$_.prototype.existsChild = function( child ){ //alert(this.constructor);
	if (this.constructor == Array){
			return "hui";
	}else {
			//var childs = $_()
	}
}

$_.prototype.getAbsPos = function() {
	if ( this.length ) return false;
	/* else */
	var res = { left : 0, top : 0 };
	var obj = this;
	while( obj) {
	  res.top += obj.offsetTop;
	  res.left += obj.offsetLeft;
	  obj = obj.offsetParent;
	}
	return res;
}

$_.prototype.styleProperty = function( prop ){		
		return { that : this, property : prop, 
																plus : function( inc ){
																			if ( this.that.constructor == Array ){
																					var res = [];																							
																					for ( var i = 0; i < this.that.length; i++)
																							res.push( $_( this.that[i] ).styleProperty( this.property ).plus( inc ) );
																					return res;
																			}else{
																					var buf = this.getValue(); 																					
																					var newVal = parseFloat( buf ) + parseFloat( inc );
																					newVal += this.getValueUnit();
																					
																					this.setValue( newVal );
																					//this.that.style[ this.property ] = newVal;
																					//return this.getValue();
																			}																			
																 },
																 minus : function( dec ){
																						return this.plus( -dec );
																 },
																 getValue : function(){
																 								var y, prop;
																								
																								if ( $_.browser.msie ){				//IE
																										prop = this.property.replace( /\-(\w)/g, function( strMatch, p1 ){ return p1.toUpperCase(); } );
																										if ( prop == "opacity" ){
																										    var oAlpha = this.that.filters['DXImageTransform.Microsoft.alpha'] || this.that.filters.alpha;
																										    y = oAlpha ? oAlpha["opacity"] : 0; 
																										    //if (oAlpha) oAlpha.opacity = nOpacity;
																										    //else elem.style.filter += "progid:DXImageTransform.Microsoft.Alpha(opacity="+nOpacity+")";
																										}else{
																												y = this.that.currentStyle[ prop ];
																												//y = this.that.runtimeStyle[ prop ];																			
																										}
																								}else if (window.getComputedStyle){
																										prop = this.property;
																										y = document.defaultView.getComputedStyle( this.that, null ).getPropertyValue( prop );
																								}
																								if ( !y ) y = 0;
																								y = /px/.test(y) ? parseFloat( y.substring(0, y.length - 2) ) : y;		
																								y = /pt/.test(y) ? parseFloat( y.substring(0, y.length - 2) ) * 96 / 72 : y;
																								if ( this.property == "opacity" && !$_.browser.msie ) y = y * 100;
																								return y;																							
																 },
																 getValueUnit : function(){
																										if ( $_.browser.msie )				//IE
																												var y = this.that.currentStyle[ this.property ];
																												//var y = this.that.runtimeStyle[ this.property ];
																										else if (window.getComputedStyle)
																												var y = document.defaultView.getComputedStyle( this.that, null ).getPropertyValue( this.property );
																										var units = [ "px", "%", "em", "pt" ];
																										
																										for ( var i = 0; i < units.length; i++ )
																												if ( ( new RegExp( units[i] ) ).test(y) ) return units[i];
																										/* else */
																										return "";
																 },
																 setValue : function( newValue ){
																								//console.log( this );
																								if ( this.that.constructor == Array ){
																									for ( var i = 0; i < this.that.length; i++ )
																										$_( this.that[ i ] ).styleProperty( this.property ).setValue( newValue );
																									return;
																								}
																 								if ( this.property == "opacity" && $_.browser.msie ){
																								    var oAlpha = this.that.filters['DXImageTransform.Microsoft.alpha'] || this.that.filters.alpha;
																									
																								    if (oAlpha) oAlpha.opacity = newValue;
																							      else this.that.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+newValue+")";	
																								}else if( this.property == "opacity" && !$_.browser.msie ){
																										newValue = newValue / 100;
																								}
																								prop = prop.replace( /\-(\w)/g, function( strMatch, p1 ){ return p1.toUpperCase(); } );
																 								this.that.style[ prop ] = newValue;
																						}
					 };
}

/* Something like initialization */
  $_("rock'n'roll !!!");
