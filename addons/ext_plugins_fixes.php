<?php
//// workaround for some plugin that substitute wp-login.php default login page
//// ... but that DO NOT reset $_POST array (like some frontend ajax login widget plugin do)

//// NOTE: you can remove this, commenting the follow line on wp_w3all.php file:
////  require_once( WPW3ALL_PLUGIN_DIR . 'addons/ext_plugins_fixes.php' );

// to detect if we are onlogin in WP

   		$w3all_check_ext_login = 0; 
   		
   			foreach ($_POST as $key => $value) {
   			
          if( strstr($key,'username') OR strstr($key,'password') OR strstr($key,'log') OR strstr($key,'pwd') OR strstr($key,'user_login') OR strstr($key,'login_user_pass') ){
          
           $w3all_uap[] = $value;
   		  	 $w3all_check_ext_login++;
   		  	 $w3all_on_ext_login = true;
   		    }
   		     if(strstr($key,'rememberme')){
   		    	$phpbb_k_val_yn = (empty($value)) ? 0 : 1;
   		     }
   		  }

	function wp_w3all_detect_login(){
		
    global $w3all_check_ext_login, $w3all_uap, $phpbb_k_val_yn;

   		if(  $w3all_check_ext_login > 0 ) {

  $pattern = '/^.*@[-a-z0-9]+\.+[-a-z0-9]+[\.[a-z0-9]+]?/'; // check if is by email address
  preg_match($pattern, $w3all_uap[0], $uname_email);

  $uname_email = (empty($uname_email)) ? 'login' : 'email';

  $w3all_uap[0] = sanitize_user( $w3all_uap[0], $strict = false );
   		
   		  $wp_user_data = get_user_by($uname_email, $w3all_uap[0]);
   		 
   if ( empty($wp_user_data) ){ // this user need to be added also 
       
   		  	$userdata = WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($w3all_uap[0]);
	
   	 if( !empty($userdata) && $userdata->user_id > 2 ){
   	 	
   		  	 if( $userdata->user_type == 1 ){
   		  	 	   $role = '';
   		  	  
   		  	  } elseif ( $userdata->group_name == 'ADMINISTRATORS' ){
      	       $role = 'administrator';
      	      
             } elseif ( $userdata->group_name == 'GLOBAL_MODERATORS' ){
            	  $role = 'editor';
          	   
          	   } else { $role = 'subscriber'; }  // for all others phpBB Groups default to WP subscriber
         
         //////// phpBB username chars fix          	   	
         // phpBB need to have users without characters like ' that is not allowed in WP as username
         // If old phpBB usersnames are like myuse'name, do not add into WP
         // check for 2 more of these on this class.wp.w3all-phpbb.php, and 1 on ext_plugins_fixes.php in case you need to add something or remove

          $pattern = '/\'/';
          preg_match($pattern, $w3all_uap[0], $matches);
          
          if($matches){
	        echo '<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em">Sorry, your <strong>registered username on our forum contain characters not allowed on this CMS system</strong>, you can\'t be added or login in this site side (and you\'ll see this message) until logged in on forums with this username. Please return back and contact the administrator reporting about this error issue. Thank you <input type="button" value="Go Back" onclick="history.back(-1)" /></p>';
           return;
         
          }
          
          /////////////
          /////////////
         
          	  $w3all_uap[0] = sanitize_user( $w3all_uap[0], $strict = false ); 
              
   		  	    $userdata = array(
               'user_login'       =>  $userdata->username,
               'user_pass'        =>  $userdata->user_password,
               'user_email'       =>  $userdata->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $userdata->user_regdate, false ),
               'role'             =>  $role
              );
               
           $user_id = wp_insert_user( $userdata );
           if( is_wp_error( $user_id ) ) {
             echo "<b>" . $user_id->get_error_message() . "</b>";
             echo "<br /><a href=\"" . wp_login_url() . "\" title=\"Return to login page\">Return to login page</a><br />Please report to Administrator about this error on login.";
             exit;
           }
           
           $wp_user_data = get_user_by('ID', $user_id);
           
   		  	if(!empty($w3all_uap[1])){     
           $test_pass = wp_check_password($w3all_uap[1], $wp_user_data->user_pass, $wp_user_data->ID);
        
   		  	 if( $test_pass === true ){ 
   
   		  	  	    WP_w3all_phpbb::phpBB_user_session_set_res($wp_user_data);
   		  	  	   
         	   return;
   		  		
   		  	 } else {  unset($wp_user_data,$userdata,$w3all_uap); return; }  
          }
   	 }
                     
  }
                  
      if(!empty($wp_user_data) && !empty($w3all_uap[1])){ // user already exist

                     	  $test_pass = wp_check_password($w3all_uap[1], $wp_user_data->user_pass, $wp_user_data->ID);

   		  	if( $test_pass === true){

   		  			  WP_w3all_phpbb::phpBB_user_session_set_res($wp_user_data);		  
              return;
   		  		 
   		  	} else { unset($wp_user_data,$userdata,$w3all_uap); return; } 
               	
   	} 
 
  } else {
  	unset($w3all_uap);
  }
} 	

// workaround 

    add_action( 'init', 'wp_w3all_detect_login', 1 );

  
//// END workaround for some plugin that substitute wp-login.php default login page

?>