<?
class LogFile
{
	var $prefix = "";
	var $debug = FALSE;
	function LogFile($fname,$ftruncate = FALSE)
	{
		if(!$this->debug)
			return false;
	    if(is_dir(PATH_LOGS) &&
			$this->fp = fopen(PATH_LOGS.'/'.$fname, 'a'))
	    {
	    	if ($ftruncate)
	    		ftruncate($this->fp, 0); 
	    }
	    else
	    {
			if(!is_dir(PATH_LOGS))
				echo 'error: You must create dir: '.PATH_LOGS;
			else
		    	echo 'error: Can\'t open file: '.PATH_LOGS.'/'.$fname;
	    	DIE;
	    }
	    
	}
	function Add($mes)
	{
		if(!$this->debug)
			return false;
		$mes = trim($mes);
		if ($mes == "")
			return false;
		$ip    = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1 (console)'; 
		if($this->fp)
		    fwrite($this->fp, date('d/m/y/H:i:s')." [ ". $ip ." ] ".$this->prefix.$mes."\n");
		return true;
	}
	function Close()
  	{
		if(!$this->debug)
			return false;
	    fclose($this->fp);
	}

	function VarDump($var,$f = 0)
	{
		if ($f == 0 && !$a = ob_get_contents())
			$f = 1;
		if ($f == 1) 
			ob_start();
		else 
			ob_clean();
		
		var_dump($var);
		$this->Add(ob_get_contents());

		
		if ($f == 1) 
			ob_end_clean();
		else 
		{
			ob_clean();
			echo $a;
		}
	}
	function LogArray($var, $f =  0)
	{
		if ($f == 0 && !$a = ob_get_contents())
			$f = 1;
		if ($f == 1) 
			ob_start();
		else 
			ob_clean();
			
		print_r($var);
		$this->Add(ob_get_contents());

		
		if ($f == 1) 
			ob_end_clean();
		else 
		{
			ob_clean();
			echo $a;
		}
	}
////////////////////////////////////////
	function SqlError($error)
	{
		$t = ob_get_contents();
	
		ob_clean();
		ob_start();
		echo 'POST:'; print_r($_POST);
		echo 'GET:';  print_r($_GET);
		$deb = my_debug_backtrace();
		echo 'BACKTRACE:'; print_r($deb);
	
		$post=ob_get_contents();
		ob_clean();
	
		$this->Add($error);
		$this->Add($post);
	
		return $error;
	}	
	static function SqlQuery($cDb,$query,$res_time) 
	{
		$rqu = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
	   	$cDb->log_query->Add($rqu);
	   	$cDb->log_query->Add($query);
	   	$cDb->log_query->Add($res_time);		  
		if($res_time >= 1) 
		{
			$cDb->log_query->Add($rqu);
			$cDb->log_big_query->Add($query);
			$cDb->log_big_query->Add($res_time);		  
		}
	}
}	
?>