<?php
class BaseModel
{
    var $cDb;
    var $table;
    var $limit = NULL;
    var $where = NULL;
    var $is_admin = FALSE;
    var $user_id = 0;

    protected static $instances;

    function __construct ()
    {
        global $cDb, $cAuth;
        $this->cDb         = $cDb;
        $this->is_admin    = $cAuth->is_admin;
        $this->user_id     = (int)gv('id',$cAuth->user,0);
    }
    final public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }
    function Limit($offer,$cnt)
    {
        $this->limit = $offer.','.$cnt;
        return $this;
    }
    function Where($where)
    {
        $this->where = $where;
        return $this;
    }
    function Get($id = NULL, $sort = NULL)
    {
        if(is_array($id))
            $data = $this->cDb->GetRows("SELECT * FROM ".$this->table." WHERE id IN (".implode(',',$id).")",0,'id');
        elseif((int)$id > 0)
            $data = $this->cDb->GetRow("SELECT * FROM ".$this->table." WHERE id = ".(int)$id);
        else
            $data = $this->cDb->GetRows("SELECT * FROM ".$this->table
                .($this->where ? ' WHERE '.$this->where : '')

                .($sort ? ' ORDER BY '.$sort : '')
                .($this->limit ? ' LIMIT '.$this->limit : ''));

        $this->limit = NULL;
        $this->where = NULL;
        return $data;
    }
    function GetByUrl($url)
    {
       return $this->cDb->GetRow("SELECT * FROM ".$this->table." WHERE url = '".$this->cDb->EscapeVal($url)."'");
    }
    function Delete($id)
    {
        return $this->cDb->Query("DELETE FROM ".$this->table." WHERE id = ".(int)$id);
    }
    function Update($id,$data,$v = NULL)
    {
        if(!is_array($data))
            $data = array($data => $v);
        $this->cDb->Update($this->table,"id =".$id,$data+array("tstamp_updated" => time()));
    }
}
?>