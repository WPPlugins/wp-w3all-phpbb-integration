<?php defined( 'ABSPATH' ) or die( 'not possible' ); ?>
<div style="text-align:right;padding:2.5em 2.5em 0;font-weight:900">Support plugin <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GUPQNQPZ6V9NG">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></div>

<?php
$config_file = get_option( 'w3all_path_to_cms' );
$config_avatars = get_option( 'w3all_conf_avatars' );
$w3all_config_avatars = unserialize($config_avatars);
$w3all_conf_pref = get_option( 'w3all_conf_pref' );
$w3all_conf_pref = unserialize($w3all_conf_pref);

if (defined('W3PHPBBCONFIG')) {
 $phpbb_config = unserialize(W3PHPBBCONFIG);
 // cookie check
 if($phpbb_config["cookie_domain"] != 'localhost' && $phpbb_config["cookie_domain"] != get_option( 'w3all_phpbb_cookie' )){
	update_option( 'w3all_phpbb_cookie', $phpbb_config["cookie_domain"] );
 }
}

$w3all_config_avatars['w3all_get_phpbb_avatar_yn'] = isset($w3all_config_avatars['w3all_get_phpbb_avatar_yn']) ? $w3all_config_avatars['w3all_get_phpbb_avatar_yn'] : 0;
$w3all_config_avatars['w3all_avatar_on_last_t_yn'] = isset($w3all_config_avatars['w3all_avatar_on_last_t_yn']) ? $w3all_config_avatars['w3all_avatar_on_last_t_yn'] : 0;
$w3all_config_avatars['w3all_lasttopic_avatar_dim'] = isset($w3all_config_avatars['w3all_lasttopic_avatar_dim']) ? $w3all_config_avatars['w3all_lasttopic_avatar_dim'] : 50;
$w3all_config_avatars['w3all_lasttopic_avatar_num'] = isset($w3all_config_avatars['w3all_lasttopic_avatar_num']) ? $w3all_config_avatars['w3all_lasttopic_avatar_num'] : 10;

