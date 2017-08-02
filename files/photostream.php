<?php
/**
 * Code For Photostream Images
 * this file makes a curl call to get images of photostream(images uploaded publicly on flickr)
 * return the no of pages according to parameter specifies items per page
 * Dynamic pagination
 */

$photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&per_page='.$mypage.'&page='.$cpage.'&format=php_serial';
$my_obj = infinite_scroll_curl_call($photourl);
$my_obj = unserialize($my_obj);

$mypic = $my_obj['photos'];
$final = $mypic['photo'];
$pages = $mypic['pages'];