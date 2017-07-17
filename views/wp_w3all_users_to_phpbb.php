<?php 

  $up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-users-to-phpbb';
 
  if ( !defined('PHPBB_INSTALLED') ){
   	die("<h2>Wp w3all miss phpBB configuration file:  please set the correct absolute path to phpBB by opening:<br /><br /> Settings -> WP w3all</h2>");

    } 
    
 	global $w3all_config,$wpdb;
  //$phpbb_config = WP_w3all_phpbb::wp_w3all_phpbb_config_init();
  $phpbb_config = unserialize(W3PHPBBCONFIG);
  $phpbb_config_file = $w3all_config;
  $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();

  if(!isset($_POST["start_select"])){
      $start_select = 0;
      $limit_select = 0;
      
       } else {
                $start_select = $_POST["start_select"] + $_POST["limit_select_prev"];
                $limit_select = $_POST["limit_select"];
             }

    if(isset($_POST["start_select"])){
      
      $args = array(
      'blog_id'      => $GLOBALS['blog_id'],
      'fields' => 'all',
    	'number'       => $limit_select,
    	'offset'       => $start_select,
    	'meta_query'   => array(),
    	'date_query'   => array(),     
	    'orderby'      => 'ID',
	    'order'        => 'ASC',
    	'count_total'  => false
);

$user_query = new WP_User_Query( $args ); 

if ( ! empty( $user_query->results ) ) {
	
	foreach ( $user_query->results as $wpu ) {

   if( $wpu->ID == 1 ){ 
 	        echo"<h4 style=\"color:brown\">The default install admin has NOT been added/imported into phpBB</h4>";
    }


  if( ($wpu->ID != 1) ){ 

/*
        $wpu_db_utab = $wpdb->prefix . 'usermeta';
	      $wp_ulang = $wpdb->get_var("SELECT meta_value FROM $wpu_db_utab WHERE user_id = '$wpu->ID' AND meta_key = 'locale'");
	   		if(empty($wp_ulang)){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
   		   $wp_lang_x_phpbb = 'en'; // no lang setting, assume en
   			} else { 
   				 $wp_lang_x_phpbb = strtolower(substr($wp_ulang, 0, strpos($wp_ulang, '_'))); // Lang code ISO Code that is phpBB suitable for this lang
   				}
*/

 $wplang = isset($wp_lang_x_phpbb) ? strtolower($wp_lang_x_phpbb) : 'en';

       $phpbb_user_type = ( empty($wpu->roles) ) ? '1' : '0'; // if no capabilities on WP, added as deactivated (1) on phpBB
       $wpu->user_registered = time($wpu->user_registered); // as phpBB do
	     $user_email_hash = sprintf('%u', crc32(strtolower($wpu->user_email))) . strlen($wpu->user_email); // as phpBB do
       $wpur = $wpu->user_registered;
       $wpul = $wpu->user_login;
       //$wpunn = $wpu->user_nicename;
       $wpup = $wpu->user_pass;
       $wpue = $wpu->user_email;
       $time = time();
       
       $wpunc = strtolower($wpul);
       
       $user_exist = $phpbb_conn->get_row("SELECT username FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$wpul'");
   
		  $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
        VALUES ('','$phpbb_user_type','2','','0','', '$wpur', '$wpul', '$wpunc', '$wpup', '$time', '$wpue', '$user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wplang', 'Europe/Rome', 'D M d, Y g:i a', '1', '0', '', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '', '', '0', '0', '', '', '', '', '', '', '', '0', '0', '0') ON DUPLICATE KEY UPDATE user_password = '$wpup',user_email = '$wpue',user_email_hash = '$user_email_hash'");
       
        $phpBBlid = $phpbb_conn->insert_id;
     
     if( $user_exist == null ){ // or will broken tables: there is no unique key in these tables
     	
       $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$phpBBlid','0','0')");
       $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");

       $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('$phpBBlid','0','0','6','0')");

	    echo "<b>Added user -> <span style=\"color:red\">". $wpu->user_login ."</span></b><br />";

     } else { 
     	       echo "<b>Overwritten existent user -> <span style=\"color:red\">". $wpu->user_login ."</span></b> (email and password)<br />";
            }
   }
}

      echo "<h2 style=\"color:brown\">Please continue adding WP users to phpBB by clicking the \"Continue to transfer WP users to phpBB\" button ...</h2>";

} else {
	      echo '<h1 style="margin-top:5.0em;color:green">No more WordPress users found. User\'s transfer to phpBB has been completed!</h1>';
	      echo '<h2>All users have been added on phpBB as default Registered users.<br /> Users with no-role on WordPress have been added as deactivated phpBB users. The WP install admin (uid 1) has been excluded from the transfer process.</h2>';
  
    }
}

 	$start_or_continue_msg = (!isset($_POST["start_select"])) ? 'Start transfer WP users to phpBB' : 'Continue to transfer WP users to phpBB';
  
 ?>
 
