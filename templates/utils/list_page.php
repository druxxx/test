<?
	global $cTemplate;
	if(empty($params['center']))
		return;
?>
<div class="pagination">
	<ul>
	<?if(isset($params['first_page_'])) :?>
		<li><a href="<?=$params['first_page_'][0]?>"><?=$params['first_page_'][1]?></a></li>
		<li>...</li>
	<?endif;?>
	  <?foreach ($params['center'] as $k => $v) :?>
		<li <?=($k==$params['center_page'] ? 'class="active"' : '')?>><a href="<?=$v?>"><?=$k?></a></li>
	  <?endforeach;?>
	<?if(isset($params['last_page_'])) :?>
		<li>...</li>
		<li><a href="<?=$params['last_page_'][0]?>"><?=$params['last_page_'][1]?></a></li>
	<?endif;?>
	</ul>
</div>
