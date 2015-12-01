<?
//утф8	
class  Db extends mysqli 
{
	var $connected = FALSE;
	var $log_errors,$log_query,$log_big_query;
	var $logging = TRUE;
    public function __construct($logging = TRUE)
    {
    	$this->logging = $logging;
    	if($this->logging)
    	{
	    	$this->log_errors    = new LogFile('db_errors');
	    	$this->log_query     = new LogFile('db_query');
	    	$this->log_big_query = new LogFile('db_big_query');
    	}    	
        parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
    	if ($this->connect_error) 
		{
			ob_clean();
			$error = "MySQL.Error(".$this->connect_errno."): ".$this->connect_error;
    		if($this->logging)
				$this->log_errors->Add($error);
			$this->Close();
			die($error);		
		}
		
		$this->connected = TRUE;		
        $this->Query("SET NAMES utf8");

		
	}
	
	function Query($query)
	{
		try 
		{
		  if($this->logging)
		  {
			$start_time = microtime(true);
		    $result = parent::query($query);
		    $res_time = microtime(true) - $start_time;

		    LogFile::SqlQuery($this, $query, $res_time);
		  }
	      else 
	    	$result = parent::query($query);
	      
		  if (!$result)
		    throw new Exception (sprintf ("MySQL.Error(%d): %s", $this->errno, $this->error));
		  
		  return $result;
		} 
		catch (Exception $e) 
		{
			$text=
				$e->getMessage()
//				."\nFile: ".$e->getFile()
//				."\nLine: ".$e->getLine()
//				."\nCode: ".$e->getCode()
				;

			$this->log_errors->SqlError($text);

            if(DEBUG)
            {
                printr(my_debug_backtrace());
                die("<div id=\"AJAX_ERRORS\">" . $text . "</div>");
            }
            else
                die("<div id=\"AJAX_ERRORS\">MySQL error! Sorry!</div>");
		}
	}

	function FreeResult($res) 
	{
		mysqli_free_result($res);
	}
	function GetRow($q,$slash=0)
	{     // mysqli_FETCH_ASSOC
		$res = $this->Query($q);
		$rec = mysqli_fetch_assoc($res);
		
		if($rec && $slash ==0)
			$rec=array_map('stripslashes',$rec);		
		$this->FreeResult($res);
		return $rec;
	} 
	function GetDataStr($q,$def = 0)
	{
		
		$res=$this->Query($q);
		$rec=mysqli_fetch_row($res);
		$this->FreeResult($res);
		return (isset($rec[0]) ? $rec[0] : $def);
	}
    function GetRows($q,$slash=0,$key = NULL,$group = false)
    {     // mysqli_FETCH_ASSOC
        $res=$this->Query($q);
        $arr=array();

        while($rec=mysqli_fetch_assoc($res))
        {
            if(count($rec)>1 && $slash == 0)
                $rec=array_map('stripslashes',$rec);

            if ($key)
            {
                if(isset($arr[$rec[$key]]) || $group)
                {
                    if(isset($arr[$rec[$key]][$key]))
                        $arr[$rec[$key]] = array($arr[$rec[$key]]);
                    $arr[$rec[$key]][] = $rec;
                }
                else
                    $arr[$rec[$key]]=$rec;
            }
            else
                $arr[]=$rec;
        }
        $this->FreeResult($res);
        return $arr;
    }
	function GetAllRows($table,$piece=0)
	{
		$cnt = $this->GetDataStr("SELECT count(*) FROM ".DB_PREFIX.$table);
		if ($piece == 0 || $cnt < $piece )			
			return $this->GetRows("SELECT * FROM ".DB_PREFIX.$table);
		$data = array();
		$b_i = 0;
		$fl = false;
		while ($b_i <= $cnt)
		{
			if (($cnt-($b_i+$piece)) <= 0)
			{
				$piece =  ($cnt-$b_i);
				$fl = true;				
			}
			$tmp = $this->GetRows("SELECT * FROM ".DB_PREFIX.$table." LIMIT ".$b_i.",".$piece);
			$data = array_merge($data,$tmp);
			if ($fl)
				break;
			$b_i += $piece;
		}
		return $data;
	}
	function IsRow($q) 
	{
		$rec = $this->GetRow($q);
		if(count($rec)>1)
			return true;
		return false;
	}
	function Insert($table,$data,$f_escape="mysqli_real_escape_string") {
		$q="INSERT INTO ".DB_PREFIX.$table." SET ";
		$cnt=count($data);
		$i=1;
		foreach($data as $key => $value)
		{
			$q .= "`".$key."`='".$this->EscapeVal($value, $f_escape)."'".($cnt != $i ? ", " : "");
			$i++;
		}
		
		$q = $this->Query($q);
		if( $q )
			return $this->insert_id;
		else
			return false;
	}
	function MultiInsert($table,$data,$f_escape="mysqli_real_escape_string") {
		$q="INSERT INTO ".DB_PREFIX.$table." VALUES ";
		$cnt=count($data);
		$i=1;
		foreach ($data as $arr)
		{
			$j = 1;
			$tmp = "";	
			$cnt2  = count($arr);					
			foreach ($arr as $value) 
			{
				$tmp .= ($value != 'NULL' ? "'" : "").
						$this->EscapeVal($value, $f_escape).
						($value != 'NULL' ? "'" : "").
				        ($cnt2 != $j ? ", " : "");
				$j++;
			}
			if ($tmp != "")
			{
				$q .= "(".$tmp.")".($cnt != $i ? ", " : "");
			}
			$i++;
		}
		
		$q = $this->Query($q);
		if( $q )
			return $this->insert_id;
		else
			return false;
	}

