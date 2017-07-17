<?php
class WP_w3all_phpbb {
	
// lost on the way

 // protected $config = '';
 // protected $w3db_conn = '';
 // protected $phpbb_config = '';
 // protected $phpbb_user_session = '';
  
public static function wp_w3all_phpbb_init() {
	
	global $w3all_get_phpbb_avatar_yn;
	
      self::verify_phpbb_credentials();
      	
      	if ( $w3all_get_phpbb_avatar_yn == 1 ): 
      	 self::init_w3all_avatars(); 
      	endif;
      	
	}
	
private static function w3all_wp_logout(){

	   		global $w3all_config,$w3cookie_domain,$useragent;
	  	$phpbb_config = W3PHPBBCONFIG;
	  	$phpbb_config = unserialize($phpbb_config);
	
	  	$phpbb_config_file = $w3all_config;
	  	$w3phpbb_conn = self::w3all_db_connect();
	    $user = wp_get_current_user();
	     	
        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';
         
   if(isset($_COOKIE[$k])){
        	
         if ( preg_match('/[^0-9A-Za-z]/',$_COOKIE[$k]) OR preg_match('/[^0-9A-Za-z]/',$_COOKIE[$sid]) OR preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	 die( "Please clean up bad cookies on your browser." );
 	            }     
 	
   $k_md5 = md5($_COOKIE[$k]);
 	 $u_id = $_COOKIE[$u];
 	 $s_id = $_COOKIE[$sid];
 	 
   $w3phpbb_conn->query("DELETE FROM ".$phpbb_config_file["table_prefix"]."sessions WHERE session_id = '$s_id' AND session_user_id = '$u_id' OR session_user_id = '$u_id' AND session_browser = '$useragent'");
   $w3phpbb_conn->query("DELETE FROM ".$phpbb_config_file["table_prefix"]."sessions_keys WHERE key_id = '$k_md5' AND user_id = '$u_id'");
  
   	// remove phpBB cookies 
 	    setcookie ("$k", "", time() - 31622400, "/");
 	    setcookie ("$sid", "", time() - 31622400, "/"); 
 	    setcookie ("$u", "", time() - 31622400, "/"); 
 	    setcookie ("$k", "", time() - 31622400, "/", "$w3cookie_domain");
 	    setcookie ("$sid", "", time() - 31622400, "/", "$w3cookie_domain"); 
 	    setcookie ("$u", "", time() - 31622400, "/", "$w3cookie_domain"); 
   }
   
   unset($phpbb_user_session);
	  
	  wp_logout();

    wp_redirect( home_url() ); exit;
  
 }


private static function w3all_db_connect(){

 global $w3all_config;
  $w3db_conn = new wpdb($w3all_config["dbuser"], $w3all_config["dbpasswd"], $w3all_config["dbname"], $w3all_config["dbhost"]);
	return  $w3db_conn;
}

private static function w3all_get_phpbb_config(){
	
	   global $w3all_config, $w3cookie_domain;
    $w3db_conn = self::w3all_db_connect();

   $a = $w3db_conn->get_results("SELECT * FROM ". $w3all_config["table_prefix"] ."config WHERE config_name IN('allow_autologin','avatar_gallery_path','avatar_path','avatar_salt','cookie_domain','cookie_name', 'max_autologin_time', 'rand_seed', 'rand_seed_last_update', 'script_path', 'session_length', 'version') ORDER BY config_name ASC");

      // Order is alphabetical 
      $res = array( 'allow_autologin' => $a[0]->config_value,
                    'avatar_gallery_path'     => $a[1]->config_value,
                    'avatar_path'     => $a[2]->config_value,
                    'avatar_salt'     => $a[3]->config_value,
                    'cookie_domain'   => $a[4]->config_value,
                    'cookie_name'     => $a[5]->config_value, 
                    'max_autologin_time'      => $a[6]->config_value,
                    'rand_seed'               => $a[7]->config_value,
                    'rand_seed_last_update'   => $a[8]->config_value,
                    'script_path'     => $a[9]->config_value,
                    'session_length'  => $a[10]->config_value,
                    'version'  => $a[11]->config_value
                  );

if( $res["cookie_domain"] != $w3cookie_domain && $res["cookie_domain"] != 'localhost' ){
	update_option( 'w3all_phpbb_cookie', $res["cookie_domain"] );
}
                  
    $res_d = serialize($res); // to pass array into define, prior php7
    define( "W3PHPBBCONFIG", $res_d ); // better define here the result, than recall this function on each possible instance
	       
	return $res;
   
 }

private static function verify_phpbb_credentials(){

           global $w3all_config, $wpdb, $w3all_ext_login_yn, $w3all_phpbb_lang_switch_yn, $useragent, $w3cookie_domain;
           $config = $w3all_config;
        	 //$phpbb_config = self::get_phpbb_config();
        	 $phpbb_config = unserialize(W3PHPBBCONFIG);
        	 $w3db_conn = self::w3all_db_connect();

      if( isset($_GET['action']) && $_GET['action'] == 'logout' ){
      	return;
      }

        	  $k   = $phpbb_config["cookie_name"].'_k';
            $sid = $phpbb_config["cookie_name"].'_sid';
            $u   = $phpbb_config["cookie_name"].'_u';
   		  	   
   $_COOKIE[$u] = isset($_COOKIE[$u]) ? $_COOKIE[$u] : $_COOKIE[$u] = 1; 	
   		        
   		  if ( is_user_logged_in() && $_COOKIE[$u] < 2 ) {
   		          self::w3all_wp_logout();
              }
      
          // HERE INSIDE WE ARE SECURE //
 
         $_COOKIE[$u] = (isset($_COOKIE[$u])) ? $_COOKIE[$u] : '';
         $_COOKIE[$sid] = (isset($_COOKIE[$sid])) ? $_COOKIE[$sid] : ''; 
         
 	     if ( $_COOKIE[$u] > 1 ){ // phpBB: uid 1 guest, uid 2 default install admin
 	      
 	        if ( !isset($_COOKIE[$k]) ){ $_COOKIE[$k] = ''; }
 	        	
 	           if ( preg_match('/[^0-9A-Za-z]/',$_COOKIE[$k]) OR preg_match('/[^0-9A-Za-z]/',$_COOKIE[$sid]) OR preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	
                die( "Clean up cookie on your browser please." );
 	            }
 	            
 	           $phpbb_k   = $_COOKIE[$k];
 	           $phpbb_sid = $_COOKIE[$sid];
 	           $phpbb_u   = $_COOKIE[$u];

 	         // group id:  1=anonimous; 5=admin; 6=bots; 
         	 // user_type: 1=not active accounts:banned,confirmation email,deactivated (and i presume coppa?)
         	 // a deactivated user in phpBB will ever return here
  
  if ( empty( $phpbb_k ) ){ // it is not a remember login
 	 
  	 	$phpbb_user_session = $w3db_conn->get_results("SELECT *  
                                              FROM ". $config["table_prefix"] ."users  
                                               JOIN ". $config["table_prefix"] ."sessions ON ". $config["table_prefix"] ."sessions.session_id =  '".$phpbb_sid."'   
                                                AND ". $config["table_prefix"] ."sessions.session_user_id = ". $config["table_prefix"] ."users.user_id 
                                                AND ". $config["table_prefix"] ."sessions.session_user_id = '".$phpbb_u."' 
                                                AND ". $config["table_prefix"] ."sessions.session_browser = '".$useragent."' 
                                               JOIN ". $config["table_prefix"] ."groups ON ". $config["table_prefix"] ."groups.group_id = ". $config["table_prefix"] ."users.group_id 
                                              LEFT JOIN ". $config["table_prefix"] ."profile_fields_data ON ". $config["table_prefix"] ."profile_fields_data.user_id = ". $config["table_prefix"] ."sessions.session_user_id");
        
       } else { // remember me auto login
       	
       	       	 	 	$phpbb_user_session = $w3db_conn->get_results("SELECT *  
                                               FROM ". $config["table_prefix"] ."users  
                                                JOIN ". $config["table_prefix"] ."sessions_keys ON ". $config["table_prefix"] ."sessions_keys.key_id = '".md5($phpbb_k)."' 
                                                 AND ". $config["table_prefix"] ."users.user_id = ". $config["table_prefix"] ."sessions_keys.user_id 
                                                  LEFT JOIN ". $config["table_prefix"] ."sessions ON ". $config["table_prefix"] ."sessions.session_user_id = ". $config["table_prefix"] ."sessions_keys.user_id 
                                                 AND ". $config["table_prefix"] ."sessions.session_browser = '".$useragent."' 
                                                LEFT JOIN ". $config["table_prefix"] ."groups ON ". $config["table_prefix"] ."groups.group_id = ". $config["table_prefix"] ."users.group_id 
                                               LEFT JOIN ". $config["table_prefix"] ."profile_fields_data ON ". $config["table_prefix"] ."profile_fields_data.user_id = ". $config["table_prefix"] ."sessions_keys.user_id ");        
                
               }         

   if ( empty( $phpbb_user_session ) OR $phpbb_user_session == 0 OR $phpbb_user_session[0]->user_type == 1 ){
        self::w3all_wp_logout();
       return;
   	} else { 
   	         $w3_phpbb_user_session = serialize($phpbb_user_session);
   	         define("W3PHPBBUSESSION", $w3_phpbb_user_session);
   	       }
              
              // moved right above, to not add deactivated phpBB user into WP
               /* if ( $phpbb_user_session[0]->user_type == 1 ){ // is this user deactivated/banned in phpBB? / logout/and deactivate in WP
                   
                     $user = get_user_by( 'login', $phpbb_user_session[0]->username );
                    if($user){
                     $wpu_db_utab = $wpdb->prefix . 'usermeta';
	                   $wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$user->ID' AND meta_key = 'wp_capabilities'");
	                  }
	                  
                  self::w3all_wp_logout();
               } */   
               
  $phpbb_user_session[0]->user_id = (!empty($phpbb_user_session[0]->user_id)) ? $phpbb_user_session[0]->user_id : $phpbb_user_session[0]->session_user_id;
 
  	if ( is_user_logged_in() ) {
  
   		$current_user = wp_get_current_user();
 
      $wp_umeta = get_user_meta($current_user->ID, '', false);

   		if( empty($wp_umeta['locale'][0]) ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
   		   $wp_lang_x_phpbb = 'en'; // no lang setting, assume en
   		    
   			} else { 
   				 $wp_lang_x_phpbb = substr($wp_umeta['locale'][0], 0, strpos($wp_umeta['locale'][0], '_')); // should extract Lang code ISO Code phpBB suitable for this lang
   				}

   		if (  ( time() - $phpbb_config["session_length"] ) > $phpbb_user_session[0]->session_time && empty( $phpbb_k ) ){
  
            self::w3all_wp_logout();  

 	     	} else { // update

                     // last visit update
   			 	           //$w3db_conn->query("UPDATE ". $config["table_prefix"] ."sessions SET session_time = '".time()."' WHERE session_id = '$phpbb_sid' OR session_browser = '".$useragent ."' AND session_user_id = '".$phpbb_user_session[0]->user_id."'");
                     //$w3db_conn->query("UPDATE ". $config["table_prefix"] ."users SET user_lastvisit = '".time()."' WHERE user_id = '".$phpbb_user_session[0]->user_id."'");
                     // last visit update both once
                     $w3db_conn->query("UPDATE ". $config["table_prefix"] ."users, ". $config["table_prefix"] ."sessions 
                     SET ". $config["table_prefix"] ."users.user_lastvisit = '".time()."', ". $config["table_prefix"] ."sessions.session_time = '".time()."', ". $config["table_prefix"] ."sessions.session_last_visit = '".time()."' 
                      WHERE ". $config["table_prefix"] ."users.user_id = '".$phpbb_user_session[0]->user_id."' 
                     AND ". $config["table_prefix"] ."sessions.session_user_id = '".$phpbb_user_session[0]->user_id."'
                     AND ". $config["table_prefix"] ."sessions.session_browser = '".$useragent."'");
                    
                // NOTE phpbb_update_profile do the update of same fields so if code changes are done here adding custom profile fields
                // look also the phpbb_update_profile method and add same things on it

                // check for users profile fields here and return for wp-admin and update, for external plugins
                // check that email, password and site url match on both for this user // add any other profile field to be updated here

            if( isset($_GET['updated']) && $_GET['updated'] == 1 ){ // on wp update
         
           	  self::phpbb_update_profile($current_user->ID, $current_user);
             } 
            
                      // WP $current_user at this point (onlogin) DO NOT contain all data fields
                  	  // $current_user->user_pass for example
                  	  // so this update is done any time user login wp, almost one time

              	  // check for match between wp and phpbb profile fields. If some profile field still not exist on phpBB at this point for this user

              $phpbb_user_session[0]->pf_phpbb_website = (!empty($phpbb_user_session[0]->pf_phpbb_website)) ? $phpbb_user_session[0]->pf_phpbb_website : $current_user->user_url;
      
       // update in these cases
       if( $phpbb_user_session[0]->user_password != $current_user->user_pass OR $phpbb_user_session[0]->user_email != $current_user->user_email OR $phpbb_user_session[0]->pf_phpbb_website != $current_user->user_url OR $phpbb_user_session[0]->user_lang != $wp_lang_x_phpbb && $w3all_phpbb_lang_switch_yn == 1 )
   	    {

   	    $wpu_db_utab = (is_multisite() == true) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
   	    
      		$phpbb_upass = $phpbb_user_session[0]->user_password;
      		$phpbb_uemail = $phpbb_user_session[0]->user_email;
          $phpbb_uurl = $phpbb_user_session[0]->pf_phpbb_website;
      		    
              if($phpbb_user_session[0]->user_lang != $wp_lang_x_phpbb && $w3all_phpbb_lang_switch_yn == 1){ // umeta updates ... in case
      		      	  $wp_locale = $phpbb_user_session[0]->user_lang == 'en' ? '' : $phpbb_user_session[0]->user_lang . '_' . strtoupper($phpbb_user_session[0]->user_lang); // should build to be WP compatible into something like it_IT or set emtpy for en WP default
      		         update_user_meta($current_user->ID, 'locale', $wp_locale); 
      		     } 
      		     
	              $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '$phpbb_upass', user_email = '$phpbb_uemail', user_url = '$phpbb_uurl' WHERE ID = '$current_user->ID'");
              if ( defined( 'WP_ADMIN' ) ) { // going on profile directly from forum iframe, update onload user's profile fields
                 wp_redirect( admin_url().'profile.php');
                 exit;
                }
             }
   		                  // END check for profile fields
   		      
         } // END update
   	  
   	  if ( defined( 'WP_ADMIN' ) ) {
   	  	return;
   	  }
   	     
	} // END is_user_logged_in()
   	
     // switch the admin uid1 on WP to admin uid 2 on phpBB, and viceversa //
  
   	$user_id = ($phpbb_user_session[0]->user_id == 2) ? '1' : $phpbb_user_session[0]->user_id;

    if( $user_id != 1 ){
    	
      $phpbb_real_username = sanitize_user( $phpbb_user_session[0]->username, $strict = false );
    	
      $user_id = username_exists( $phpbb_real_username );

     }
 
      if ( ! $user_id  ) { 

        if ( $phpbb_user_session[0]->group_name == 'ADMINISTRATORS' ){
      	      
      	      $role = 'administrator';
      	      
            } elseif ( $phpbb_user_session[0]->group_name == 'GLOBAL_MODERATORS' ){
            	
            	   $role = 'editor';
          	  
               }  else { $role = 'subscriber'; }  // for all others phpBB Groups default to WP subscriber
               	
         if ( $phpbb_user_session[0]->user_type == 1 ){
  
               	 // $role = ''; not added into WP if deactivated in phpBB (due to deletion in WP maybe)
               	 return false;
              }
              
         //////// phpBB username chars fix          	   	
         // phpBB need to have users without characters like ' that is not allowed in WP as username
         // If old phpBB usersnames are like myuse'name on WP_w3all integration, do not add into WP
         // check for 2 more of these on this class.wp.w3all-phpbb.php, and 1 on ext_plugins_fixes.php in case you need to add something or remove
         
          $pattern = '/\'/';
           preg_match($pattern, $phpbb_user_session[0]->username, $matches);
          if($matches){
	        echo '<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em">Sorry, your <strong>registered username on our forum contain characters not allowed on this CMS system</strong>, you can\'t be added or login in this site side (and you\'ll see this message) until logged in on forums as <b>'.$phpbb_user_session[0]->username.'</b>. Please return back and contact the administrator reporting about this error issue. Thank you <input type="button" value="Go Back" onclick="history.back(-1)" /></p>';
          return;
          
          }
          	
          ///////////////////
          ///////////////////
          	
              $phpbb_user_session[0]->username = sanitize_user( $phpbb_user_session[0]->username, $strict = false ); 
              
              $userdata = array(
               'user_login'       =>  $phpbb_user_session[0]->username,
               'user_pass'        =>  $phpbb_user_session[0]->user_password,
               'user_email'       =>  $phpbb_user_session[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbb_user_session[0]->user_regdate ),
               'role'             =>  $role
               );
               
           $user_id = wp_insert_user( $userdata );
           
          if ( ! is_wp_error( $user_id ) ) {
      	
        	$user = get_user_by( 'ID', $user_id );
   
      	  if ( $phpbb_real_username != $user->user_login  ) {
      	   // not equal update needed 
      	 $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
  
	            $wpdb->query("UPDATE $wpu_db_utab SET user_login = '$phpbb_real_username', user_login = '$phpbb_real_username', user_nicename = '$phpbb_real_username' WHERE ID = '$user->ID'");
        
      	    }
           }
         }

     $user = get_user_by( 'ID', $user_id );
     
     if ( ! is_user_logged_in() && ! is_wp_error( $user_id ) && $user !== false ) {
     
     	$remember = ( empty($phpbb_k) ) ? false : true;
     	 
       $secure = is_ssl();
  
        wp_set_current_user( $user_id, $user->user_login );
        wp_set_auth_cookie( $user_id, $remember, $secure );
       
        do_action( 'wp_login', $user->user_login);
        $current_user = wp_get_current_user();
      
     // REDIRECTION ON WP LOGIN
     // correct redirect CHECK if ... $_SERVER['HTTP_REFERER'] is available on this server, if not default to WP home
     // check if something wrong on $_SERVER['HTTP_REFERER'], and assign WP home url by default if the case
   if( isset($_SERVER['HTTP_REFERER']) ){
      $wpdomain = preg_replace('/(f|ht)tps?:\/\//', '',get_option('siteurl'));
       if(strpos($wpdomain, '/')){ // if not skip
        $wpdomain = substr($wpdomain, 0, strpos($wpdomain, '/'));
       } else { $wpdomain = $wpdomain; } // else pass as is to check
       $purl = trim(htmlspecialchars(utf8_encode($_SERVER['HTTP_REFERER'])));
       $w3ck = preg_replace('/(f|ht)tps?:\/\//', '',$purl);
      if(strpos($w3ck, '/')){	// if not skip
       $w3ck = substr($w3ck, 0, strpos($w3ck, '/'));
       $uparts = array_reverse(explode('.',$w3ck));
       $w3ckdomain = (isset($uparts[1])) ? $uparts[1] . '.' . $uparts[0] : $uparts[0]; // build the real passed domain in URL to be checked
      } else { $w3ckdomain = $w3ck; }
    } // END correct redirect CHECK if $_SERVER['HTTP_REFERER']
      
       if( isset($wpdomain) && isset($w3ckdomain) && !stristr($wpdomain,$w3ckdomain) OR !isset($_SERVER['HTTP_REFERER'])){
         if( !isset($_REQUEST['redirect_to']) ){ 
         	 $_REQUEST['redirect_to'] = home_url();
          }
         } else {
         	 	if(strpos($_SERVER['HTTP_REFERER'],get_option( 'w3all_forum_template_wppage' ))){
        		$_REQUEST['redirect_to'] = home_url().'/index.php/'.get_option( 'w3all_forum_template_wppage' );       	         	
           } 
          }

		   if ( isset( $_REQUEST['redirect_to'] ) ) {
		   	          // if it is login page, while adding user, or user will be logged in, but is redirected to wp-login page screen
         if( strpos($_REQUEST['redirect_to'], 'wp-login.php' ) && is_user_logged_in() ){ 
           $_REQUEST['redirect_to'] = user_admin_url(); // home_url(); // maybe redirect to profile here is more appropriate ...
         }
		   	
	       	$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
         } else {
         	 if(strpos($_SERVER['HTTP_REFERER'],get_option( 'w3all_forum_template_wppage' ))){
        		$_REQUEST['redirect_to'] = home_url().'/index.php/'.get_option( 'w3all_forum_template_wppage' );
        		$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
        	} else {
        		     $redirect_to = $requested_redirect_to = home_url();
		            }
	        } 
	              
	  $redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $current_user );
	     
	   wp_redirect( $redirect_to );
	     exit;
	     
    }
   
    return;

 }  // END // HERE INSIDE WE ARE SECURE // END // 
    
 return;
    
}  // END verify_phpbb_credentials(){ // END //


private static function last_forums_topics($ntopics = 10){
	
     global $w3all_config,$w3all_exclude_phpbb_forums,$w3all_wlastopicspost_max,$w3all_get_topics_x_ugroup;
  
     $config = $w3all_config;
     $w3db_conn = self::w3all_db_connect();
     
     $ntopics = (empty($ntopics)) ? '10' : $ntopics; 
         
 $forumlist_exclude = $w3all_exclude_phpbb_forums;

if($w3all_get_topics_x_ugroup == 1){ // list of allowed forums to retrieve topics if option active
           
if (defined('W3PHPBBUSESSION')) {
   $us = unserialize(W3PHPBBUSESSION);
   $ug = $us[0]->group_id;
  } else {
	$ug = 1; // the default phpBB guest user group
}
  
 $gaf = $w3db_conn->get_results("SELECT DISTINCT forum_id FROM ".$config["table_prefix"]."acl_groups WHERE group_id = ".$ug." ORDER BY forum_id");

 if(empty($gaf)){
	 return array(); // no forum found that can show topics for this group ... 
 } else { 
 	    $gf = '';
 	     foreach( $gaf as $v ){
        $gf .= $v->forum_id.',';
       }
   $gf = substr($gf, 0, -1);
   $topics_x_ugroup = "AND T.forum_id IN(".$gf.")";
}

} else {
	$topics_x_ugroup = '';
}

       // From 1.6.7 added also user's info
       // TODO: should retreve only needed data, and not the pass for example   
   if (empty( $forumlist_exclude )){
         	
         	    //$topics = $w3db_conn->get_results("SELECT * FROM ".$config["table_prefix"]."posts, ".$config["table_prefix"]."topics WHERE (SELECT MAX(topic_last_post_time) FROM ".$config["table_prefix"]."topics WHERE topic_visibility = 1) AND ".$config["table_prefix"]."posts.post_id = ".$config["table_prefix"]."topics.topic_last_post_id AND ".$config["table_prefix"]."posts.topic_id = ".$config["table_prefix"]."topics.topic_id AND ".$config["table_prefix"]."posts.post_visibility = 1 ORDER BY post_time DESC LIMIT 0,$ntopics");
              $topics = $w3db_conn->get_results("SELECT T.*, P.*, U.* FROM ".$config["table_prefix"]."topics AS T, ".$config["table_prefix"]."posts AS P, ".$config["table_prefix"]."users AS U 
              WHERE T.topic_visibility = 1 
              ".$topics_x_ugroup." 
              AND T.topic_last_post_id = P.post_id 
              AND P.post_visibility = 1 
              AND U.user_id = T.topic_last_poster_id 
              ORDER BY T.topic_last_post_time DESC LIMIT 0,$ntopics");

     } else {
        	
        	      if ( preg_match('/^[0-9,]+$/', $forumlist_exclude )) { 

        	         	$exp = explode(",", $forumlist_exclude);
        	         	$no_forums_list = '';
                    while (list(, $value) = each($exp)) {
	                        $no_forums_list .= "'".$value."',";
                        }
                        
                    $no_forums_list = substr($no_forums_list, 0, -1);
                    
        	  		   //$topics = $w3db_conn->get_results("SELECT * FROM ".$config["table_prefix"]."posts, ".$config["table_prefix"]."topics WHERE (SELECT MAX(topic_last_post_time) FROM ".$config["table_prefix"]."topics WHERE topic_visibility = 1) AND ".$config["table_prefix"]."topics.forum_id NOT IN(".$no_forums_list.")  AND ".$config["table_prefix"]."posts.post_id = ".$config["table_prefix"]."topics.topic_last_post_id AND ".$config["table_prefix"]."posts.topic_id = ".$config["table_prefix"]."topics.topic_id AND ".$config["table_prefix"]."posts.post_visibility = 1 ORDER BY post_time DESC LIMIT 0,$ntopics");
                   $topics = $w3db_conn->get_results("SELECT T.*, P.*, U.* FROM ".$config["table_prefix"]."topics AS T, ".$config["table_prefix"]."posts AS P, ".$config["table_prefix"]."users AS U 
                   WHERE T.topic_visibility = 1 
                   ".$topics_x_ugroup." 
                   AND T.forum_id NOT IN(".$no_forums_list.") 
                   AND T.topic_last_post_id = P.post_id 
                   AND P.post_visibility = 1 
                   AND U.user_id = T.topic_last_poster_id 
                   ORDER BY T.topic_last_post_time DESC LIMIT 0,$ntopics");
                  
                  } else {  
        	          //$topics = $w3db_conn->get_results("SELECT * FROM ".$config["table_prefix"]."posts, ".$config["table_prefix"]."topics WHERE (SELECT MAX(topic_last_post_time) FROM ".$config["table_prefix"]."topics WHERE topic_visibility = 1) AND ".$config["table_prefix"]."posts.post_id = ".$config["table_prefix"]."topics.topic_last_post_id AND ".$config["table_prefix"]."posts.topic_id = ".$config["table_prefix"]."topics.topic_id AND ".$config["table_prefix"]."posts.post_visibility = 1 ORDER BY post_time DESC LIMIT 0,$ntopics");
                    $topics = $w3db_conn->get_results("SELECT T.*, P.*, U.* FROM ".$config["table_prefix"]."topics AS T, ".$config["table_prefix"]."posts AS P, ".$config["table_prefix"]."users AS U 
                    WHERE T.topic_visibility = 1 
                    ".$topics_x_ugroup." 
                    AND T.topic_last_post_id = P.post_id 
                    AND P.post_visibility = 1 
                    AND U.user_id = T.topic_last_poster_id 
                    ORDER BY T.topic_last_post_time DESC LIMIT 0,$ntopics");
                   }                
	          }

	  if( $w3all_wlastopicspost_max == $ntopics ){
	   $t = is_array($topics) ? serialize($topics) : serialize(array());
     define( "W3PHPBBLASTOPICS", $t ); // see wp_w3all.php
    }
	  return $topics; 
}


private static function phpBB_user_session_set($wp_user_data){

	      global $w3all_config,$wpdb,$w3cookie_domain,$useragent;
       $phpbb_config_file = $w3all_config;
	     $phpbb_config = unserialize(W3PHPBBCONFIG);
       $w3phpbb_conn = self::w3all_db_connect();
      	
        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';
         
         if ( !$wp_user_data OR $wp_user_data->ID == 0 ){
		  	      return; 
		      }    

     if( $wp_user_data->ID == 1 ){ // switch admin
         	
         	$user_id = 2;
         	
       } else { // TODO change for email hash search here
               $wp_user_data->user_login = esc_sql($wp_user_data->user_login);
               $phpbb_uid = $w3phpbb_conn->get_row("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$wp_user_data->user_login' OR user_email = '$wp_user_data->user_email' ") ;
          
               $user_id = $phpbb_uid->user_id;
          
                if ( $phpbb_uid->user_type == 1 ){ // is this user deactivated/banned in phpBB? / logout/and deactivate in WP
                 //update_user_meta($user_id, 'wp_capabilities', $cap); maybe substitute with this
                  //$wpu_db_utab = $wpdb->prefix . 'usermeta';
	                $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
	                $wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$wp_user_data->ID' AND meta_key = 'wp_capabilities'");
	                 
                   self::w3all_wp_logout();
               }
             }

       $time = time();
       $val = md5($phpbb_config["rand_seed"] . microtime()); // to user_form_salt
       $val = md5($val);
       $phpbb_config["rand_seed"] = md5( $phpbb_config["rand_seed"] . $val . rand() ); // the rand seed to be updated
       $phpbb_rand_seed = $phpbb_config["rand_seed"];
       
        $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$phpbb_rand_seed' WHERE config_name = 'rand_seed'");
        $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$time' WHERE config_name = 'rand_seed_last_update'");
        $w3_unique_id = substr($val, 4, 16); 
        $w3session_id = md5($w3_unique_id);
     
  //   $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_form_salt = '$val' WHERE user_id = '$user_id'");
   
        $uip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        //$uag = (!empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        $uag = $useragent;
        $auto_login = 1; 
           $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."sessions (session_id, session_user_id, session_last_visit, session_start, session_time, session_ip, session_browser, session_forwarded_for, session_page, session_viewonline, session_autologin, session_admin, session_forum_id) 
          VALUES ('$w3session_id', '$user_id', '$time', '$time', '$time', '$uip', '$uag', '', 'index.php', '1', '$auto_login', '0', '0')");
 
     // $key_id = unique_id(hexdec(substr($this->session_id, 0, 8))); // phpBB

      $key_id = hexdec(substr($w3session_id, 0, 8));
      $valk = $phpbb_config["rand_seed"] . microtime() . $key_id;
      $valk = md5($valk);
      
      $key_id_k  = substr($valk, 4, 16); // to k

      $key_id_sk = md5($key_id_k); // to sessions_keys
      
  // if ( $phpbb_config["allow_autologin"] == 1 ){//  phpBB 'if allowed autologin' feature // enable it here and below

   // if (! empty( $_POST['rememberme'] )){ 
    
            $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."sessions_keys (key_id, user_id, last_ip, last_login) 
           VALUES ('$key_id_sk', '$user_id', '$uip', '$time')");
       
  // }
 
  //}
  
	    $cookie_expire = $time + (($phpbb_config['max_autologin_time']) ? 86400 * (int) $phpbb_config['max_autologin_time'] : 31536000);

// if ( $phpbb_config["allow_autologin"] == 1 ){//  phpBB 'if allowed autologin' feature // enable it also here

    $secure = is_ssl();
    
 // if (! empty( $_POST['rememberme'] )){ 

	    setcookie ("$k", "$key_id_k", $cookie_expire, "/", $w3cookie_domain, $secure);
	// }
	    
	  // }
	    
 	    setcookie ("$sid", "$w3session_id", $cookie_expire, "/", $w3cookie_domain, $secure); 
 	    setcookie ("$u", "$user_id", $cookie_expire, "/", $w3cookie_domain, $secure);
 	    
      $current_user = wp_get_current_user(); 
     //if( $current_user->ID == 0 && !is_multisite() ){
     	if( $current_user->ID == 0  ){
        wp_set_current_user( $wp_user_data->ID, $wp_user_data->user_login );
        wp_set_auth_cookie( $wp_user_data->ID, true, $secure );
         do_action( 'wp_login', $wp_user_data->user_login);
        //wp_redirect( admin_url().'profile.php');
        // exit;
     } /*else {
      	if (isset($_GET['key'])){
     	  wp_set_current_user( $wp_user_data->ID, $wp_user_data->user_login );
        wp_set_auth_cookie( $wp_user_data->ID, true, $secure );
         do_action( 'wp_login', $wp_user_data->user_login);
         // to profile.php if on multisite here return error loop redirect
         // TODO: temp fix rewiev
        wp_redirect();
         exit;
         }
        }*/
}

private static function create_phpBB_user($wpu){

	global $w3all_config, $w3all_phpbb_user_deactivated_yn, $w3all_phpbb_lang_switch_yn;
   $phpbb_config_file = $w3all_config;
	 $w3phpbb_conn = self::w3all_db_connect();
   //$phpbb_config = self::get_phpbb_config();
   $phpbb_config = unserialize(W3PHPBBCONFIG);
   $wp_lang = get_option('WPLANG');

   		if(empty($wp_lang) OR $w3all_phpbb_lang_switch_yn == 0 ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
   		   $wp_lang_x_phpbb = 'en'; // no lang setting, assume en by default
   			} else { 
   				 $wp_lang_x_phpbb = strtolower(substr($wp_lang, 0, strpos($wp_lang, '_'))); // should extract Lang code ISO Code that is phpBB suitable for this lang
   				}

    if( empty($wpu) ){ return; }
     
     //maybe to be added as option
     // if you wish to setup gravatar by default into phpBB profile for the user when register in WP
     $uavatar = $avatype = ''; // this will not affect queries if the two here below are commented
     //$uavatar = get_option('show_avatars') == 1 ? $wpu->user_email : '';
     //$avatype = (empty($uavatar)) ? '' : 'avatar.driver.gravatar';
     
     $wpu->user_login = esc_sql($wpu->user_login);
       $phpbb_any = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$wpu->user_login' OR user_email = '$wpu->user_email'");
     
         // $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($wpu->user_login, $wpu->user_email, 1);
     
            $u = $phpbb_config["cookie_name"].'_u';
            
            if ( preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	
                die( "Clean up cookie on your browser please!" );
 	            }
 	            
 	           $phpbb_u = $_COOKIE[$u];
 	        
 	    // only need to fire when user do not exist on phpBB already, and/or user is an admin that add an user manually 
   if ( $phpbb_u < 2 OR !empty($phpbb_u) && current_user_can( 'manage_options' ) === true ) {
      
      // check that the user need to be added as activated or not into phpBB
      	
        $phpbb_user_type = ($w3all_phpbb_user_deactivated_yn == 1) ? 1 : 0; 
        if(current_user_can( 'manage_options' ) === true){ // admin adding user, reset and add as active by the way this user
        	$phpbb_user_type = 0;
        }      
        
      $wpu->user_registered = time($wpu->user_registered); // as phpBB do
	    $user_email_hash = self::w3all_phpbb_email_hash($wpu->user_email);
	     
      $wpur = $wpu->user_registered;
      $wpul = $wpu->user_login;
       //$wpunn = $wpu->user_nicename;
      $wpup = $wpu->user_pass;
      $wpue = $wpu->user_email;
      $time = time();
      
      $wpunn = esc_sql(utf8_encode(strtolower($wpul)));
      $wpul  = esc_sql($wpul);
      $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
         VALUES ('','$phpbb_user_type','2','','0','', '$wpur', '$wpul', '$wpunn', '$wpup', '0', '$wpue', '$user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', 'D M d, Y g:i a', '1', '0', '', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '$uavatar', '$avatype', '0', '0', '', '', '', '', '', '', '', '0', '0', '0')");
      
      $phpBBlid = $w3phpbb_conn->insert_id;
   
     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$phpBBlid','0','0')");
     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");

     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('$phpBBlid','0','0','6','0')");
    		
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = config_value + 1 WHERE config_name = 'num_users'");

       $newest_member = $w3phpbb_conn->get_results("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$phpbb_config_file["table_prefix"]."users) AND group_id != '6'");
       $uname = $newest_member[0]->username;
       $uid   = $newest_member[0]->user_id;
     
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$wpul' WHERE config_name = 'newest_username'");
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");

 
 }

}


// check existance in phpBB, of provided username and email // 
  public static function phpBB_user_check( $sanitized_user_login, $user_email, $is_admin_action = 1 ){

	      global $w3all_config;
        $phpbb_config_file = $w3all_config;
	      $w3phpbb_conn = self::w3all_db_connect();
        //$phpbb_config = self::get_phpbb_config();
        $phpbb_config = unserialize(W3PHPBBCONFIG);
    
        $u = $phpbb_config["cookie_name"].'_u';
            
            if ( isset($_COOKIE["$u"]) && preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	
                die( "Clean up cookie on your browser." );
 	            }
 	            
 	      $sanitized_user_login = esc_sql($sanitized_user_login);   
 	      $user_email = esc_sql($user_email);  
 	      
 	     if( $is_admin_action == 1 ){
 	     	         $phpbb_any = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$sanitized_user_login' OR user_email = '$user_email'");
         if ( null !== $phpbb_any ) {
           return true;
 	       }
 	     }
 	     	       
 	     	  $_COOKIE[$u] = (isset($_COOKIE[$u])) ? $_COOKIE[$u] : '';
 	     	  
 	        if ( $_COOKIE["$u"] < 2 && $is_admin_action == 0 ){ // check this only if NEW phpBB user come as NOT logged in or get 'undefined wp_delete' error
         $phpbb_any = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$sanitized_user_login' OR user_email = '$user_email'");
       if ( null !== $phpbb_any ) {
        return true;
     }
     
    }
    
     return false;

  }
  
// check existance in phpBB, of provided username and email on-register in WP 2nd // 
// add_filter( 'registration_errors', 'wp_w3all_check_fields', 10, 3 );
  public static function phpBB_user_check2( $errors, $sanitized_user_login, $user_email ){

	      global $w3all_config;
        $phpbb_config_file = $w3all_config;
	      $w3phpbb_conn = self::w3all_db_connect();

       $sanitized_user_login = esc_sql($sanitized_user_login);   
         $phpbb_is_there_anybody = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$sanitized_user_login' OR user_email = '$user_email'");
       if ( null !== $phpbb_is_there_anybody ) {
        return true;
     }

    return false;
  }  


public static function check_phpbb_passw_match_on_wp_auth ( $username, $is_phpbb_admin = 0 ) {
  
     global $wpdb, $w3all_config;
     $phpbb_config_file = $w3all_config;
     
   if( empty($username) ){ return; }

	    $w3phpbb_conn = self::w3all_db_connect();

      $wpu = get_user_by('login', $username);
      
      if( $is_phpbb_admin == 1 ){ // wp default install admin

      $phpbb_pae = $w3phpbb_conn->get_row("SELECT user_password, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_id = '2'");

	     if( $phpbb_pae->user_password != $wpu->user_pass && !empty($phpbb_pae->user_password) ){
	
	     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
	   //$wpu_db_utab = $wpdb->prefix . 'users';
  
	        $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '$phpbb_pae->user_password' WHERE ID = '1'");
        
        return $phpbb_pae->user_password;
     }
  }
 
    if( $is_phpbb_admin == 0 ){ // passw change for all others

       	  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
  
     $phpbb_pae = $w3phpbb_conn->get_row("SELECT user_password, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$wpu->user_login'");

// pass empty, do not check if user isn't created in phpBB
	     if( $phpbb_pae->user_password != $wpu->user_pass && !empty($phpbb_pae->user_password) ){
	
	    // $wpu_db_utab = $wpdb->prefix . 'users';
	       $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
	        $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '$phpbb_pae->user_password' WHERE user_login = '$wpu->user_login'");

        return $phpbb_pae->user_password;
    }
  }
  
  return false;
   
}

  
  
public static function wp_w3all_phpbb_logout() {
	 
	 global $w3all_config,$w3cookie_domain,$useragent;
      $phpbb_config_file = $w3all_config;
  	  $w3phpbb_conn = self::w3all_db_connect();
	  	//$phpbb_config = self::get_phpbb_config();
	  	$phpbb_config = unserialize(W3PHPBBCONFIG);
        	  	
        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';
        
     if(isset($_COOKIE[$k])){   
      if ( preg_match('/[^0-9A-Za-z]/',$_COOKIE[$k]) OR preg_match('/[^0-9A-Za-z]/',$_COOKIE[$sid]) OR preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	 die( "Please clean up cookies on your browser." );
 	            }

   $k_md5 = md5($_COOKIE[$k]);
 	 $u_id = $_COOKIE[$u];
 	 $s_id = $_COOKIE[$sid];

    // logout user
    $w3phpbb_conn->query("DELETE FROM ".$phpbb_config_file["table_prefix"]."sessions WHERE session_id = '$s_id' AND session_user_id = '$u_id' OR session_user_id = '$u_id' AND session_browser = '$useragent'");
    $w3phpbb_conn->query("DELETE FROM ".$phpbb_config_file["table_prefix"]."sessions_keys WHERE key_id = '$k_md5' AND user_id = '$u_id'");
  // $w3phpbb_conn->query("DELETE ".$phpbb_config_file["table_prefix"]."sessions, ".$phpbb_config_file["table_prefix"]."sessions_keys FROM ".$phpbb_config_file["table_prefix"]."sessions INNER JOIN ".$phpbb_config_file["table_prefix"]."sessions_keys WHERE ".$phpbb_config_file["table_prefix"]."sessions.session_id = '".$_COOKIE["$sid"]."' AND ".$phpbb_config_file["table_prefix"]."sessions.session_user_id = '".$_COOKIE["$u"]."' AND ".$phpbb_config_file["table_prefix"]."sessions_keys.user_id = '".$_COOKIE["$u"]."' AND ".$phpbb_config_file["table_prefix"]."sessions_keys.key_id = '".md5($_COOKIE["$k"])."'");
  
 	// remove phpBB cookies
 	
      setcookie ("$k", "", time() - 31622400, "/");
 	    setcookie ("$sid", "", time() - 31622400, "/"); 
 	    setcookie ("$u", "", time() - 31622400, "/"); 
 	    setcookie ("$k", "", time() - 31622400, "/", "$w3cookie_domain");
 	    setcookie ("$sid", "", time() - 31622400, "/", "$w3cookie_domain"); 
 	    setcookie ("$u", "", time() - 31622400, "/", "$w3cookie_domain"); 
   }

}

    
public static function phpbb_pass_update($user, $new_pass) {

     	 global $w3all_config,$wpdb;
     
     $phpbb_config_file = $w3all_config;
     $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();

        	$wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
  
	     $ud = $wpdb->get_row("SELECT * FROM  $wpu_db_utab WHERE ID = '$user->ID'");

    if ( $user->ID == 1 ){ // update uid2
      	
       $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_password = '$ud->user_pass' WHERE	user_id = '2'");
     // should reset any session here
     // $w3phpbb_conn->query("DELETE ".$phpbb_config_file["table_prefix"]."sessions, ".$phpbb_config_file["table_prefix"]."sessions_keys FROM ".$phpbb_config_file["table_prefix"]."sessions INNER JOIN ".$phpbb_config_file["table_prefix"]."sessions_keys WHERE ".$phpbb_config_file["table_prefix"]."sessions.session_user_id = '2' AND ".$phpbb_config_file["table_prefix"]."sessions_keys.user_id = '2'");


      } else { // update using uname
     
       $ulogin = esc_sql($user->user_login);

       $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_password = '$ud->user_pass' WHERE username = '".$ulogin."'");
     //  should reset any session here
	   //  $w3phpbb_conn->query("DELETE ".$phpbb_config_file["table_prefix"]."sessions, ".$phpbb_config_file["table_prefix"]."sessions_keys FROM ".$phpbb_config_file["table_prefix"]."sessions INNER JOIN ".$phpbb_config_file["table_prefix"]."sessions_keys WHERE ".$phpbb_config_file["table_prefix"]."sessions.session_user_id = '$phpbb_uid->user_id' AND ".$phpbb_config_file["table_prefix"]."sessions_keys.user_id = '$phpbb_uid->user_id'");
   
     } 

    }
    
public static function phpbb_update_profile($user_id, $old_user_data) {

   global $wpdb,$w3all_config,$w3all_phpbb_lang_switch_yn;

     $phpbb_config_file = $w3all_config;
     $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();
     //$phpbb_config = self::get_phpbb_config();
     $phpbb_config = unserialize(W3PHPBBCONFIG);

     $phpbb_version = substr($phpbb_config["version"], 0, 3);
     
     $wpu = get_user_by('ID', $user_id);

    $phpbb_user_type = ( empty($wpu->roles) ) ? '1' : '0';
    
 if ( is_multisite() ) {    
//$wp_user_p_blog = get_user_meta($user_id, 'primary_blog', true);
// a normal user result with no capability in MU???
// how we get user capabilities for users that not choose for a site on register?
// they not with a role defined elsewhere seem to me ... TODO check this ...

// temp fix: set user type by the way as active in phpBB
$phpbb_user_type = 0;
}

     if ( $wpu->ID == 1 ) {
      $phpbb_is_there_anybody = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_email = '$wpu->user_email' AND user_id != '2'");
 	    	 
 	    	    if ( null !== $phpbb_is_there_anybody ) { // revert // if there are usernames or email address, reset to old value and return error
      	
          $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
  
        $old_user_data->user_login = esc_sql($old_user_data->user_login);
	      $wpdb->query("UPDATE $wpu_db_utab SET user_email = '$old_user_data->user_email' WHERE user_login = '$old_user_data->user_login'");
   
       return true;
       
     }
                                               
               $phpbb_user_data = $w3phpbb_conn->get_results("SELECT *  
                                   FROM ". $phpbb_config_file["table_prefix"] ."users  
                                   JOIN ". $phpbb_config_file["table_prefix"] ."groups ON ". $phpbb_config_file["table_prefix"] ."groups.group_id = ". $phpbb_config_file["table_prefix"] ."users.group_id 
                                   AND  ". $phpbb_config_file["table_prefix"] ."users.user_id = '2' 
                                   LEFT JOIN ". $phpbb_config_file["table_prefix"] ."profile_fields_data ON ". $phpbb_config_file["table_prefix"] ."profile_fields_data.user_id = '2'");
                                        
      
      
      } else {
      	
      $wpu->user_login = esc_sql($wpu->user_login);   	
      $phpbb_is_there_anybody = $w3phpbb_conn->get_row("SELECT username, user_email FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_email = '$wpu->user_email' AND username != '$wpu->user_login'");
 	    	    
 	    	       if ( null !== $phpbb_is_there_anybody ) { // revert // if there are usernames or email address, reset to old value and return error
      	
          $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpu_db_utab = $wpdb->prefix . 'users';
   	    //$wpu_db_utab = $wpdb->prefix . 'users';
  
	      $wpdb->query("UPDATE $wpu_db_utab SET user_email = '$old_user_data->user_email' WHERE user_login = '$old_user_data->user_login'");
   
       return true;
       
     }
 	         
           	$phpbb_user_data = $w3phpbb_conn->get_results("SELECT *  
                                              FROM ". $phpbb_config_file["table_prefix"] ."users  
                                              JOIN ". $phpbb_config_file["table_prefix"] ."groups ON ". $phpbb_config_file["table_prefix"] ."groups.group_id = ". $phpbb_config_file["table_prefix"] ."users.group_id 
                                              AND  ". $phpbb_config_file["table_prefix"] ."users.user_email = '".$wpu->user_email."' 
                                              LEFT JOIN ". $phpbb_config_file["table_prefix"] ."profile_fields_data ON ". $phpbb_config_file["table_prefix"] ."profile_fields_data.user_id = ". $phpbb_config_file["table_prefix"] ."users.user_id");       
             	
             $phpbb_udata_rget = $w3phpbb_conn->get_results("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '".$wpu->user_login."'");	// as we have not the uid on phpBB with above query
                                
  }
    
   // prepare update 
   
            // NOTE phpbb_verify_credentials do the update of same fields so if code changes are done here
            // we need to look also the phpbb_verify_credentials method and add same things on it

       $wp_umeta = get_user_meta($wpu->ID, '', false);

   		if( empty($wp_umeta['locale'][0]) OR $w3all_phpbb_lang_switch_yn == 0 ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
   		   $wp_lang_x_phpbb = 'en'; // no lang setting, assume en by default
   			} else { 
   				 $wp_lang_x_phpbb = strtolower(substr($wp_umeta['locale'][0], 0, strpos($wp_umeta['locale'][0], '_'))); // should extract Lang code ISO Code that is phpBB suitable for this lang
   				}

             $user_email_hash = self::w3all_phpbb_email_hash($wpu->user_email);
             
            if ( $wpu->ID == 1 ) { // install admin update // except roles
            	
            	               	// profile row exist for this user?
               	        	$phpbb_profile_fields = $w3phpbb_conn->get_results("SELECT *  
                                              FROM ". $phpbb_config_file["table_prefix"] ."profile_fields_data  
                                              JOIN ". $phpbb_config_file["table_prefix"] ."users ON ". $phpbb_config_file["table_prefix"] ."users.user_id = ". $phpbb_config_file["table_prefix"] ."profile_fields_data.user_id  
                                              AND  ". $phpbb_config_file["table_prefix"] ."users.user_id = '2'");
             
             $uid = 2; // switch to admin uid 
             
             if(empty($phpbb_profile_fields)){
              	// you need to add here for admin and below for others users UPDATE, any other value for external plugins updates!
              	// the same need to be done into phpbb_verify_credentials
              	
              	$u_url = $wpu->user_url;
              
              $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '2'");
  	         if(!empty( $u_url )){
  	         	// phpBB version 3.2>
  	         	   if( $phpbb_version == '3.2' ){
  	              $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_location, pf_phpbb_youtube, pf_phpbb_twitter, pf_phpbb_googleplus, pf_phpbb_skype, pf_phpbb_facebook, pf_phpbb_icq, pf_phpbb_website, pf_phpbb_yahoo, pf_phpbb_aol)
                  VALUES ('$uid','','','','','','','','','','$u_url','','') ON DUPLICATE KEY UPDATE pf_phpbb_website = '$u_url'");
 
        	       } else { // phpbb <3.2
        	     	        $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_facebook, pf_phpbb_googleplus, pf_phpbb_icq, pf_phpbb_location, pf_phpbb_skype, pf_phpbb_twitter, pf_phpbb_website, pf_phpbb_wlm, pf_phpbb_yahoo, pf_phpbb_youtube, pf_phpbb_aol)
                        VALUES ('$uid','','','','','','','','','$u_url','','','','') ON DUPLICATE KEY UPDATE pf_phpbb_website = '$u_url'");

        	              }
        	    
        	    }
        	} else {
        		 if( $w3all_phpbb_lang_switch_yn == 1 ){ // update lang if activated option
              $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '2'");
             } else { // not update lang if not activated option
             	$w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash' WHERE user_id = '2'");
             }
              $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."profile_fields_data SET pf_phpbb_website = '$wpu->user_url' WHERE user_id = '$uid'");
	
	             // update more fields here: phpBB tab -> 'phpbb_profile_fields_data'
            }
            
               } else { // all others // also roles
               	// the same need to be done into phpbb_verify_credentials
               	
               	$uid = $phpbb_udata_rget[0]->user_id; 
               	$u_url = $wpu->user_url;
               	
               	        	$phpbb_profile_fields = $w3phpbb_conn->get_results("SELECT *  
                                              FROM ". $phpbb_config_file["table_prefix"] ."profile_fields_data  
                                              JOIN ". $phpbb_config_file["table_prefix"] ."users ON ". $phpbb_config_file["table_prefix"] ."users.user_id = ". $phpbb_config_file["table_prefix"] ."profile_fields_data.user_id  
                                              AND  ". $phpbb_config_file["table_prefix"] ."users.username = '".$wpu->user_login."'");
              
        
                if(empty($phpbb_profile_fields)){
                	 if( $w3all_phpbb_lang_switch_yn == 1 ){ // not update lang if not activated option
                    $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '$uid'");
              	   } else {
              	  	    $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash' WHERE user_id = '$uid'");
              	       }
              	if (!empty($u_url)){
 	         	    // phpBB version 3.2>
  	         	   if( $phpbb_version == '3.2' ){
  	              $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_location, pf_phpbb_youtube, pf_phpbb_twitter, pf_phpbb_googleplus, pf_phpbb_skype, pf_phpbb_facebook, pf_phpbb_icq, pf_phpbb_website, pf_phpbb_yahoo, pf_phpbb_aol)
                  VALUES ('$uid','','','','','','','','','','$u_url','','') ON DUPLICATE KEY UPDATE pf_phpbb_website = '$u_url'");
 
        	       } else{ // phpbb <3.2
        	     	        $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_facebook, pf_phpbb_googleplus, pf_phpbb_icq, pf_phpbb_location, pf_phpbb_skype, pf_phpbb_twitter, pf_phpbb_website, pf_phpbb_wlm, pf_phpbb_yahoo, pf_phpbb_youtube, pf_phpbb_aol)
                        VALUES ('$uid','','','','','','','','','$u_url','','','','') ON DUPLICATE KEY UPDATE pf_phpbb_website = '$u_url'");

        	              }
             	      }
              } else {
              	        if( $w3all_phpbb_lang_switch_yn == 1 ){ // not update lang if not activated option
               	          $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '$uid'");
               	        } else {
               	        	 $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$wpu->user_pass', user_email = '$wpu->user_email', user_email_hash = '$user_email_hash' WHERE user_id = '$uid'");
               	        }
                          $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."profile_fields_data SET pf_phpbb_website = '$u_url' WHERE user_id = '$uid'");
            	        }
        }
}     

        
public static function w3_check_phpbb_profile_wpnu($username){

	if(empty($username)): return; endif;

 global $w3all_config,$wpdb;
 $phpbb_config_file = $w3all_config;
 $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();
 
    $user_id = username_exists( $username );
    $user_info = get_userdata( $user_id );

if(isset($_POST["log"])){

     $username = sanitize_user( $username, $strict = false ); 
 
         //////// phpBB username chars fix          	   	
         // phpBB need to have users without characters like ' that is not allowed in WP as username
         // If old phpBB usersnames are like myuse'name on WP_w3all integration, do not add into WP
         // check for 2 more of these on this class.wp.w3all-phpbb.php, and 1 on ext_plugins_fixes.php in case will be necessary to add something or remove
  
          $pattern = '/\'/';
          preg_match($pattern, $username, $matches);
          
          if($matches){
	        echo '<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em">Sorry, your <strong>registered username on our forum contain characters not allowed on this CMS system</strong>, you can\'t be added or login in this site side (and you\'ll see this message) until logged in on forums with this username. Please return back and contact the administrator reporting about this error issue. Thank you <input type="button" value="Go Back" onclick="history.back(-1)" /></p>';
          return;
          
          }

          $username = esc_sql($username);
          $phpbb_user = $w3phpbb_conn->get_results("SELECT u.*, g.* 
                                               FROM ". $phpbb_config_file["table_prefix"] ."users u, ". $phpbb_config_file["table_prefix"] ."groups g 
                                               WHERE u.username = '".$username."' 
                                               AND u.group_id != '1' AND u.group_id != '6' 
                                               AND u.group_id = g.group_id");            

      if(empty($phpbb_user)){ return; }
      if ( $phpbb_user[0]->user_id < 3 ){ // exclude the default phpBB install admin
      	return; 
		  }

// activated on phpBB?
if( $phpbb_user[0]->user_type != 1 && empty($user_info->wp_capabilities) ){ // re-activate this 'No role' WP user

  if ( is_multisite() ) {

	 	 $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpu_db_utab = $wpdb->prefix . 'usermeta';
	 	 $subscriber = 'a:1:{s:10:"subscriber";b:1;}';
	   $wpdb->query("UPDATE $wpu_db_utab SET meta_value = '$subscriber' WHERE user_id = '$user_id' AND meta_key = 'wp_capabilities'");

	  
  } else {
	 	 // user should be re-activated with proper role maybe: subscriber as default as it is 
	 	 // here the db tab will be the one of the site, the user will login first (or come as logged)
	 	 // so not switch the db table prefix here
	 	    $wpu_db_utab = $wpdb->prefix . 'usermeta';
	 	    $subscriber = 'a:1:{s:10:"subscriber";b:1;}';
	      $wpdb->query("UPDATE $wpu_db_utab SET meta_value = '$subscriber' WHERE user_id = '$user_id' AND meta_key = 'wp_capabilities'");
	    }	   
}

 if ( !is_multisite() ) {
 	// NOTE: TODO check if this is suitable for all login flows
 if(!empty($user_info)){ 	
  $wp_urole = implode(', ', $user_info->roles);
  if( $phpbb_user[0]->user_type == 1 && !empty($user_info->wp_capabilities) ){ // workaround for this new WP user, may still not active into phpBB
   $user_email_hash = self::w3all_phpbb_email_hash($user_info->user_email);
   $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '0' WHERE user_email_hash = '$user_email_hash'");
  }
 }
}

	 if ( ! $user_id  && $phpbb_user[0]->user_type != 1 ) { 

        if ( $phpbb_user[0]->group_name == 'ADMINISTRATORS' ){
      	      
      	      $role = 'administrator';
      	      
            } elseif ( $phpbb_user[0]->group_name == 'GLOBAL_MODERATORS' ){
            	
            	   $role = 'editor';
          	  
               } elseif ( $phpbb_user[0]->user_type != 1 && empty($user_info->wp_capabilities) ) {
               	 $role = '';
              }
               
               else { $role = 'subscriber'; }  // for all others phpBB Groups default to WP subscriber
               
               
          	
              $userdata = array(
               'user_login'       =>  $phpbb_user[0]->username,
               'user_pass'        =>  $phpbb_user[0]->user_password,
               'user_email'       =>  $phpbb_user[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbb_user[0]->user_regdate ),
               'role'             =>  $role
               );
               
           $user_id = wp_insert_user( $userdata );
           
   if(is_wp_error( $user_id )){
       echo '<h3>Error: '.$user_id->get_error_message().'</h3>' . '<h4><a href="'.get_edit_user_link().'">Return back</a><h4>';
           exit;
    }
           
 if ( !is_multisite() ) { // check that this user is correctly activated in wp at this point and add options about roles 
     $wpu_db_utab = $wpdb->prefix . 'usermeta';
	 	 $subscriber = 'a:1:{s:10:"subscriber";b:1;}';
	   $wpdb->query("UPDATE $wpu_db_utab SET meta_value = '$subscriber' WHERE user_id = '$user_id' AND meta_key = 'wp_capabilities'");
 }  
           
  if ( is_multisite() ) {
	 	 $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpu_db_utab = $wpdb->prefix . 'usermeta';
	 	 $wpu_db_captab = $wpdb->prefix . 'capabilities';
	 	 $subscriber = 'a:1:{s:10:"subscriber";b:1;}';
	   $wpdb->query("UPDATE $wpu_db_utab SET meta_value = '$subscriber' WHERE user_id = '$user_id' AND meta_key = '$wpu_db_captab'");
  }     

        }	  
		}
		
		return;
}    


public static function wp_w3all_get_phpbb_user_info($username){ // email/user_object/username
	
 global $w3all_config;
 $phpbb_config_file = $w3all_config;
 $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();
 
         //////// phpBB username chars fix          	   	
         // phpBB need to have users without characters like ' that is not allowed in WP as username
         // If old phpBB usernames are like myuser'name, do not add into WP
         // check for 2 more of these on this class.wp.w3all-phpbb.php in case you need to add something or remove
              
          $pattern = '/\'/';
          preg_match($pattern, $username, $matches);
          
          if($matches){
	         echo '<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em">Sorry, your <strong>registered username on our forum contain characters not allowed on this CMS system</strong>, you can\'t be added or login in this site side (and you\'ll see this message) until logged in on forums with this username. Please return back and contact the administrator reporting about this error issue. Thank you <input type="button" value="Go Back" onclick="history.back(-1)" /></p>';
           return;
          }

   $uname_email = is_email( $username ) ? 'user_email_hash' : 'username';

  if(is_email( $username )){ 
   $username = self::w3all_phpbb_email_hash($username);
  } elseif (is_object( $username )) {
  	$username = $username->user_login;
  } else { $username = $username; }

  $username = esc_sql($username);

   $phpbb_user_data = $w3phpbb_conn->get_results("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users 
   JOIN ". $phpbb_config_file["table_prefix"] ."groups ON ". $phpbb_config_file["table_prefix"] ."groups.group_id = ". $phpbb_config_file["table_prefix"] ."users.group_id
  AND ".$phpbb_config_file["table_prefix"]."users.".$uname_email." = '".$username."'");
  
 return $phpbb_user_data;
 
} 

public static function wp_w3all_phpbb_delete_user ($user_id){
	
 global $w3all_config;
 $phpbb_config_file = $w3all_config;
 $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();
 
// Only deactivate user in phpBB if deleted on WP
// TODO: switch to email hash only
 $user = get_user_by( 'ID', $user_id );
 $user->user_login = esc_sql($user->user_login);
 $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '1' WHERE username = '$user->user_login'");
// temp fix for signups remove (if the user had not ativate his account maybe)
 global $wpdb;
 // exist the signup table?
 $wpu_db_utab = $wpdb->prefix . 'signups';
 $wpdb->query("SHOW TABLES LIKE '$wpu_db_utab'");
  if($wpdb->num_rows > 0){
  $wpdb->query("DELETE FROM $wpu_db_utab WHERE user_email = '$user->user_email' OR user_login = '$user->user_login'");
  }
}
    

public static function wp_w3all_phpbb_delete_user_signup($user_id){
	
 global $w3all_config;
 $phpbb_config_file = $w3all_config;
 $w3phpbb_conn = self::wp_w3all_phpbb_conn_init();
 
// Only deactivate user in phpBB if deleted on WP

 $user = get_user_by( 'ID', $user_id );
 $user_email_hash = self::w3all_phpbb_email_hash($user->user_email);
 $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '1' WHERE user_email_hash = '$user_email_hash'");
if ( is_multisite() ) { // clean also signup of this user if WPMU on delete for compatibility with integration
	// the check is done against an user that exist into users table, not signup: change in case this to better do all things?
	// reason: we can't leave the user into signup, while not result in user tab: because in phpBB an user could register with same email another user in the while
global $wpdb;
$wpu_db_utab = $wpdb->prefix . 'signups';
$wpdb->query("DELETE FROM $wpu_db_utab WHERE user_email = '$user->user_email' OR user_login = '$user->user_login'");
}
}


 public static function wp_w3all_wp_after_pass_reset( $user ) { 
	
	 global $w3all_config,$w3all_phpbb_user_deactivated_yn;
	
	$w3db_conn = self::wp_w3all_phpbb_conn_init();
	$phpbb_config_file = $w3all_config;
    
    $user_info = get_userdata($user->ID);
    $wp_user_role = implode(', ', $user_info->roles);

if ( $w3all_phpbb_user_deactivated_yn == 1 && !empty($wp_user_role) OR $w3all_phpbb_user_deactivated_yn != 1 ){

		$phpbb_user_data = self::wp_w3all_get_phpbb_user_info($user->user_email);

		if ( $phpbb_user_data[0]->user_type == 1 ) {
			$res = $w3db_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '0' WHERE user_email_hash = '".$phpbb_user_data[0]->user_email_hash."'");
     }

  }
}

//#######################
// START ABOUT AVATARS
//#######################

// from 1.6.7 switch to search in phpBB as email hash that is Index Key into phpBB db users table

public static function wp_w3all_assoc_phpbb_wp_users() {
	 
	global $w3all_get_phpbb_avatar_yn, $w3all_last_t_avatar_yn, $w3all_lasttopic_avatar_num;
	$w3all_avatars_yn = $w3all_get_phpbb_avatar_yn == 1 ? true : false;
	
    $nposts = get_option( 'posts_per_page' );

 $post_list = get_posts( array(
    'user_id',
    'numberposts'    => $nposts,
    'sort_order' => 'desc',
    'post_status' => 'publish'
  ) );
  
    foreach ( $post_list as $post ) {
    	
     $uname = get_user_by('ID', $post->post_author);
     $p_unames[] = self::w3all_phpbb_email_hash($uname->user_email);
   
     $comments = get_comments( array( 'post_id' => $post->ID ) );
      foreach ( $comments as $comment ) :
       if ( $comment->user_id > 0 ):
        $p_unames[] = self::w3all_phpbb_email_hash($comment->comment_author_email);
       endif;
      endforeach;
   }
   
// add the current user
// if any other condition fail assigning avatars to users, add it here

   // add current user
   $current_user = wp_get_current_user();
   if ($current_user->ID > 0){
   $p_unames[] = self::w3all_phpbb_email_hash($current_user->user_email);
  }
 
  // add usernames for last topics widget, if needed
 if ( $w3all_avatars_yn ) : 
  $w3all_last_posts_users = self::last_forums_topics($w3all_lasttopic_avatar_num);
   if(!empty($w3all_last_posts_users)):
      foreach ( $w3all_last_posts_users as $post_uname ) :
       $pun = esc_sql($post_uname->user_email_hash);
       $p_unames[] = $pun;
      endforeach;
   endif;
 endif;

  $w3_un_results = array_unique($p_unames); 

$query_un ='';
   foreach($w3_un_results as $w3_unames_ava)
   {
    	$query_un .= '\''.$w3_unames_ava.'\',';
   }

 $query_un = substr($query_un, 0, -1);
      
 $w3all_u_ava_urls = self::w3all_get_phpbb_avatars_url($query_un);

 if (!empty($w3all_u_ava_urls)){

    foreach( $w3all_u_ava_urls as $ava_set_x ){
   
    	$usid = get_user_by('login', $ava_set_x['uname']);
    	
    	if($usid):
      	$wp_user_phpbb_avatar[] = array("wpuid" => $usid->ID, "phpbbavaurl" => $ava_set_x['uavaurl']);
      endif;
  }
}

  $wp_user_phpbb_avatar = (empty($wp_user_phpbb_avatar)) ? '' : $wp_user_phpbb_avatar;
  
   $u_a = serialize($wp_user_phpbb_avatar);
   define("W3ALLPHPBBUAVA", $u_a);
  return $wp_user_phpbb_avatar;

}


public static function w3all_get_phpbb_avatars_url( $w3unames ) {
   global $w3all_config;
  $config = $w3all_config;
  $w3db_conn = self::w3all_db_connect();
	//$phpbb_config = self::get_phpbb_config();
	$phpbb_config = unserialize(W3PHPBBCONFIG);
// this not work by user_email_hash, but were necessary by username
// $uavatars = $w3db_conn->get_results( $w3db_conn->prepare("SELECT username, user_avatar, user_avatar_type FROM ".$config["table_prefix"]."users WHERE user_email_hash IN(%d) ORDER BY user_id DESC", $w3unames ));
 $uavatars = $w3db_conn->get_results( "SELECT username, user_avatar, user_avatar_type FROM ".$config["table_prefix"]."users WHERE user_email_hash IN(".$w3unames.") ORDER BY user_id DESC" );

  if(!empty($uavatars)){

   	foreach($uavatars as $user_ava) {
     	
     if(!empty($user_ava->user_avatar)){ // has been selected above by the way, check it need to be added
     	
     		if ( $user_ava->user_avatar_type == 'avatar.driver.local' ){
     			
     			$phpbb_avatar_url = get_option( 'w3all_url_to_cms' ) . '/' . $phpbb_config["avatar_gallery_path"] . '/' . $user_ava->user_avatar;
     			$u_a[] = array("uname" => $user_ava->username, "uavaurl" => $phpbb_avatar_url);
     		
     		}  elseif ( $user_ava->user_avatar_type == 'avatar.driver.remote' ){
     			$phpbb_avatar_url = $user_ava->user_avatar;
     			$u_a[] = array("uname" => $user_ava->username, "uavaurl" => $phpbb_avatar_url);
     		
     		} else {
 	         $avatar_entry = $user_ava->user_avatar;
            $ext = substr(strrchr($avatar_entry, '.'), 1);
	         // $avatar_entry	= intval($avatar_entry);
           // LEPALOSE MODIFIED ABOVE LINE TO BELOW
           $avatar_entry = strtok($avatar_entry, '_');
             
          // LEPALOSE COMMENT OUT BELOW
	        // if ( $user_ava->user_avatar_type == 'avatar.driver.upload' && preg_match('/^([g])([0-9]+).*/', $user_ava->user_avatar, $w3m, PREG_OFFSET_CAPTURE) )
	        /* { 
            if($w3m[1][0] && $w3m[2][0]){ // this is a group avatar
          	 $gprefix = '_' . $w3m[1][0] . $w3m[2][0]; // switch
            }
	        }
	        
	          if ( $user_ava->user_avatar_type == 'avatar.driver.upload' && isset($gprefix) ){ // switch
	          	$phpbb_avatar_filename = $phpbb_config["avatar_salt"]  . $gprefix . '.' . $ext;
	          } else {
	                  $phpbb_avatar_filename = $phpbb_config["avatar_salt"] . '_' . $avatar_entry . '.' . $ext;
	                }
            LEPALOSE END OF COMMENT OUT */
            // LEPALOSE COPY ELSE LINE ABOVE TO THE LINE BELOW
            $phpbb_avatar_filename = $phpbb_config["avatar_salt"] . '_' . $avatar_entry . '.' . $ext;

            $phpbb_avatar_url = get_option( 'w3all_url_to_cms' ).'/'.$phpbb_config["avatar_path"].'/'.$phpbb_avatar_filename;

    	// in phpBB there is Gravatar as option available as profile image
    	// so if it is the case, the user at this point can have an email address, instead than an image url as value
      // $pemail = '/^.*@[-a-z0-9]+\.+[-a-z0-9]+[\.[a-z0-9]+]?/';
      // preg_match($pemail, $user_ava->user_avatar, $url_email);
      // $phpbb_avatar_url = (empty($url_email)) ? $phpbb_avatar_url : $user_ava->user_avatar;
       
        $phpbb_avatar_url = ( is_email( $user_ava->user_avatar ) !== false ) ? $user_ava->user_avatar : $phpbb_avatar_url;
        $u_a[] = array("uname" => $user_ava->username, "uavaurl" => $phpbb_avatar_url);
      } 
     } 
    } 
  } else { $u_a = ''; }
  	$u_a = (empty($u_a)) ? '' : $u_a;
  return $u_a;
}


public static function wp_w3all_phpbb_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

//$uids_urls = self::wp_w3all_assoc_phpbb_wp_users();
$uids_urls = unserialize(W3ALLPHPBBUAVA);

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }

 	if ( isset($user) && $user && is_object( $user ) ) {
 		
     if (!empty($uids_urls)){

       foreach($uids_urls as $w3all_wupa) {
     	
    	    //$pemail = '/^.*@[-a-z0-9]+\.+[-a-z0-9]+[\.[a-z0-9]+]?/';
          //preg_match($pemail, $w3all_wupa["phpbbavaurl"], $is_email);
          //$phpbb_avatar_url = (is_email( $user_ava->user_avatar ) == true) ? $user_ava->user_avatar : $phpbb_avatar_url;
          //could be an email, get so gravatar url if the case
          if( is_email( $w3all_wupa["phpbbavaurl"] ) !== false ) {
           $w3all_wupa["phpbbavaurl"] = get_avatar_url( $w3all_wupa["phpbbavaurl"] );
          } 

          if ( $user->data->ID == $w3all_wupa["wpuid"] ) {
           	  $avatar = $w3all_wupa["phpbbavaurl"];
              $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
         } 
       }
     } 
 }
   
    return $avatar;
}

public static function init_w3all_avatars(){
	self::wp_w3all_assoc_phpbb_wp_users();
	add_filter( 'get_avatar', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_custom_avatar' ), 10 , 5  );
}

//#######################
// END ABOUT AVATARS
//#######################

public static function w3all_get_phpbb_config_res() {
	
    $res = self::w3all_get_phpbb_config();
    return $res;
 } 

public static function create_phpBB_user_res($wpu) {
	
    $res = self::create_phpBB_user($wpu);
    return $res;
 }  

public static function phpBB_user_session_set_res($wp_user_data){
	
      $res = self::phpBB_user_session_set($wp_user_data);                                 
	   return $res; 
}


public static function phpbb_pass_update_res($user, $new_pass){
	
      $res = self::phpbb_pass_update($user, $new_pass);                                 
	   return $res; 
}


public static function last_forums_topics_res($ntopics){
	
      $topics_display = self::last_forums_topics($ntopics);                                 
	   return $topics_display; 
}

public static function wp_w3all_phpbb_conn_init() {
	
        	$w3db_conn = self::w3all_db_connect();	
        	return $w3db_conn;
	}

//############################################
// START PHPBB TO WP FUNCTIONS
//############################################

public static function phpBB_password_hash($pass){

 // phpBB 3.1> require bcrypt() with a min cost of 10
   require_once( WPW3ALL_PLUGIN_DIR . '/addons/bcrypt/bcrypt.php');
    $pass = htmlspecialchars(trim($pass));
    $hash = w3_Bcrypt::hashPassword($pass, 10);
   return $hash;
}

// wp_w3all custom -> phpBB get_unread_topics() ... this should fit any needs about users read/unread topics/posts.
// using it only for WP registered users, as option to be activated on w3all_config admin page. Retrieve read/unread posts in phpBB for WP current user
// $sql_limit = wp_w3all last topics numb to retrieve option
// original function in phpBB: get_unread_topics($username = false, $sql_extra = '', $sql_sort = '', $sql_limit = 1001, $sql_limit_offset = 0) // the phpBB function into functions.php file

public static function w3all_get_unread_topics($username = false, $sql_extra = '', $sql_sort = '', $sql_limit = 1001, $sql_limit_offset = 0)
{    
        global $w3all_config,$w3all_lasttopic_avatar_num;
        // NOTE this: we guess an user have setup a value for 'Last Forums Topics number of users's avatars to retrieve' on wp_w3all config: if not, we'll search until 50 by default. If a widget need to display more than 50 posts and no avatar option is active, than this value need to be changed here directly, or setting up the option value for 'Last Forums Topics number of users's avatars to retrieve' even if avatars aren't used
        $sql_limit = empty($w3all_lasttopic_avatar_num) ? 50 : $w3all_lasttopic_avatar_num;

        $phpbb_config_file = $w3all_config;
	      $w3phpbb_conn = self::w3all_db_connect();
        $phpbb_config = unserialize(W3PHPBBCONFIG);
        /*$phpbb_usession = unserialize(W3PHPBBUSESSION);*/

     /*
     $u = $phpbb_config["cookie_name"].'_u';
      if ( isset($_COOKIE[$u]) && $_COOKIE[$u] > 1 ){
        if ( preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	 die( "Please clean up bad cookies on your browser." );
 	            }     
 	      $user_id = $_COOKIE[$u]; 
      } else { return; }
      */
   
    $user = wp_get_current_user();
    if ($user->ID < 1){ return false; } // only for WP logged in users
    $user_email_hash = self::w3all_phpbb_email_hash($user->user_email);
    $phpbb_u = $w3phpbb_conn->get_row("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_email_hash = '$user_email_hash' OR username = '$user->user_login'") ;
    $user_id = $phpbb_u->user_id;
      
    $last_mark = $phpbb_u->user_lastmark; // when/if the user have mark all as read
    // $last_mark = time() - 3600; //$phpbb_u->user_lastvisit; //time() - 1123600; // some test ...

	// Data array we're going to return
	$unread_topics = array();

	if (empty($sql_sort))
	{
		$sql_sort = "ORDER BY ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_time DESC, ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_id DESC";
	}

	//if ($config['load_db_lastread'] && $user->data['is_registered']) // wp_w3all config active or not, and user logged or not. At moment all this is not necessary:
	if($user_id > 0)
	{
		// Get list of the unread topics
		
	 $w3all_exec_sql_array = $w3phpbb_conn->get_results("SELECT ".$phpbb_config_file["table_prefix"]."topics.topic_id, ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_time, ".$phpbb_config_file["table_prefix"]."topics_track.mark_time as topic_mark_time, ".$phpbb_config_file["table_prefix"]."forums_track.mark_time as forum_mark_time 
     FROM ".$phpbb_config_file["table_prefix"]."topics 
      LEFT JOIN ".$phpbb_config_file["table_prefix"]."topics_track 
        ON ".$phpbb_config_file["table_prefix"]."topics_track.user_id = '".$user_id."' 
       AND ".$phpbb_config_file["table_prefix"]."topics.topic_id = ".$phpbb_config_file["table_prefix"]."topics_track.topic_id 
      LEFT JOIN ".$phpbb_config_file["table_prefix"]."forums_track 
        ON ".$phpbb_config_file["table_prefix"]."forums_track.user_id = '".$user_id."' AND ".$phpbb_config_file["table_prefix"]."topics.forum_id = ".$phpbb_config_file["table_prefix"]."forums_track.forum_id
     WHERE ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_time > '".$last_mark."' 
       AND (
				(".$phpbb_config_file["table_prefix"]."topics_track.mark_time IS NOT NULL AND ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_time > ".$phpbb_config_file["table_prefix"]."topics_track.mark_time) OR
				(".$phpbb_config_file["table_prefix"]."topics_track.mark_time IS NULL AND ".$phpbb_config_file["table_prefix"]."forums_track.mark_time IS NOT NULL AND ".$phpbb_config_file["table_prefix"]."topics.topic_last_post_time > ".$phpbb_config_file["table_prefix"]."forums_track.mark_time) OR
				(".$phpbb_config_file["table_prefix"]."topics_track.mark_time IS NULL AND ".$phpbb_config_file["table_prefix"]."forums_track.mark_time IS NULL)
				)
			$sql_sort LIMIT $sql_limit");

    foreach( $w3all_exec_sql_array as $k => $v ):
      $topic_id = $v->topic_id;
			$unread_topics[$topic_id] = ($v->topic_mark_time) ? (int) $v->topic_mark_time : (($v->forum_mark_time) ? (int) $v->forum_mark_time : $last_mark);
    endforeach;
    
    $unread_topics = serialize($unread_topics); // to pass array into define, prior php7
    define( "W3UNREADTOPICS", $unread_topics ); // better define here the result, than recall this function into wp_w3all_phpbb_last_topics() for each possible last topics activated instance?
	 return $unread_topics;
	}

	return false;
}

public static function w3all_phpbb_email_hash($email)
{    
  $h = sprintf('%u', crc32(strtolower($email))) . strlen($email);
   return $h;
}
//############################################
// END PHPBB TO WP FUNCTIONS
//############################################

//############################################
// START X WP MS MU
//############################################

public static function create_phpBB_user_wpms_res($username, $user_email, $key, $meta){
	
      $r = self::create_phpBB_user_wpms($username, $user_email, $key, $meta);                                 
	   return $r; 
}

public static function wp_w3all_wp_after_pass_reset_msmu( $user ) { 
	
	 global $w3all_config,$wpdb;
	if(!$user){ return; }
	$w3db_conn = self::wp_w3all_phpbb_conn_init();
	$phpbb_config_file = $w3all_config;
 
		$user_email_hash = self::w3all_phpbb_email_hash($user->user_email);
    $res = $w3db_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."users SET user_type = '0', user_password = '".$user->user_pass."' WHERE user_email_hash = '".$user_email_hash."'");

}

private static function create_phpBB_user_wpms($username = '', $user_email = '', $key = '', $meta = ''){
	
	// $username can be ID and $key 'is_admin_action'

	global $w3all_config, $w3all_phpbb_lang_switch_yn;
   $phpbb_config_file = $w3all_config;
	 $w3phpbb_conn = self::w3all_db_connect();
   $phpbb_config = unserialize(W3PHPBBCONFIG);
   $wp_lang = get_option('WPLANG');
   
   if( $key == 'is_admin_action' ){ // see wp_w3all.php // add_action( 'init', 'w3all_network_admin_actions' );
   	$user = get_user_by( 'ID', $username ); // passed user ID in place of username - see wp_w3all.php mums // add_action( 'init', 'w3all_network_admin_actions' );
    $username = $user->user_login; 
  	$user_email = $user->user_email; 
  }

   // review this two checks maybe not necessary
   			  $username = sanitize_user($username, $strict = false ); 
  			  $email = sanitize_email($user_email);

   		if(empty($wp_lang) OR $w3all_phpbb_lang_switch_yn == 0 ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
   		   $wp_lang_x_phpbb = 'en'; // no lang setting, assume en by default
   			} else { 
   				 $wp_lang_x_phpbb = strtolower(substr($wp_lang, 0, strpos($wp_lang, '_'))); // should extract Lang code ISO Code that is phpBB suitable for this lang
   				}

    if( empty($username) OR empty($user_email) OR !is_email($user_email) ){ return; }
    
     //maybe to be added as option
     // if you wish to setup gravatar by default into phpBB profile for the user when register in WP
     $uavatar = $avatype = ''; // this not will affect queries if the two here below are or not commented out 
     //$uavatar = get_option('show_avatars') == 1 ? $wpu->user_email : '';
     //$avatype = (empty($uavatar)) ? '' : 'avatar.driver.gravatar';
     
     $username = esc_sql($username);

            $u = $phpbb_config["cookie_name"].'_u';
            
            if ( preg_match('/[^0-9]/',$_COOKIE[$u]) ){
 	           	
                die( "Clean up cookie on your browser please!" );
 	            }
 	            
 	           $phpbb_u = $_COOKIE[$u];
 	        
 	    // only need to fire when user do not exist on phpBB already, and/or user is an admin that add an user manually 
   if ( $phpbb_u < 2 OR !empty($phpbb_u) && current_user_can( 'manage_options' ) === true ) {
      
      $phpbb_user_type = 1; //  set to 1 as deactivated on phpBB on WP MSMU except for admin action
      if( $key == 'is_admin_action' ){ 
      	$phpbb_user_type = 0; 
      }
      
	    $user_email_hash = self::w3all_phpbb_email_hash($email);
	     
      $wpur = time();
      $wpul = $username;
      $wpup = md5(mt_rand(5,10) . microtime() . str_shuffle("ALEa0bc1AdeOf28P3ghEij4kRlm5nopqrD0Lst9uvwx9yzSSIO" . microtime()) . mt_rand(10,20)); // a temp pass to be updated after signup finished
      $wpup = self::phpBB_password_hash($wpup); // a temp pass, even not necessary as the user is not active at this point for wp msmu
    if( $key == 'is_admin_action' ){ 
      	$wpup = $user->user_pass; // if admin action, add the pass of this user
      }
      $wpue = $email;
      $time = time();

      $wpunn = esc_sql(utf8_encode(strtolower($wpul)));
      $wpul  = esc_sql($wpul);
      $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
         VALUES ('','$phpbb_user_type','2','','0','', '$wpur', '$wpul', '$wpunn', '$wpup', '0', '$wpue', '$user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', 'D M d, Y g:i a', '1', '0', '', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '$uavatar', '$avatype', '0', '0', '', '', '', '', '', '', '', '0', '0', '0')");
      
      $phpBBlid = $w3phpbb_conn->insert_id;
   
     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('2','$phpBBlid','0','0')");
     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");

     $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('$phpBBlid','0','0','6','0')");
    		
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = config_value + 1 WHERE config_name = 'num_users'");

       $newest_member = $w3phpbb_conn->get_results("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$phpbb_config_file["table_prefix"]."users) AND group_id != '6'");
       $uname = $newest_member[0]->username;
       $uid   = $newest_member[0]->user_id;
     
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$wpul' WHERE config_name = 'newest_username'");
     $w3phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");

 
 }
 
}

//############################################
// END X WP MS MU
//############################################

} // END class WP_w3all_phpbb
?>
