<?
global $gUser;
$cTemplate->Register("tmpl_utags_a");
$cTemplate->Register("tmpl_login_success");
$cTemplate->Register("tmpl_login_default");
$cTemplate->Render("header",array('metadata' => $params['metadata'],'js' => $params['js']));
?>
<body id="g_body">
<div class="main-container">
   <header>
    <div class="login" id="login_container">
        <?if($gUser) :?>
            <?$cTemplate->Render("login/success");?>
        <?else :?>
            <?$cTemplate->Render("login/default",$params['form_login']);?>
        <?endif;?>
    </div>
   </header>
   <div class="clearfix"></div>
   <a href="<?=WEBSITE?>">Главная</a>
       <div id="g_container" style="margin: 50px 0 0 0;" class="center_forum">
           <?$cTemplate->Render($params['center_template'],$params['center_data']);?>
       </div>
</div>
<?$i=1;
foreach ($params['dialog'] as $v) :?>
    <?if(!empty($v['options'])) :?>
        <div id="dialog<?=$i?>"><?=$v['data']?></div>
        <script type="text/javascript">$(function() {DialogOpen(<?=$v['options']?>);});</script>

    <?endif;?>
    <?$i++;endforeach;?>

<script>
    $(document).ready(function(){
        $('.forum td').hover(
            function(){ $(this).addClass('rowhighlight') },
            function(){ $(this).removeClass('rowhighlight') }
        );
    });
    <?=$params['js']?>
</script>

</body>
</html>
