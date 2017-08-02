<?php
function getuser_flickr_image(){

    $requested_page = $_POST['page_num'];
    $id = $_POST['id'];
    $api = $_POST['api'];
    $items = $_POST['items'];
    $source = $_POST['source'];
    $imgsize = $_POST['imgsize'];
    $gallery = $_POST['gallery'];
    $group = $_POST['group'];
    $photoset = $_POST['photoset'];
    $tags = $_POST['tags'];

    if ($source == 'photoset') {
        if ($api != '' && $id != '' && $photoset != '') {
            $photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=' . $api . '&photoset_id=' . $photoset . '&per_page=' . $items . '&page=' . $requested_page . '&format=php_serial';
            $my_obj = infinite_scroll_curl_call($photourl);
            $my_obj = unserialize($my_obj);
            $mypic = $my_obj['photoset'];
            $final = $mypic['photo'];
            $pages = $mypic['pages'];
        }
    }
    /*
     *  Code to get Group Images
     */
    if ($source == 'group') {
        if ($api != '' && $id != '' && $group != '') {
            $photourl = 'http://api.flickr.com/services/rest/?method=flickr.groups.pools.getPhotos&api_key=' . $api . '&group_id=' . $group . '&per_page=' . $items . '&page=' . $requested_page . '&format=php_serial';
            $my_obj = infinite_scroll_curl_call($photourl);
            $my_obj = unserialize($my_obj);
            $mypic = $my_obj['photos'];
            $final = $mypic['photo'];
            $pages = $mypic['pages'];
        }
    }
    /*
     *  Code to get Gallery Images
     */
    if ($source == 'gallery') {
        if ($api != '' && $id != '' && $gallery != '') {
            $photourl = 'http://api.flickr.com/services/rest/?method=flickr.galleries.getPhotos&api_key=' . $api . '&gallery_id=' . $gallery . '&per_page=' . $items . '&page=' . $requested_page . '&format=php_serial';
            $my_obj = infinite_scroll_curl_call($photourl);
            $my_obj = unserialize($my_obj);
            $mypic = $my_obj['photos'];
            $final = $mypic['photo'];
            $pages = $mypic['pages'];
        }
    }
    /*
     *  Code to get Photostream Images
     */
    if ($source == 'photostream') {
        if ($api != '' && $id != '') {
            $photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . $api . '&user_id=' . $id . '&per_page=' . $items . '&page=' . $requested_page . '&format=php_serial';
            $my_obj = infinite_scroll_curl_call($photourl);
            $my_obj = unserialize($my_obj);

            $mypic = $my_obj['photos'];
            $final = $mypic['photo'];
            $pages = $mypic['pages'];
        }
    }
    /*
     *  Code to get Tagged Images
     */

    if ($source == 'tags') {
        if ($api != '' && $id != '' && $tags != '') {
            $photourl = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $api . '&user_id=' . $id . '&tags=' . $tags . '&per_page=' . $items . '&page=' . $requested_page . '&format=php_serial';
            $my_obj = infinite_scroll_curl_call($photourl);
            $my_obj = unserialize($my_obj);
            $mypic = $my_obj['photos'];
            $final = $mypic['photo'];
        }
    }
    if (count($final)>0) {
        $html = '';
        foreach ($final as $images) {


            $title = $images['title'];
            $img_src = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_m.jpg';
            $final_src = str_replace('_m', '_n', $img_src);
            $href = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_b.jpg';

            $html .= '<div class="box photo ' . $imgsize . '"><a href="' . $href . '"  title= "' . $title . '" >
                 <img src="' . $final_src . '" /> 
                 </a></div>';
        }

        echo $html;
    } else {
        echo 'nodata';
    }

    die();
}
?>