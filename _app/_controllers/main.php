<?

class Controller_Main  extends BaseController
{
    function Main_Action  ()
    {
        $topics = $this->GetModel('topics')->Get();

        $users_id = array();
        foreach ($topics as $v)
        {
            if(!in_array($v['user_id'],$users_id))
                $users_id[] = $v['user_id'];
        }

        $data = array(
            "data" => $topics,
            "cnt_posts" => $this->GetModel('posts')->GetCntPosts(),
            "users_login" =>  $this->GetModel('users')->GetListLogins($users_id),

        );

        parent::Render(NULL,$data);
    }
}
?>