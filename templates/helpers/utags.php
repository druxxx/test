<?
function tmpl_utags_a ($params) 
{
	$href = (!empty($params['href'])   ? $params['href'] : '#');
	if(substr($href,0,1) != '?' && !empty($params['href']) && !preg_match("/^".str_replace("/","\/",preg_quote(WEBSITE)). "/",$href))
		$href = WEBSITE.(substr($href,0,1) != '/' ? '/' : '').$href;

	echo '<a href="'.$href.'"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['target']) ? ' target="'.$params['target'].'"' : '').
			(!empty($params['onclick']) ? ' onclick="'.$params['onclick'].'"' : '').
			(!empty($params['alt'])     ? ' alt="'.$params['alt'].'"' : '').
            (!empty($params['title'])   ? ' title="'.$params['title'].'"' : '').
			(!empty($params['confirm'])   ? ' data-confirm="'.$params['confirm'].'"' : '').
			(!empty($params['others'])   ? ' '.$params['others'] : '').
			(!empty($params['value'])   ? '>'.$params['value'].'</a>' : '></a>');
}
function tmpl_utags_aimg ($params) 
{
	$src = (!empty($params['src']) ? $params['src'] : '');
//	if(!preg_match("/^".str_replace("/","\/",preg_quote(WEBSITE)). "/",$src))
//		$src = IMAGES_URL.$src;

	if (!empty($params['fid']))
	{
		if ( $fn = Images::GetFilenameImgById($params['fid']))
		{
			$fn = rawurlencode($fn);
			$src = UPLOADS_IMAGES_URL . $fn;
			$href = UPLOADS_IMAGES_URL . $fn;
			if ((!empty($params['height']) || !empty($params['width'])))
			{
				$w = !empty($params['width']) ? $params['width'] : 0;
				$h = !empty($params['height']) ? $params['height'] : 0;
				$src =/* $href =*/
					WEBSITE . '/uploads/' . $w . 'x' . $h . '_' . $fn;
				if (!isset($params['href']))
					$params['href'] = WEBSITE . '/uploads/'.$fn;;

			}
		}
	}

    if (empty($params['alt']) && !empty($params['value']))
        $params['alt'] = $params['value'];
    if (empty($params['title']) && !empty($params['alt']))
        $params['title'] = $params['alt'];


    $params['value'] = '<img src="'.$src.'"'.
			(!empty($params['iid'])     ? ' id="'.    $params['iid'].'"' : '').
		 	(!empty($params['iclass'])  ? ' class="'. $params['iclass'].'"' : '').
			(!empty($params['istyle'])  ? ' style="'. $params['istyle'].'"' : '').
			(!empty($params['alt'])     ? ' alt="'.$params['alt'].'"' : '').
			(!empty($params['title'])   ? ' title="'.$params['title'].'"' : '').
			(!empty($params['width'])   ? ' width="'.$params['width'].'"' : '').
			(!empty($params['height'])  ? ' height="'.$params['height'].'"' : '').
			(!empty($params['align'])   ? ' align="'.$params['align'].'"' : '').
            (!empty($params['valign'])  ? ' valign="'.$params['valign'].'"' : '').
            (!empty($params['tooltip']) ? ' data-tooltip="'.$params['tooltip'].'"' : '').
		 ' />';
    tmpl_utags_a($params);
}
function tmpl_utags_img ($params) 
{
	$src = (!empty($params['src']) ? $params['src'] : '');
	if(!preg_match("/^".str_replace("/","\/",preg_quote(WEBSITE)). "/",$src))
		$src = IMAGES_URL.$src;
	if (!empty($params['fid']))
	{
		if ( $fn = Images::GetFilenameImgById($params['fid']))
		{
			$fn = rawurlencode($fn);
			$src = UPLOADS_IMAGES_URL . $fn;
//			$href = UPLOADS_IMAGES_URL . $fn;
			if ((!empty($params['height']) || !empty($params['width'])))
			{
				$w = !empty($params['width']) ? $params['width'] : 0;
				$h = !empty($params['height']) ? $params['height'] : 0;
				$src =/* $href =*/
					WEBSITE . '/uploads/' . $w . 'x' . $h . '_' . $fn;
			}
		}
	}
	echo '<img src="'.$src.'"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['onclick']) ? ' onclick="'.$params['onclick'].'"' : '').
			(!empty($params['alt'])     ? ' alt="'.$params['alt'].'"' : '').
			(!empty($params['title'])   ? ' title="'.$params['title'].'"' : '').
			(!empty($params['width'])   ? ' width="'.$params['width'].'"' : '').
			(!empty($params['height'])  ? ' height="'.$params['height'].'"' : '').
			(!empty($params['align'])   ? ' align="'.$params['align'].'"' : '').
			(!empty($params['valign'])   ? ' valign="'.$params['valign'].'"' : '').
            (!empty($params['tooltip']) ? ' data-tooltip="'.$params['tooltip'].'"' : '').
		 ' />';
}
?>