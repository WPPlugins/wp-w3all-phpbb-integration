<?php
/**
 * @package wp_w3all
 */
/*
Plugin Name: WordPress w3all phpBB integration
Plugin URI: http://axew3.com/w3
Description: Integration plugin between WordPress and phpBB. It provide free integration - users transfer/login/register. Easy, light, secure, powerful. 
Version: 1.7.3
Author: axew3
Author URI: http://www.axew3.com/w3
License: GPLv2 or later
Text Domain: wp-w3all-phpbb-integration
Domain Path: /languages/

=====================================================================================
Copyright (C) 2017 - axew3.com
=====================================================================================
*/

// Security
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if ( defined( 'W3PHPBBUSESSION' ) OR defined( 'W3PHPBBLASTOPICS' ) OR defined( 'W3PHPBBCONFIG' ) OR defined( 'W3UNREADTOPICS' ) OR defined( 'W3ALLPHPBBUAVA' ) ):
	echo 'Sorry, something goes wrong';
exit;
endif;

define( 'WPW3ALL_VERSION', '1.7.3' );
define( 'WPW3ALL_MINIMUM_WP_VERSION', '4.0' );
define( 'WPW3ALL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPW3ALL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Force here the reset of cookie domain (removing the two chars // in front on next line)
// $w3reset_cookie_domain = '.mydomain.com'; // change to fit THE SAME COOKIE DOMAIN SETTING as you have set it in phpBB config (note the dot in front). To RESET/force cookie domain setting: remove // chars in front of this line, edit and save, than load any WP page one time. So comment out other time this line re-adding // chars and save.

// Force Deactivation WP_w3all plugin // to clean uninstall if something goes wrong
// $w3deactivate_wp_w3all_plugin = 'true';

$w3all_w_lastopicspost_max = get_option( 'widget_wp_w3all_widget_last_topics' );
$config_avatars = get_option('w3all_conf_avatars');
$w3all_conf_pref = get_option('w3all_conf_pref');
$w3cookie_domain = get_option('w3all_phpbb_cookie');

if(empty($w3cookie_domain) && !isset($w3reset_cookie_domain)){
 $w3cookie_domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
 $w3cookie_domain0 = $w3cookie_domain;
 
 if( preg_match('/[^\w\.-]/',$w3cookie_domain) OR preg_match('/[^\w\.-]/',$w3cookie_domain0) ){
	$w3cookie_domain = $w3cookie_domain0 = '';
}
 
  if( !empty($w3cookie_domain) ){
   	$w3cookie_domain = w3all_extract_cookie_domain( $w3cookie_domain );
  	 if ( preg_match('/[^-\w\.]/',$w3cookie_domain) ){
       $w3cookie_domain = preg_replace('/^[^\.]*\.([^\.]*)\.(.*)$/', '\1.\2', $w3cookie_domain0);
     } else { 
  	          update_option( 'w3all_phpbb_cookie', $w3cookie_domain );
  	        }
  }
}

if(isset($w3reset_cookie_domain)){
	update_option( 'w3all_phpbb_cookie', $w3reset_cookie_domain );
	$w3cookie_domain = $w3reset_cookie_domain;
}

  $useragent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? esc_sql($_SERVER['HTTP_USER_AGENT']) : 'unknown';

   $w3all_config_avatars = unserialize($config_avatars);
   $w3all_get_phpbb_avatar_yn = isset($w3all_config_avatars['w3all_get_phpbb_avatar_yn']) ? $w3all_config_avatars['w3all_get_phpbb_avatar_yn'] : '';
   $w3all_last_t_avatar_yn = isset($w3all_config_avatars['w3all_avatar_on_last_t_yn']) ? $w3all_config_avatars['w3all_avatar_on_last_t_yn'] : '';
   $w3all_last_t_avatar_dim = isset($w3all_config_avatars['w3all_lasttopic_avatar_dim']) ? $w3all_config_avatars['w3all_lasttopic_avatar_dim'] : '';
   $w3all_lasttopic_avatar_num = isset($w3all_config_avatars['w3all_lasttopic_avatar_num']) ? $w3all_config_avatars['w3all_lasttopic_avatar_num'] : '';

   $w3all_conf_pref = unserialize($w3all_conf_pref);
   $w3all_transfer_phpbb_yn = isset($w3all_conf_pref['w3all_transfer_phpbb_yn']) ? $w3all_conf_pref['w3all_transfer_phpbb_yn'] : '';
   $w3all_phpbb_widget_mark_ru_yn = isset($w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] : '';
   $w3all_phpbb_user_deactivated_yn = isset($w3all_conf_pref['w3all_phpbb_user_deactivated_yn']) ? $w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] : '';
   $w3all_phpbb_wptoolbar_pm_yn = isset($w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']) ? $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] : '';  
   $w3all_exclude_phpbb_forums = isset($w3all_conf_pref['w3all_exclude_phpbb_forums']) ? $w3all_conf_pref['w3all_exclude_phpbb_forums'] : '';  
   $w3all_phpbb_lang_switch_yn = isset($w3all_conf_pref['w3all_phpbb_lang_switch_yn']) ? $w3all_conf_pref['w3all_phpbb_lang_switch_yn'] : 0;
   $w3all_get_topics_x_ugroup = isset($w3all_conf_pref['w3all_get_topics_x_ugroup']) ? $w3all_conf_pref['w3all_get_topics_x_ugroup'] : 0;

   // The follow get the max number of topics to retrieve
   // it is passed on private static function last_forums_topics($ntopics = 10){
   // to define W3PHPBBLASTOPICS when 'at MAX'
   // so it is used on class.wp.w3all.widgets-phpbb.php 
   // inside public function wp_w3all_phpbb_last_topics($post_text, $topics_number, $text_words) {
   // to avoid another call if possible

   if(!empty($w3all_w_lastopicspost_max)){
     foreach ($w3all_w_lastopicspost_max as $row) {
        if(isset($row['topics_number'])){
        	$w3all_wlastopicspost_max[] = $row['topics_number'];
        }
     }
      $w3all_wlastopicspost_max = isset($w3all_wlastopicspost_max) && is_array($w3all_wlastopicspost_max) ? max($w3all_wlastopicspost_max) : 0;
    } else { $w3all_wlastopicspost_max = 0; }
                
if ( defined( 'WP_ADMIN' ) ) {

	function w3all_VAR_IF_U_CAN(){
    if ( !current_user_can( 'manage_options' ) && isset( $_POST["w3all_conf"]["w3all_url_to_cms"]) OR !current_user_can( 'manage_options' ) && isset( $_POST["w3all_conf"]["w3all_path_to_cms"] ) ) {
    	 unset($_POST);
    	die('<h3>you can\'t perfom this action.</h3>');
    }
    
     if ( isset($_POST["w3all_conf"]["w3all_url_to_cms"]) ){
    	$_POST["w3all_conf"]["w3all_url_to_cms"] = utf8_encode($_POST["w3all_conf"]["w3all_url_to_cms"]);
      $_POST["w3all_conf"]["w3all_url_to_cms"] = trim($_POST["w3all_conf"]["w3all_url_to_cms"]);
     }
     
     if ( isset($_POST["w3all_conf"]["w3all_path_to_cms"]) ){
    	$_POST["w3all_conf"]["w3all_path_to_cms"] = utf8_encode($_POST["w3all_conf"]["w3all_path_to_cms"]);
    	$_POST["w3all_conf"]["w3all_path_to_cms"] = trim($_POST["w3all_conf"]["w3all_path_to_cms"]);
    	$up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-options';
	     wp_redirect( $up_conf_w3all_url );
    	$config_file = $_POST["w3all_conf"]["w3all_path_to_cms"] . '/config.php';  
       ob_start();
		    include( $config_file );
       ob_end_clean(); 
     }
  }
  
	 add_action( 'init', 'w3all_VAR_IF_U_CAN' );
    
            	// or will search for some config file elsewhere instead
                	$w3all_path_to_cms = get_option( 'w3all_path_to_cms' );
	                if(!empty($w3all_path_to_cms)){    
                   $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
                     	ob_start();
		                   include( $config_file );
                      ob_end_clean(); 
                 }
    
 if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){
 
  if ( defined('WP_W3ALL_MANUAL_CONFIG') ){
  	
  	        $w3all_config = array(
                  'dbms'     => $w3all_dbms,
                  'dbhost'   => $w3all_dbhost,
                  'dbport'   => $w3all_dbport,
                  'dbname'   => $w3all_dbname,
                  'dbuser'   => $w3all_dbuser,
                  'dbpasswd' => $w3all_dbpasswd,
                  'table_prefix' => $w3all_table_prefix,
                  'acm_type' => $w3all_acm_type 
                  );
      	
  } else { 
      
        $w3all_config = array(
                  'dbms'     => $dbms,
                  'dbhost'   => $dbhost,
                  'dbport'   => $dbport,
                  'dbname'   => $dbname,
                  'dbuser'   => $dbuser,
                  'dbpasswd' => $dbpasswd,
                  'table_prefix' => $table_prefix,
                  'acm_type' => $acm_type 
                  );
            }      
     
    
      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' );
      add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_phpbb_config_res'), 1); // before any other
      }
      
      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-admin.php' );
		  require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );	
	  	add_action( 'init', array( 'WP_w3all_admin', 'wp_w3all_init' ) );
	   
 if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){
     
	    add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_conn_init' ) );

	   // for the user profile update, when profile changes done by user on phpBB and go directly to visit his profile on WP
     add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init' ), 2 );

    function wp_w3all_phpbb_registration_save( $user_id ) {
     
     if ( is_multisite() ) { return; } // or get error on activating MUMS user ... msmu user will use a different way
     
      $wpu  = get_user_by('id', $user_id);
      
      if(!$wpu){ return; }
      
       $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($wpu->user_login, $wpu->user_email, 1);
 
         if($wp_w3_ck_phpbb_ue_exist === true){
         	
           wp_delete_user( $user_id ); // remove WP user just created, username or email exist on phpBB
          
           temp_wp_w3_error_on_update();
           exit;  // REVIEW // REVIEW // add_action( 'admin_notices', 
        
         }
         
       if(!$wp_w3_ck_phpbb_ue_exist){
         $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
       }
 }

// review this
function wp_w3all_up_phpbb_prof($user_id, $old_user_data) {

   $phpBB_upp = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data);
   
     if($phpBB_upp === true){
     	// REVIEW TO BE CHANGED: PAGE ADMIN WITH WP ERRORS
      temp_wp_w3_error_on_update();
      exit;
 
    }
}
   
   // stuff about profile changes WP to phpBB
 add_action( 'profile_update', 'wp_w3all_up_phpbb_prof', 10, 2 );
 add_action( 'user_register', 'wp_w3all_phpbb_registration_save', 10, 1 );
 add_action( 'delete_user', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_delete_user' ) ); // just deactivated on phpBB, when deleted in WP

 add_action( 'set_logged_in_cookie', 'wp_w3all_user_session_set', 10, 5 );
 
 if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
 add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pm
 }
 
