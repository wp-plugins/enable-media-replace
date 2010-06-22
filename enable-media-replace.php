<?php
/*
Plugin Name: Enable Media Replace
Plugin URI: http://www.mansjonasson.se/enable-media-replace
Description: Enable replacing media files by uploading a new file in the "Edit Media" section of the WordPress Media Library. 
Version: 2.1
Author: Måns Jonasson
Author URI: http://www.mansjonasson.se

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Developed for .SE (Stiftelsen för Internetinfrastruktur) - http://www.iis.se
*/

ini_set("display_errors", "on");


add_action( 'init', 'enable_media_replace_init' );
add_action('admin_menu', 'emr_menu');
add_filter('attachment_fields_to_edit', 'enable_media_replace', 10, 2);

add_shortcode('file_modified', 'emr_get_modified_date');


function emr_menu() {
	add_submenu_page('upload.php', __("Enable Media Replace", "enable-media-replace"), __("Replace media", "enable-media-replace"), 'upload_files', __FILE__, 'emr_options');
}


// Initialize this plugin. Called by 'init' hook.
function enable_media_replace_init() {
	load_plugin_textdomain( 'enable-media-replace', false, dirname( plugin_basename( __FILE__ ) ) );
	}

function enable_media_replace( $form_fields, $post ) {
	if ($_GET["attachment_id"]) {
		$editurl = get_bloginfo("wpurl") . "/wp-admin/upload.php?page=enable-media-replace/enable-media-replace.php&attachment_id={$_GET["attachment_id"]}";
		if (FORCE_SSL_ADMIN) {
			$editurl = str_replace("http:", "https:", $editurl);
		}
		$link = "href=\"$editurl\"";
		$form_fields["enable-media-replace"] = array("label" => __("Replace media", "enable-media-replace"), "input" => "html", "html" => "<p><a $link>" . __("Upload a new file", "enable-media-replace") . "</a></p>", "helps" => __("To replace the current file, click the link and upload a replacement.", "enable-media-replace"));
	}
	return $form_fields;
}

function emr_options() {
	if ( array_key_exists("attachment_id", $_GET) && $_GET["attachment_id"] > 0) {
		include("popup.php");
	}
	
	else {
	?>
	<div class="wrap">
		<h2>Enable media replace</h2>
		<p><?php _e("This plugin allows you to replace any uploaded media file by uploading a new one.", "enable-media-replace"); ?></p>
		<img src="<?php echo plugins_url("enable-media-replace/emr-list.png"); ?>" alt="Preview of Enable Media Replace link" />
		<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("First, locate the uploaded file you want to replace, using the", "enable-media-replace");?> <a href="<?php echo get_bloginfo("wpurl") . "/wp-admin/upload.php";?>"><?php _e("media library browser", "enable-media-replace");?></a>. <?php _e("Click the \"Edit\" link", "enable-media-replace");?>.</p>
		<img style="margin-top: 20px;" src="<?php echo plugins_url("enable-media-replace/emr-preview.png"); ?>" alt="Preview of Enable Media Replace link" />
		<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Second, click the link \"Upload a new file\" and follow the instructions.", "enable-media-replace");?></p>
	</div>
	
	<?php
	}
}

function emr_get_modified_date($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'format' => get_option('date_format') . " " . get_option('time_format'),
	), $atts));
	
	if ($id == '') return false;
     
    // Get path to file
	$current_file = get_attached_file($id, true);

	// Get file modification time     
	$filetime = filemtime($current_file);
	
	// Do timezone magic to get around UTC
	$timezone = date_default_timezone_get();
	date_default_timezone_set(get_option('timezone_string'));

	// do date conversion
	$content = date($format, $filetime);

	// Set timezone back to default
	date_default_timezone_set($timezone);
    
	return $content;
     
}


?>
