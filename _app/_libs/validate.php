<?
// --------------------------------------------------------------------------
//                        validate.php
//                       -----------------------
// begin:     13.07.2011 
// contacts:  druxxx@gmail.com
// copyright: (C) by DruX
// Класс проверки форм

class  Validate
{
    static function ValidateRegister(&$form)
    {
        $fl = true;

        if(!self::IsSubmit())
            return false;

        if(!self::CheckField('login'))
        {
            $form['t_login']['class'] .= 'error_input';
            $form['t_login']['errors'][1]['visibility'] = true;
            $fl = false;
        }
        elseif(!self::CheckField('login','login'))
        {
            $form['t_login']['class'] .= 'error_input';
            $form['t_login']['errors'][2]['visibility'] = true;
            $fl = false;
        }
        elseif(self::IssetLogin($_POST['login']))
        {
            $form['t_login']['class'] .= 'error_input';
            $form['t_login']['errors'][3]['visibility'] = true;
            $fl = false;
        }

        if(!self::CheckField('password') || !self::CheckField('repassword'))
        {
            $form['t_password']['class'] .= 'error_input';
            $form['t_password']['errors'][1]['visibility'] = true;
            $fl = false;
        }
        elseif($_POST['password'] != $_POST['repassword'])
        {
            $form['t_repassword']['class'] .= ' error_input';
            $form['t_repassword']['errors'][1]['visibility'] = true;
            $fl = false;
        }

        if(!self::CheckField('email'))
        {
            $form['t_email']['class'] .= ' error_input';
            $form['t_email']['errors'][1]['visibility'] = true;
            $fl = false;
        }
        elseif(!self::CheckField('email','email'))
        {
            $form['t_email']['class'] .= ' error_input';
            $form['t_email']['errors'][2]['visibility'] = true;
            $fl = false;
        }
        elseif(self::IssetEmail($_POST['email']))
        {
            $form['t_email']['class'] .= ' error_input';
            $form['t_email']['errors'][3]['visibility'] = true;
            $fl = false;
        }

        if(!isset($_POST['f_rules']))
        {
            $form['cb_rules']['class'] .= ' error_input';
            $fl = false;
        }
        if(!isset($_POST['name']))
        {
            $fl = false;
        }


        return $fl;

    }
    static function ValidateFormRecoveryPassword(&$form,$step)
    {
        $fl = true;
        if(!self::IsSubmit())
            return false;

        if($step == 3)
        {
            if(!self::CheckField('password') || !self::CheckField('repassword'))
            {
                $form['t_repassword']['class'] .= 'error_input';
                $form['t_repassword']['errors'][1]['visibility'] = true;
                $fl = false;
            }
            elseif($_POST['password'] != $_POST['repassword'])
            {
                $form['t_repassword']['class'] .= ' error_input';
                $form['t_repassword']['errors'][2]['visibility'] = true;
                $fl = false;
            }

            return $fl;
        }

        if(!self::CheckField('email'))
        {
            $form['t_email']['class'] .= ' error_input';
            $form['t_email']['errors'][1]['visibility'] = true;
            $fl = false;
        }
        elseif(self::CheckField('email','email'))
        {
            if(!self::IssetEmail(gv('email',$_POST)))
            {
                $form['t_email']['class'] .= ' error_input';
                $form['t_email']['errors'][3]['visibility'] = TRUE;
                $fl = FALSE;
            }
        }
        elseif(!self::IssetLogin(gv('email',$_POST)))
        {
            $form['t_email']['class'] .= ' error_input';
            $form['t_email']['errors'][4]['visibility'] = true;
            $fl = false;
        }

        return $fl;
    }
    static function ValidateAddTopic(&$form)
    {
        $fl = true;
        if(!self::IsSubmit())
            return false;
        if(!self::CheckField('title'))
        {
            $form['t_title']['class'] .= ' error_input';
            $form['t_title']['errors'][1]['visibility'] = true;
            $fl = false;
        }
        if(!self::CheckField('post'))
        {
            $form['t_post']['class'] .= ' error_input';
            $form['t_post']['errors'][1]['visibility'] = true;
            $fl = false;
        }


        return $fl;
    }
 ///////////////////////////////////////////////////////////////////////////////////

    static function IssetEmail($email) {
        global $cDb;
        $rec = $cDb->getRow("SELECT * FROM accounts WHERE email = '".$cDb->EscapeVal($email)."'");
        if(count($rec)>1)
            return true;
        return false;
    }
    static function IssetLogin($login) {
        global $cDb;
        $rec = $cDb->getRow("SELECT * FROM accounts WHERE login = '".$cDb->EscapeVal($login)."'");
        if(count($rec)>1)
            return true;
        return false;
    }
    static function ValidateLogin($login) {
        return preg_match('/^[A-Za-z][A-Za-z0-9_]{2,15}$/i',$login);
    }
    static function ValidatePhone($phone) {
        return preg_match('/^[0-9]{10,15}$/',$phone) ||
               preg_match('/^\+[0-9]{10,15}$/',$phone);
    }
    static function ValidateEmail($email) {
        return preg_match('/^[-_A-Za-z0-9][A-Za-z0-9\._-]*[A-Za-z0-9_]*@([A-Za-z0-9]+([A-Za-z0-9-]*[A-Za-z0-9]+)*\.)+[A-Za-z]+$/',$email);
    }
    static function ValidateCaptcha($str)
    {
        return true;
        if (!isset($_SESSION['sec_code_session']))
            return false;
        if (strlen($_SESSION['sec_code_session']) > 0 &&
            $_SESSION['sec_code_session']   == $str)
            return true;
        return false;
    }
    static function ValidateDate($str)
    {
        if(preg_match('/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})$/',$str,$rec))
            return $rec;
        return FALSE;
    }
    static function ValidateUrl($str)
    {
        return (!filter_var($str, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false);
    }
    static function CheckField($var,$type = 'str') //POST
    {
        if(!empty($_POST[$var]))
        {
            if($type == 'str')
                return true;
            elseif($type == 'int' && isInt($_POST[$var]))
                return true;
            elseif($type == 'double' && isDouble($_POST[$var]))
                return true;
            elseif($type == 'captcha' && self::ValidateCaptcha($_POST[$var]))
                return true;
            elseif($type == 'email' && self::ValidateEmail($_POST[$var]))
                return true;
            elseif($type == 'date' && self::ValidateDate($_POST[$var]))
                return true;
            elseif($type == 'login' && self::ValidateLogin($_POST[$var]))
                return true;
        }
        return false;
    }
    static function IsSubmit()
    {
        if(isset($_POST['btn_submit_form']) )
            return true;

        return false;
    }


}