function wp_w3all_user_session_set( $logged_in_cookie, $expire, $expiration, $user_id, $scheme ) {
	$user = get_user_by( 'ID', $user_id );
    $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user); 
    return;
}

} // if defined phpbb installed end


	//register_activation_hook( __FILE__, array( 'WP_w3all_admin', '' ) );
	// TODO maybe wrap this for admin only
    register_uninstall_hook( __FILE__, array( 'WP_w3all_admin', 'clean_up_on_plugin_off' ) );

} else { // not in admin
	
	// or will search for some config file elsewhere instead
	$w3all_path_to_cms = get_option( 'w3all_path_to_cms' );
	if(!empty($w3all_path_to_cms)){ 
   $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
    ob_start();
		 include( $config_file );
    ob_end_clean();       
   }
    
  if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){ 

  if ( defined('WP_W3ALL_MANUAL_CONFIG') ){
  	
  	        $w3all_config = array(
                  'dbms'     => $w3all_dbms,
                  'dbhost'   => $w3all_dbhost,
                  'dbport'   => $w3all_dbport,
                  'dbname'   => $w3all_dbname,
                  'dbuser'   => $w3all_dbuser,
                  'dbpasswd' => $w3all_dbpasswd,
                  'table_prefix' => $w3all_table_prefix,
                  'acm_type' => $w3all_acm_type 
                  );
      	
  } else { 

  $w3all_config = array(
                  'dbms'=> $dbms,
                  'dbhost'   => $dbhost,
                  'dbport'   => $dbport,
                  'dbname'   => $dbname,
                  'dbuser'   => $dbuser,
                  'dbpasswd' => $dbpasswd,
                  'table_prefix' => $table_prefix,
                  'acm_type' => $acm_type 
                  );
	
   }

     $phpbb_on_template_iframe = get_option( 'w3all_iframe_phpbb_link_yn' );
     $wp_w3all_forum_folder_wp = get_option( 'w3all_forum_template_wppage' ); // remove from iframe mode links on last topics than
     $w3all_url_to_cms         = get_option( 'w3all_url_to_cms' );
  
	   require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' ); 
     require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );
  
      add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_phpbb_config_res'), 1); // before any other wp_w3all
  
      add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init'), 2);
      
     if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
      add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pm
     }
     