$w3all_conf_pref['w3all_exclude_phpbb_forums'] = isset($w3all_conf_pref['w3all_exclude_phpbb_forums']) ? $w3all_conf_pref['w3all_exclude_phpbb_forums'] : '';
$w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] = isset($w3all_conf_pref['w3all_phpbb_user_deactivated_yn']) ? $w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] = isset($w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] = isset($w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']) ? $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] : 0;
$w3all_conf_pref['w3all_transfer_phpbb_yn'] = isset($w3all_conf_pref['w3all_transfer_phpbb_yn']) ? $w3all_conf_pref['w3all_transfer_phpbb_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_lang_switch_yn'] = isset($w3all_conf_pref['w3all_phpbb_lang_switch_yn']) ? $w3all_conf_pref['w3all_phpbb_lang_switch_yn'] : 0;
$w3all_conf_pref['w3all_get_topics_x_ugroup'] = isset($w3all_conf_pref['w3all_get_topics_x_ugroup']) ? $w3all_conf_pref['w3all_get_topics_x_ugroup'] : 0;

	       if (!empty($config_file)){
        
           $config_file =  get_option( 'w3all_path_to_cms' ) . '/config.php';
 	    
 	    	ob_start();
		     include( $config_file );
        ob_end_clean(); 
}

 if (isset( $_POST["w3all_conf"]["w3all_path_to_cms"] ) ){

 $config_file =  $_POST["w3all_conf"]["w3all_path_to_cms"] . '/config.php';
} 
   
    if ( !defined('PHPBB_INSTALLED') ){
     echo __('<h3 style="color:#ff0000">Before to activate the integration by setting the path to a phpBB config.php file, <a target="_blank" href="https://www.axew3.com/w3/2016/02/configure-phpbb-for-cookie-on-subdomains/">Setup the cookie setting into phpBB</a> and read the <a target="_blank" href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/">Install Help Page</a></h3>', 'wp-w3all-phpbb-integration');
     echo __('<h3 style="color:#ff0000">Wp w3all miss phpBB configuration file (or you have the phpBB config.php not well configured).</h3>', 'wp-w3all-phpbb-integration');
     echo __('<h3 style="color:#ff0000">Set the correct full ABSOLUTE PATH that need to point to a folder containing a valid phpBB config.php file!</h3>', 'wp-w3all-phpbb-integration');
     echo __('<h3 style="color:#ff0000">Notice: <span style="color:green">WP_w3all for subdomains installations.</span> Use the manual config option. If you choose to include/use the default phpBB config.php file that reside into real phpBB folder, and result impossible to correctly setup the wp_w3all config path here, please read this <a target="_blank" href="https://www.axew3.com/w3/2016/03/how-to-disable-open_basedir-for-subdomain-file-inclusion/">post about files inclusions restrictions</a>.</h3>', 'wp-w3all-phpbb-integration');
     }
    
$up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-options';


if (isset( $_POST["w3all_conf_pref_template_embed"]["w3all_forum_template_wppage"] ) ){

// if you want auto name your embedded board page with something different like 'myniceforum' add it here
$w3all_pages = array('board' => 'board', 'boards' => 'boards', 'community' => 'community', 'forums' => 'forums', 'forum' => 'forum');

$w3all_embed_page_name =  get_option( 'w3all_forum_template_wppage' );

 if( ! in_array($w3all_embed_page_name, $w3all_pages) && !empty($w3all_embed_page_name) ){
 		echo __('<h2 style="color:#ff0000">Error: please choose and set the correct name for template page. Available valid names are:<br /><i style="color:#000">board, boards, community, forum, forums</i><br />The page name <i style="color:#000">forum</i> has been created. You can repeat the process if you like another name.<br />Now it is necessary to create a blank WordPress page titled/named the same as you set this value.</h2>', 'wp-w3all-phpbb-integration');
 	  $w3all_embed_page_name = "forum";
}

 $w3all_emb_page = 'page-' . $w3all_embed_page_name . '.php';

 $w3all_page_td = get_template_directory() . '/' . $w3all_emb_page;

foreach ($w3all_pages as $page) {
   $w3all_emb_page_tg = get_template_directory() . '/' . 'page-' . $page . '.php';
    @unlink($w3all_emb_page_tg);
}

$w3fpath = WPW3ALL_PLUGIN_DIR . 'addons/page-forum.php';
$w3all_default_template = file_get_contents($w3fpath);
file_put_contents($w3all_page_td, $w3all_default_template);

}

if(!defined('PHPBB_INSTALLED')){
	$style_warn = 'color:#FF0000;';
} else {
	$style_warn = 'color:green;';
}
?>


<div class="wrap">

<div class=""><h1 style="color:green"><?php echo __('WP_w3all Path and Url configuration</h1>', 'wp-w3all-phpbb-integration'); ?></div>
<form name="w3all_conf" id="w3all-conf" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<hr />

<div class="">
<?php echo __('<b>Note:</b> This is the most important setting, the absolute path to a phpBB <i>config.php</i> file, that can be the <b>phpBB root <i>config.php</i> file</b> or a <b>custom <i>config.php</i> file</b> on a custom folder.  
WP_w3all require a correct <i>config.php</i> file to work: you can setup here the path to include the <b>root phpBB config.php file</b> OR a <b>manual edited custom config.php file</b>. Manual custom config.php has been introduced to get WP_w3all easy to be installed on subdomains, and for compatibility issues about some external plugin that seem to conflict with phpBB vars names as are on a <i>phpBB root config.php</i> default file: <b>it isn\'t strictly required</b>, but some external images plugin for example, require to choose manual custom config.php on WP_w3all to work as expected. 
<br /><br /><b>Manual custom config.php and path to config.php how to</b>: you can choose to use/include an <b>edited custom config.php file</b> OR the <b>phpBB root config.php</b> file. If you choose to use/include, the manual edited custom <i>config.php</i>, set the correct path to it after you have complete this easy procedure:
<br /><b><a href="https://www.axew3.com/w3/2016/09/how-to-setup-wp_w3all-manual-phpbb-config-php-file-and-path/" target="_blank">How to setup custom manual config.php and set correct path</a></b>

<br /><br /><b>Skip custom manual config.php, setup path to phpBB root config.php how to</b>: <b><a href="https://www.axew3.com/w3/index.php/forum/?viewforum=7&viewtopic=61" target="_blank">Path config how to</a></b>

<br /><br />Path Example to folder for manual custom phpBB config.php: <i>/web/htdocs/home/wp-content/plugins/<b>wp-w3all-config</b></i>
<br /><br />Path Example to phpBB root folder, for config.php on phpBB root folder: <i>/web/htdocs/home/<b>forum</b></i>
<br /><br />If you have choose to include/use the custom <i>wp-content/plugins/wp-w3all-config/<b>config.php</b></i> file, <b>edit it</b> before to apply the path value to file\'s folder here
<br />', 'wp-w3all-phpbb-integration'); ?>
<input id="w3all_path_to_cms" name="w3all_conf[w3all_path_to_cms]" type="text" size="25" value="<?php echo esc_attr( get_option('w3all_path_to_cms') ); ?>"> <b><span style="<?php echo $style_warn ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Path</b> - <b style="<?php echo $style_warn ?>"><?php echo __('Absolute path to config.php file on <b><i>wp-content/plugins/wp-w3all-config</i></b> OR phpBB root folder</b> - NOTE: do NOT add final slash \'/\' here', 'wp-w3all-phpbb-integration'); ?></div>
<hr />
<div class="">
<input id="w3all_url_to_cms" name="w3all_conf[w3all_url_to_cms]" type="text" size="25" value="<?php echo esc_attr( get_option('w3all_url_to_cms') ); ?>"><?php echo __(' <b>(REQUIRED) </span> URL</b> &nbsp;- Real phpBB URL - NOTE: do NOT add final slash \'/\' here. <strong>Example</strong>: http://www.axew3.com/forum', 'wp-w3all-phpbb-integration'); ?></div>
<hr />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save WP_w3all configuration', 'wp-w3all-phpbb-integration');?>">
<?php wp_nonce_field( 'w3all_conf_nonce', 'w3all_conf_nonce_f' ); ?>
</form></div>


<div class="wrap" style="margin-top:4.0em;">
<form name="w3all_conf_pref" id="w3all-conf-pref" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">	
<div class=""><h1 style="color:green">WP_w3all Preferences</h1></div>
<hr />
<div class=""><h3><?php echo __('Exclude phpBB forums from listing on Last Topics Posts', 'wp-w3all-phpbb-integration');?></h3></div>
<p><label""><input id="w3all_exclude_phpbb_forums" name="w3all_conf_pref[w3all_exclude_phpbb_forums]" type="text" size="25" value="<?php echo $w3all_conf_pref['w3all_exclude_phpbb_forums']; ?>"> <?php echo __('Comma separated, phpBB forums ID to be excluded from w3all Last Topics Posts widget</label><br /><b>Note</b>: if string contain a different sequence than <b>NumberCommaNumber</b> the option will not work (or return error inside the front end widget) <b>Correct example: 2,3,7,12,20</b>', 'wp-w3all-phpbb-integration');?></p>
<hr />
<div class=""><?php echo __('<h3>Retrieve posts on Last Topics Widget based on phpBB user\'s permissions</h3>', 'wp-w3all-phpbb-integration'); ?></div>
<?php echo __('If some forum require specific permissions to be viewed and user not belong to this specific group, posts/topics from these forums are not retrieved to be displayed into Last Tospics.', 'wp-w3all-phpbb-integration'); ?>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_get_topics_x_ugroup]" id="w3all_get_topics_x_ugroup_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_get_topics_x_ugroup']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_get_topics_x_ugroup]" id="w3all_get_topics_x_ugroup_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_get_topics_x_ugroup']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<div class=""><?php echo __('<h3>Deactivate phpBB user account until WP confirmation</h3>If this option is set to Yes, users are added in phpBB as <b><i>deactivated</i></b> when they register on WordPress. The phpBB user account will be <b><i>activated</i></b> only after his first login on WordPress. Normally it is not necessary and all will work as expected with users that you want to approve, before to be activated in WP/phpBB, but in case you can force this behavior by setting to yes this option.', 'wp-w3all-phpbb-integration');?>
<?php echo __('<br /><br /><b>Note</b>: this work only with default WP registration system where WP send an email link to set first user\'s password, that user do not know at this time. If you have install an external registration plugin that let choose the password to the user on register, than this option may will not affect. If your registration plugin provide option to let choose password or not on register for users, than set no the option, and all here should work as expected about WP/phpBB account confirmation/activation.', 'wp-w3all-phpbb-integration');?></div>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_user_deactivated_yn]" id="w3all_phpbb_user_deactivated_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_user_deactivated_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_user_deactivated_yn]" id="w3all_phpbb_user_deactivated_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_user_deactivated_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<div style="background-color:#FFF"><div style="padding:10px"><div class=""><?php echo __('<h3>Activate notify Read/Unread Topics/Posts into Last Topics widgets </h3>Set to <b>Yes</b>, to notify on Last Topics Widgets if listed topics are <i>read</i> or <i>unread Topics/Posts</i> on phpBB. This will affect only registered users.', 'wp-w3all-phpbb-integration'); ?>
<br /><b>Note</b>: may not suitable for iframe mode. The update status, if the WP iframe forum page display the sidebar last topics (in a custom two columns forum page template for example) will not update until the WP page reload. <b>Improved on next versions</b>.</div>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_mark_ru_yn]" id="w3all_phpbb_widget_mark_ru_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_mark_ru_yn]" id="w3all_phpbb_widget_mark_ru_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<div class=""><?php echo __('<h3>Activate notify Read/Unread Private Messages into Admin Tool Bar </h3>Display notification about new user\'s phpBB Private Messages into WP admin user\'s toolbar.', 'wp-w3all-phpbb-integration'); ?>
<br /> <b>Note</b>: to update status in iframe mode even without WP page reload, from 1.6.9 follow here instructions: <a href="https://www.axew3.com/w3/2017/04/wp_w3all-overall_footer-html-for-iframe-jsajax-pm-on-address-bar-and-right-click-copy-links/" target="_blank">Page forum ajax responsive</a>.</div>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_wptoolbar_pm_yn]" id="w3all_phpbb_wptoolbar_pm_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_wptoolbar_pm_yn]" id="w3all_phpbb_wptoolbar_pm_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
</div></div>
<hr />
<div class=""><?php echo __('<h3>Activate language update/switch on profile for users between WordPress and phpBB</h3>When user change language on profile, it will be so updated also on phpBB/WP.<br /><strong>Note: if same language not exist</strong> installed also into phpBB, (ex. an user switch on his WP profile to a language available into WordPress, but that has not been installed into phpBB) phpBB may will return error for this user on certain situations (on send out a PM for example)', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_lang_switch_yn]" id="w3all_phpbb_lang_switch_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_lang_switch_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_lang_switch_yn]" id="w3all_phpbb_lang_switch_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_lang_switch_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<div class=""><?php echo __('<h3>Activate WordPress to phpBB users transfer</h3>Once activated it will be visible in wordpress admin side menu under Settings Menu: when transfer has been finished, you can turn it off (and remove from admin side Settings Menu).', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_transfer_phpbb_yn]" id="w3all_transfer_phpbb_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_transfer_phpbb_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_transfer_phpbb_yn]" id="w3all_transfer_phpbb_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_transfer_phpbb_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save WP_w3all Preferences', 'wp-w3all-phpbb-integration');?>">
</form></div>


