<?

$is_ajax = isset($gIs_ajax) ? $gIs_ajax : FALSE;
$route   = getval("route",'g;p','str');
//////////////////////////RewriteRule/////////////////////////////////
if($route == '') $route ='main';
elseif($route == 'forums') $route = 'Forums/main';
elseif($route == 'topics') $route = 'Topics/main';
//////////////////////////////////////////////////////////////////////
$route = explode("/", $route);
if(count($route) == 1)
    $route = array("Main",$route[0]);


$controller_name = !empty($route[0]) ? ucfirst($route[0]) : NULL;
$action_name = !empty($route[1]) ? ucfirst(str_replace('_','',$route[1])) : NULL;
$action_name_ = gv(1,$route,'');

if(!isset($g_controllers_list[$controller_name]))
	$controller_name = "Main";
$controller_name_= strtolower( $controller_name);

$class_name = "Controller_".$controller_name;
$model_name = "Model_".$controller_name;

if(isset($controllers_cache[$controller_name_]))
{
    $cController = $controllers_cache[$controller_name_];
}
elseif(is_file(APP_DIR.'_controllers/'.$controller_name_.'.php'))
{
    require APP_DIR.'_controllers/' . $controller_name_ . '.php';
    $cController        = new $class_name($is_ajax);
    $controllers_cache[$controller_name_] = &$cController;
    $cController->ajax_dialog = (bool)getval('flag_dialog','g');
}
elseif(is_admin_pk())
{
    echo "Not found controller: " .$controller_name_ . '.php<br>';
    die;
}
else
    BaseController::Page_404();


if(is_file(APP_DIR.'_models/'.$controller_name_.'.php'))
{
    $cController->model = $cController->GetModel($controller_name_);
}
elseif(is_admin_pk())
{
//    echo "Not found model: " . $controller_name_ . '.php<br>';
//    die;
}

$action_name .= "_Action";

$cController->data['g_method'] = strtolower($action_name_);
if($cController->protect && method_exists($cController, $action_name))
	$cController->$action_name();
else
    echo 'debug:'.$controller_name.'/'.$action_name.';'; //debug

return $cController ;
?>