	function Update($table,$where,$data,$f_escape="mysqli_real_escape_string") {
		$q="UPDATE ".DB_PREFIX.$table." SET ";
		$cnt=count($data);
		$i=1;
		foreach($data as $key => $value)
		{
			if(substr($value,0,1) == '`')
				$v = $value;
			else
				$v = "'".$this->EscapeVal($value, $f_escape)."'";
			$q .= "`".$key."`=".$v.($cnt != $i ? ", " : "");
			$i++;
		}
		$q .= " WHERE ".$where;
		return $this->Query($q);
	}
    function GetCountRows($table, $where=NULL)
    {
       return $this->GetDataStr("SELECT count(*) FROM " .DB_PREFIX. $table . ($where ? " WHERE ".$where : ""));
    }
	function GetAssoc($q,$group = false)
	{
		$res=$this->Query($q);
		$arr = array();
		while($rec=mysqli_fetch_row($res)) {			
			if(isset($rec[0]) && isset($rec[1]))
            {
                if($group)
                {
                    if(!isset($arr[$rec[0]]))
                        $arr[$rec[0]] = array();
                    $arr[$rec[0]][] = $rec[1];
                }
                else
                    $arr[$rec[0]] = $rec[1];
            }
		}
		unset($rec);
		$this->FreeResult($res);
		return $arr;
	} 
	function GetRowsArray($q){
		$res=$this->Query($q);
		$arr = array();
		while($rec=mysqli_fetch_row($res)) {			
			if(isset($rec[0]) && strlen($rec[0]) >  0)
				$arr[] = $rec[0];
		}
		$this->FreeResult($res);
		if(count($arr)> 0)
			return $arr;
	}
	function EscapeVal($value,$f_escape = "mysqli_real_escape_string")
	{
		if(!empty($f_escape) && function_exists($f_escape))
		{
			if ($f_escape == "mysqli_real_escape_string")
				return  parent::real_escape_string($value);
			else 
				return $f_escape($value);
		}
		else
			return $value;
	}
	function Close()
	{
		if ($this->connected)			
			parent::close();

		if($this->logging)
		{
			$this->log_errors->Close();	
			$this->log_query->Close();
			$this->log_big_query->Close();
			$this->log_images->Close();
		}		
	} 
}
?>