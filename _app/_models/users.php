<?
// --------------------------------------------------------------------------
//                        users.php
//                       -----------------------
// begin:     03.08.2011 
// contacts:  druxxx@gmail.com
// copyright: (C) by DruX
class Model_Users extends BaseModel
{
    var $table = 'accounts';
    function Add()
    {
        $activation_str = md5($_POST['login'].$_POST['email'].time());

        $params = array(
            "login" => $_POST['login'],
            "password" => Auth::GetPassword($_POST['password']),
            "email" => $_POST['email'],
            "firstname" => $_POST['name'],
            "ip_added" => ip2int(),
            "tstamp_added" => time(),
            "activation_str" => $activation_str,
        );
        $params['id'] = $this->cDb->Insert($this->table,$params);
        return $params;
    }
    function GetByLogin($login)
    {
        return $this->cDb->GetRow("SELECT * FROM accounts WHERE login = '".$this->cDb->EscapeVal($login)."'");
    }
    function GetByEmail($email)
    {
        return $this->cDb->GetRow("SELECT * FROM accounts WHERE email = '".$this->cDb->EscapeVal($email)."'");
    }
    function SuccessActivationEmail($id)
    {
        $this->Update($id,"activation_str","");
    }
    function RecoveryPassword ($id, $h)
    {
        $this->Update($id,"recovery_str",$h);
    }
    function ChangePassword ($id, $pass)
    {
        $this->Update($id,array(
            "recovery_str"    => "",
            "password"        => Auth::GetPassword($pass),
        ));
    }
    function GetListLogins($id = NULL)
    {
        if(is_array($id) && !empty($id))
            return $this->cDb->GetAssoc("SELECT id,login FROM accounts WHERE id IN (".implode(',',$id).")");

        else
            return $this->cDb->GetAssoc("SELECT id,login FROM accounts");
    }
    function GetFormRegistration()
    {
        $params = array(
            "form" => array("method" => "POST", "onsubmit" => "return Ajax.Register(this);"),
            "t_login"    => array("name" => "login", "label" => "Логин", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_LOGIN_ERR1','value' => 'Введите логин'),
                2 => array('id' => 'regErr_LOGIN_ERR2','value' => 'Некорректный логин'),
                3 => array('id' => 'regErr_LOGIN_ERR3','value' => 'Логин занят'),
            )),
            "t_password" => array("name" => "password", "label" => "Пароль", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_PASS_ERR1','value' => 'Укажите пароль'),
            )),
            "t_repassword" => array("name" => "repassword", "label" => "Повтор пароля", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_PASS_ERR2','value' => 'Пароли не совпадают',"validate" => "ComparePassword")
            )),
            "t_email" => array("name" => "email", "label" => "E-mail", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_EMAIL_ERR1','value' => 'Введите email'),
                2 => array('id' => 'regErr_EMAIL_ERR2','value' => 'Некорректный email', "validate" => "Email"),
                3 => array('id' => 'regErr_EMAIL_ERR3','value' => 'данный email уже зарегистрирован'),
            )),
            "cb_rules"  => array("name" => "f_rules", "label" => "Я согласен с правилами", "class" => "", "errors" => array(
            )),
            "t_name" => array("name" => "name", "label" => "Ваше имя", "class" => ""),

            "b_submit"   => array("name" => "btn_submit_form", "only_field" => TRUE, "value" => "Далее"),
        );
        $params['validate'] = Validate::ValidateRegister($params);
        return $params;
    }
    function GetFormRecoveryPassword()
    {
        return array(
            "form"        => array("method" => "POST", "onsubmit" => "return Ajax.Load({},'dialogrecovery_password',this);"),
            "t_email"    => array("name" => "email", "label" => "E-Mail", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_EMAIL_ERR1','value' => 'Укажите email'),
                2 => array('id' => 'regErr_EMAIL_ERR2','value' => 'Некорректный email',"validate" => "Email"),
                3 => array('id' => 'regErr_EMAIL_ERR3','value' => 'Пользователя с данным email не существует'),
                4 => array('id' => 'regErr_EMAIL_ERR4','value' => 'Пользователя с данным логином не существует'),
            )),
            "t_password"   => array("name" => "password", "label" => "Пароль", "class" => "", "errors" => array(
                1 => array('value' => 'Укажите пароль'),

            )),
            "t_repassword" => array("name" => "repassword", "label" => "Повторите пароль", "class" => "", "errors" => array(
                1 => array('value' => 'Укажите пароль'),
                2 => array('value' => 'Пароли не совпадают',"validate" => "ComparePassword"))
            ),
            "b_submit" => array("name" => "btn_submit_form", "value" => 'Восстановить'),
        );
    }

}
?>