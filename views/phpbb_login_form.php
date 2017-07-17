<?php // IFRAME MODE LINKS
if( get_option('w3all_iframe_phpbb_link_yn') == 1 ){ 
	$wp_w3all_forum_folder_wp = get_option( 'w3all_forum_template_wppage' );
	include_once 'login_form_include_iframe_mode_links.php';
} else { // NO IFRAME MODE LINKS
	include_once 'login_form_include_noiframe_mode_links.php';
}
?>
