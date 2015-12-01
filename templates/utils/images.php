<?
include_once(TEMPLATES_DIR.'helpers/utags.php');

function tmpl_utils_images_add ($params)
{
	global $cTemplate;
	$cTemplate->Register("tmpl_utils_images_carousel");
?>
	<div id="cont_gallery">
		<?if (!empty($params['images'])) :?>
			<?$cTemplate->Render("tmpl_utils_images_carousel",$params); ?>
		<?endif;?>
	</div><br />
        <script type="text/javascript" src="<?=JS_URL?>swfobject.js"></script>
            <script type="text/javascript" charset="utf-8">
            var FU_err = "";
            function FUpload_start(p)
        	{
//        		$('#loading_bg, #loading_icon').css('display','block');
        	}
            function FUpload_success(p)
            {
                Images.ViewCarousel("cont_gallery","<?=$params['obj_type']?>","<?=$params['obj_id']?>");
//        		$('#loading_bg, #loading_icon').css('display','none');
                if(FU_err.length > 0) {
                    alert(FU_err);
                    FU_err = '';
                }
            }
            function FUpload_error(p)
            {
                FU_err +=p;
            }
            function FUpload_exit(p)
            {
                alert("Загружено максимальное количество фотографий");
            }
			function MultiUploads_DoFSCommand(command, args)
			{
		        window[command].call(null, args);
			}
         </script>

		<!--[if IE]> 
		<script type="text/javascript" event="FSCommand(command,args)" for="MultiUploads">
		    MultiUploads_DoFSCommand(command, args);
		</script>
		<![endif]-->
        <script type="text/javascript">
            swfobject.embedSWF(
                "<?=WEBSITE?>/MultiUploads.swf", "flashContent", 
                "500px", "40px", 
                "11.1.0", false, 
                {"obj_type" : "<?=$params['obj_type']?>", "obj_id":<?=$params['obj_id']?>,"session_id" : "<?=session_id()?>","path" : "<?=WEBSITE?>/"}, 
                {"quality" : "high", "bgcolor" : "#ffffff", "allowscriptaccess" : "sameDomain", "allowfullscreen" : "true"},                
                {"id" : "MultiUploads","name" : "MultiUploads"});

        </script>
	        <div id="flashContent"></div>


<?	
}
function tmpl_utils_images_carousel ($params)
{
    global $cTemplate;
    $cTemplate->Register("tmpl_utags_a");
    $cTemplate->Register("tmpl_get_img_pach");
    $cTemplate->Register("tmpl_utags_storage_img");

    if(isset($params['data']))
        $params['images'] = $params['data'];


    if (empty($params['images']) ||
        empty($params['obj_type']) ||
        empty($params['obj_id']) )
    {
        return false;
    }

    $div_id    = !empty($params['div_id']) ? $params['div_id'] : 'cont_gallery';

    $obj_type  = $params['obj_type'];
    $obj_id    = $params['obj_id'];
    $module    = '';//$params['module'];
    $main_img  = !empty($params['mimg']) ? $params['mimg'] : 0;
    $class_div = !empty($params['class_div']) ? $params['class_div'] : 'sc_menu';
    $class_ul  = !empty($params['class_ul']) ? $params['class_ul'] : 'sc_menu';
    $obj       = !empty($params['obj']) ? $params['obj'] : NULL;
    $only_img  = !empty($params['only_img']) ? true : false;
    $js_method = !empty($params['js_method']) ? $params['js_method'] : NULL;
    ?>

    <div class="<?=$class_div?>" <?=($only_img ? 'style="height: 105px;"' : '')?>><ul class="<?=$class_ul?>">
            <?foreach ($params['images'] as $img)
            {
                if ($module == "articles")
                    $name_img = !empty($img['name']) ? $img['name'] : "Название";
                else
                    $name_img = !empty($img['name']) ? preg_replace('/\.[a-zA-Z]{3,4}$/','',$img['name']) : "Название";

                $data_img = $img;
                ?>
                <li id="li_img_id_<?=$div_id.'_'.$img['id']?>">
                    <?if (!$only_img) :?>
                        <?=$img['description']?>
                        <?if ($module == "articles") :?>
                            <br />id: <?=$img['id']?>
                            <br />code: <?=$img['code_number']?>

                        <?endif;?>

                    <?endif;?>

                    <?$cTemplate->Render("tmpl_utags_storage_img",$data_img,array(
                        "img_code_number" => $img['code_number'],
                        "img_name" => $img['name'],
                        "href" => "src",
                        "onclick" => $js_method ? "Images.".$js_method."(".$img['obj_id'].",".$img['id'].",'".$obj_type."','".$module."'); return false;" : "return false;",
                        "width" => 100,
                        "height" => 100,
                        "target" => "_blank",
                        "iid" => "c_".$obj_type."_img_".$img['id'],

                    ));?>

                    <?if (!$only_img)
                    {?>
                        <span id="ChangeNameImage<?=$img['id']?>">
                <?$cTemplate->Render("tmpl_utags_a",array(
                    "onclick" => "editFieldInput('ChangeNameImage".$img['id']."','".addslashes($name_img)."'); return false;",
                    "value" => $name_img
                ))?>
            </span>
                        <?if ($module != "articles") {?>
                        <?if ($img['id'] == $main_img) :?>
                            <div style="text-align: center; font-size: 10px; margin-top: 0px; color: green;">Главная</div>
                        <?else :?>
                            <span>
                    <?$cTemplate->Render("tmpl_utags_a",array(
                        "onclick" => "Images.SetMainImage('".$div_id."',".$img['obj_id'].",".$img['id'].",'".$obj_type."','".$module."',1,".($module == "brands_models" || $module == "brands" ? $obj['id'] : 0)."); return false;",
                        "value" => "Сделать главной",
                    ))?>
                    </span>
                        <?endif;
                    }?>
                        <?if ($module == "brands_models" && $obj) : ?>
                        <?$cTemplate->Render("tmpl_utags_a",array(
                            "onclick" => "Images.MoveImageToBrandFromModel('".$div_id."','brands_images',".$img['id'].",".$obj['id'].",".$obj['id_model']."); return false;",
                            "value" => "Перенести в марку",
                        ))?>
                    <?elseif ($module == "brands" && $obj) : ?>
                        <?$cTemplate->Render("tmpl_utags_a",array(
                            "onclick" => "Images.MoveImageToModelFromBrand('".$div_id."','models_images',".$img['id'].",".$obj['id_model'].",".$obj['id']."); return false;",
                            "value" => "Перенести в модель",
                        ))?>
                    <?endif;?>
                        <span>
                <?$cTemplate->Render("tmpl_utags_a",array(
                    "onclick" => "Images.DeleteInGallery('".$div_id."','".$obj_type."',".$obj_id.",".$img['id']."); return false;",
                    "value" => "Удалить",
                ))?>
            </span>
                    <?}?>
                </li>
            <?}?>
        </ul></div>
    <script type= "text/javascript">
        jQuery.initCarousel('<?=$class_div?>','<?=$class_ul?>',15);
    </script>

<?
}	
?>