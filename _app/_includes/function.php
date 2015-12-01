<?
function is_admin_pk()
{
    $ip = array('217.73.84.223','192.168.0.10','127.0.0.1');
    if(in_array($_SERVER['REMOTE_ADDR'],$ip))
        return true;
    return false;
}
function redirect($url,$send_post = FALSE)
{
    if($send_post)
    {
        $_SESSION['last_redirect_post'] = $_POST;
        $_SESSION['last_redirect_post_tstamp'] = time()+60*60;

    }
    header('Location: '.WEBSITE.$url);

    die;
}
function get_session_post()
{
    if(!gv('last_redirect_post',$_SESSION) || !gv('last_redirect_post_tstamp',$_SESSION))
        return $_POST;
    if((int)gv('last_redirect_post_tstamp',$_SESSION) < time())
        return $_POST;
    return gv('last_redirect_post',$_SESSION);
}
function my_session_start()
{
	if (ini_get('session.use_cookies') && isset($_COOKIE['PHPSESSID'])) {
		$sessid = $_COOKIE['PHPSESSID'];
	} elseif (!ini_get('session.use_only_cookies') && isset($_GET['PHPSESSID'])) {
		$sessid = $_GET['PHPSESSID'];
	} else {
		session_start();
		return false;
	}

	if (!preg_match('/^[a-z0-9]{2,32}$/', $sessid)) {
		return false;
	}
	session_start();

	return true;
}

function printr($printarray,$fl=0)
{
	echo"<pre>";
	print_r($printarray);
	echo"</pre>";
	if($fl==1)
		die();
}
function gv($i,$arr,$def = NULL)
{
    if(!$arr)
        return $def;
    if(isset($arr[$i]))
        return $def == '-' && empty($arr[$i]) ? $def : $arr[$i];
    else
        return $def;
}
function getval($val,$str='g',$type="int",$def = NULL) //str=g;p;s;c
{
    $str.=';';
    if($type == "int")
        $r = 0;
    elseif($type == "int")
        $r = "";
    else
        $r = NULL;
    if($def)
        $r = $def;
    $s=explode(';',$str);
    for($i=0;$i<=count($s);$i++){
        if(empty($s[$i])) continue;
        $v=$s[$i];
        if($v=='g' && !empty($_GET[$val]) && (!is_array($_GET[$val]) || $type == "array") )
            return $_GET[$val];
        if($v=='p' && !empty($_POST[$val]) && (!is_array($_POST[$val]) || $type == "array") )
            return $_POST[$val];
        if($v=='s' && !empty($_SESSION[$val]) && (!is_array($_SESSION[$val]) || $type == "array") )
            return $_SESSION[$val];
        if($v=='c' && !empty($_COOKIE[$val]) && (!is_array($_COOKIE[$val]) || $type == "array"))
            return $_COOKIE[$val];
    }
    return $r;
}
function isDouble($v) 
{
	if(!empty($v) && (preg_match('/^[0-9]+\.[0-9]+/',$v) || isInt($v)) )
		return true;
	return false;
}
function isInt($v,$null=0) 
{
	if(isset($v) && preg_match('/^\-*[0-9]{1,100}$/',$v)) {
		if(($null == -1) 	||
		   ($null ==  0 && $v > 0)	||
		   ($null ==  1 && $v >= 0))
			return true;
	}

	return false;
}
function escape_html($str)
{
    return htmlspecialchars($str);
}
function arrayToQ($ignore = array(),$t=NULL)
{
    $ignore[] = 'route';
    $ignore[] = 'container_id';
    $ignore[] = 'cget';
    $ignore[] = 'callback';


    if(!$t) $t = $_GET;
	foreach ($ignore as $value) 
	{
		if(isset($t[$value]))
			unset($t[$value]);
	}
    $g = str_replace(array('%5B','%5D','%7C'), array('[',']','|'), http_build_query($t)); 
    return '?'.(!empty($g) ? $g."&" : '');
	
}

function toTranslit($str,$fl=0) 
{
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ё"=>"E","Ж"=>"J","З"=>"z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","Х"=>"H","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
        "х"=>"h", " "=> "_", "."=> ".", "/"=> "-"
    );
    if($fl==1){
    	$tr=array_flip($tr);
//    	printr($tr);
    }
    return strtr($str,$tr);
}
function updateArray($arr)
{
	$narr = array();
	foreach($arr as $el) 
	{
		if(!empty($el))
			$narr[] = $el;
	}
	return $narr;
}
function my_debug_backtrace($fileToExclude = NULL)
{
  $arr = debug_backtrace();
  $k = NULL;
  for ($i = 0; $i < count($arr); $i ++)
  {
      $f = $arr[$i]['file'];
      if ($fileToExclude != $f &&
          $k === NULL)
          $k = $i;
      $mes = & $arr[$i];
      $arr[$i] = array
      (
          'file' => $f,
          'line' => $mes['line'],
          'function' => $mes['function']
      );
  }
  return array($arr, $k);
}
function explodeAssoc($glue,$str)
{
   $arr=explode($glue,$str);

   $size=count($arr);

   for ($i=0; $i < $size/2; $i++)
       $out[$arr[$i]]=$arr[$i+($size/2)];

   return($out);
}; 
function send_email($from,$to,$subject,$message){
          $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: '.$to. "\r\n";
//            $headers .= 'From: '.('=?windows-1251?B?' . base64_encode('������������� �����') . '?=').' <'.$from.'>' . "\r\n";
            $headers .= 'From: '.$from. "\r\n";
//            $subject  = '=?windows-1251?B?' .     base64_encode($subject) . '?=';
            
            return mail($to, $subject, $message, $headers);
}

function int2ip($i)
{
    $d[0]=(int)($i/256/256/256);
    $d[1]=(int)(($i-$d[0]*256*256*256)/256/256);
    $d[2]=(int)(($i-$d[0]*256*256*256-$d[1]*256*256)/256);
    $d[3]=$i-$d[0]*256*256*256-$d[1]*256*256-$d[2]*256;
    return "$d[0].$d[1].$d[2].$d[3]";
}
function ip2int($ip = NULL)
{
    if(!$ip)
        $ip = $_SERVER['REMOTE_ADDR'];
    $a=explode(".",$ip);
    return $a[0]*256*256*256+$a[1]*256*256+$a[2]*256+$a[3];
}
?>