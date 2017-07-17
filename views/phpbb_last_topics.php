<?php
$phpbb_on_template_iframe = get_option( 'w3all_iframe_phpbb_link_yn' );
$w3all_phpbb_display_topics_how = get_option( 'w3all_phpbb_display_topics_how' );
$w3all_avatars_yn = ( $w3all_get_phpbb_avatar_yn == 1 && $w3all_last_t_avatar_yn == 1 ) ? true : false; // avatars or not

$w3all_lastopics_style_ul = 'list-style:none;margin:auto auto auto 0px;'; // change into whatever you need
$w3all_lastopics_style_ul_class = 'w3all_ul_widgetLastTopics'; // declare this class .w3all_ul_widgetLastTopics into your css template and style ul element as needed (maybe rename also into whatever you like)
$w3all_lastopics_style_li_class = 'w3all_li_widgetLastTopics'; // declare this class .w3all_li_widgetLastTopics into your css template and style li elements as needed (maybe rename also into whatever you like)

if(!empty($last_topics)){

	   echo "<ul class=\"".$w3all_lastopics_style_ul_class."\" style=\"".$w3all_lastopics_style_ul."\">\n";

$countn = 0;	 
foreach ($last_topics as $key => $value) {
	
	if ( $countn < $topics_number ){ // instance topics number
	// wp_w3all_phpbb_last_topics() on class.wp.w3all.widgets-phpbb.php
  $w3all_post_state_ru = (isset($phpbb_unread_topics) && array_key_exists($value->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = '<span style="color:red">&#9754;</span>' : '';

	if ( $w3all_avatars_yn ){
	
			  $wpu = get_user_by('login', $value->topic_last_poster_name); 
			  
			  if( ! $wpu ){
			  	
			  	$w3all_avatar_display = get_avatar(0, $w3all_last_t_avatar_dim);
			  } else {
      	
     	      $w3all_avatar_display = ( $wpu == true ) ? get_avatar($wpu->ID, $w3all_last_t_avatar_dim) : '';  	
          }
  } 
 
	$value->topic_last_poster_name = (empty($value->topic_last_poster_name)) ? __( 'Guest', 'wp-w3all-phpbb-integration' ) : $value->topic_last_poster_name;

 if ( $wp_w3all_post_text == 1 ){
   $value->post_text = wp_w3all_remove_bbcode_tags($value->post_text, $wp_w3all_text_words);
  }

  if ( $phpbb_on_template_iframe == 1 ){ // if on iframe mode, links are in this way
  	
  	global $wp_w3all_forum_folder_wp;
  	
  	if ( $wp_w3all_post_text == 0 ){ // only links author and date display: 
  	
  	     	if ( $w3all_avatars_yn ){
  	     		   
  	     		  echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"".get_home_url()."/index.php/$wp_w3all_forum_folder_wp/?forum_id=$value->forum_id&amp;topic_id=$value->topic_id&amp;post_id=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a> ".$w3all_post_state_ru."<br />".__( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
 
  	     	} else {
  	     		
  	     		echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"".get_home_url()."/index.php/$wp_w3all_forum_folder_wp/?forum_id=$value->forum_id&amp;topic_id=$value->topic_id&amp;post_id=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a> ".$w3all_post_state_ru."<br />".__( 'by' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
     
  	     	}
  
      } 
      
  if ( $wp_w3all_post_text == 1 ){ // links, post text, author and date
  	   
  	   if ( $w3all_avatars_yn ){
  	   	
  	   	  echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"".get_home_url()."/index.php/$wp_w3all_forum_folder_wp/?forum_id=$value->forum_id&amp;topic_id=$value->topic_id&amp;post_id=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
      
       } else {
  	  	        echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"".get_home_url()."/index.php/$wp_w3all_forum_folder_wp/?forum_id=$value->forum_id&amp;topic_id=$value->topic_id&amp;post_id=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a><br />$value->post_text ...<br />".__( 'by' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
     
  	          }
  }
  
  
   } else { // if not iframe phpBB embedded mode, direct url
   	
     	 if ( $wp_w3all_post_text == 0 ){ // only links author and date
     		
     		if ( $w3all_avatars_yn ){
     			
     			echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table cellpadding=\"0\" cellspacing=\"0\" style=\"vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a> ".$w3all_post_state_ru."<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
         
         } else {
     			        
     			        echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a><br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name " . __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
                }
       }
    
         if ( $wp_w3all_post_text == 1 ){ // links, post text, author and date
         	
         	if ( $w3all_avatars_yn ){
         		
         		 echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table cellpadding=\"0\" cellspacing=\"0\" style=\"vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
          
            } else {
           	
         		         echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\" title=\"Last Post: $value->post_subject\">$value->topic_title</a><br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
                  }
         }
     }
     
    }
     
  $countn++;
  
}

	   echo "</ul>";

}
?>
