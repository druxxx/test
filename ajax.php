<?
header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
require "_init.php";
$controllers_cache = array();

$g_controllers_list = array_flip($g_controllers_list);


$gIs_ajax = TRUE;

$data       = str_replace(array("\n","\t"),array("\\n","    "),getval('adata','g;p'));
$method     = getval('method','g;p');
$frame_load = getval('frame_load');
$arr        = json_decode($data,true);
//echo $data;var_dump(json_last_error_msg());
$response = array();
if(empty($arr))
	return;
foreach ($arr as $value) {
	if(!isset($value['id']) || !isset($value['params']))
		return;
	$id      = $value['id'];
	$aParams = (array)$value['params'];
	if(!empty($aParams['current_page']))
		$CURRENT_PAGE = $aParams['current_page'];

	if($method == 1)
		$_POST = $aParams;
	else
		$_GET = $aParams;
    if(isset($arr[0]['params']['cget']) && is_array($arr[0]['params']['cget']))
        $_GET += $arr[0]['params']['cget'];
    elseif(isset($aParams['cget']))
        $_GET += $aParams['cget'];

    $ajax = require GLOBAL_DIR.'/routing.php';

	if($ajax)
	{
		$res = $ajax->AjaxGetResult();
		$res['container_id'] = $id;
		$res['callback'] = gv('callback',$aParams);
//		$res = array_map('trim',$res);
		$response[] = $res;
		UNset($ajax);
	}
}
//ob_clean();

if($frame_load == 1)
	echo "<div id=\"AJAX_IFRAME_CONTENT\">".json_encode($response, JSON_HEX_TAG)."</div><div id=\"AJAX_IFRAME_SUCCESS\">1</div>";
else
	echo json_encode($response);//,JSON_UNESCAPED_UNICODE);


?>