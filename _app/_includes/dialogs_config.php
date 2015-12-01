<?php
//params http://api.jqueryui.com/dialog/ (options);
$dialogs_config = array(
    "login" => array(
        "title"	 => "Вход",
        "width"  => 458,
        "height" => 150,
        "dialogClass" => "modal_login",
        "key" => "login",
        "show" => array("effect" => "fade", "duration" => 100),
        "hide" => array("effect" => "fade", "duration" => 100),
    ),
    "register" => array(
        "title"	 => "Регистрация",
        "width"  => 458,
        "height" => 275,
        "dialogClass" => "modal_register",
        "key" => "register",
        "show" => array("effect" => "fade", "duration" => 100),
        "hide" => array("effect" => "fade", "duration" => 100),
    ),
    "recovery_password" => array(
        "title"	 => "Восстановление пароля",
        "width"  => 361,
        "height" => "auto",
        "dialogClass" => "modal_recovery_password",
        "key" => "recovery_password",
        "show" => array("effect" => "fade", "duration" => 100),
        "hide" => array("effect" => "fade", "duration" => 100),
    ),

);
?>