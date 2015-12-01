<table class="forum">
    <tr>
        <th style="width: 79%; text-align: left;">Название темы</th>
        <th style="width: 7%">Ответов</th>
        <th style="width: 14%">Автор</th>
    </tr>
    <?if(!empty($params['data'])) :?>
        <?foreach($params['data'] as $v) :?>
            <tr>
                <td class="row2"><a href="<?=WEBSITE?>/topics.html?id=<?=$v['id']?>"><?=escape_html($v['title'])?></a></td>
                <td class="row1" style="text-align: center"><?=gv($v['id'],$params['cnt_posts'],0)?></td>
                <td class="row1" style="text-align: center"><?=escape_html(gv($v['user_id'],$params['users_login'],''))?></td>
            </tr>
        <?endforeach;?>
    <?else :?>
        <tr><td  class="row2" colspan="4" style="text-align: center;">В данном форуме еще нет тем!<br><a href="<?=WEBSITE?>/topics/add.html" onclick="return Ajax.CheckLogin()">Создайте первую</a></td> </tr>
    <?endif;?>
</table>

<a href="<?=WEBSITE?>/topics/add.html" onclick="return Ajax.CheckLogin()" style="color: red;">Создать тему</a>