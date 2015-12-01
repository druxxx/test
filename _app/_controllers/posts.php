<?

class Controller_Posts extends BaseController
{
    function Save_Action()
    {
        $id = (int)getval('id');
        if($id <= 0 || !$this->user)
            die;
        $rec = $this->model->Get($id);
        if(!$rec || $this->user['id'] != $rec['user_id'] )
            die;
        if ($this->ajax && getval('subm_'))
        {
            $f = getval('func2');
            $name = getval('name');
            switch ($f)
            {
                case "message" :
                    $this->model->Update($id, 'message', $name);
                    break;

            }
        }
    }
    function Delete_Action()
    {
        $id = (int)getval('id');
        if($id <= 0 || !$this->user)
            die;
        $rec = $this->model->Get($id);
        if(!$rec || $this->user['id'] != $rec['user_id'] )
            die;
        if($rec['f_main'] == 1)
        {
            $this->GetModel('topics')->Delete($rec['topic_id']);
            $this->model->DeleteByTopic($rec['topic_id']);
            redirect('/');
        }
        else
        {
            $this->model->Delete($id);
            redirect('/topics.html?id=' . $rec['topic_id']);
        }
    }
    function Add_Action()
    {
        $topic_id = (int)getval('topic_id','p');
        if($topic_id <= 0 || !$this->user)
            die;
        $rec = $this->GetModel('topics')->Get($topic_id);
        if(!$rec)
            die;

        $this->model->Add($topic_id);
        redirect('/topics.html?id=' . $topic_id);
    }
}
?>