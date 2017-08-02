<div class="easy-flicker-setting">
<h2 class="page_head">Easy Flickr Galley Settings</h2>
<div id="intro" >
	Use shortcode <b>[efpg]</b> to show Flickr Gallery in posts or pages<br />
	OR <br />
	call this function in template file  <b> do_shortcode('[efpg]');</b>
</div>
<form method="POST" id="admin-details" action="" class="forms"><input type="hidden" name="update_details" value="true" />
	<div class="upper">
        <div class="options_head"><h2>Flickr User Details</h2></div>
		<div class="align"><label class="labels">Flickr Api</label>

			<input type="text" id="efpg_api" name="efpg_api"  value="<?php echo $efpg_user_api; ?>"> &nbsp;&nbsp;<font size='2'>Don't have a Flickr API Key?  Get it from <a href="http://www.flickr.com/services/api/keys/" target='blank'>here.</a> Go through the <a href='http://www.flickr.com/services/api/tos/'>Flickr API Terms of Service.</a></font>
		</div>
		<div class="align"><label class="labels">Flickr Username</label>
			<input type="text" id="id" name="efpg_user"  value="<?php echo get_option('efpg_user'); ?>">
		</div>

		<?php
		$invalid = infinite_scroll_invalid();
		echo $invalid;
		?>
		<div class="align">
			<input type="submit"  name="submit" id="b2" class="button button-primary" value="Submit" />
		</div>
	</div>
</form>
<form method="POST" id="admin_styles" class="forms" name="admin_styles" action=""><input type="hidden" name="update_flickr" value="true" />
<div class="upper">
<div class="options_head"><h2>Flickr Options</h2></div>
<div  class="align"><label class="labels">Curl time Out<small>(In Sec)</small></label>
	<input type="text"  name="efpg_curltime"  value="<?php
	if ($efpg_curltime != '') {
		echo $efpg_curltime;
	} else {
		echo 10;
	}
	?>">
</div>
<div id="options_left_src" class="align"><label class="labels">Source</label>

	<select name="efpg_source" id="source">
		<option value="def" disabled="disabled" selected="selected">Select Source</option>
		<option value="photoset" <?php
		if ($efpg_source == "photoset") {
			echo "selected";
		}
		?>>Photoset</option>
		<option value="gallery" <?php
		if ($efpg_source == "gallery") {
			echo "selected";
		}
		?>>Gallery</option>
		<option value="photostream" <?php
		if ($efpg_source == "photostream") {
			echo "selected";
		}
		?>>Photo Stream</option>
		<option value="group" <?php
		if ($efpg_source == "group") {
			echo "selected";
		}
		?>>Group</option>
		<option value="tags" <?php
		if ($efpg_source == "tags") {
			echo "selected";
		}
		?>>Tags</option>
	</select>
</div>

<?php

/**
 * code to get a dropdown list of photosets made by user on flickr
 */
