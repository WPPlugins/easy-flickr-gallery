<?php

/*
Plugin Name: Easy Flicker Photo Gallery
Plugin URI: http://www.oscitasthemes.com
Description: Easy Flicker Photo Gallery enables you to show your snaps from flickr to your blog in simplest way.
Version: 1.0
Author: oscitas
Author URI: http://www.oscitasthemes.com
*/


define('ROOT', dirname(__FILE__));
require_once(ROOT . '/files/functions.php');
add_action('admin_enqueue_scripts', 'oscitas_infinite_scroll_init');
add_action('init', 'oscitas_infinite_scroll_front_init');
add_action('wp_head','efg_ajaxurl');
add_action('wp_ajax_user_flickr_images','getuser_flickr_image');
add_action('wp_ajax_nopriv_user_flickr_images','getuser_flickr_image');
add_action('admin_menu', 'oscitas_infinite_scroll_toaddmymenu');
add_shortcode('efpg', 'oscitas_infinite_scroll_final');

function efg_ajaxurl() {
	?>
	<script type="text/javascript">
		var efg_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
<?php
}

/**
 * function to add  plugin menu page to admin panel
 */
function oscitas_infinite_scroll_toaddmymenu() {
	/* adding top level menu */
	add_menu_page('EFPG Settings', 'EFPG Settings', 'manage_options', 'infinite_scroll', 'oscitas_infinite_scroll_start');
}

/*
 * include ajax function to display images
 */
include(ROOT . '/files/data.php');


/**
 * function to include required JS and CSS files To plugin settings page
 */
function oscitas_infinite_scroll_init() {
	$efg_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
	if (is_oscitas_infinite_screen()) {
		wp_enqueue_style('admin_panel', $efg_plugin_path . "css/admin_panel.css");
		wp_enqueue_script('oscitas_infinite_scroll', $efg_plugin_path . "js/oscitas_infinite_scroll_admin.js");
	}
}


/**
 * Function to add JS and CSS files to front end
 */
function oscitas_infinite_scroll_front_init() {
	if (!is_admin()) {
		if (is_oscitas_shortcode_defined('efpg')) {

			$efg_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
			wp_enqueue_style('efg_scroll', $efg_plugin_path . "css/front_end.css");
			wp_enqueue_style('efg-magnific-popup', $efg_plugin_path . "css/magnific-popup.css");

			// enqueue scripts
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery.masonry.min', $efg_plugin_path . "js/jquery.masonry.min.js");
            wp_enqueue_script('jquery.magnific-popup.js', $efg_plugin_path . "js/jquery.magnific-popup.js");

			wp_enqueue_script('efpg_front', $efg_plugin_path . "js/efpg_front.js");
			if(get_option('efpg_gallerystyle')=='scroll'){
				wp_enqueue_script('efpg_scroll', $efg_plugin_path . "js/efpg_scroll.js");
				// localise the JS

				wp_localize_script( 'efpg_scroll', 'efpgdata', array(
						'action'=>'user_flickr_images',
						'api' =>  get_option('efpg_api') ,
						'id'=> get_option('efpg_id') ,
						'source'=> get_option('efpg_source') ,
						'photoset'=> get_option('efpg_photoset') ,
						'gallery'=> get_option('efpg_gallery') ,
						'group'=>get_option('efpg_group') ,
						'items'=>get_option('efpg_items'),
						'imgsize'=>get_option('efpg_imgsize'),
						'tags'=>get_option('efpg_tags'),
					));
				wp_localize_script( 'efpg_scroll', 'efpgpage', array());

			}
		}
	}
}


