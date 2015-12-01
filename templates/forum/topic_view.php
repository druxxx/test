<?php
$cTemplate->Register("tmpl_form_open");
$cTemplate->Register("tmpl_form_hidden");
$cTemplate->Register("tmpl_form_text");
$cTemplate->Register("tmpl_form_textarea");
$cTemplate->Register("tmpl_form_submit");

?>
<div class="topic">
    <div class="title"><?=$params['topic']['title']?></div>
    <?foreach($params['posts'] as $post) :?>
        <div class="post">
            <div class="user">
                Пользователь: <?=escape_html(gv('login',$post['user_data']))?>
                <?if($post['is_author']) :?> | <a href="#" onclick="return Ajax.EditPost(<?=$post['id']?>)">Редактировать</a> | <a href="<?=WEBSITE?>/posts/delete.html?id=<?=$post['id']?>" onclick="return ConfirmDeletePost(<?=$post['f_main']?>) ">Удалить</a><?endif;?>
            </div>
        <div class="content" id="m_post<?=$post['id']?>"><?=nl2br(escape_html($post['message']))?></div>
        </div>

    <?endforeach;?>
<div style="<?=(IS_LOGIN ? '' : 'display:none')?>" class="login_area">
    <div class="title">Добавить пост</div>
    <div  class="row2" style="width: 100%">
        <div class="form_default form_register clearfix">
            <?$cTemplate->Render("tmpl_form_open",$params["form"]["form"],array("action" => WEBSITE."/posts/add.html"));?>
            <?$cTemplate->Render("tmpl_form_hidden",$params["form"]["h_topic"])?>
            <?$cTemplate->Render("tmpl_form_textarea",$params["form"]["t_post"])?>

            <div style="padding-left: 175px;">
                <?$cTemplate->Render("tmpl_form_submit",$params["form"]["b_submit"])?>
            </div>


            </form>
        </div>
    </div>
</div>
<div style="color: red; <?=(IS_LOGIN ? 'display:none;' : '' )?>" class="login_error"><br>Чтоб добавлять посты - пожалуйста, зайдите в свой аккаунт </div>
</div>

