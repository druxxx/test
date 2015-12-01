<?php
include_once(TEMPLATES_DIR . 'helpers/utags.php');
include_once(TEMPLATES_DIR . 'helpers/form.php');

function tmpl_login_success($params)
{
    global $gUser, $cTemplate;
    $cTemplate->Register("tmpl_utags_a");
?>
    <div style="width:100%; text-align: center;">Привет <?=escape_html($gUser['firstname'])?>!</div>
    <?$cTemplate->Render("tmpl_utags_a",array(
        "value" => "Выход",
        "href" => "/users/logout.html",
      "onclick" => "return Ajax.Load(this,'login_container');"));?>

<?
}
function tmpl_login_default($params)
{
    global $cTemplate;
    $cTemplate->Register("tmpl_utags_a");
    $cTemplate->Register("tmpl_form_text");
    $cTemplate->Register("tmpl_form_password");
    $cTemplate->Register("tmpl_form_checkbox");
    $cTemplate->Register("tmpl_form_submit");
    $cTemplate->Register("tmpl_form_open");
    $cTemplate->Register("tmpl_form_close");
?>
  <?$cTemplate->Render("tmpl_form_open",$params['form']);?>
    <div class="form_default form_login clearfix">
        <?$cTemplate->Render("tmpl_form_text",$params['tLogin'])?>
        <?$cTemplate->Render("tmpl_form_password",$params['tPassword'])?>
        <div class="left"><?$cTemplate->Render("tmpl_form_checkbox",$params['cbSaveMe']);?></div>
        <div class="right"><?$cTemplate->Render("tmpl_form_submit",$params['bSubmit']);?></div>
        <div class="clearfix"></div>
        <div class="left"> <?$cTemplate->Render("tmpl_utags_a",array("href"  => "/users/recovery_password.html","onclick" => "return Ajax.Load(this,'dialogrecovery_password');", "value" => "Забыл пароль"));?></div>
        <div class="right"><?$cTemplate->Render("tmpl_utags_a",array("href"  => "/users/register.html",  "onclick" => "return Ajax.Load(this,'dialogRegister');",  "value" => "Регистрация"));?></div>

    </div>
  <?$cTemplate->Render("tmpl_form_close");?>

<?
}
?>