function oscitas_infinite_scroll_final() {
$efpg_user_api=get_option('efpg_api');
$efpg_source=get_option('efpg_source');
$efpg_user_id=get_option('efpg_id');
$efpg_items=get_option('efpg_items');
$efpg_group=get_option('efpg_group');
$efpg_photoset=get_option('efpg_photoset');
$efpg_gallery=get_option('efpg_gallery');
$efpg_tags=get_option('efpg_tags');
$efpg_imagesize=get_option('efpg_imgsize');
$efpg_bound=get_option('efpg_bound');
$efpg_radius=get_option('efpg_rad');
$efpg_gallerystyle=get_option('efpg_gallerystyle');
$efpg_mdrange=get_option('efpg_mdrange');
if ($efpg_items != '' && ctype_digit($efpg_items)) {
	$mypage = $efpg_items;
} else {
	$mypage = 10;
}
if($efpg_gallerystyle=='paginate'){
	$cpage=isset($_GET['paged'])?$_GET['paged']:1;

} else{
	$cpage=1;
}
/**
 *  Code to get Photoset Images
 */
if ($efpg_source == 'photoset') {
	if ($efpg_user_api != '' && $efpg_user_id != '' && $efpg_photoset != '') {
		require_once(ROOT . '/files/photoset.php');
	}
}
/*
 *  Code to get Group Images
 */
if ($efpg_source == 'group') {
	if ($efpg_user_api != '' && $efpg_user_id != '' && $efpg_group != '') {
		require_once(ROOT . '/files/group.php');
	}
}
/*
 *  Code to get Gallery Images
 */
if ($efpg_source == 'gallery') {
	if ($efpg_user_api != '' && $efpg_user_id != '' && $efpg_gallery != '') {
		require_once(ROOT . '/files/gallery.php');
	}
}
/*
 *  Code to get Photostream Images
 */
if ($efpg_source == 'photostream') {
	if ($efpg_user_api != '' && $efpg_user_id != '') {
		require_once(ROOT . '/files/photostream.php');
	}
}
/**
 *  Code to get Taged Images
 */
if ($efpg_source == 'tags') {
	if ($efpg_user_api != '' && $efpg_user_id != '' && $efpg_tags != '') {
		require_once(ROOT . '/files/tags.php');
	}
}
if($efpg_gallerystyle=='paginate'){
	if ($efpg_mdrange != '' && ctype_digit($efpg_mdrange)) {
		$myrange = $efpg_mdrange;
	} else {
		$myrange = 2;
	}
	efpg_pagination($pages,$myrange);
} elseif($efpg_gallerystyle=='scroll'){
	$actual_row_count = intval($mypic['total']);
	?>
	<script>efpgpage.actual_count = parseInt(<?php echo $actual_row_count?>);
		efpgpage.page_count=parseInt(<?php echo $mypage; ?>)
	</script>
<?php } ?>
<style>
	.box {
		padding: <?php echo $efpg_bound ?>px;
		border-radius: <?php echo $efpg_radius ?>px;
	}
</style>

<div id='result' class="clearfix easy-flickr-photo-gallery">
	<?php
	if ($final) {
		$html = '';
		foreach ($final as $images) {
//
			$imgsize = $efpg_imagesize;
			$title = $images['title'];
			$img_src = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_m.jpg';
			$final_src = str_replace('_m', '_n', $img_src);
			$href = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_b.jpg';
			$html.='<div class="box photo ' . $imgsize . '">';
			$html .= '<a href="' . $href . '"  title= "' . $title . '" >
                 <img src="' . $final_src . '" /> 
                 </a>';
			$html.='</div>';
		}
		echo $html;
		echo "<div id='more' >Loading More Content</div>
<div id='no-more' >No More Content</div>";
		echo '</div>';
	}
	}



	function oscitas_infinite_scroll_start() {


		if (isset($_POST['update_details']) && $_POST['update_details'] == 'true') {
			infinite_scroll_options_update_details();
		}

		if (isset($_POST['update_flickr']) && $_POST['update_flickr'] == 'true') {
			infinite_scroll_options_update_gallery();

		}
		$efpg_user_api=get_option('efpg_api');
		$efpg_source=get_option('efpg_source');
		$efpg_user_id=get_option('efpg_id');
		$efpg_items=get_option('efpg_items');
		$efpg_group=get_option('efpg_group');
		$efpg_photoset=get_option('efpg_photoset');
		$efpg_gallery=get_option('efpg_gallery');
		$efpg_tags=get_option('efpg_tags');
		$efpg_imagesize=get_option('efpg_imgsize');
		$efpg_bound=get_option('efpg_bound');
		$efpg_radius=get_option('efpg_rad');
		$efpg_gallerystyle=get_option('efpg_gallerystyle');
		$efpg_mdrange=get_option('efpg_mdrange');
		$efpg_first=get_option('efpg_first');
		$efpg_last=get_option('efpg_last');
		$efpg_pre=get_option('efpg_pre');
		$efpg_next=get_option('efpg_next');
		$efpg_curltime=get_option('efpg_curltime');


		require_once(ROOT . '/files/settings.php');

	}
	?>
