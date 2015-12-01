<?
	global $cTemplate;
	if(empty($params['center']))
		return;
	$cnt = $params['cnt_page'];
?>
<div class="select">
	<strong>Показывать по: </strong>
	<select onclick="return ChangeCnt('<?=$params['obj_type']?>',this,'<?=preg_replace('/[\?|\&]cnt\=[0-9]+/','',$_SERVER['REQUEST_URI']).(FALSE === strpos($_SERVER['REQUEST_URI'],'?') ? '?' : '&')?>')">
		<option value="10" <?=($cnt == 10 ? 'selected' : '')?>>10</option>
		<option value="20" <?=($cnt == 20 ? 'selected' : '')?>>20</option>
		<option value="30" <?=($cnt == 30 ? 'selected' : '')?>>30</option>
		<option value="50" <?=($cnt == 50 ? 'selected' : '')?>>50</option>
		<option value="100" <?=($cnt == 100 ? 'selected' : '')?>>100</option>
	</select>
</div>

<div class="pagination">
	<ul>
	<?if(isset($params['first_page_'])) :?>
		<li><a class="button" href="<?=$params['first_page_'][0]?>"><?=$params['first_page_'][1]?></a></li>
		<li>...</li>
	<?endif;?>
	  <?foreach ($params['center'] as $k => $v) :?>
		<li <?=($k==$params['center_page'] ? 'class="active"' : '')?>><a class="button" href="<?=$v?>"><?=$k?></a></li>
	  <?endforeach;?>
	<?if(isset($params['last_page_'])) :?>
		<li>...</li>
		<li><a class="button" href="<?=$params['last_page_'][0]?>"><?=$params['last_page_'][1]?></a></li>
	<?endif;?>
	</ul>
</div>
