objXHR = new Object();

objXHR.el = false;
objXHR.timeOutRepeat = 1; // seconds
objXHR.onBusyLayer =false;
objXHR.viewBusyLayer = false;
objXHR.iframe_name = 0;
objXHR.frame_init = false;
objXHR.post = 0;
objXHR.forms = null;
objXHR.last_params = null;
objXHR.container_id_objs = [];
objXHR.callbacks_objs = [];

objXHR.GetResponse = function(params,callback,cget)
{
	if (params['confirm'])
	{
		val = confirm(params['confirm']);
		if(!val)
			return false;
	}

	frame_init = false;
	params_send = {};
	if(!params.container_id && objXHR.el)
	 	params.container_id = objXHR.el;

	if(!params[0])
    	params_send = [params];
    else
    	params_send = params;

	if (cget)
		var get_ = objXHR.GetGETParams();
    for ( var i in params_send)
	{
		if(cget)
			params_send[i]['cget'] = get_;
		if(typeof(params_send[i]['container_id']) == 'object')
		{
			objXHR.container_id_objs.push(params_send[i]['container_id']);
			params_send[i]['container_id'] = "jquery"+(objXHR.container_id_objs.length-1);
		}
		if(typeof(params_send[i]['callback']) == 'function')
		{
			objXHR.callbacks_objs.push(params_send[i]['callback']);
			params_send[i]['callback'] = "cb"+(objXHR.callbacks_objs.length-1);
		}

		if(params_send[i]['form'])
		{
			var t  = objXHR.SerializeForm(params_send[i]['form']);
			this.forms = params_send[i]['form'];
			delete(params_send[i]['form']);
			params_send[i] = merge_arrays(params_send[i],t);
 		}
	}

	if(!callback)
		 objXHR.callback = function() { return;};
	else
		objXHR.callback = callback;
    
	objXHR.oldContent = jQuery("#"+objXHR.el).html();
    
//	debug(objXHR.GetJson(params_send));
	objXHR.last_params = params_send;

	var a_obj =
	{
	  type: "post",
  	  url: website+"/ajax.php",
	  data: "method="+objXHR.post+"&"+objXHR.GetJson(params_send),
	  success: objXHR.processReqChange,
	  fail: objXHR.PrintError,
	  dataType: 'json',	  
	}
	if(objXHR.viewBusyLayer)
	{
		objXHR.showBusyLayer();
	}
//	debug(a_obj);
	
	jQuery.ajax(a_obj);	
} ,

