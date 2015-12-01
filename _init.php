<?
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
date_default_timezone_set('Europe/Kiev');
ob_start();
require "_app/_includes/function.php";

my_session_start();

require "constants.php";

require "_app/_libs/_init.php";

require GLOBAL_DIR . '/_app/_controllers/_basecontroller.php';
require GLOBAL_DIR . '/_app/_models/_basemodel.php';

$cTemplate = new Template();
$cDb       = new Db();

$cAuth     = new Auth();

DEFINE("IS_LOGIN",$cAuth->is_login);


require "_app/_includes/dialogs_config.php";


$g_controllers_list = array(
    "Main",
    "Users",
    "Topics",
    "Posts"
);
?>