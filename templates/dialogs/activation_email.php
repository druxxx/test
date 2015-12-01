<?if (!empty($params['success'])) :?>
    <span style="color:green;"><?=$cTemplate->GetLang("activation/success_email")?></span>
    <?return;
endif;?>
<?if (!empty($params['error'])) :?>
    <span style="color:red;"><?=$params['error']?></span><br /><br />
<?endif?>
