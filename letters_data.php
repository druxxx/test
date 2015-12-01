<?php
$letters_activation = array(
    "subject" => 'Активация аккаунта',
    "text" => '<b>Уважаемый(-ая) [name] !</b>
<br /><br />
Вы зарегистрировались на сайте XXX,
<br /><br />
Ваши данные
<br /><br />
Логин: [login]<br />
Пароль: [password]
<br /><br />
Чтобы войти на сайт XXX, Вы должны активировать свой аккаунт, перейдя по следующей ссылке: <b><a href="[activation_link]">[activation_link]</a></b>
<br /><br />
Если Вы получили это письмо по ошибке, просто проигнорируйте и удалите его.
<br /><br />
<b>C уважением,<br />
Администрация сайта <a href="'.GLOBAL_URL.'">XXX</a></b>',
);
$letters_recovery_password = array(
    "subject" => "Тема",
    "text" => "Здравствуйте [name]<br><br>
Шаблон письма<br><br>
<a href='[url]'>Восстановить пароль</a> "
);
?>