<?
function tmpl_form_open ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
	echo '<form'.
			(!empty($params['method']) ? ' method="'.$params['method'].'"' : '').
			(!empty($params['enctype'])? ' enctype="'.$params['enctype'].'"' : '').
			(!empty($params['action']) ? ' action="'.$params['action'].'"' : '').
			(!empty($params['target']) ? ' target="'.$params['target'].'"' : '').
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['onsubmit']) ? ' onsubmit="'.$params['onsubmit'].'"' : '').
		'>';
}
function tmpl_form_close ($params) 
{
	echo '</form>';
}
function tmpl_form_text ($params) 
{
    global $cTemplate;
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    $cTemplate->Register('tmpl_form_before_field');
    $cTemplate->Register('tmpl_form_after_field');

    $cTemplate->Render('tmpl_form_before_field',$params);

    $val = tmpl_form_getval($params);
	echo '<input type="text"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			' value="'.  tmpl_form_getval($params).'"'.
            (!empty($params['size'])   ? ' size="'.  $params['size'].'"' : '').
            (!empty($params['title'])   ? ' title="'.  $params['title'].'"' : '').
			(!empty($params['maxlength'])   ? ' maxlength="'.  $params['maxlength'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['onclick']) ? ' onclick="'.$params['onclick'].'"' : '').
			(!empty($params['onkeyup']) ? ' onkeyup="'.$params['onkeyup'].'"' : '').
			(!empty($params['onkeydown']) ? ' onkeydown="'.$params['onkeydown'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['disabled'])   ? ' disabled' : '').
			(!empty($params['readonly'])   ? ' readonly' : '').
            (!empty($params['placeholder'])   ? ' placeholder="'.  $params['placeholder'].'"' : '').
			'>';
    $cTemplate->Render('tmpl_form_after_field',$params);


}

function tmpl_form_text_list ($params)
{
	if(!isset($params['max']))
		$params['max'] = 3;
	
	echo "<div id=\"ctl_".$params['name']."\">";
	if(isset($_POST[$params['name']]))
		$params['data'] = $_POST[$params['name']];
	for ($i = 0; $i < count($params['data']); $i++) 
	{
		tmpl_form_text(array(
			"name" => $params['name'].'[]',
			"id" => $params['name'].'_id'.($i+1),
			"value" => $params['data'][$i])
		);
		if($i == 0)
			echo "<a href=\"#\" id=\"a_".$params['name']."_p\" onclick=\"return addInput('".$params['name']."',false,".$params['max'].");\" class=\"plus\">+</a>";
		if (($i+1) != count($params['data']))
			echo "<br />";
	}
	echo "</div>";	
}
function tmpl_form_text_list_horizontal ($params)
{
	if(!isset($params['max']))
		$params['max'] = 3;

	echo "<div style=\"float:left\" id=\"ctl_".$params['name']."\">";
	if(isset($_POST[$params['name']]))
		$params['data'] = $_POST[$params['name']];
    $fl = 0;
	for ($i = 0; $i < count($params['data']); $i++)
	{
        if(!empty($params['data'][$i]) || $fl == 0)
        {
            if(empty($params['data'][$i])) $fl = 1;

            tmpl_form_text(array(
                    "name"  => $params['name'] . '[]',
                    "id"    => $params['name'] . '_id' . ($i + 1),
                    "value" => $params['data'][$i])
            );
        }
        else
            break;
	}
	echo "</div>";
	echo "<div style=\"float:left\">
			<a href=\"#\" id=\"a_".$params['name']."_p\" onclick=\"return addInput('".$params['name']."',true,".$params['max'].");\" class=\"plus\" style=\"".($i<3 ? "" : "display:none")."\">+</a>
			<a href=\"#\" id=\"a_".$params['name']."_m\" onclick=\"return removeInput('".$params['name']."','ctl_".$params['name']."');\" class=\"minus\" style=\"".($i>0 ? "" : "display:none")."\">-</a>
		</div>";
	
	
}