<div class="wrap" style="margin-top:4.0em;">
<div class=""><h1>Transfer WordPress Users to phpBB forum ( raw w3_all )</h1></div>
<h4><span style="color:red">Please Read</span>: do not put so hight value for users to transfer each time. It is set by default to 20 users x time, but you can change the value.<br />Try out: maybe 50, 100 or also 500 or more users to be added x time is ok for your system/server resources.<br />If error come out due to max execution time, it is necessary to adjust to a lower value the number of users to be added x time.<br />Refresh manually from browser: it will "reset the counter" of the transfer procedure.<br /> 
 Repeat the process by setting a lower value for users to be added x time: continue adding users until a <span style="color:green">green message</span> will display that the transfer has been completed.<br />After this, please remember to Fix phpBB values about <i>Total</i> and <i>Newest Members</i> here below, or do these two steps directly on phpBB ACP.<br />If there is an existent same username on phpBB, his email address and password are overwrite by the email address and password of the transferred WP user. The process exclude both WP and phpBB default install admins. 
 All users are added on phpBB as registered users if they have a role on WP, as deactivated in phpBB if no roles on WP.<br />Note: if some modification to the default phpBB database user's tables structure, this procedure will return error and nothing will be added into phpBB.</h4>
<form name="w3all_conf_add_users_to_phpbb" id="w3all-conf-add-users-to-phpbb" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<p>
 Transfer <input type="text" name="limit_select" value="20" /> users x time
  <input type="hidden" name="limit_select_prev" value="<?php echo $limit_select; ?>" />
  <input type="hidden" name="start_select" value="<?php echo $start_select;?>" /><br /><br />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></form></div>
<hr /><hr />


<div class="wrap" style="margin-top:3.0em;">

	<div class=""><h1>Fix phpBB values after users transfers</h1>
		
		<br /><b>Fix phpBB Total Members Counter</b><br /><br />
<?php 
if( isset($_POST["phpbb_fix_members_counter"]) ){
// phpBB: ID 0 guest ID 6 Bots
 $tot_users_count = $phpbb_conn->get_var("SELECT COUNT(*) FROM ".$phpbb_config_file["table_prefix"]."users WHERE group_id !='6' AND group_id !='1'");
 
 $phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$tot_users_count' WHERE config_name = 'num_users'");

 echo "<h1 style=\"color:green\">phpBB Total Members Counter value has been fixed</h1>";
}
?>

<form name="w3all_fix_phpbb_total_members_count" id="w3all-fix-total-members-count" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <input type="hidden" name="phpbb_fix_members_counter" value="1" />
<input type="submit" name="submit" id="submit" class="button button-primary" value="Fix phpBB Total members">
</form>
		</div>
<div class="">
		<br /><br /><br /><b>Fix phpBB Newest Member</b><br /><br />
<?php 
if( isset($_POST["phpbb_fix_newest_member"]) ){

$newest_member = $phpbb_conn->get_results(" SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$phpbb_config_file["table_prefix"]."users) AND group_id != '6'");
$uname = $newest_member[0]->username;
$uid   = $newest_member[0]->user_id;
$phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uname' WHERE config_name = 'newest_username'");
$phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");

	   echo "<h1 style=\"color:green\">phpBB Newest Member value has been fixed</h1>";
   
}
?>
<form name="w3all_fix_phpbb_newest_member" id="w3all-fix-total-members-count" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <input type="hidden" name="phpbb_fix_newest_member" value="1" />
<input type="submit" name="submit" id="submit" class="button button-primary" value="Fix phpBB Newest Member">
</form>
		</div>		
		
</div>