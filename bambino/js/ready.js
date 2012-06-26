function $(id){
	if (id.constructor == String){
		if (id.substring(0, 1) == "#"){
			if (document.getElementById(id.substring(1)) != null){
				document.getElementById(id.substring(1)).styleAttr = styleAttr;
				document.getElementById(id.substring(1)).parentNode.styleAttr = styleAttr;
				return document.getElementById(id.substring(1));
			}else{return null;}
		}else{
			id = id.replace(/ /g, "");
			if (((pos = id.indexOf("<#")) != -1)&&(pos == id.lastIndexOf("<#"))){
				parent_ = $(id.substring(pos + 1));
				tag_ = id.substring(0, pos);
				if ((res = parent_.getElementsByTagName(tag_)) != null) return res;
				  else return null;
			}else{
				return null;
			}
			return id;
		}
	}
}
function styleAttr(attr){
	b = navigator.userAgent.toLowerCase();
	browser = {
		safari: /webkit/.test(b),
		opera: /opera/.test(b),
		msie: /msie/.test(b) && !/opera/.test(b),
		mozilla: /mozilla/.test(b) && !/compatible/.test(b)
	};
  switch(attr){
		case "margin-top":		  attr = browser.msie ? "marginTop": "margin-top";		  break;
		case "margin-bottom":		  attr = browser.msie ? "marginBottom": "margin-bottom";		  break;
		case "margin-left":		  attr = browser.msie ? "marginLeft": "margin-left";		  break;
		case "margin-right":		  attr = browser.msie ? "marginRight": "margin-right";		  break;
	}	

	if (this.currentStyle)
	//IE
		y = this.currentStyle[attr];
	else if (window.getComputedStyle)
		y = document.defaultView.getComputedStyle(this,null).getPropertyValue(attr);
	y = /px/.test(y) ? parseInt(y.substring(0, y.length - 2)) : y;
	return y;
}
//-------------------------------------------------
function ready(f){
	if (f.constructor == Function){
		ready_f = f;
	}
	var b = navigator.userAgent.toLowerCase();
	browser = {
		safari: /webkit/.test(b),
		opera: /opera/.test(b),
		msie: /msie/.test(b) && !/opera/.test(b),
		mozilla: /mozilla/.test(b) && !/compatible/.test(b),
	};
  
	if ( browser.mozilla || browser.opera ) {
		document.addEventListener( "DOMContentLoaded", ready_f, false );
	} else if ( browser.msie ) {
		document.write("<scr" + "ipt id=__ie_init defer=true " + 
			"src=//:><\/script>");
		var script = document.getElementById("__ie_init");
		script.onreadystatechange = function() {
			if ( this.readyState != "complete" ) return;
			this.parentNode.removeChild( this );
			ready_f();
		};	
		script = null;
	} else if ( browser.safari ) {
		safariTimer = setInterval(function(){
			if ( document.readyState == "loaded" || 
				document.readyState == "complete" ) {
				clearInterval( safariTimer );
				safariTimer = null;
				ready_f();
			}
		}, 10);
	}
}
