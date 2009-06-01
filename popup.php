<?php

$wppath = str_replace("wp-content/plugins/enable-media-replace/popup.php", "", __FILE__);

require_once($wppath . "wp-load.php");
require_once($wppath . "wp-admin/admin.php");

if (!current_user_can('upload_files'))
	wp_die(__('You do not have permission to upload files.', 'enable-media-replace'));

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

global $wpdb;

$table_name = $wpdb->prefix . "posts";

$sql = "SELECT guid, post_mime_type FROM $table_name WHERE ID = {$_GET["attachment_id"]}";

list($current_filename, $current_filetype) = mysql_fetch_array(mysql_query($sql));

$current_filename = substr($current_filename, (strrpos($current_filename, "/") + 1));


?><html>
<head>
	<title><?=__("Replace media upload")?></titl>
	
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/global.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/wp-admin.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/colors-fresh.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/media.css?ver=20081210' type='text/css' media='all' />
</head>
<body id="media-upload">
<div class="wrap">
		<div id="icon-upload" class="icon32"><br /></div>
	<h2><?=__("Replace Media Upload", "enable-media-replace")?></h2>
	
	<form enctype="multipart/form-data" method="post" action="<?=get_bloginfo("wpurl") . "/wp-content/plugins/enable-media-replace/upload.php"?>">
		<input type="hidden" name="ID" value="<?=$_GET["attachment_id"]?>" />
		<div id="message" class="updated fade"><p><?=__("NOTE: You are about to replace the media file", "enable-media-replace")?> "<?=$current_filename?>". <?=__("There is no undo. Think about it!", "enable-media-replace")?></p></div>
	
		<p><?=__("Choose a file to upload from your computer", "enable-media-replace")?></p>
	
		<input type="file" name="userfile" />
		
		<p><?=__("Select media replacement type:", "enable-media-replace")?></p>
		
		<label for="replace_type_1"><input CHECKED id="replace_type_1" type="radio" name="replace_type" value="replace"> <?=__("Just replace the file", "enable-media-replace")?></label>
		<p class="howto"><?=__("Note: This option requires you to upload a file of the same type (", "enable-media-replace")?><?=$current_filetype?><?=__(") as the one you are replacing. The name of the attachment will stay the same (", "enable-media-replace")?><?=$current_filename?><?=__(") no matter what the file you upload is called.", "enable-media-replace")?></p>
		
		<label for="replace_type_2"><input id="replace_type_2" type="radio" name="replace_type" value="replace_and_search"> <?=__("Replace the file, use new file name and update all links", "enable-media-replace")?></label>
		<p class="howto"><?=__("Note: If you check this option, the name and type of the file you are about to upload will replace the old file. All links pointing to the current file (", "enable-media-replace")?><?=$current_filename?><?=__(") will be updated to point to the new file name.", "enable-media-replace")?></p>
		
		<input type="submit" class="button" value="<?=__("Upload", "enable-media-replace")?>" /> <a href="#" onclick="window.close();"><?=__("Cancel", "enable-media-replace")?></a>

	</form>
</div>
</body>
</html>