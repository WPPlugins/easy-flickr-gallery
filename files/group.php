<?php
/**
 * Code For Selected Group Images
 * this file makes a curl call to get images of selected Group from list of user's group
 * return the no of pages according to parameter specifies items per page
 * Dynamic pagination
*/


$photourl = 'http://api.flickr.com/services/rest/?method=flickr.groups.pools.getPhotos&api_key=' . $efpg_user_api . '&group_id='  . $efpg_group . '&per_page=' .$mypage. '&page='.$cpage.'&format=php_serial';
$my_obj = infinite_scroll_curl_call($photourl);

$my_obj = unserialize($my_obj);
$mypic = $my_obj['photos'];
$final = $mypic['photo'];
$pages = $mypic['pages'];