<div class="wrap" style="margin-top:4.0em;">
<form name="w3all_conf_pref" id="w3all-conf-pref" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">	
<div class=""><h1 style="color:green">WP_w3all Avatars Options (1.0)</h1></div>
<hr />
<div class=""><?php echo __('<h3>Use phpBB avatar to replace WordPress user\'s avatar</h3>If set to Yes, Gravatars profiles images on WordPress, are replaced by phpBB user\'s avatars images, where an avatar\'s image is available in phpBB for the user. Return WP Gravatar of the user, if no avatar\'s image has been found in phpBB (one single fast query to get avatars for all users).
<br /><b>Note</b>: you can activate only this option, if you do not want to display user\'s avatars on WP_w3all Last Forum Topics Widgets, but only on WP posts.
<br /><b>If this option is set to No (not active) others avatar\'s options <i>Last Forums Topics widgets</i> here below, do not affect</b>.
<br />Note about .htaccess: <b>"i\'ve setup avatar here, but avatar\'s images aren\'t displayed!" ... please <a href="https://www.axew3.com/w3/2016/09/phpbb-htaccess-set-avatars-images-available-over-your-domain/" target="_blank">take a look to this easy <i>why and how to resolve</i> post</a></b> before you activate this feature.
<br />Check that on <i>WordPress Admin -> Settings -> Discussion</i> the setting about avatars is enabled. Check also that isn\'t set to BLANK this setting (if you do not want really it).', 'wp-w3all-phpbb-integration'); ?>
<p><label""><input type="radio" name="w3all_conf_avatars[w3all_get_phpbb_avatar_yn]" id="w3all_conf_pref_avatar_1" value="1" <?php checked('1', $w3all_config_avatars['w3all_get_phpbb_avatar_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_avatars[w3all_get_phpbb_avatar_yn]" id="w3all_conf_pref_avatar_0" value="0" <?php checked('0', $w3all_config_avatars['w3all_get_phpbb_avatar_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<div style="padding:20px 35px;background-color:#fff;border-top:2px solid #869eff;border-bottom:2px solid #869eff">
<div class=""><?php echo __('<h3 style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</h3>Add avatars for each user on Last Forums Topics widget (add a query, for each loaded widget on page)', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input type="radio" name="w3all_conf_avatars[w3all_avatar_on_last_t_yn]" id="w3all_avatar_on_last_t_1" value="1" <?php checked('1', $w3all_config_avatars['w3all_avatar_on_last_t_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_avatars[w3all_avatar_on_last_t_yn]" id="w3all_avatar_on_last_t_0" value="0" <?php checked('0', $w3all_config_avatars['w3all_avatar_on_last_t_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<div class=""><?php echo __('<h3>Last Forums Topics Widget avatar\'s dimension</h3>Set the avatar dimension for Last Forum Topics Widget (Ex: 50).<br />Note: affect only if the above <i style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</i> option is set to yes', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input id="w3all_lasttopic_avatar_dim" name="w3all_conf_avatars[w3all_lasttopic_avatar_dim]" type="text" size="25" value="<?php echo esc_attr( $w3all_config_avatars['w3all_lasttopic_avatar_dim'] ); ?>"></p>
<hr />
<div class=""><?php echo __('<h3>Last Forums Topics number of users\'s avatars to retrieve</h3><b>Notice</b>: if not set, 10 by default, but this value should be set the same as is the most hight value, of topic\'s numbers you have choose to display on Last Topics Widgets, for example:<br />if activating different Last Forums Topics widgets, you have choose to display 5 topics in one widget instance, and 10 topics in another, than set 10 as value here.<br />Note: affect only if the above <i style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</i> option is set to yes', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input id="w3all_lasttopic_avatar_num" name="w3all_conf_avatars[w3all_lasttopic_avatar_num]" type="text" size="25" placeholder="10" value="<?php echo esc_attr( $w3all_config_avatars['w3all_lasttopic_avatar_num'] ); ?>"></p>
</div><!-- close <div style="padding:20px 35px -->
<hr />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save WP_w3all Avatars Options', 'wp-w3all-phpbb-integration');?>">
</form></div>


<div class="wrap" style="margin-top:4.0em;">
<form name="w3all_conf_pref_template_embed" id="w3all-conf-pref-template-embed" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">	
<div class=""><h1 style="color:green">WP_w3all phpBB embedded on WordPress Template</h1></div>
<hr style="border-color:gray" />
<div class=""><?php echo __('<h4 style="color:#333">Before you activate this option, <b><a href="https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/" target="_blank">please read this article</a></b><br />it is necessary to edit the phpBB overall_footer.html template file, and to add the "iframeResizer.contentWindow.min.js" file into phpBB root folder.
<br />Note: you can completely ignore this part about using iframe mode and use wp_w3all without embed phpBB template in a WP page.<br />Please remember about widgets login/out and last post: activate this option if you use the iframe mode, deactivate if you are not going to be using the WP_w3all iframe mode, or widget links will points to wrong urls (page name option not affected)</h4></div>
<div class=""><h3>Create or rebuild WordPress forum page template</h3>', 'wp-w3all-phpbb-integration'); ?></div>
<p><label""><input id="w3all_forum_template_wppage" name="w3all_conf_pref_template_embed[w3all_forum_template_wppage]" type="text" size="25" value="<?php echo get_option('w3all_forum_template_wppage'); ?>"><?php echo __(' Valid names to be used are: <b>board, boards, community, forum, forums</b>. Do not use different terms or <i>page-<b>forum</b>.php</i> will be created by default.<br />
<br />This option set the name of (and create) the page template that will embed the phpBB forum iframe on WordPress.<br />It is required to create a new BLANK page on WordPress (WP admin -> Pages -> Add New), with the same title as set here that will contain the embedded iframe phpBB  forum on WordPress. Ex: if you entered "board" as the value you will need to create a new page in wp named board. Open this page after to see your embedded phpbb forum in WP.
<br /><br />The created template file will be located inside your WordPress <b>wp-content/themes/yourtheme</b> template folder. It can be edited as any other WordPress template page.
<br /><br />The template file name to search for, inside the active theme, template directory, can be: <b>page-forum.php</b> or <b>page-board.php</b>, and so on, depending on how you set the value here.<br />
<b>Note:</b> if there is not a created <i>page-forum(or board etc).php</i> file into your active Wp template folder, manually copy it in <i>plugins/wp-w3all-phpbb-integration/addons</i> and paste Or upload into your WP template folder. Rename it as needed if necessary (so into <i>page-board.php</i> if you set <i>board</i> as name here).<br />
<b>Note:</b> the page name here is a required value to be set for iframe mode (as well you need to create a blank page in <i>WP -> pages -> Add New</i>).

<br /><br /><b>Warning (for on same domain installations)</b>: if your forum folder is located on a sub-folder on the same WP root, like <b>wordpress/forum</b></i> in this case it is required to choose a different name than <i>forum</i> for the template page to be created here. If not, WordPress will point to the existent <i>forum folder</i> and will return content not found. It is a normal WP behavior.
<br /><br /><b>Warning</b>: Any click on "Create WP_w3all phpBB Page Template" button, will replace the template page with the default content file: the previous created template page will be removed and substituted with the default content file. In case you made modifications to the template page after its his creation, and that you do not want to lose, you should rename or move the template file in some different folder than the theme template folder, before you click on "Create WP_w3all phpBB Page Template" button.</p>', 'wp-w3all-phpbb-integration'); ?>
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Create/Rebuild WP_w3all phpBB Page Template', 'wp-w3all-phpbb-integration');?>">
</form></div>

<div class="wrap" style="margin-top:4.0em;">
<form name="w3all_conf_pref_template_embed_link" id="w3all-conf-pref-template-embed-link" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">	
<div class=""><h3><?php echo __('Last Topics and Login widgets links for embedded phpBB iframe into WordPress', 'wp-w3all-phpbb-integration'); ?></h3></div>
<?php echo __('Changes the links for wp_w3all Last Topics Post and wp_w3all Login widgets to embedded page:<br />if set to Yes, it changes the links on <i>Last Topics Posts Widget and Login/out links</i> that will point to the created WP page that contain the embedded phpBB forum iframe, if set to No it will link to the real phpbb URL/folder.', 'wp-w3all-phpbb-integration'); ?>
<p><label""><input type="radio" name="w3all_conf_pref_template_embed_link[w3all_iframe_phpbb_link_yn]" id="w3all_iframe_phpbb_link_1" value="1" <?php checked('1', get_option('w3all_iframe_phpbb_link_yn')); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref_template_embed_link[w3all_iframe_phpbb_link_yn]" id="w3all_iframe_phpbb_link_0" value="0" <?php checked('0', get_option('w3all_iframe_phpbb_link_yn')); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Last Topics and Login/Out widgets links for Embedded Template', 'wp-w3all-phpbb-integration');?>">
</form><br /><hr style="border-color:gray" /></div>