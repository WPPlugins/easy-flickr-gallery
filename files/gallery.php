<?php
/**
 * Code For Selected Gallery
 * this file makes a curl call to get images of selected Photoset from list of user's Gallery
 * NO pagination bcoz flickr doesnt support parameters per_page n page for gallery
 */

$photourl = 'http://api.flickr.com/services/rest/?method=flickr.galleries.getPhotos&api_key=' . $efpg_user_api . '&gallery_id=' . $efpg_gallery . '&per_page='.$mypage.'&page='.$cpage.'&format=php_serial';
$my_obj = infinite_scroll_curl_call($photourl);
$my_obj = unserialize($my_obj);
$mypic = $my_obj['photos'];
$final = $mypic['photo'];
$pages = $mypic['pages'];