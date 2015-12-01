<?
//Утф8
class Auth
{
	var $auth           = false;
	var $is_login       = false;
	var $is_admin       = false;
	var $user           = NULL;
	var $login_error    = NULL;
	function __construct()
	{
		global $cDb;
		$this->cDb = $cDb;

		$this->params = array();

		$this->Auth_();



		if($this->auth)
			return true;
		return false;
	}
	function Auth_($user = NULL) {
		$this->log_auth = new LogFile('auth');
		$pAuth = false;
		if($user) //auto login
		{
			$this->user = $user;
			$pAuth = $this->SaveSession($this->user["login"],$this->user['password'],TRUE,$this->user['id']);
		}
		elseif($this->CheckAuth()){
			$pAuth  = TRUE;

 		}
		elseif(isset($_POST['subm_login']) && !empty($_POST['login']) && isset($_POST['password'])) {
			$this->log_auth->Add("User ".$_SERVER['REMOTE_ADDR']." Login: ".$_POST['login'].". Try login");
				 
			$login = $_POST['login'];
			$pass = $this->GetPassword($_POST['password']);
			$alwaysLogin = TRUE;//isset($_POST['always_login']) ? true : false;

			if(Validate::ValidateEmail($login))
				$this->user = $this->cDb->GetRow("SELECT * FROM accounts WHERE email  = '".$this->cDb->EscapeVal($login)."'");
			else
				$this->user = $this->cDb->GetRow("SELECT * FROM accounts WHERE login = '".$this->cDb->EscapeVal($login)."'");

			if(  isset($this->user["login"]) &&
				isset($this->user["password"]) &&
				$this->user["password"] == $pass && !empty($this->user['activation_str']))
			{
				$this->login_error = "Пожалуйста, активируйте свой аккаунт";
			}
			elseif(  isset($this->user["login"]) &&
                 isset($this->user["password"]) &&
                 $this->user["password"] == $pass)
			{

				$this->log_auth->Add("User ".$_SERVER['REMOTE_ADDR']." Login: ".$_POST['login'].". Success login. Id_user: ".$this->user['id']);

				$this->cDb->Update('accounts','id = '.$this->user['id'],array(
					"last_login_tstamp" => time(),
					"last_login_ip" => ip2int()
				));
				$pAuth = $this->SaveSession($this->user["login"],$this->user['password'],$alwaysLogin,$this->user['id']);
			}
			else{
				$this->log_auth->Add("User ".$_SERVER['REMOTE_ADDR']." Login: ".$_POST['login'].". Failed login");
				$this->login_error = "Неверный логин или пароль";
			}
		}
		$this->log_auth->Close();
		if($pAuth)
		{
			$GLOBALS['gLogin']    = $this->is_login ? $this->user['login'] : false;
			$GLOBALS['gIs_login'] = $this->is_login;
			$GLOBALS['gUser']     = $this->user;
			$this->is_login = true;
			return $this->is_login;
		}

		return $this->is_login = false;

	}
	function SaveSession($login,$password,$alwaysLogin,$id) {
		$_SESSION['_auth'] = true;
		$_SESSION['_authId'] = $id;
		$_SESSION['_authLogin'] = $login;
		$_SESSION['_authIP'] = $_SERVER['REMOTE_ADDR'];
		if($alwaysLogin) {
              setcookie(COOKIES_PREF."userStr",$this->GetCookieStr($login,$password),time()+3600*24*31);
              setcookie(COOKIES_PREF."userId",$id,time()+3600*24*31);
		}
		return true;
	}
	function GetCookieStr($l,$p) {
		 return md5($l.$this->GetPassword($p));
	}	
	static function GetPassword($pass) {
			return md5($pass);
	}
	function CheckAuth() 
	{
		if(isset($_SESSION['_auth']) && !empty($_SESSION['_authIP'])  && !empty($_SESSION['_authLogin']) && !empty($_SESSION['_authId']) && preg_match('/^[0-9]+$/',$_SESSION['_authId']) && $_SESSION['_authIP'] == $_SERVER['REMOTE_ADDR']) {
                $this->user = $this->cDb->GetRow("SELECT *  FROM accounts WHERE id = ".(int)$_SESSION["_authId"]);
			if(!empty($this->user['login']) && $this->user['login'] == $_SESSION['_authLogin'])
			{
				return TRUE;
            }
		}
		if(!empty($_COOKIE[COOKIES_PREF."userStr"])) {

			$currCookiesStr = "";
			if((int)gv(COOKIES_PREF."userId",$_COOKIE) > 0 )
			{
                $this->user = $this->cDb->GetRow("SELECT * FROM accounts WHERE id = ".(int)$_COOKIE[COOKIES_PREF."userId"]);

				$l=isset($this->user['login']) ? $this->user['login'] : "";
				$p=isset($this->user['password']) ? $this->user['password'] : "";
				$currCookiesStr = $this->GetCookieStr($l,$p);
			}
			else
				return FALSE;
			
			if(gv(COOKIES_PREF."userStr",$_COOKIE) &&
		   	   gv(COOKIES_PREF."userStr",$_COOKIE) == $currCookiesStr) {
				$this->SaveSession($this->user['login'],$this->user['password'],0,$this->user['id']);
				return true;
			}
		}
		return false;			
	}	

	static function Logout() 
	{
		unset($_SESSION['_auth']);
		unset($_SESSION['_authId']);
		unset($_SESSION['_authLogin']);
        unset($_SESSION['_authIP']);
        unset($_COOKIE[COOKIES_PREF.'userStr']);
        unset($_COOKIE[COOKIES_PREF.'userId']);
        setcookie(COOKIES_PREF.'userStr',"",1,"*");
        setcookie(COOKIES_PREF.'userId',"",1,"*");

        header("Refresh:3");

//		header("Location: ".WEBSITE);
	}

    function GetParamsFormLogin()
    {
		$params = array(
			"form"      => array("method" => "POST","onsubmit" => "return Ajax.Login(this)", "name" => "form_login"),
			"tLogin"    => array("name" => "login", "label" => "Login/E-Mail:"),
			"tPassword" => array("name" => "password", "label" => "Пароль:"),
			"cbSaveMe"  => array("name" => "always_login", "label" => " - Запомнить"),
			"bSubmit"   => array("name" => "subm_login", "value" => "Вход"),
			"error"     => $this->login_error
		);

        return $params;
    }

}
?>