//// workaround for some plugin that substitute wp-login.php default login page
//// it is not strictly required, but used by default for compatibilities with some external plugin

//require_once( WPW3ALL_PLUGIN_DIR . 'addons/ext_plugins_fixes.php' ); // this is not more needed, since addition of add_action( 'set_logged_in_cookie', 'w3all_user_session_set', 10, 5 ); more above (on IF wp_admin code)
  
//// END workaround for some plugin that substitute wp-login.php default login page

  add_filter( 'registration_errors', 'wp_w3all_check_fields', 10, 3 ); // this prevent any user addition, if phpBB email or username already exist in phpBB
  add_action( 'user_register', 'wp_w3all_phpbb_registration_save2', 10, 1 );
  add_action( 'after_password_reset', 'wp_w3all_wp_after_password_reset', 10, 2 ); 

function wp_w3all_check_fields( $errors, $sanitized_user_login, $user_email ) {
      
      global $wpdb;
      
     $test = WP_w3all_phpbb::phpBB_user_check2($errors, $sanitized_user_login, $user_email);
           
      if($test === true){
      	  $errors->add( 'w3all_user_exist', __( '<strong>ERROR</strong>: provided email or username already exist on our forum database.', 'wp-w3all-phpbb-integration' ) );
          return $errors;
           temp_wp_w3_error_on_update(); // not more needed, due the above
          exit;
      }
      
      return $errors;
}


