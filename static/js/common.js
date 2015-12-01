//УТФ8
var g_container_id = "container_content";
var g_container_dialog = "dialog";
var g_opened_dialogs = {};
var g_cnt_dialog = 1;
/////////INIT//////////////////////
 $(window).resize(function() {
    $("div[id^=dialog]").each(function(o){
        if($(this).attr('id') != "dialog-overlay")
            $(this).dialog("option", "position", {my: "center", at: "center", of: window});
    });
});

///////////////////DIALOGS//////////////////////////////////////
function DialogOpen(options)
{

	if(!options.key)
		return;
	if(g_opened_dialogs[options.key])
		return;

	id = gDialogId();
	g_opened_dialogs[options.key] = {
        "id" : id,
        "preview_url" : DynamicHistory.preview_url
    };

//	debug(g_opened_dialogs);
	$( "#"+id).dialog({
		autoOpen: false,
		width:600,
		height: 610,
		modal: true,
		draggable: false,
		closeOnEscape: true,
		position : ["center"],
//		position : {  at: "center top+500", of: $("div#main-wrapper"), within : $("div#main-wrapper") },

	});

	$("#"+id).dialog("option", options);
	$("#"+id).dialog("open");
	$("html").addClass("no_scroll");

	g_cnt_dialog++;
}
function gDialogId(key)
{
	if(key && g_opened_dialogs[key])
		return g_opened_dialogs[key]['id'];

	var id = g_container_dialog+g_cnt_dialog;
	if(!getEl(id))
	{
		$("<div>")
		.attr("id",id)
//		.addClass("")
		.appendTo( $("body") );
	}
	return id;
}
function isOpenDialog(key)
{
    if (g_opened_dialogs[key])
        return true;
    return false
}

function DialogResize(key,w,h)
{
    var $d = $( "#"+gDialogId(key));
    $d.dialog("widget").animate({
        width: w,
        height: h
    }, {
        duration: 200,
        step: function (now, tween) {
            if (tween.prop == "width") {
                $d.dialog("option", "width", now);
            } else {
                $d.dialog("option", "height", now);
            }
        }
    });
}
function DialogClose(key)
{
    if(isOpenDialog(key)) {
        $("#" + gDialogId(key)).dialog("close");
        $("#" + gDialogId(key)).hide();

    }
}
///////////////////DOM////////////////////////