if ($efpg_user_id != '') {

	$photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&format=php_serial';
	$rsp_obj = infinite_scroll_curl_call($photourl);
	$rsp_obj = unserialize($rsp_obj);
	if ($rsp_obj['stat'] == 'ok') {
		$photoset = $rsp_obj['photosets'];
		$tot = $photoset['total'];
		if ($tot == 0) {
			echo '<div id="efpg_photoset" class="align osc_src_option" ><label class="labels">Photosets</label><h4 class="error">Have have not created any Photoset yet.</h4></div>';
		} else {
			$try = $photoset['photoset'];
			?>


			<div id="efpg_photoset" class="align osc_src_option" ><label class="labels">Photosets</label>
				<select name="efpg_photoset"  >
					<option value="def1" disabled="disabled" selected="selected">Select Photoset</option>
					<?php
					foreach ($try as $photo) {
						$photoset_id = $photo['id'];
						$name = $photo['title'];
						$photoset_name = $name['_content'];
						?>
						<option value="<?php echo $photoset_id; ?>"  <?php
						if ($efpg_photoset == $photoset_id) {
							echo "selected";
						}
						?> ><?php echo $photoset_name; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php
		}
	}


/**
 * code to get a dropdown list of galleries made by user on flickr
 */

	$photourl = 'http://api.flickr.com/services/rest/?method=flickr.galleries.getList&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&format=php_serial';
	$rsp_obj = infinite_scroll_curl_call($photourl);
	$rsp_obj = unserialize($rsp_obj);
	if ($rsp_obj['stat'] == 'ok') {
		$photoset = $rsp_obj['galleries'];
	$tot = $photoset['total'];
		if ($tot == 0) {
			echo '<div id="efpg_gallery" class="align osc_src_option"><label class="labels">Galleries</label><h4 class="error">Have have not created any gallery yet.</h4></td></tr></div>';
		} else {
			$try = $photoset['gallery'];
			?>
			<div id="efpg_gallery" class="align osc_src_option"><label class="labels">Galleries</label>
				<select name="efpg_gallery" >
					<option value="def2" disabled="disabled" selected="selected">Select Gallery</option>
					<?php
					foreach ($try as $photo) {
						$photoset_id = $photo['id'];
						$name = $photo['title'];
						$photoset_name = $name['_content'];
						?>
						<option value="<?php echo $photoset_id; ?>" <?php
						if ($efpg_gallery == $photoset_id) {
							echo "selected";
						}
						?> ><?php echo $photoset_name; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php
		}
	}

/**
 * code to get images posted by user publically(photostream)
 */

	$photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&format=php_serial';
	$rsp_obj = infinite_scroll_curl_call($photourl);
	$rsp_obj = unserialize($rsp_obj);
	if ($rsp_obj['stat'] == 'ok') {
		$photoset = $rsp_obj['photos'];
		$tot = $photoset['total'];
		if ($tot == 0) {
			echo '<div id="efpg_photostream" class="align osc_src_option" ><h4 class="error">Have have not uploaded any images yet.</h4></div>';
		} else {
			$try = $photoset['photo'];
			?>
		<?php
		}
	}

/**
 * code to get Tagged Images
 */


	$photourl = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&tags=' . $efpg_tags . '&per_page=2&page=1&format=php_serial';
	$my_obj = infinite_scroll_curl_call($photourl);
	$my_obj = unserialize($my_obj);
	$mypic = $my_obj['photos'];
	$final = $mypic['photo'];
	//$final_count=count($final);
	?>
	<div id="efpg_tags" class="align osc_src_option"><label class="labels">Tag</label>
		<textarea rows="4" cols="50" id="tags" name="efpg_tags"><?php
			echo $efpg_tags;
			?></textarea>
		<?php
		echo '<div id="tag_intro">Enter tags separated by comma. For example: <b>tag1, tag2, tag3, tag4</b><br/>
Photos matching any of the given tags will be displayed.';
		if (count($final) == 0) {
			echo '<br/><span class="error">No Image is Tagged with this Tag.</span>';
		}
		echo '</div>';
		?>
	</div>
<?php



/**
 * code to get a dropdown list of groups made by user on flickr
 */


	$photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicGroups&api_key=' . $efpg_user_api . '&user_id=' . $efpg_user_id . '&format=php_serial';
	$rsp_obj = infinite_scroll_curl_call($photourl);
	$rsp_obj = unserialize($rsp_obj);
	if ($rsp_obj['stat'] == 'ok') {
		$photoset = $rsp_obj['groups'];

		$try = $photoset['group'];

		if (empty($try)) {
			echo '<div id="efpg_group" class="align osc_src_option"><label class="labels">Groups</label><h4 class="error">Have have not created any group yet.</h4></div>';
		} else {
			?>
			<div id="efpg_group" class="align osc_src_option"><label class="labels">Groups</label>
				<select name="efpg_group">
					<option value="def3" disabled="disabled" selected="selected">Select Group</option>
					<?php
					foreach ($try as $photo) {
						$photoset_id = $photo['nsid'];
						$photoset_name = $photo['name'];
						?>
						<option value="<?php echo $photoset_id; ?>" <?php
						if ($efpg_group == $photoset_id) {
							echo "selected";
						}
						?> ><?php echo $photoset_name; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php
		}
	}
}
?>

<!-- Field for Items per page  -->
<div id="items" class="align"><label class="labels">Items Per Page</label>
	<input type="text"  name="efpg_items"  value="<?php
	if ($efpg_items != '') {
		echo $efpg_items;
	} else {
		echo 10;
	}
	?>">
</div>
<div class="align"><label class="labels">Gallery style</label>
	<select name="efpg_gallerystyle" id="efpg_gallerystyle">
		<option value="scroll" <?php
		if ($efpg_gallerystyle == "scroll") {
			echo "selected";
		}
		?>  >Scroll</option>

		<option value="paginate" <?php
		if ($efpg_gallerystyle == "paginate") {
			echo "selected";
		}
		?>  >Paginate</option>
	</select>
</div>
<div class="gallerystyle_intro">
	If you select scroll option then new images will be loaded on page scroll<br/>
	If you select pgination option a you can navigate images by pagination<br/>
	For more details read <a href="http://wordpress.org/plugins/easy-flickr-gallery">Documentation</a>
</div>
<br class="efpg_paginate"/>
<div class="options_head efpg_paginate"><h2>Pagination Option</h2></div>
<!-- Field for Mid Range  -->
<div class="align efpg_paginate"><lable class="labels">Mid Range</lable>
	<input type="text" id="mdrange" name="efpg_mdrange" size="10" value="<?php
	if ($efpg_mdrange != '') {
		echo $efpg_mdrange;
	} else {
		echo 2;
	}
	?>">
</div>
<!-- Field for First button text  -->
<div class="align efpg_paginate"><lable class="labels">'First' Text</lable>
	<input type="text" id="first" name="efpg_first" size="10" value="<?php
	if ($efpg_first != '') {
		echo $efpg_first;
	} else {
		echo 'First';
	}
	?>">
</div>
<!-- Field for Last button text  -->
<div class="align efpg_paginate"><lable class="labels">'Last' Text</lable>
	<input type="text" id="last" name="efpg_last" size="10" value="<?php
	if ($efpg_last != '') {
		echo $efpg_last;
	} else {
		echo 'Last';
	}
	?>">
</div>
<!-- Field for Previous button text  -->
<div class="align efpg_paginate"><lable class="labels">'Previous' Text</lable>
	<input type="text" id="pre" name="efpg_pre" size="10" value="<?php
	if ($efpg_pre != '') {
		echo $efpg_pre;
	} else {
		echo 'Prev';
	}
	?>">
</div>
<!-- Field for Next button text  -->
<div class="align efpg_paginate"><lable class="labels">'Next' Text</lable>
	<input type="text" id="next" name="efpg_next" size="10" value="<?php
	if ($efpg_next != '') {
		echo $efpg_next;
	} else {
		echo 'Next';
	}
	?>">
</div>


<div class="options_head"><h2>Image Styling</h2></div>
<!-- Drop down list for Image themes  -->


<!-- Drop down list for Image sizes  -->
<div class="align"><label class="labels">Image Size</label>
	<select name="efpg_imgsize">
		<option value="col1" <?php
		if ($efpg_imagesize == "col1") {
			echo "selected";
		}
		?>  >Column 1</option>

		<option value="col2" <?php
		if ($efpg_imagesize == "col2") {
			echo "selected";
		}
		?>  >Column 2</option>

		<option value="col3" <?php
		if ($efpg_imagesize == "col3") {
			echo "selected";
		}
		?>  >Column 3</option>

		<option value="col4" <?php
		if ($efpg_imagesize == "col4") {
			echo "selected";
		}
		?>  >Column 4</option>

		<option value="col5" <?php
		if ($efpg_imagesize == "col5") {
			echo "selected";
		}
		?>  >Column 5</option>


	</select>
</div>
<div id="img_boundry" class="align"><label class="labels">Image Boundary</label>
	<input type="text"  name="efpg_bound"  value="<?php
	if ($efpg_bound != '') {
		echo $efpg_bound;
	} else {
		echo 5;
	}
	?>">
</div>
<div id="img_round" class="align"><label class="labels">Boundary  Radius</label>
	<input type="text"  name="efpg_rad"  value="<?php
	if ($efpg_radius != '') {
		echo $efpg_radius;
	} else {
		echo 5;
	}
	?>">
</div>


<div class="align">
	<input type="submit"  name="button2" id="button2" class="button button-primary button-large" value="Save" />
</div>
</div>
</form>
</div>