function wp_w3all_wp_after_password_reset($user, $new_pass) {
     $phpBB_user_pass_set = WP_w3all_phpbb::phpbb_pass_update_res($user, $new_pass); 
     $phpBB_user_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset($user); 
}

function wp_w3all_phpbb_registration_save2( $user_id ) {

     $wpu = get_user_by('ID', $user_id);
     
           $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($wpu->user_login, $wpu->user_email, 0);

         if($wp_w3_ck_phpbb_ue_exist === true){

   if (function_exists('wp_delete_user')) {
   
      wp_delete_user( $user_id ); // remove WP user if just created, username or email exist on phpBB

          temp_wp_w3_error_on_update();
          exit;  // REVIEW // REVIEW // 
         }  
        }
        
     if( !$wp_w3_ck_phpbb_ue_exist ){
          $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
        }

}

// a phpBB user not logged into phpBB, WP login first time 
add_action( 'wp_authenticate', array( 'WP_w3all_phpbb', 'w3_check_phpbb_profile_wpnu' ), 10, 1 );  

add_action( 'wp_logout', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_logout' ) );

  
  function wp_w3all_phpbb_login($user_login, $user = '') {
   // add_action('wp_login', 'wp_w3all_phpbb_login', 10, 2);
   // has been moved also inside function -> wp_check_password // more below
     $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user); 
   }
   
 // if (!isset($w3all_on_ext_login) ) {
   add_action('wp_login', 'wp_w3all_phpbb_login', 10, 2);
 // }
  
  
   function wp_w3all_up_wp_prof_on_phpbb($user_id, $old_user_data) {
    	
     $phpBB_user_up_prof_on_wp_prof_up = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data); 
     
             if($phpBB_user_up_prof_on_wp_prof_up === true){
         
        temp_wp_w3_error_on_update();
        exit;
      }
   }

  add_action( 'profile_update', 'wp_w3all_up_wp_prof_on_phpbb', 10, 2 );
  


if ( $w3all_phpbb_widget_mark_ru_yn == 1 ) {
  add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_unread_topics'), 9);
}

// this affect the lost password url on WP
// comment out the follow 'add_filter' line to set lost password link point to phpbb lost password page
//add_filter( 'lostpassword_url', 'phpbb_reset_pass_url', 10, 2 ); 

