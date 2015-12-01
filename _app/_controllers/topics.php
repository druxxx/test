<?

class Controller_Topics extends BaseController
{
    function Main_Action  ()
    {
        $id = (int)gv('id',$_GET);
        if($id <= 0)
            parent::Page_404();

        $topic = $this->model->Get($id);
        if(!$topic)
            parent::Page_404();

        $posts = $this->GetModel('posts')->GetByTopic($id);

        $users_id = array();
        foreach ($posts as $v)
        {
            if(!in_array($v['user_id'],$users_id))
                $users_id[] = $v['user_id'];
        }
        $users_data = !empty($users_id) ? $this->GetModel('users')->Get($users_id) : NULL;
        foreach ($posts as $k => $v)
        {
            $posts[$k]['user_data'] = gv($v['user_id'],$users_data);
            $posts[$k]['is_author'] = gv('id',$this->user) && gv('id',$this->user) == $v['user_id'] ? TRUE : FALSE;
        }

        $data = array(
            "topic" => $topic,
            "posts" => $posts,
            "form"  => $this->GetModel('posts')->GetForm($id)
        );
        parent::Render('forum/topic_view/',$data);
    }
    function Add_Action  ()
    {
        $this->CheckUser();

        $form = $this->model->GetForm();
        if($this->user && Validate::ValidateAddTopic($form))
        {
            $params = $this->model->Add();
            redirect("/topics.html?id=".$params['id']);
        }

        $data = array(
            "form" => $form,
        );
        parent::Render('forum/topic_add/',$data);
    }
}
?>