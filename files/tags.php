<?php

/**
 * Code For Selected tags Images
 * this file makes a curl call to get images of selected Group from list of user's group
 * return the no of pages according to parameter specifies items per page
 * Dynamic pagination
 */


$photourl = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&tags=' . $efpg_tags . '&per_page='. $mypage .'&page='.$cpage.'&format=php_serial';
$my_obj = infinite_scroll_curl_call($photourl);
$my_obj = unserialize($my_obj);
$mypic = $my_obj['photos'];
//echo '<pre>';
//var_dump($mypic);
//echo '</pre>';
$final = $mypic['photo'];
$mynumber=count($final);
if($mynumber<21)
{
    $number_count=20;
}
else{
    $number_count=$mynumber;
}

if (count($final) == 0) {
    echo '<div class="error">No Image is Tagged with this Tag.</div>';
}
$pages = $mypic['pages'];