// this affect the register url on WP
// comment out the follow 'add_filter' line to set register link point to phpbb register
//add_filter( 'register_url', 'phpbb_register_url' ); 

  
  function phpbb_reset_pass_url( $lostpassword_url, $redirect ) {
   	
   	global $w3all_url_to_cms, $phpbb_on_template_iframe, $wp_w3all_forum_folder_wp;
   	
    if( $phpbb_on_template_iframe == 1 ){ // lost pass phpBB link iframe mode
    	
   	    $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   	   	$redirect = $wp_w3all_forum_folder_wp . '/?mode=sendpassword';
   	   return $redirect;
   	   	 
      } else { // lost pass no iframe
    	
   	           $redirect = $w3all_url_to_cms . '/ucp.php?mode=sendpassword';
               return $redirect;
             }
   }
   

    
     function phpbb_register_url( $register_url ) {
   	   	global $w3all_url_to_cms, $phpbb_on_template_iframe, $wp_w3all_forum_folder_wp;
   	
       if( $phpbb_on_template_iframe == 1 ){ 
    	
   	       $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   	     	 $redirect = $wp_w3all_forum_folder_wp . '/?mode=register';
   	      return $redirect;
   	   	 
         } else { // register no iframe, direct link to phpBB
    	
   	             $redirect = $w3all_url_to_cms . '/ucp.php?mode=register';
                 return $redirect;
               }

      }

   } // end PHPBB_INSTALLED
} // end not in admin

  if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){ 
  	
// signup common check: on signup user check for duplicate // check for $_POST vars passed in case if need to add something for some else
// this has been added for Buddypress compatibility, but work for any signup fired

add_filter( 'validate_username', 'w3all_on_signup_check', 10, 2 ); 

  	function w3all_on_signup_check( $valid, $username ) { 
  		
  		if(isset($_POST['signup_username']) && isset($_POST['signup_email'])){
  			  $username = sanitize_user( $_POST['signup_username'], $strict = false ); 
  			  $email = sanitize_email($_POST['signup_email']);
  			  if ( !is_email( $email ) ) {
          echo $message = __( '<h3>Error: email address not valid.</h3><br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Please return back', 'wp_w3all_phpbb' ) . '</a><h4>';
           exit;
         }
  			  $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($username, $email, 0);
         if($wp_w3_ck_phpbb_ue_exist === true){
            temp_wp_w3_error_on_update();
          exit;
         }  
  		}
  		
  	return $valid; 	
}

// END signup common check

 function temp_wp_w3_error_on_update(){
     	
     		$class = 'notice notice-error';
	      echo $message = __( '<h3>Error: username or email already exist</h3> The username or/and email address provided already exist, or result associated, to another existent user account on our forum database.<br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Please return back', 'wp_w3all_phpbb' ) . '</a><h4>';
    
     }

    
 function wp_w3all_toolbar_new_phpbbpm( $wp_admin_bar ) {
		global $wp_w3all_forum_folder_wp,$w3all_url_to_cms,$w3all_phpbb_wptoolbar_pm_yn;

	if ( defined("W3PHPBBUSESSION") && $w3all_phpbb_wptoolbar_pm_yn == 1 ) {
        $phpbb_user_session = unserialize(W3PHPBBUSESSION);
        if($phpbb_user_session[0]->user_unread_privmsg > 0){
        $hrefmode = get_option( 'w3all_iframe_phpbb_link_yn' ) == 1 ? get_home_url() . "/index.php/".$wp_w3all_forum_folder_wp.'/?i=pm&amp;folder=inbox">' : $w3all_url_to_cms.'/ucp.php?i=pm&amp;folder=inbox';
        $args_meta = array( 'class' => 'w3all_phpbb_pmn' );
        $args = array(
                'id'    => 'w3all_phpbb_pm', 
                'title' => __( 'You have ', 'wp-w3all-phpbb-integration' ) . $phpbb_user_session[0]->user_unread_privmsg . __( ' unread forum PM', 'wp-w3all-phpbb-integration' ),
                'href'  => $hrefmode,
                'meta' => $args_meta );

       $wp_admin_bar->add_node( $args );
     }
  } else { return false; }
} 

 if ( ! function_exists( 'wp_hash_password' ) ) :


function wp_hash_password( $password ) {
	 
	 $pass = WP_w3all_phpbb::phpBB_password_hash($password);
	return $pass;

}

endif;

 if ( ! function_exists( 'wp_check_password' ) ) :

function wp_check_password($password, $hash, $user_id = '') {
   global $wp_hasher;

   if( $user_id < 1 ){ return; }
 
    $is_phpbb_admin = ( $user_id == 1 ) ? 1 : 0; // switch for phpBB admin // 1 admin 0 all others
    
    $wpu = get_user_by( 'ID', $user_id );
 
   $changed = WP_w3all_phpbb::check_phpbb_passw_match_on_wp_auth($wpu->user_login, $is_phpbb_admin);
   
	 if ( $changed !== false ){ 
	 	
      $hash = $changed;
    }
	 	 
	 // If the hash is still md5...
    if ( strlen($hash) <= 32 ) {
        $check = hash_equals( $hash, md5( $password ) );
        if ( $check && $user_id ) {
            // Rehash using new hash.
            wp_set_password($password, $user_id);
            $hash = wp_hash_password($password);
        }
     }
     
	// new style phpass portable hash.
	if ( empty($wp_hasher) ) {
		require_once( ABSPATH . WPINC . '/class-phpass.php');
		// By default, use the portable hash from phpass
		$wp_hasher = new PasswordHash(8, true);
	}
     $password = trim($password);
     $check = $wp_hasher->CheckPassword($password, $hash); // WP check

     if ($check !== true && strlen($hash) > 32){ // check that isn't an md5 at this point before to follow
     	// or get PHP Fatal error:  Uncaught exception 'Exception' with message 'Unsupported hash format.' in ...addons/bcrypt/bcrypt.php:111
       require_once( WPW3ALL_PLUGIN_DIR . 'addons/bcrypt/bcrypt.php');
       $password = htmlspecialchars($password);
       $ck = new w3_Bcrypt();
       $check = $ck->checkPassword($password, $hash);
     }
     
     if ($check === true){
     	if($wpu){
     	  $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($wpu); 
      } else {
           add_action('wp_login', 'wp_w3all_phpbb_login', 10, 2);
        }
     }
 
	   return apply_filters( 'check_password', $check, $password, $hash, $user_id );

}

endif;


function wp_w3all_remove_bbcode_tags($post_str, $words){

 $post_string = preg_replace('/[[\/\!]*?[^\[\]]*?]/', '', $post_str);
 
 $post_string = strip_tags($post_string);
 
 $post_s = $post_string;
  
 $post_string = explode(' ',$post_string);

  if( count($post_string) < $words ) : return $post_s; endif;

 $post_std = ''; $i = 0; $b = $words;
 
  foreach ($post_string as $post_st) {
	
	  $i++;
	  if( $i < $b + 1 ){ // offset of 1

      $post_std .= $post_st . ' ';
    }
  }

 //$post_std = $post_std . ' ...'; // if should be a link to the post, do it on phpbb_last_topics

return $post_std;

}

/////////////////////////   
// W3ALL WPMS MU START
/////////////////////////

function w3all_wpmu_activate_user_phpbb( $user_id, $password, $meta ) { 
  	 global $w3all_config,$w3all_phpbb_user_deactivated_yn;

	$w3db_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
	$phpbb_config_file = $w3all_config;
  $user = get_user_by('id', $user_id);
  $user_info = get_userdata($user->ID);
  $wp_user_role = implode(', ', $user_info->roles);

		$phpbb_user_data = WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($user->user_email);
     $password = WP_w3all_phpbb::phpBB_password_hash($password);
		if ( $phpbb_user_data[0]->user_type == 1 ) {
		 	$res = $w3db_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '0', user_password = '".$password."' WHERE user_email_hash = '".$phpbb_user_data[0]->user_email_hash."'");
    }
}
  
function w3all_wpmu_new_user_up_pass( $user_id ) { 

    $wpu  = get_user_by('id', $user_id);
    $phpBB_u_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset_msmu($wpu); // msmu: the pass updated is the one of WP
} 
         
function w3all_wpmu_new_user_signup( $user, $user_email, $key, $meta ) { 
      $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user, $user_email, $key, $meta );
}
  
