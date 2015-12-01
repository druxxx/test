<?
if(!is_file(dirname(__FILE__).'/constants.php'))
{
    echo 'Запустите <a href="install.php">install.php</a>';
    die;
}
if(is_file(dirname(__FILE__).'/install.php'))
{
    echo '<span style="color: red;">Удалите install.php</span>';
}
//test!!git

require "_init.php";
$g_controllers_list = array_flip($g_controllers_list);
require "routing.php";
?>