function tmpl_form_hidden ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    echo '<input type="hidden"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(isset($params['value'])  ? ' value="'. $params['value'].'"' : '').
		'>';
}
function tmpl_form_submit ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    echo '<input type="submit"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
		(!empty($params['disabled'])   ? ' disabled' : '').
			(isset($params['value'])  ? ' value="'. $params['value'].'"' : '').
		'>';
}
function tmpl_form_button ($params)
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    echo '<button'.
			(!empty($params['type'])   ? ' type="'.  $params['type'].'"' : ' type="button"').
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['onclick']) ? ' onclick="'.$params['onclick'].'"' : '').
		(!empty($params['disabled'])   ? ' disabled' : '').
			(isset($params['value_'])  ? ' value="'. $params['value_'].'"' : ' value="1"').
			'>'.(!empty($params['value']) ? $params['value'] : '').
	      '</button>';
}
function tmpl_form_password ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    global $cTemplate;
    $cTemplate->Register('tmpl_form_before_field');
    $cTemplate->Register('tmpl_form_after_field');

    $cTemplate->Render('tmpl_form_before_field',$params);
//	$val = tmpl_form_getval($params);
	echo '<input type="password"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(isset($params['value'])  ? ' value="'. $params['value'].'"' : '').
//			(isset($params['value'])  ? ' value="'. $params['value'].'"' : ' value="'.$val.'"').
			(!empty($params['size'])   ? ' size="'.  $params['size'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
		'>';
    $cTemplate->Render('tmpl_form_after_field',$params);
}
function tmpl_form_checkbox ($params) 
{
	global $cTemplate;
	$cTemplate->Register('tmpl_form_before_field');
	$cTemplate->Register('tmpl_form_after_field');

	$label = isset($params['label']) ?$params['label'] : NULL;
	unset($params['label']);

	$cTemplate->Render('tmpl_form_before_field',$params);
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';

	echo '<input type="checkbox"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['checked']) || (!empty($params['name']) && ! empty($_POST[$params['name']])) ? ' checked' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['disabled'])   ? ' disabled' : '').
			(isset($params['value'])  ? ' value="'. $params['value'].'"' : '').
			'>';
	if(!empty($label))
		echo '<label for="'.$params['id'].'" class="cb_label">  '.$label.'</label>';
	$cTemplate->Render('tmpl_form_after_field',$params);


	
}
function tmpl_form_file ($params) 
{
	global $cTemplate;
	$cTemplate->Register('tmpl_form_before_field');
	$cTemplate->Register('tmpl_form_after_field');

	$cTemplate->Render('tmpl_form_before_field',$params);
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
	echo '<input type="file"'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
		'>';
	$cTemplate->Render('tmpl_form_after_field',$params);
}
function tmpl_form_radio ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
	$pChecked = false;
	if(!empty($params['name']) && preg_match('/([^\[]+)\[([^\]]+)/',$params['name'],$q))
	{
		if(isset($q[1]) && isset($q[2]) &&
			isset($_POST[$q[1]][$q[2]]))// && $_POST[$q[1]][$q[2]] == $params['value'])
			$pChecked = true;
	}
	if (!$pChecked && !empty($params['name']) && isset($_POST[$params['name']]) &&  $_POST[$params['name']] == $params['value'])
		$pChecked = true;
	elseif (!$pChecked && !empty($params['checked']))// && $params['checked'] == $params['value'])
		$pChecked = true;
	echo '<input type="radio"'.
	(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
	(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
	(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
	(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
	($pChecked ? ' checked' : '').
//    (!empty($params['tooltip']) ? ' data-tooltip="'.$params['tooltip'].'"' : '').
	(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
	(isset($params['value'])  ? ' value="'. $params['value'].'"' : '').
	'>';
	if (!empty($params['label']))
	{
		echo '<label '.
         (!empty($params['tooltip']) ? ' data-tooltip="'.$params['tooltip'].'"' : '').
		 (!empty($params['id'])     ? 'for="'.    $params['id'].'"' : '').
		 '> - '.$params['label'].'</label>';
	}
}

function tmpl_form_radio_list ($params) 
{
    global $cTemplate;
    $cTemplate->Register('tmpl_form_before_field');
    $cTemplate->Register('tmpl_form_after_field');

    $cTemplate->Render('tmpl_form_before_field',$params);
	if(!empty($params['data']))
	{
		foreach($params['data'] as $v)
		{
            $v['id'] = $params['name'].$v['value'].'_id';
			tmpl_form_radio(array_merge($params,$v));
		}
	}
    $cTemplate->Render('tmpl_form_after_field',$params);
}

function tmpl_form_cb_list ($params) 
{
	global $cTemplate;
	$cTemplate->Register('tmpl_form_before_field');
	$cTemplate->Register('tmpl_form_after_field');

	$cTemplate->Render('tmpl_form_before_field',$params);
	$colums = isset($params['columns']) ? $params['columns'] : 1;
	if (!empty($params['name']) && ! empty($_POST[$params['name']]))
		$val = $_POST[$params['name']];
	elseif(!empty($params['value']))
		$val = $params['value'];
	else
		$val = '';
	if(!empty($params['data']) && !empty($params['name']))
	{
		if($colums > 1)
			echo "<table style=\"width:100%\"".
                (!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
                 "><tr>";
		$i=0;
		foreach($params['data'] as $k => $par)
		{
			if(!is_array($par))
				$par = array('name' => $k,'id' => $params['name'].$k,'value_' => $par);

			if(empty($par['id']) && !empty($par['name']))
				$par['id'] = $par['name'].'_id';

			if($colums > 1 && $i % $colums == 0)
				echo "</tr><tr>";
				
			if($colums > 1)
				echo "<td style=\"width:".((int)(100/$colums))."%\">";
			echo '<input type="checkbox"'.
				(!empty($par['name'])   ? ' name="'. $params['name'].'['. $par['name'].']"' : '').
				(!empty($par['id'])     ? ' id="'.    $par['id'].'"' : '').
				(!empty($par['class'])  ? ' class="'. $par['class'].'"' : '').
				(!empty($par['style'])  ? ' style="'. $par['style'].'"' : '').
//				((!is_array($val) && $val == $par['name']) || (is_array($val) && in_array($par['name'],$val)) || !empty($par['checked']) ? ' checked' : '').
				((!is_array($val) && $val == $par['name']) || (is_array($val) && isset($val[$par['name']])) || !empty($par['checked']) ? ' checked' : '').
				'><label for="'.$par['id'].'">'.
				(!empty($params['separator'])  ? $params['separator'] : ' ').
				(!empty($par['lang'])  ? $cTemplate->GetLang($par['lang']) : '').
				(!empty($par['value_']) ? $par['value_'] : '').
			    '</label>';
			if($colums > 1)
				echo "</td>";
			else 	
				echo '<br />';
			$i++;
		}
		if($colums > 1)
			echo "</tr></table>";
	}
	$cTemplate->Render('tmpl_form_after_field',$params);
}
function tmpl_form_cb_tr_list ($params) 
{
	global $cTemplate;
    if (!isset($params['columns']))
        $params['columns'] = 1;

	if(!empty($params['data']) && !empty($params['name']))
	{
		for ($i=0; $i <count($params['data']); $i++) { 
			$par  = &$params['data'][$i];
            if(!isset($par['id']))
            {
                $par['id'] = $par['name'].'_id'.$i;
            }

            if ($params['columns'] == 2 && ($i) %2==0)
                echo "<tr>";
            elseif ($params['columns'] == 1)
                echo "<tr>";

            echo "<td>".(!empty($par['lang'])  ? "<label for=\"".$par['id']."\">".$cTemplate->GetLang($par['lang'])."</label>" : '')."</td><td>";
			echo '<input type="checkbox"'.
				(!empty($par['name'])   ? ' name="'. $params['name'].'['. $par['name'].']"' : '').
				(!empty($par['id'])     ? ' id="'.    $par['id'].'"' : '').
				(!empty($par['class'])  ? ' class="'. $par['class'].'"' : '').
				(!empty($par['style'])  ? ' style="'. $par['style'].'"' : '').
				(!empty($par['checked']) || (!empty($par['name']) && !empty($_POST[$params['name']][$par['name']])) ? ' checked' : '').
				'>';
			echo "</td>";

            if ($params['columns'] == 2 && ($i+1) %2==0)
                echo "</tr>";
            elseif ($params['columns'] == 1)
                echo "</tr>";

		}
	}
}
function tmpl_form_cb_list_text ($params) 
{
	global $cTemplate;
	if(!empty($params['data']))
	{
		for ($i=0; $i <count($params['data']); $i++) { 
			$par  = &$params['data'][$i];
			echo (!empty($par['checked']) && !empty($par['lang']) ? $cTemplate->GetLang($par['lang'])."<br />" : '');
		}
	}
}
function tmpl_form_select ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
    global $cTemplate;
    $cTemplate->Register('tmpl_form_before_field');
    $cTemplate->Register('tmpl_form_after_field');
    $cTemplate->Render('tmpl_form_before_field',$params);
	if (!empty($params['name']) && ! empty($_POST[$params['name']]))
		$val = $_POST[$params['name']];
	elseif(!empty($params['value']))
		$val = $params['value'];
	else
		$val = '';
	echo '<select'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['multiple'])   ? 'multiple' : '').
			(!empty($params['size'])   ? ' size="'.  $params['size'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['disabled'])   ? ' disabled' : '').
		'>';
	if(isset($params['data']))
	{
		$params['data'] = (array)$params['data'];
		if(!isset($params['data'][0]) && !isset($params['ns']))
			echo '<option value="0">' . (!empty($params['no_select']) ? $params['no_select'] : '&rarr;') . '</option>';
	}
	if(!empty($params['add']))
		echo '<option value="-1">[Нет в списке]</option>';
	if(!empty($params['data']))
	{
		foreach ($params["data"] as $key => $value)
		{
			if(is_array($value)) // с группами
			{
				echo '<optgroup label="'.$key.'">';
				foreach ($value as $k => $v)
				{
					echo '<option value="' . $k . '"' .
						((!is_array($val) && $val == $k) || (is_array($val) && in_array($k, $val)) ? " selected" : "") .
						'>' . $v . '</option>';
				}
				echo '</optgroup>';
			}
			else
			{
				echo '<option value="' . $key . '"' .
					((!is_array($val) && $val == $key) || (is_array($val) && in_array($key, $val)) ? " selected" : "") .
					'>' . $value . '</option>';
			}
		}
	}
	echo '</select>';
    $cTemplate->Render('tmpl_form_after_field',$params);
}
function tmpl_form_textarea ($params) 
{
    if(empty($params['id']) && !empty($params['name']))
        $params['id'] = $params['name'].'_id';
	global $cTemplate;
	$cTemplate->Register('tmpl_form_before_field');
	$cTemplate->Register('tmpl_form_after_field');

	$cTemplate->Render('tmpl_form_before_field',$params);
	$val = !empty($params['name']) && isset($_POST[$params['name']]) ? $_POST[$params['name']] : '';
	echo '<textarea'.
			(!empty($params['name'])   ? ' name="'.  $params['name'].'"' : '').
			(!empty($params['id'])     ? ' id="'.    $params['id'].'"' : '').
			(!empty($params['class'])  ? ' class="'. $params['class'].'"' : '').
			(!empty($params['style'])  ? ' style="'. $params['style'].'"' : '').
			(!empty($params['rows'])   ? ' rows="'.  $params['rows'].'"' : '').
			(!empty($params['cols'])   ? ' cols="'.  $params['cols'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['onclick']) ? ' onclick="'.$params['onclick'].'"' : '').
			(!empty($params['onkeyup']) ? ' onkeyup="'.$params['onkeyup'].'"' : '').
			(!empty($params['onkeydown']) ? ' onkeydown="'.$params['onkeydown'].'"' : '').
			(!empty($params['onchange'])   ? ' onchange="'.  $params['onchange'].'"' : '').
			(!empty($params['disabled'])   ? ' disabled' : '').
			(!empty($params['readonly'])   ? ' readonly' : '').
			(!empty($params['placeholder'])   ? ' placeholder="'.  $params['placeholder'].'"' : '').
		'>'.
			(isset($params['value'])   ? $params['value'] : $val).
		'</textarea>';

    $cTemplate->Render('tmpl_form_after_field',$params);
}
function tmpl_form_getval($params)
{
	$name = isset($params['name']) ? $params['name'] : '';
	$value = NULL;
	if(preg_match('/([^\[]+)\[([^\]]+)/',$name,$q))
	{
		if(isset($q[1]) && isset($q[2]) &&
			isset($_POST[$q[1]][$q[2]]))
			$value = $_POST[$q[1]][$q[2]];
	}
	if (!$value && isset($_POST[$name]))
		$value = $_POST[$name];

	
	if (!$value && !empty($params['value']))
		$value = $params['value'];

	//if(strpos($name, "_nam"))
//    var_dump(htmlspecialchars($value));

    return htmlspecialchars($value);
}
function tmpl_form_error ($params)
{
    global $cTemplate;
    $colspan = isset($params['colspan']) ? $params['colspan'] : NULL;
	$tr_vis = isset($params['tr_vis']) ? true : false;
	$class_ = isset($params['class']) ? ' '.$params['class'] : '';
    unset($params['colspan']);
	unset($params['tr_vis']);
	unset($params['class']);

    if(!empty($params['value']))
        $params = array($params);

    $id = $visibility = false;
    $buffer = '';

	$pref = "ERROR_".rand(1000,9999).'_';
	$i=0;
    foreach ($params as $v)
    {
		if(!isset($v['id']))
			$v['id'] = $pref.$i;

        if(!$id)
            $id = preg_match('/^(.*)[0-9]$/', $v['id'],$res) ? $res[1] : $v['id'];
        if(!$visibility && !empty($v['visibility']))
            $visibility = true;

        if(isset($v['lang']))
            $val = $cTemplate->GetLang($v['lang']);
        elseif(isset($v['value']))
            $val = $v['value'];
        else
            $val = '';

        $buffer .= '<div id="'.$v['id'].'" class="error'.$class_.'" '.
            (!empty($v['visibility']) ? 'style="display:block;"' : '').
            (!empty($v['validate']) ? 'data-vt="'.$v['validate'].'"' : 'data-vt="String:1"').
            '>'.
            $val.
            '</div>';
		$i++;
    }

//    if(isset($colspan))
//        echo '<tr style="'.	($visibility || $tr_vis ? '' : 'display:none;').'" '.($tr_vis ? '' : 'id="tr_'.$id.'"').'><td colspan='.$colspan.'>';
//    else
//         echo '<tr style="'.	($visibility || $tr_vis ? '' : 'display:none;').'" '.($tr_vis ? '' : 'id="tr_'.$id.'"').'><td></td><td>';
//		echo '<tr><td></td><td>';
    echo $buffer;
//	echo '</td></tr>';
}

function tmpl_form_before_field($params)
{
    if(isset($params['only_field']))
        return;
    global $cTemplate;
    $cTemplate->Register('tmpl_utags_img');

    echo '<div class="field_container '.(!empty($params['class_fc']) ? $params['class_fc'] : '').'">';

//        if(!empty($params['tooltip']))
//            echo '<div class="label tooltip_" data-tooltip="'.$params['tooltip'].'">';
//        else
        echo '<div class="label">';
        if(!empty($params['label']))
        {
            echo $params['label'];
        }

//        if(!empty($params['tooltip']))
//            $cTemplate->Render("tmpl_utags_img", array("src" => 'icon_tooltip.png', "tooltip" => $params['tooltip']));
        if(!empty($params['errors']))
        {
            echo '<span class="required"> *</span>';
        }

        echo '</div>';
    echo '<div class="field">';

}
function tmpl_form_after_field($params)
{
    if(isset($params['only_field']))
        return;
    global $cTemplate;
    $cTemplate->Register('tmpl_form_error');
	$cTemplate->Register('tmpl_utags_img');
	$cTemplate->Register('tmpl_utags_aimg');

	if(!empty($params['fid']))
	{
		$cTemplate->Render("tmpl_utags_aimg", array("fid" => $params['fid'], "width" => 40, "height" => 40,"target" => "_blank"));

	}

    if(!empty($params['errors']))
    {
        echo '<div class="error">';
        $cTemplate->Render('tmpl_form_error', $params['errors']);
        echo '</div>';
    }
    echo '</div>';
	if(!empty($params['tooltip']))
	{
		echo '<div class="tooltip">';
		$cTemplate->Render("tmpl_utags_img", array("src" => 'icon_tooltip.png', "tooltip" => $params['tooltip']));
		echo '</div>';
	}
	if(!empty($params['rtext']))
	{
		echo '<div class="rtext">'.$params['rtext'].'</div>';
	}
    echo '</div>';
}


?>