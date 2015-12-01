<?php
$cTemplate->Register("tmpl_form_open");
$cTemplate->Register("tmpl_form_text");
$cTemplate->Register("tmpl_form_textarea");
$cTemplate->Register("tmpl_form_submit");

?>
<table class="forum">
    <tr>
        <th style="text-align: left;">Добавление темы</th>
    </tr>

    <tr><td  class="row2">
        <div class="form_default form_register clearfix">
            <?$cTemplate->Render("tmpl_form_open",$params["form"]["form"]);?>
            <?$cTemplate->Render("tmpl_form_text",$params["form"]["t_title"])?>
            <?$cTemplate->Render("tmpl_form_textarea",$params["form"]["t_post"])?>

            <div style="padding-left: 175px;">
                <?$cTemplate->Render("tmpl_form_submit",$params["form"]["b_submit"])?>
            </div>


            </form>
        </div>
    </td> </tr>
</table>