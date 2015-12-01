<?
include_once(TEMPLATES_DIR.'helpers/utags.php');
include_once(TEMPLATES_DIR.'helpers/form.php');
class Template
{
    var $funcs_array = array();
    var $static_libs = array();
	
	function Template () 
	{
		$this->log_file = new LogFile("template");
	}
	function Register ($func_name, $func_real_name = NULL) 
	{
		if ($func_real_name)
			$this->funcs_array[$func_name] = $func_real_name;
		else
			$this->funcs_array[$func_name] = $func_name;
	}
	function Render ($fname, $params = NULL, $additional_params = NULL,$return = false)
	{
        if ($additional_params)
        {
            foreach ($additional_params as $k => $v)
                $params[$k] = $v;
        }


        $cTemplate = &$this;
		$debug=debug_backtrace();
		$dstr = isset($debug[0]) ? $debug[0]['file'].':'.$debug[0]['line'] : '';
		$file = NULL;
//		$tmp_fname = $fname;
        $res = array();
		if (strpos($fname,"/"))
		{
			$res = explode("/",$fname);
            $fl = preg_match("/\/$/",$fname);
			if ($fl)
                array_pop($res);

            $fname_ = array_pop($res);

            $file  = implode('/',$res);
            $fname = false === strpos($fname_,'tmpl_') ? 'tmpl_'.str_replace('/','_',$file).'_'.$fname_ : $fname_;
            if ($fl)
                $file .= '/'.$fname_;
		}
        else
            $file = false === strpos($fname,'tmpl_') ? $fname : NULL;

		$func_name = isset($this->funcs_array[$fname]) ? $this->funcs_array[$fname] : '';

        if ($return)
        {
            $cont = ob_get_contents();
            ob_clean();
        }
          if (!function_exists($func_name) && $file && isset($this->funcs_array[$fname]))
          {
            if (is_file(TEMPLATES_DIR.$file.'.php'))
                include_once(TEMPLATES_DIR.$file.'.php');
            else
            {
                $this->log_file->Add($dstr);
                $this->log_file->Add("Err: file not found: '".$file.".php'");
                return false;
            }
          }
          if (!isset($this->funcs_array[$fname]))
          {
              if ($file && is_file(TEMPLATES_DIR.$file.'.php'))
              {
                  require (TEMPLATES_DIR.$file.'.php');
//                  include_once(TEMPLATES_DIR . $file . '.php');
              }
              else
              {
                  $this->log_file->Add($dstr);
                  $this->log_file->Add("Err: Not found function '" . $fname . "' in array");
              }
          }
          elseif (!function_exists($func_name))
          {
              $this->log_file->Add($dstr);
              $this->log_file->Add("Err: '".$func_name."' is not a function; file: ".$file);
              return false;
          }
          else
              $func_name($params);

        if ($return)
        {
			$res = ob_get_contents();
			ob_clean();
			echo $cont;
			return $res;
		}
	}
//	function Get($file,$params = NULL) {
//		if (is_file(TEMPLATES_DIR.$file.'.php'))
//			include_once(TEMPLATES_DIR.$file.'.php');
//	}
/*    function Render($file,$center = NULL,$params = array(),$return = null)
    {
        global $cTemplate;
        if($center)
        {
//            $params["center_data"] = $params;
            $params["center_template"] = $center;
        }


        if ($return)
        {
            $cont = ob_get_contents();
            ob_clean();
        }

        if (is_file(TEMPLATES_DIR.$file.'.php'))
            include_once(TEMPLATES_DIR.$file.'.php');


        if ($return)
        {
            $res = ob_get_contents();
            ob_clean();
            echo $cont;
            return $res;
        }
    }*/
    function GetLang($code,$replace=NULL,$lang = NULL)
    {
        global $lang_dir;
        if($lang)
            $lang_dir = $lang;

        $file = NULL;
        $arr  = "main";
        if (strpos($code,"/"))
        {
            $res = explode("/",$code);
            switch (count($res)) {
                case 3:
                    $file = $res[0].'/'.$res[1];
                    $arr  = $res[0].'_'.$res[1];
                    $code = $res[2];
                    break;
                case 2:
                    $file = $res[0];
                    $arr  = $res[0];
                    $code = $res[1];
                    break;
                default:
                    break;
            }
        }
        $arr = 'lang_'.$arr;
        if(!isset($GLOBALS[$arr]) && $file)
        {
            if(!is_file(LANGUAGES_DIR.$lang_dir.'/'.$file.'.php'))
                return  "Err: Open lang file";
            require(LANGUAGES_DIR.$lang_dir.'/'.$file.'.php');

        }

        if(!isset($GLOBALS[$arr]))
            return  "Err: Get Lang";
//var_dump($GLOBALS[$arr][$code]);
        if(isset($GLOBALS[$arr][$code]))
        {
            $lr = $GLOBALS[$arr][$code];
            if($replace)
            {
                $k = array_keys($replace);
                $v = array_values($replace);
                return str_replace($k, $v, $lr);
            }
            return $lr;
        }
        else
            return  "Err: Get Code";
    }
    function RegisterStatic($f)
    {
        if(is_array($f))
        {
            foreach ($f as $v)
            {
                $this->static_libs[] = $v;
            }
        }
        else
            $this->static_libs[] = $f;
    }
    function LoadStatic($arr = NULL)
    {
        $arr = $arr ? $arr : $this->static_libs;
        foreach($arr as $f)
        {
            if(false !== strpos($f,'.css') || false !== strpos($f,'/css') )
                echo "<link rel=\"stylesheet\" href=\"".(false !== strpos($f,'http://') || false !== strpos($f,'https://') || substr($f,0,1) == '/' ? "" : CSS_URL).$f."\" />\n";
            elseif(false !== strpos($f,'.js'))
            {
                if (false === strpos($f,'http://') &&
                   false === strpos($f,'https://'))
                {
                    $f = (substr($f,0,1) != '/' ? JS_URL : '').
                         $f.
                         (is_file(GLOBAL_DIR.'/static/js/'.$f) ? '?r='.filemtime(GLOBAL_DIR.'/static/js/'.$f) : '');
                }
                echo "<script type=\"text/javascript\" src=\"".$f."\"></script>\n";
            }
        }
    }
}	
?>