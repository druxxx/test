var Validate = {
    Init : function(f,alert)
    {
        var cnt_err = 0;
        var arr_tmp = [];
        $(f).find('div.error div').each(function()
        {

            var type = $(this).attr('data-vt').split(':');
            var container = $(this).parents('.field_container');
            $(container).uniqueId();




            if(!Validate['Is'+type[0]])
            {
                debug("Not found validate func: "+'Is'+type[0]);
                return false;
            }

            var container_error = this;
            $(container).find('input, textarea, select').each(function(){
                if(!Validate['Is'+type[0]](f[$(this).attr('name')].value,(type[1] ? type[1] : null),f))
                {
                    $(this).addClass('error_input');
                    if(!arr_tmp[$(container).attr('id')]) {
                        arr_tmp[$(container).attr('id')] = 1;
                        $(container_error).show();
                    }
                    cnt_err++;
                }
                else
                {
                    if(!arr_tmp[$(container).attr('id')])
                        $(this).removeClass('error_input');
                    $(container_error).hide();
                }


            });
        });

        return cnt_err == 0 ? true : false;
    },
////////Особые формы//////////////////
//////////////////////////////////////
    IsString : function(str,flag)
    {
         if(flag == 1)
            return str.length > 0 ? true : false;
        return true;
    },
	IsDouble : function(str,flag) {
		if(str.toString().match(/^[0-9]+\.[0-9]+$/) || this.IsInt(str))
		{
			if(flag == 1)
				return parseFloat(str) > 0 ? true : false;
			return true;
		}
		return false;
	},
    IsInt : function(str,flag) {
        if(flag == 1)
            return parseInt(str) > 0 ? true : false;
        return str.toString().match(/^[0-9]+$/);
    },
    IsDate : function(str,flag) {
        return str.match(/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/);
    },
	IsEmail : function(str) {
		return str.toString().match(/^[-_A-Za-z0-9][A-Za-z0-9\._-]*[A-Za-z0-9_]*@([A-Za-z0-9]+([A-Za-z0-9-]*[A-Za-z0-9]+)*\.)+[A-Za-z]+$/);
	},
    IsComparePassword : function(str,flag,f)
    {
        return str == f['password_id'].value ? true : false;
    },
	IsPhone : function(str) 
	{
		return str.toString().match(/^\+*[0-9]{10,15}$/);
	},
	IsLogin : function(str) {
		return str.toString().match(/^[A-Za-z][A-Za-z0-9_]{2,15}$/i);
	},
	CheckNumber : function(val)
	{
		if(typeof(val) == 'string' && (!Validate.IsDouble(val,1) || val.replace(' ','').length === 0))
		{
			if (val.toString().replace(' ','').length == 0)
				return '';
			if(!val.toString().match(/^[0-9\,\.]+$/))
				val = parseFloat(val);
			else if( val.toString().match(/[\,\.]/g) && val.toString().match(/[\,\.]/g).length > 1)
				val = parseFloat(val);
		}
		if(val)
			val = val.toString().replace(',','.');
		return val;
	},
////////////////Для не стандартных форм//////////////////////////////////

	CheckErrors : function(errorsList,errors,linkErrEl,pref,alert_flag) 
	{
		var er=0;
		var buff = "";
		var tmp_errors = {};
        var tmp_tr_open = {};
	    for(var i=0;i<errorsList.length;i++)
	    {	
	  		if(errors[errorsList[i]]==1 && getEl(pref + errorsList[i]))
	  		{
	  			if(alert_flag)
	  				buff += getEl(pref + errorsList[i]).innerHTML + "\n";
	  			else 
	  			{
		       		var tr_container = pref + errorsList[i];
		       		if(tmp = tr_container.match(/(.*)[0-9]$/))
		       			tr_container = tmp[1];
		       		getEl(pref + errorsList[i]).style.display = 'block';
                    if(getEl('tr_' + tr_container)) {
                        getEl('tr_' + tr_container).style.display = '';
                        tmp_tr_open['tr_' + tr_container] = true;
                    }
	  			}
	  			
		       	if(linkErrEl[errorsList[i]] && linkErrEl[errorsList[i]][0] && linkErrEl[errorsList[i]][0] == 'object') 
		       	{ // object
		       		    for (var k in linkErrEl[errorsList[i]]) 
		       		    {
		       		    	if(k ==0) continue;
				       		$(linkErrEl[errorsList[i]][k]).addClass("error_input");
				       		tmp_errors[linkErrEl[errorsList[i]][k].name] = 1; 
							if(linkErrEl[errorsList[i]][k] == "[object HTMLInputElement]")
					       		linkErrEl[errorsList[i]][k].focus();
						}
		       	}
		       	else 
		       	{
					$(linkErrEl[errorsList[i]]).addClass("error_input");
		       		tmp_errors[linkErrEl[errorsList[i]].name] = 1; 
					if(linkErrEl[errorsList[i]] == "[object HTMLInputElement]")
			       		linkErrEl[errorsList[i]].focus();
				}
	       		er++;
	    	}
	        else if(getEl(pref + errorsList[i]))
	        {
    	       	var tr_container = pref + errorsList[i];
	       		if(tmp = tr_container.match(/(.*)[0-9]$/))
	       			tr_container = tmp[1];

                if(getEl('tr_' + tr_container)) {
                    if (!tmp_tr_open['tr_' + tr_container])
                        getEl('tr_' + tr_container).style.display = 'none';
                }

                getEl(pref + errorsList[i]).style.display = 'none';
 		       	if(linkErrEl[errorsList[i]] && linkErrEl[errorsList[i]][0] && linkErrEl[errorsList[i]][0] == 'object')
		       	{
		       	// object
	       		    for (var k in linkErrEl[errorsList[i]]) {
	       		    	if(k ==0) continue;
	       		    	if(!tmp_errors[linkErrEl[errorsList[i]][k].name])
							$(linkErrEl[errorsList[i]][k]).removeClass("error_input");
			       	}
		       	}

		       	else if(linkErrEl[errorsList[i]])
		       	{
       		    	if(!tmp_errors[linkErrEl[errorsList[i]].name])
       		    	{
						$(linkErrEl[errorsList[i]]).removeClass("error_input");
			       	}
		       	}
		        else
		        	debug("El '"+errorsList[i]+"' not found in linkErrEl");
	        }
	        else
	        	debug("El '"+pref + errorsList[i]+"' not found");
	    }
	    
	    if (er==0)
	    	return true;
	    if(alert_flag)
	    	alert(buff);
	    return false;
		
	}

};