function w3all_wpmu_validate_user_signup( $result ){

           $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($result['user_name'], $result['user_email'], 0);
         if($wp_w3_ck_phpbb_ue_exist === true){
            temp_wp_w3_error_on_update();
          exit;
         } 
        
    return $result; 
}

function w3all_wpmu_delete_user( $id ) { 
global $wpdb;
   WP_w3all_phpbb::wp_w3all_phpbb_delete_user_signup($id);
   // for compatibility, this delete will remove user from wp signup table also  
}

function w3all_after_signup_site( $domain, $path, $title, $user, $user_email, $key, $meta ) { 
	  $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user, $user_email, $key, $meta );
}

function w3all_wpmu_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) { 
    $user = get_user_by('ID', $user_id);
    $phpBB_u_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset_msmu($user);
}

function w3all_wpmu_new_blog_by_admin( $blog_id, $user_id, $domain, $path, $site_id, $meta ) { 
	$user = get_user_by('ID', $user_id);
	$wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($user->user_login, $user->user_email, 1);

  if($wp_w3_ck_phpbb_ue_exist === false){ // could be added a site for an existent user
  	// pass $key as string 'is_admin_action' to switch
    $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user_id, $user->user_email, $key='is_admin_action', $meta );
  }
}

function w3all_pre_check_phpbb_u() {
if(isset($_POST['add-user']) && current_user_can( 'create_users' )){
	 $username = sanitize_user($_POST['user']['username'], $strict = false ); 
   $email = sanitize_email($_POST['user']['email']);
$wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($username, $email, 1);
  if($wp_w3_ck_phpbb_ue_exist === true){
       temp_wp_w3_error_on_update();
    exit;
  } 
}
}

