<?php
/**
 * Code For Selected Photoset Images
 * this file makes a curl call to get images of selected Photoset from list of user's Photosets
 * return the no of pages according to parameter specifies items per page
 * Dynamic pagination
 */

$photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=' . $efpg_user_api . '&photoset_id=' . $efpg_photoset . '&per_page='.$mypage.'&page='.$cpage.'&format=php_serial';
$my_obj = infinite_scroll_curl_call($photourl);
$my_obj = unserialize($my_obj);
$mypic = $my_obj['photoset'];
$final = $mypic['photo'];
//var_dump($final);
$pages = $mypic['pages'];