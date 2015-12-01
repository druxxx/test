<?
require GLOBAL_DIR.'/letters_data.php';
class Controller_Users  extends BaseController
{
    function Login_Action ()
    {
        $ref = getval('ref','g','string');
        $this->ajax_vars['AJAX_JSINFO']['ref'] = $ref;

        if($this->ajax)
        {
            $this->cAuth->Auth_();
            $this->ajax_vars['AJAX_JSINFO']['login'] = ($this->cAuth->is_login ? 1 : 0);
            if($this->cAuth->login_error)
                $this->ajax_vars['AJAX_ERRORS'] = $this->cAuth->login_error;
        }
        elseif ($this->cAuth->is_login)
        {
            redirect('/');
        }

        parent::Render();
    }
    function Logout_Action ()
    {
        $this->cAuth->Logout ();

        if($this->ajax) {
            $this->cTemplate->Register("tmpl_login_default");
            $this->cTemplate->Render("login/tmpl_login_default",$this->data['form_login']);
            return $this->ajax_vars;
        }
        else
        {
            redirect('/');
        }
        parent::Render();
    }
    function Register_Action ()
    {
        global $letters_activation;
        $data = $this->model->GetFormRegistration();
        if($data['validate'])
        {
            $params = $this->model->Add();

            send_email(EMAIL_SUPPORT,
                $params['email'],
                $letters_activation["subject"],
                str_replace(
                    array("[name]","[login]","[password]","[activation_link]"),
                    array(!empty($params['firstname']) ? $params['firstname'] : $params['login'],$params['login'],$_POST['password'],GLOBAL_URL . "/users/activation.html?id=" . $params['id'] . "&hash=" . $params['activation_str']),
                    $letters_activation["text"]
                )
            );

            $this->cAuth->Auth_();
            $this->ajax_vars['AJAX_JSINFO']['success'] = 1;
            $this->ajax_vars['AJAX_INFO'] = "Спасибо за регистрацию на нашем форуме";
        }
        else
            $this->CreateDialog('register','dialogs/register/',$data);

        parent::Render();
    }

///////////////////////////////////////////////////////////////////////////////////////////
    function RecoveryPassword_Action()
    {
        global $letters_recovery_password;
        $step = 1;
        $email = getval('email','p');
        $is_subm = getval('subm','p','bool') ? true : false;
        $id = (int)getval('id');
        $hash = getval('hash');

        $params  = array();
        $params['form'] = $this->model->GetFormRecoveryPassword();

        $form = &$params['form'];
        if($id > 0 && strlen($hash) == 32 && !$email)
        {
            $user = $this->model->Get($id);
            if ($user && $user['recovery_str'] == $hash)
            {
                $step = 3;
                if(Validate::ValidateFormRecoveryPassword($form,3))
                {
                    $step = 4;
                    $this->model->ChangePassword($user['id'],$_POST['repassword']);
                }
            }
            else
                $this->data['js'] = 'sAlert("Недействительная ссылка для восстановления пароля.<br><br>Пожалуйста, повторите процедуру восстановления.");';

        }
        elseif(Validate::ValidateFormRecoveryPassword($form,1))
        {
            if(Validate::ValidateEmail($email))
                $user = $this->model->GetByEmail($email);
            else
                $user = $this->model->GetByLogin($email);
            if($user)
            {
                $h = md5($email.time());
                $this->model->RecoveryPassword($user['id'], $h);
                $step = 2;

                send_email(EMAIL_SUPPORT,
                    $email,
                    $letters_recovery_password['subject'],
                    str_replace(
                        array("[name]","[url]"),
                        array(!empty($user['firstname']) ? $user['firstname'] : $user['login'],GLOBAL_URL . "/users/recovery_password.html?id=" . $user['id'] . "&hash=" . $h),
                        $letters_recovery_password['text']
                    )
                );
            }
        }
        $this->cTemplate->Register("tmpl_dialogs_recovery_password_step".$step);
        $this->CreateDialog('recovery_password','dialogs/recovery_password/step'.$step,$params);
        parent::Render();
    }
    function Activation_Action()
    {
        $hash = getval('hash');
        $id   = (int)getval('id');

        if($id <=0 || strlen($hash) != 32)
            parent::page_404();
        $user = $this->model->Get($id);

        if(!$user)
        {
            $this->data['js'] = 'sAlert("Пользователь не найден.");';
        }
        elseif($user['activation_str'] == '')
            $this->data['js'] = 'sAlert("Ваш email успешно активирован.");';
        else
        {
            if($user['activation_str'] == $hash)
            {
                $this->model->SuccessActivationEmail($user['id']);
                $this->cAuth->Auth_($user);
                $this->data['js'] = 'sAlert("Ваш email успешно активирован.");gObjSite.is_login = 1';
            }
            else
                $this->data['js'] = 'sAlert("Пользователь не найден.");';
        }

        include GLOBAL_DIR.'/_app/_controllers/main.php';
        Controller_Main::getInstance()->data['js'] = $this->data['js'];
        Controller_Main::getInstance()->Main_Action();
    }
}
?>