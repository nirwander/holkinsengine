function ajax(obj){
	ajax.response = null;
	if (obj.method.toLowerCase() == "get" || obj.method.toLowerCase() == "post"){
	   try {
		 	 ajax.request = new XMLHttpRequest();
		 }catch (trymicrosoft){
		 	  xmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP");
				for (i=0;i<xmlHttpVersions.length && !ajax.request; i++)
					try{ ajax.request = new ActiveXObject(xmlHttpVersions[i]);}catch(e){ajax.request = false;}
		}
		if (!ajax.request){alert("Error initializing XMLHttpRequest Object"); return false;}

     if (obj.method.toLowerCase() == "get"){		 	 obj.url = obj.params ? obj.url + "?" + obj.params: obj.url;		 }
     
	 obj.async = (obj.async == null || obj.async == undefined) ? true : obj.async;

	 ajax.url = obj.url;

	 ajax.request.open(obj.method, ajax.url, obj.async);

		 ajax.callback = obj.callback ? obj.callback: null;

		 ajax.request.onreadystatechange = function(){
				 if (ajax.request.readyState == 4)
				   if (ajax.request.status == 200){		       	 		       	 
					 ajax.responseHeader = ajax.request.getResponseHeader("Content-type");		
					 if (ajax.responseHeader.indexOf("application/json") != -1){		       	 	
								 try{ 
									data = eval('('+ajax.request.responseText+')');
									ajax.response = data;
								 }catch(e){
										alert(e);
										ajax.response = null;
								 }
							 }else{
								 ajax.response = ajax.request.responseText;	
							 }
							 if ( ajax.callback ) {
								resp = {response: ajax.response,
										srcResponse : ajax.request }
								//alert(resp);
								if (obj.special) {resp.special = obj.special;}
								ajax.callback(resp);
							 }
				  }		      
			 }
//	 }
     ajax.request.setRequestHeader("X-Powered-By", "XMLHTTPRequestHola");
	 if (obj.method.toLowerCase() == "post"){
       ajax.request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;"); //
       ajax.request.setRequestHeader("Content-Length", obj.params.length); //
			 ajax.request.setRequestHeader("Connection", "Close");       

       ajax.request.send(obj.params);
     }else{	ajax.request.send(null); }
   if (!obj.async) {
			 ajax.responseHeader = ajax.request.getResponseHeader("Content-type");		
			 if (ajax.responseHeader.indexOf("application/json") != -1){		       	 	
						 try{ 
							data = eval('('+ajax.request.responseText+')');
							ajax.response = data;
						 }catch(e){
								alert(e);
								ajax.response = null;
						 }
					 }else{
						 ajax.response = ajax.request.responseText;							 
					 }
	 		return ajax.response;
	 }
//   if (!obj.async) return ajax.response;
  }  
}