function  getEl(id)
{
	var el = document.getElementById(id);
	if(el)
		return el;
	return false;
}
function geByName(tag, el_name)
{
	var buff = [];
	var els = document.getElementsByTagName(tag);
//    for (el in els)
    for(var el=0; el < els.length; el++)
    {
    	if(els[el].name && els[el].name.match(el_name))
   		{
//		   	debug(els[el]);

        	buff[buff.length] = els[el];
   		}
    }
    return buff;
}
function geByTag(searchTag, node) {
	  return (node || document).getElementsByTagName(searchTag);
}
function each(object, callback) {
  var name, i = 0, length = object.length;

  if (length === undefined) {
    for (name in object)
      if (callback.call(object[name], name, object[name]) === false)
        break;
  } else {
    for (var value = object[0];
      i < length && callback.call(value, i, value) !== false;
        value = object[++i]) {}
  }

  return object;
}
///////////////////////////////////////////////////////////////////////////
function merge_arrays(arr) {
	var merged_array = arr;
	for (var i = 1; i < arguments.length; i++) {
	    for (var j in arguments[i]) {
	    	merged_array[j] = arguments[i][j];
		}
	}
	return merged_array;
}
function deleteConfim(url){
	var ver=confirm("Вы уверенны на удалении?");
	if(ver) location.href=url;
	else return false;
}
function objectLength(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
}
function printObject(obj,l) {
	var str = "";
    for (key in obj) {
        str += key + " => " + obj[key]+"\n";
    }
    if(l == 1)
    	console.log(str);
    else
    	alert(str);
}
function updateObject(obj) {
	var nobj = {};
	var i = 0;
    for (key in obj) {
        nobj[i] = obj[key];
        i++;
    }
    return nobj;
}
function debug(str)
{
	if(console)
		console.log(str);
	else
		alert(str);
}
function sAlert(str,class_)
{

	$('<div>')
		.attr('id','sAlert_overlay')
		.addClass('sAlert_bg')
		.click(function(){
			$('#sAlert_overlay, #sAlert').remove();
		})
		.appendTo($('body'));
	$('<div>')
		.attr('id','sAlert')
		.addClass('sAlert').addClass(class_)
		.html("<div><span onclick='$(\"#sAlert_overlay\").click();'>×</span></div><div>"+str+"</div><div onclick='$(\"#sAlert_overlay\").click();'>OK</div>")
		.appendTo($('body'));

}
//////////////////////////////////////////////////
function  getPageSize(){
   var xScroll, yScroll;

   var ua = navigator.userAgent.toLowerCase();
   var isOpera = (ua.indexOf('opera')  > -1);
   var isIE = (!isOpera && ua.indexOf('msie') > -1);

   var viewportHeight = ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientHeight : document.body.clientHeight : (document.parentWindow || document.defaultView).innerHeight;
   yScroll = Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight : document.documentElement.scrollHeight, viewportHeight);

   var viewportWidth = ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientWidth : document.body.clientWidth : (document.parentWindow || document.defaultView).innerWidth;
   xScroll = Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollWidth : document.documentElement.scrollWidth, viewportWidth);

   var windowWidth, windowHeight;
   if (self.innerHeight) { // all except Explorer
		   windowWidth = self.innerWidth;
		   windowHeight = self.innerHeight;
   } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		   windowWidth = document.documentElement.clientWidth;
		   windowHeight = document.documentElement.clientHeight;
   } else if (document.body) { // other Explorers
		   windowWidth = document.body.clientWidth;
		   windowHeight = document.body.clientHeight;
   }

   if(yScroll < windowHeight){
		   pageHeight = windowHeight;
   } else {
		   pageHeight = yScroll;
   }

   if(xScroll < windowWidth){
		   pageWidth = windowWidth;
   } else {
		   pageWidth = xScroll;
   }

   return {"pageWidth" : pageWidth, "pageHeight" : pageHeight,"windowWidth" : windowWidth, "windowHeight" : windowHeight};
}
////////////////////////////////////////OBJECTS/////////////////////////////////////////////////
var DynamicHistory = {
	symbol_html4 : '#!',
    preview_url : null,
    preview_url_dialog : null,

	SetUrl : function(url)
	{
        this.preview_url = document.URL;
		if(!url)
			return false;
		url = url.replace('?&','?');
		if(history && history.pushState)
	 		history.pushState({"rand":Math.random()}, document.title,url);
		else
		{
			var q = url.match(/\?(.*)/);
			dhtmlHistory.add("#!"+q[1],Math.random());
		}
	},
	HistoryChange : function(newLocation, historyData)
	{
	},
	OnLoad : function()
	{
		if(history && history.pushState)
		{
			window.addEventListener('popstate', function(e){
				if (history.state)
					window.location.href = window.location.href;
			}, false);

		}
		else
		{
			dhtmlHistory.initialize();
			dhtmlHistory.addListener(this.HistoryChange);
		}

	}
};
Ajax = {
	Load : function(el,container_id,form) //el - "tag a" or link
	{
		var url = null;
		var route = null;
		var load  = true;
		if(!container_id)
			container_id = g_container_id;
		else
		{
			var did = container_id.match(/^dialog(.*)/);
			if (did)
				container_id = gDialogId(did[1].toString());
		}

		if(typeof el == 'object') {
			if ($(el).attr('data-confirm')) {
				if (!confirm($(el).attr('data-confirm')))
					return false
			}
			if ($(el).attr('data-route'))
				route = $(el).attr('data-route');
			if (($(el).attr('data-load') == 1 && $('#'+container_id).html().replace(' ','').length > 0) ||
				$(el).attr('data-load') === 0
			)
				load = false;

			url = el.href ? el.href : null;
		}
		else
			url = website+'/'+el;

		DynamicHistory.SetUrl(url);

		if(!load)
			return false;

		if(!route) {
			var route = window.location.href.replace(/^.*\/\/[^\/]+/, '').substr(website.length + 1);
			if(route.match(/\?/))
				route = route.match(/[^\?]+\?/);
			if (route)
			{
				route = route.toString();
				if(route.substr(-1) == '/' || route.substr(-1) == '?')
					route = route.substr(0, route.length - 1);
				route = route.replace('.html','');
			}
		}

		if(form) {
			objXHR.post = 1;
			if(!Validate.Init(form))
				return false;
		}


		params = {"container_id" : container_id,"route" : route,"cget" : true, "form" : form};
		objXHR.GetResponse(params,function(cont,js_obj) {
			if(js_obj && js_obj['dialog_options'])
				DialogOpen(js_obj['dialog_options']);
			if(js_obj && js_obj['url'])
				DynamicHistory.SetUrl(js_obj['url']);
			if(route.match(/logout/)) {
				gObjSite.is_login = 0;
				Ajax.CheckLogin(true);
			}




		},true);
		/**/
		return false;
	},
    Login : function(form)
    {
        var params = {"container_id" : "login_container","route" : "users/login","form" : form};

        callback = function(content,obj)
        {
            DialogClose('login');

            if(obj.login == 1)
            {
                gObjSite.is_login = 1;
				Ajax.CheckLogin(true);
                if(obj.ref && obj.ref.length > 0) {
                    window.location = website+obj.ref;
                }
            }

        };
        objXHR.post = 1;
        objXHR.GetResponse(params,callback,1);
        return false;
    },
    Register : function(form)
    {
        if(!Validate.Init(form))
            return false;
        params = {"container_id" : gDialogId("register"), "route" : "users/register", "form" : form};
        objXHR.post = 1;
        objXHR.GetResponse(params,function(c,obj){
            if(obj.success) {
                DialogClose("register");
            }
        });
        return false;
    },
	EditPost : function(id)
	{
		var value = $.trim($('#m_post'+id).html());
		$('#m_post'+id).html($('<textarea>')
			.css({width : '100%', height : '70px'})
			.html(value)
			.blur(function(){
				var n_value = $(this).val();
				objXHR.GetResponse({
					'route' : "posts/save",
					'func2' : 'message',
					'id' : id,
					'name' : n_value,
					'subm_' : true,
					'callback' : function() {
						$('#m_post'+id).text(n_value);
						$('#m_post'+id).html($('#m_post'+id).html().replace(/\n/g,'<br/>'));
					}})
			}));


	},
	CheckLogin : function(no_alert)
	{
		if(gObjSite.is_login == 1) {
			$('.login_area').show();
			$('.login_error').hide();
			return true;
		}
		$('.login_area').hide();
		$('.login_error').show();
		if(!no_alert)
			sAlert("Пожалуйста, авторизируйтесь и повторите данное действие");
		return false;
	}
}
function ConfirmDeletePost(f)
{
	if(f ==  1)
		var str = "Это действие удалить данную тему.\nПожалуйста, подтвердите";
	else
		var str = "Это действие удалить данное сообщение.\nПожалуйста, подтвердите";
	if(confirm(str))
		return true;
	return false;

}