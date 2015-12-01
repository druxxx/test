<?
// --------------------------------------------------------------------------
//                        users.php
//                       -----------------------
// begin:     03.08.2011 
// contacts:  druxxx@gmail.com
// copyright: (C) by DruX
include_once GLOBAL_DIR.'/_app/_models/posts.php';
class Model_Topics extends BaseModel
{
    var $table = 'topics';
    function GetForm()
    {
        return array(
            "form"       => array("method" => "POST", "onsubmit" => "return Validate.Init(this)"),
            "t_title"    => array("name" => "title", "label" => "Название темы", "style" => "width:600px", "class" => "", "errors" => array(
                1 => array('id' => 'regErr_EMAIL_ERR1','value' => 'Введите название'),
            )),
            "t_post"   => array("name" => "post", "label" => "Текст", "class" => "", "style" => "width:600px; height: 300px", "errors" => array(
                1 => array('value' => 'Введите текст'),
            )),
            "b_submit" => array("name" => "btn_submit_form", "value" => 'Добавить'),
        );
    }

    function Add($id = NULL, $fields_list = NULL,$ignore_list = NULL)
    {
        $params = array(
            "user_id" => $this->user_id,
            "title" => $_POST['title'],
            "tstamp_added" => time(),
            "tstamp_updated" => time(),
        );
        $params['id'] = $topic_id = $this->cDb->Insert($this->table,$params);

        Model_Posts::getInstance()->Add($topic_id,1);

        return $params;
    }

}
?>