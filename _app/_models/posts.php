<?
// --------------------------------------------------------------------------
//                        users.php
//                       -----------------------
// begin:     03.08.2011 
// contacts:  druxxx@gmail.com
// copyright: (C) by DruX
class Model_Posts extends BaseModel
{
    var $table = "posts";
    function GetForm()
    {
        return array(
            "form"       => array("method" => "POST", "onsubmit" => "return Validate.Init(this)"),
            "h_topic"   => array("name" => "topic_id", "value" => (int)gv('id',$_GET)),
            "t_post"   => array("name" => "post", "label" => "Текст", "class" => "", "style" => "width:600px; height: 300px", "errors" => array(
                1 => array('value' => 'Введите текст'),
            )),
            "b_submit" => array("name" => "btn_submit_form", "value" => 'Добавить'),
        );
    }
    function Add($topic_id,$f_main = 0)
    {
        $params = array(
            "topic_id" => $topic_id,
            "user_id" => $this->user_id,
            "message" => $_POST['post'],
            "tstamp_added" => time(),
            "tstamp_updated" => time(),
            "f_main" => $f_main,
        );
        $params['id'] = $topic_id = $this->cDb->Insert($this->table,$params);

        return $params;
    }
    function GetByTopic($topic_id)
    {
        return $this->cDb->GetRows("SELECT * FROM ".$this->table." WHERE topic_id = ".$topic_id);
    }
    function DeleteByTopic($topic_id)
    {
        return $this->cDb->Query("DELETE FROM ".$this->table." WHERE topic_id = ".$topic_id);
    }
    function GetCntPosts()
    {
        return $this->cDb->GetAssoc("SELECT topic_id, count(*) FROM posts GROUP BY topic_id");
    }


}
?>