if ( is_multisite() ) {   
// admin

if ( defined( 'WP_ADMIN' ) ){
add_action( 'init',  'w3all_pre_check_phpbb_u' ); 
}	  
add_action( 'init', 'w3all_network_admin_actions' );
function w3all_network_admin_actions() { 
if ( defined( 'WP_ADMIN' ) && current_user_can( 'create_users' ) ){
add_action( 'wpmu_new_blog', 'w3all_wpmu_new_blog_by_admin', 10, 6 ); 	
}
}
// user with site registration
add_action( 'wpmu_new_blog', 'w3all_wpmu_new_blog', 10, 6 ); 
add_action( 'after_signup_site', 'w3all_after_signup_site', 10, 7 ); 
add_filter( 'wpmu_validate_user_signup', 'w3all_wpmu_validate_user_signup', 10, 1 );
// no site user registration
add_action( 'wpmu_delete_user', 'w3all_wpmu_delete_user', 10, 1 );
add_action( 'after_signup_user', 'w3all_wpmu_new_user_signup', 10, 4 ); // see wp_w3all_phpbb_registration_save more above about this
add_action( 'wpmu_activate_user', 'w3all_wpmu_activate_user_phpbb', 10, 3 );
add_action( 'wpmu_new_user', 'w3all_wpmu_new_user_up_pass', 10, 1 );
}

/////////////////////////   
// W3ALL WPMS MU END
/////////////////////////

/////////////////////////////////////
// x BUDDYPRESS profile fields START
/////////////////////////////////////

function w3all_xprofile_updated_profile( $user_id, $posted_field_ids, $errors, $old_values, $new_values ) { 
   // todo
}; 

add_action( 'xprofile_updated_profile', 'w3all_xprofile_updated_profile', 10, 5 );

///////////////////////////////////
// x BUDDYPRESS profile fields END
///////////////////////////////////


} // END   if ( defined('PHPBB_INSTALLED') ){ // 2nd //


// WP_w3all - this extract ever the correct cookie domain (except for sub hosted/domains like: mydomain.my-hostingService-domain.com)
// in this case the setting for the cookie can be forced on top of this file OR when added as option, on wp_w3all admin config page
// TODO: setting on wp_w3all config to force cookie setting or change it
function w3all_extract_cookie_domain( $w3cookie_domain ) {

require_once( WPW3ALL_PLUGIN_DIR . 'addons/w3_icann_domains.php' );

$count_dot = substr_count($w3cookie_domain, ".");

	 if($count_dot >= 3){
	  preg_match('/.*(\.)([-a-z0-9]+)(\.[-a-z0-9]+)(\.[a-z]+)/', $w3cookie_domain, $w3m0, PREG_OFFSET_CAPTURE);
	  $w3cookie_domain = $w3m0[2][0].$w3m0[3][0].$w3m0[4][0];
   }
   
   $ckcd = explode('.',$w3cookie_domain);

  if(!in_array('.'.$ckcd[1], $w3all_domains)){
   $w3cookie_domain = preg_replace('/^[^\.]*\.([^\.]*)\.(.*)$/', '\1.\2', $w3cookie_domain);
  }

	$w3cookie_domain = '.' . $w3cookie_domain;

$pos = strpos($w3cookie_domain, '.');
if($pos != 0){
	$w3cookie_domain = '.' . $w3cookie_domain;
}

return $w3cookie_domain;

}

?>