objXHR.sendURIIframe = function(form,params,callback)
{

	objXHR.frame_init = true;
	params_send = new Object();
	if(!params.container_id)
	 	params.container_id = objXHR.el;

	if(!callback)
		callback = function() { return;};
	objXHR.callback  = callback;

    if(!params[0])
    	params_send = [params];
    else
    	params_send = params;

    objXHR.oldContent = $("#"+params_send[0]['container_id']).html();

    $(form)
		.attr('action', website+"/ajax.php" + "?method="+objXHR.post+"&frame_load=1&" + objXHR.GetJson(params_send))
//		.on('submit', function(){ return true;});

	if (typeof ($(form).attr('target')) == 'undefined')
	{
		objXHR.iframe_name = 'tmp_dynamic_iframe'+Math.floor((Math.random() * 100) + 1);
		$("<iframe>")
			.attr('name',objXHR.iframe_name)
			.attr('id',objXHR.iframe_name+'_id')
			.appendTo($('#tmp_dynamic_div'));
		$(form).attr('target',objXHR.iframe_name);
	}
	else
		objXHR.iframe_name = $(form).attr('target');

	objXHR.last_params = params_send;
	$(form).submit();


    window.setTimeout("objXHR.processReqChange()", objXHR.timeOutRepeat * 1000);
}
objXHR.processReqChange = function(data) {
	if(objXHR.frame_init) 
	{
		var contents = jQuery("iframe[name=\""+objXHR.iframe_name+"\"]").contents().find("#AJAX_IFRAME_SUCCESS").html();
		if(contents != "1"){			
	    	objXHR.showBusyLayer();
	      	window.setTimeout("objXHR.processReqChange()", objXHR.timeOutRepeat * 1000);
	      	return false;
		}
		var contents = jQuery("iframe[name=\""+objXHR.iframe_name+"\"]").contents().find("#AJAX_IFRAME_CONTENT").html();
		data = $.parseJSON(contents);
	    objXHR.hideBusyLayer();
	    jQuery("iframe[name=\""+objXHR.iframe_name+"\"]").contents().find("html").html("");
	}
    if(typeof(data) != "object")
    {
        data = [{'AJAX_CONTENT' : data,'container_id' : objXHR.container_id}]
    }

	for(i in data) {
	    var AError   = data[i]["AJAX_ERRORS"];
	    var AInfo    = data[i]["AJAX_INFO"];
	    var AJsInfo  = data[i]["AJAX_JSINFO"];
	    var Acontent = data[i]["AJAX_CONTENT"];
	    var container_id = data[i]["container_id"];

		if(container_id.match(/^jquery/))
			container_id = objXHR.container_id_objs[container_id.match(/[0-9]+$/)];
		else
			container_id = '#'+container_id;

	    if(AError != "")
	    {
			sAlert(AError,'error');
        	objXHR.callback(AError,null);
	    }
	    else {
			if (jQuery(container_id))
				jQuery(container_id).html(Acontent, null);
			if (AInfo != "")
				sAlert(AInfo);

			if (data[i]['callback'])
			{
				objXHR.callbacks_objs[data[i]['callback'].substring(2)](Acontent,AJsInfo);
			}
			else
	        	objXHR.callback(Acontent,AJsInfo);
	        if(this.forms)
	        {
	        	for ( var i in this.forms) 
	        	{
	        		this.forms.reset();
				}
	        }
	    }
	}
    objXHR.hideBusyLayer();
    objXHR.Clean();
	
}
//////////////////////////////////////////////
objXHR.GetJson = function (params)
{
	b = "adata=[";
	for(i in params) 
	{
		if(typeof(params[i]) == "object")
			b += (b != "adata=[" ? "," : "") +
			     "{\"id\":\"" + params[i]['container_id'] + "\",\"params\" : "+objXHR.ObjToStr(params[i])+"}";
	}
	b += "]";
	return b;
},
objXHR.ObjToStr = function(o,quotes){
	quotes = quotes ? quotes : '"';

    var parse = function(_o,_quotes){
        var a = [], t;
        
        for(var p in _o){
        
            if(_o.hasOwnProperty(p)){
            
               t = _o[p];
                
                if(t && typeof t == "object")
                {                
                    a[a.length]= _quotes+p+_quotes + ":{ " + arguments.callee(t,_quotes).join(", ") + "}";
                }
                else// if (t)
                {
//                    console.log(p+":"+encodeURIComponent(t));
                    
                    if(typeof t == "string"){
//                        a[a.length] = [ '"'+p+'"'+ ": \"" + encodeURI(t.toString().replace(/\"/g,'\\"').replace(/\n/g,"\\n")) + "\"" ];
                        a[a.length] = [ _quotes+p+_quotes+ ": "+_quotes  + encodeURIComponent(t.toString().replace(/\"/g,'\\"')) + _quotes ];
                    }
                    else if(t){
                        a[a.length] = [ _quotes+p+_quotes+ ": " + t.toString()];
                    }
                    
                }
            }
        }
        
        return a;
        
    }
    
    return "{" + parse(o,quotes).join(", ") + "}";
}
/*
objXHR.FormToObj = function (form, obj_params,params)
{
	for (var i in obj_params)
	{
		var par = obj_params[i];
		if (form[par])
		{
			 console.log(par+":"+form[par]);
			 var v = '';
			if(form[par] == '[object HTMLSelectElement]')
				v = GetSelectedSelect(form[par]);
			else if(form[par] == '[object NodeList]')
				v = GetSelectedRadio(form[par]);
			else if (form[par].type && form[par].type == "checkbox")
				v = form[par].checked ? 1 : 0;
			else if (form[par].value) 
				v =form[par].value;


			if(q = par.match(/([^\[]+)\[([^\]]+)/))
			{
				if(!params[q[1]])
					params[q[1]] = {};
				params[q[1]][q[2]] = v;
			}
			else
				params[par] = v ;
		}
	}
//	console.log(params);
	return params;
},
*/
objXHR.SerializeForm = function (form) 
{

  if (typeof(form) != 'object') 
  {
    return false;
	}
  var result = {};
  var g = function(n) 
  {
    return geByTag(n, form);
  };
  var nv = function(i, e)
  {

      v = (!e.value && form[e.name]) ? form[e.name].value : e.value;
      k = e.name;
		var kk = k.split('[');
		if(kk.length >1)
		{
			if (kk[1]  == ']')
			{
				k = kk[0];// k.substr(0,k.length-2);
				debug(k);
				if(result[k])
					result[k][objectLength(result[k])] = v;
				else
					result[k] = [v];	
			}
			else
				result = objXHR.Str2Obj(kk,result,v,1);
		}
		else
			result[k] = v;
  };
  each(g('input'), function(i, e) 
  {
    if ((e.type != 'radio' && e.type != 'checkbox') || e.checked) return nv(i, e);
  });
  each(g('select'), nv);
  each(g('textarea'), nv);

  return result;
},
objXHR.GetGETParams = function () 
{
	qsParm = {};
	var query = window.location.search.substring(1);
    query = query.replace(/%5B/g,'[').replace(/%5D/g,']');
    var parms = query.split('&');
	
	for (var i=0; i<parms.length; i++) 
	{
		var pos = parms[i].indexOf('=');
		if (pos > 0) 
		{
			var key = parms[i].substring(0,pos);
			var val = parms[i].substring(pos+1);
			var k = key.split('[');
			if(k.length >1)
				qsParm = objXHR.Str2Obj(k,qsParm,val,1);
			else
				qsParm[key] = val;
		}
	}
//	console.log(qsParm);
	return qsParm;
}, 
objXHR.Str2Obj = function(k, obj,val,l) // STR "f[1][2]=3" to obj
{
	for(var j in k)
	{
			k[j] = k[j].replace(']','');
			t = k[j];
			if(k.length == l)
				obj[t] = val;
			else
			{
				if(!obj[t])
					obj[t] = {};
			}
			delete(k[j]);
			
			this.Str2Obj(k,obj[t],val,l+1);
			return obj;
	}
}

objXHR.PrintError = function(obj,tError)
{
	if(console)
		console.log(tError);
	else
		alert(tError);
}
objXHR.showBusyLayer = function() 
{
 	if(objXHR.onBusyLayer)
    	return;
	var busyLayer    = jQuery("#busy_layer");
	var loadingLayer = jQuery("#loading-layer");
	if (busyLayer && !objXHR.onBusyLayer) 
	{
		busyLayer.css("visibility","visible");
		sizeWindow = getPageSize();
//		debug(sizeWindow);              
		busyLayer.css("height",sizeWindow.pageHeight);
	}
	if(loadingLayer)
		loadingLayer.css("display","");
	objXHR.onBusyLayer = true;
}
objXHR.hideBusyLayer = function() 
{
    if(!objXHR.onBusyLayer)
    	return;
	var busyLayer    = jQuery("#busy_layer");
	var loadingLayer = jQuery("#loading-layer");
	if (busyLayer) 
	{	
		busyLayer.css("visibility","hidden");
		busyLayer.css("height","0");
	}
	if(loadingLayer)
		loadingLayer.css("display","none");
	objXHR.onBusyLayer = false;
}
objXHR.Clean = function() 
{
	objXHR.el = false;
	objXHR.onBusyLayer =false;
	$("#tmp_dynamic_div").remove();
	objXHR.iframe_name = 0;
	objXHR.frame_init = false;
	objXHR.post = 0;
	objXHR.forms = null;
	objXHR.viewBusyLayer = false;
}