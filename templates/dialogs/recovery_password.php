<?
include_once(TEMPLATES_DIR.'helpers/utags.php');
include_once(TEMPLATES_DIR.'helpers/form.php');

function tmpl_dialogs_recovery_password_step1 ($params)
{
	global $cTemplate;
	$cTemplate->Register("tmpl_form_text");
	$cTemplate->Register("tmpl_form_submit");
	$cTemplate->Register("tmpl_form_open");
	$cTemplate->Register("tmpl_form_close");
?>
Для восстановления доступа к аккаунту, вам необходимо ввести логин или e-mail, которые были указаны при регистрации.
<div class="form_default clearfix">
  <?$cTemplate->Render("tmpl_form_open",$params["form"]["form"]);?>
    <?$cTemplate->Render("tmpl_form_text",$params["form"]["t_email"])?>
    <br>
    <div class="btn_right" style="padding-right: 58px;">
        <?$cTemplate->Render("tmpl_form_submit",$params["form"]["b_submit"])?>
    </div>
  <?$cTemplate->Render("tmpl_form_close");?>
</div>
<?
}
function tmpl_dialogs_recovery_password_step2 ($params)
{
    global $cTemplate;
?>
    <span style="color:green;">
        На ваш e-mail было выслано письмо с дальнейшими инструкциями по восстановлению пароля
    </span>
<?
}
function tmpl_dialogs_recovery_password_step3 ($params)
{
    global $cTemplate;
    $cTemplate->Register("tmpl_form_password");
    $cTemplate->Register("tmpl_form_submit");
    $cTemplate->Register("tmpl_form_open");
    $cTemplate->Register("tmpl_form_close");
?>
    <div class="form_default clearfix">
        <?$cTemplate->Render("tmpl_form_open",$params["form"]["form"]);?>
        <?$cTemplate->Render("tmpl_form_password",$params["form"]["t_password"])?>
        <?$cTemplate->Render("tmpl_form_password",$params["form"]["t_repassword"])?>
        <div class="btn_right" style="padding-right: 58px;">
            <?$cTemplate->Render("tmpl_form_submit",$params["form"]["b_submit"],array(
                'value' => 'Сохранить'))?>
        </div>
        <?$cTemplate->Render("tmpl_form_close");?>
    </div>
<?
}
function tmpl_dialogs_recovery_password_step4 ($params)
{
    global $cTemplate;
?>
    <span style="color:green;">
        Ваш пароль изменен
    </span>
<?
}

?>