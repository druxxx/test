<?php
$cTemplate->Register("tmpl_form_open");
$cTemplate->Register("tmpl_form_text");
$cTemplate->Register("tmpl_form_checkbox");
$cTemplate->Register("tmpl_form_password");
$cTemplate->Register("tmpl_form_submit");

$cTemplate->Register("tmpl_form_before_field");
$cTemplate->Register("tmpl_form_after_field");

?>
<div class="form_default form_register clearfix">
    <?$cTemplate->Render("tmpl_form_open",$params["form"]);?>
    <?$cTemplate->Render("tmpl_form_text",$params["t_login"])?>
    <?$cTemplate->Render("tmpl_form_text",$params["t_email"])?>
    <?$cTemplate->Render("tmpl_form_password",$params["t_password"])?>
    <?$cTemplate->Render("tmpl_form_password",$params["t_repassword"])?>
    <?$cTemplate->Render("tmpl_form_text",$params["t_name"])?>

    <?$cTemplate->Render("tmpl_form_checkbox",$params["cb_rules"],array("class_fc" => "container_rules"))?>
    <div class="btn_right">
        <?$cTemplate->Render("tmpl_form_submit",$params["b_submit"])?>
    </div>


    </form>
</div>