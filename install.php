<?
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$step = 1;
$error = array();
$success_log = array();
$f_connected = FALSE;
if (isset($_POST['subm_button']))
{
    if(!isset($_POST['db_host']) ||
       !isset($_POST['db_username']) ||
       !isset($_POST['db_password']) ||
       !isset($_POST['db_name']))
        die;


    try
    {
        if(!empty($_POST['db_name']))
            $cDb = new mysqli($_POST['db_host'], $_POST['db_username'], $_POST['db_password'], $_POST['db_name']);
        else
            throw new mysqli_sql_exception('Введите название  базы данных');
        $f_connected = TRUE;
        $success_log[] = "Подключение к базе - успешно";
        $step = 2;

    }
    catch (mysqli_sql_exception $e) {
       $error[] = $e->getMessage();
    }
}
if ($step == 2)
{
  try
  {
    if(!is_file(dirname(__FILE__).'/constants.php-default'))
        throw new Exception("Не найден файл constants.php-default");

    $url = isset($_POST['site_path']) ? parse_url($_POST['site_path']) : NULL ;
    $content_cfg = file_get_contents(dirname(__FILE__).'/constants.php-default');
    $new_cfg = str_replace(
        array("[DB_HOST]","[DB_LOGIN]","[DB_PASSWORD]","[DB_NAME]","[WEBSITE]","[DOMAIN]"),
        array($_POST['db_host'],$_POST['db_username'],$_POST['db_password'],$_POST['db_name'],$_POST['site_path2'],$_POST['site_path']),
        $content_cfg
    );
    if(!$h = @fopen(dirname(__FILE__).'/constants.php','w+'))
        throw new Exception("Не удается создать файл ".dirname(__FILE__).'/constants.php');
    if(fwrite($h,$new_cfg))
    {
        $success_log[] = "Создание конфига - успешно";
        $step = 3;
    }
    else
        throw new Exception("Не удается записать в файл ".dirname(__FILE__).'/constants.php');
 }
  catch (Exception $e) {
      $error[] = $e->getMessage();
  }
}
if ($step == 3)
{
  try
  {
      if(!is_file(dirname(__FILE__).'/db.sql'))
          throw new Exception("Не найден файл db.sql");
      $arr = file(dirname(__FILE__).'/db.sql');
      try
      {
          foreach ($arr as $v)
          {
            $cDb->Query($v);
          }
          $success_log[] = "Загрузка дампа - успешно";
          $step = 4;
      }
      catch (mysqli_sql_exception $e) {
          throw new Exception($e->getMessage());
      }

  }
  catch (Exception $e) {
      $error[] = $e->getMessage();
  }


}
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>INSTALL</title>
</head>
<body style="background-color: #ccc;">
   <div style="width: 800px; margin: 0 auto; background-color: #ffffff;">
       <div style="width: 500px; margin: 0 auto;">
           <div style="color: green; font-weight: bold;"><?=implode('<br>',$success_log)?></div>
           <div style="color: red; font-weight: bold;"><?=implode('<br>',$error)?></div>
           <?
           if ($step<4)
           {
           ?>
           <form method="POST">
           <table>
               <tr><td colspan="2" style="text-align: center; font-weight: bold;">SQL</td></tr>
               <tr><td>HOST</td><td><input type="text" name="db_host" value="localhost"></td></tr>
               <tr><td>Username</td><td><input type="text" name="db_username" value="root"></td></tr>
               <tr><td>Userpasswd</td><td><input type="password" name="db_password"></td></tr>
               <tr><td>DB Name</td><td><input type="text" name="db_name"></td></tr>
               <tr><td></td><td><span id="container_test_db"></span></td></tr>
               <tr><td colspan="2" style="text-align: center; font-weight: bold;">Настройки</td></tr>
               <tr><td>Домен/ИП</td><td><input type="text" name="site_path" value="http://<?=$_SERVER['HTTP_HOST']?>" size="80"></td></tr>
               <tr><td>Директория</td><td><input type="text" name="site_path2" value="<?=substr($_SERVER['REQUEST_URI'],0,-12)?>" size="80"></td></tr>
               <tr><td></td><td><input type="submit" name="subm_button"></td></tr>
           </table>
           </form>
           <?}?>
       </div>
   </div>
</body>
</html>

