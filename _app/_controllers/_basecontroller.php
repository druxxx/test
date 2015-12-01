<?php
class BaseController
{
    var $cTemplate, $cDb,$cAuth;
    var $models_cache = array();
    var $protect = TRUE;
    var $ajax = FALSE;
    var $ajax_dialog = FALSE;

    var $data = array(
        "center_template" => "forum/main/",
        "center_data"     => array(),
        "dialog"          => array(),
        "js"              => null,
        "metadata"        => array()
    );
    var $c_data = array();
    var $dialogs_data = array();
    var $main_template = "main";
    var $ajax_vars = array(
        "AJAX_ERRORS"  => "",
        "AJAX_INFO"    => "",
        "AJAX_JSINFO"  => array(),
        "AJAX_CONTENT" => "",
    );
    var $is_login = false;
    var $is_admin = false;
    var $model;

    protected static $instances;


    function __construct ($ajax = FALSE)
    {
        global $cTemplate, $cDb, $cAuth;
        $this->cTemplate   = $cTemplate;
        $this->cDb         = $cDb;
        $this->cAuth       = $cAuth;
        $this->is_login    = $cAuth->is_login;
        $this->is_admin    = $cAuth->is_admin;

        $this->ajax        = $ajax;
        $this->user        = $cAuth->user;
        $this->dialogs_data= &$this->data['dialog'];

        $this->data['center_data'] = &$this->c_data;
        $this->data['form_login'] = $this->cAuth->GetParamsFormLogin();
    }
    function Main_Action()
    {
        $this->Render();
    }

    function Render ($ct = NULL,$data = NULL,$dialog = false)
    {

        if (($this->ajax || $dialog) && $ct)
        {
            return $this->cTemplate->Render($ct,$data !== NULL ? $data : $this->data,NULL,$dialog);
        }
        elseif (!$this->ajax)
        {
            if($data  !== NULL)
                $this->c_data = $data;
            if($ct)
                $this->data['center_template'] = $ct;
            $this->cTemplate->Render($this->main_template, $this->data);
        }
    }
    function CreateDialog($key,$template,$data)
    {
        $this->ajax_vars['AJAX_JSINFO']['dialog_options'] = $GLOBALS['dialogs_config'][$key];

        $this->dialogs_data[] = array(
            'options' => json_encode($GLOBALS['dialogs_config'][$key]),
            'data'    => $this->Render($template,$data, ($this->ajax ? FALSE : TRUE))
        );
    }
    static function Page_404()
    {
        global $cTemplate;
        $cTemplate->Render('errors/404/');
        die;
    }
    function CheckUser($url = NULL)
    {
        if(!$this->user)
        {
            if($url)
                redirect('/login/?ref='.$url);
            else
                $this->data['js'] .= "Ajax.CheckLogin();";
            return false;
        }
        return true;

    }
    function AjaxGetResult()
    {
        $this->ajax_vars['AJAX_CONTENT'] = ob_get_contents();
        ob_clean();
        return $this->ajax_vars;
    }


  ////////////////////////////////////////////////////////////////
    function GetModel($name)
    {

        if(isset($this->models_cache[$name]))
        {
            return $this->models_cache[$name];
        }
        require_once APP_DIR.'_models/'.strtolower($name).'.php';

        $model_name = "Model_".str_replace('_','',$name);
        return $this->models_cache[$name] = new $model_name();
    }

    final public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }
  ///////////////////////////////////////////////////////////////////////////////////
}
?>