<?php

/**
 * function to make a curl call
 * @param type $url (url sent to get flickr images)
 * @return return curl response i.e. flickr images
 */
function infinite_scroll_curl_call($url) {
	$efpg_curltime=get_option('efpg_curltime');
	if ($efpg_curltime != '' && ctype_digit($efpg_curltime)) {
		$curmtime = $efpg_curltime;
	} else {
		$curmtime = 10;
	}
    $ch = curl_init();
    $timeout = $curmtime; // set to zero for no timeout
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

/**
 * function to verrify if user submiited correct user id and api key
 * @return string return a string warning that 'null api key or user id' or 'invalid api key or user id'
 */
function infinite_scroll_invalid() {
	$efpg_user_api=get_option('efpg_api');
	$efpg_user_id=get_option('efpg_id');

    if ($efpg_user_api == '' || $efpg_user_id == '') {
        return "<h3 class='error'>Api Key and User Id Can't be null</h3>";
    } elseif ($efpg_user_api != '' && $efpg_user_id != '') {

        $photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&format=php_serial';
        $rsp_obj = infinite_scroll_curl_call($photourl);
        $rsp_obj = unserialize($rsp_obj);
        if ($rsp_obj['stat'] != 'ok') {
            return '<h4 class="error">Invalid User Name or API Key or Check your Internet Connection</h3>';
        }
    }
}

/**
 * function to update user api key and user id;
 */
function infinite_scroll_options_update_details() {
    update_option('efpg_user', $_POST['efpg_user']);
    update_option('efpg_api', $_POST['efpg_api']);
$user = 'http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=' . $_POST['efpg_api'] . '&username=' . urlencode($_POST['efpg_user']) . '&format=php_serial';
$rsp_obj = infinite_scroll_curl_call($user);
$rsp_obj = unserialize($rsp_obj);

if ($rsp_obj['stat'] == 'ok') {
	echo update_option('efpg_id',$rsp_obj['user']['id']);
}
}
/**
 * function to update flickr gallery options
 */
function infinite_scroll_options_update_gallery() {
    update_option('efpg_imgsize', $_POST['efpg_imgsize']);
    update_option('efpg_group', $_POST['efpg_group']);
    update_option('efpg_photoset', $_POST['efpg_photoset']);
    update_option('efpg_gallery', $_POST['efpg_gallery']);
    update_option('efpg_source', $_POST['efpg_source']);
	update_option('efpg_first', $_POST['efpg_first']!=''?$_POST['efpg_first']:'First');
	update_option('efpg_last', $_POST['efpg_last']!=''?$_POST['efpg_last']:'First');
	update_option('efpg_pre', $_POST['efpg_pre']!=''?$_POST['efpg_pre']:'Pre');
	update_option('efpg_next', $_POST['efpg_next']!=''?$_POST['efpg_next']:'Next');
	update_option('efpg_mdrange', $_POST['efpg_mdrange']!=''?$_POST['efpg_mdrange']:2);
	update_option('efpg_curltime', $_POST['efpg_curltime']!=''?$_POST['efpg_curltime']:10);

	update_option('efpg_gallerystyle', $_POST['efpg_gallerystyle']);
    if($_POST['efpg_tags']!=''){
    update_option('efpg_tags', $_POST['efpg_tags']);
    }
    else{
        update_option('efpg_tags', 'nature');
    }
    if($_POST['efpg_items']!='' && ctype_digit($_POST['efpg_items'])){
    update_option('efpg_items', $_POST['efpg_items']);
    }
    else{
        update_option('efpg_items', 10);
    }
    if($_POST['efpg_bound']!='' && ctype_digit($_POST['efpg_bound'])){
    update_option('efpg_bound', $_POST['efpg_bound']);
    }
    else{
        update_option('efpg_bound', 5);
    }
    if($_POST['efpg_rad']!='' && ctype_digit($_POST['efpg_rad'])){
    update_option('efpg_rad', $_POST['efpg_rad']);
    }
    else{
        update_option('efpg_rad', 5);
    }
}
/*
 * check efg plugin screen
 */
function is_oscitas_infinite_screen() {
	$screen = get_current_screen();
	if (is_object($screen) && $screen->id == 'toplevel_page_infinite_scroll') {
		return true;
	} else {
		return false;
	}
}
/*
 * check if efg shortcode included
 */
function is_oscitas_shortcode_defined($shortcode) {
	global $shortcode_tags;
	if (isset($shortcode_tags[$shortcode])) {
		return TRUE;
	} else {
		return FALSE;
	}
}
function efpg_pagination($pages = '', $range = 2)
{
	$showitems = ($range * 2)+1;

	global $paged;
	if(empty($paged)) $paged = 1;

	if($pages == '')
	{

		$pages = 1;

	}

	if(1 != $pages)
	{
		echo "<div class='easy-flickr-gallery-pagination clearfix'>";
		if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>".get_option('efpg_first')."</a>";
		if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>".get_option('efpg_pre')."</a>";

		for ($i=1; $i <= $pages; $i++)
		{
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			{
				echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>".get_option('efpg_next')."</a>";
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".get_option('efpg_last')."</a>";

		$URI='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		echo '<form name="go_to" action="'.$URI.'" method="get">';
		if(isset($_GET) && count($_GET)>0){
			foreach($_GET as $g=> $v){
				if($g!='paged'){
					echo '<input type="hidden" name="'.$g.'" value="'.$v.'">';
				}
			}
		}

		echo '<select name="paged" class="navbutton pagi_item">';
		for ($i = 1; $i <= $pages; $i++) {
			$selected=$_GET['paged']==$i?'selected="selected"':'';
			echo "<option value='". $i."' ".$selected.">".$i."</option>";
		}
		echo '</select>';
		echo '<button type="submit" class="efpg_button pagi_item">Go To</button>';
		echo '</form>';
		echo "</div>\n";
	}
}
