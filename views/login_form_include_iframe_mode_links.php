<?php
$wp_w3all_forum_folder_wp = get_home_url() . "/index.php/" . $wp_w3all_forum_folder_wp;
  global $wp, $w3all_last_t_avatar_dim;
$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
$current_url = substr($current_url, 0, strrpos($current_url, "?"));
$current_url = empty($current_url) ? get_home_url() : $current_url;

$w3all_loginform_style_ul = 'list-style:none;margin:auto auto auto 0px;';
$w3all_loginform_style_ul_class = '';
$w3all_loginform_style_li_class = '';
?>

<!--<div class="wrap">-->
		<?php if ( ! is_user_logged_in() ){ ?>
<form method="post" action="<?php echo $w3all_url_to_cms; ?>/ucp.php?mode=login" class="headerspace">
	<h3><a href="<?php echo $wp_w3all_forum_folder_wp; ?>/?mode=login"><?php echo __( 'Login' , 'wp-w3all-phpbb-integration' ); ?></a> - <a href="<?php echo $wp_w3all_forum_folder_wp; ?>/?mode=register"><?php  echo __( 'Register' , 'wp-w3all-phpbb-integration' ); ?></a></h3>
<label for="username"><span><?php echo __( 'Username:' , 'wp-w3all-phpbb-integration' ); ?></span> <input type="text" tabindex="1" name="username" id="username" size="10" class="" title="Username"></label>
<label for="password"><br /><span><?php echo __( 'Password:' , 'wp-w3all-phpbb-integration' ); ?> </span> <input type="password" tabindex="2" name="password" id="password" size="10" class="" title="Password" autocomplete="off"></label>
<br /><br /><a href="<?php echo $wp_w3all_forum_folder_wp; ?>/?mode=sendpassword"><?php echo __( 'I forgot my password', 'wp-w3all-phpbb-integration' ); ?></a>
 <label for="autologin"><?php echo __( 'Remember me' , 'wp-w3all-phpbb-integration' ); ?> <input type="checkbox" tabindex="4" name="autologin" id="autologin" checked="checked"></label>
<input type="submit" tabindex="5" name="login" value="<?php echo __( 'Login' , 'wp-w3all-phpbb-integration' ); ?>" class="">
<input type="hidden" name="redirect" value="<?php echo $current_url; ?>">
</form>
	<?php } ?>
	
<?php 
if ( is_user_logged_in() && isset($phpbb_user_session) ){ 
		if( $display_phpbb_user_info_yn == 1 ){ // if widget instance option is set to yes
		echo '<ul class="'.$w3all_loginform_style_ul_class.'" id="" style="'.$w3all_loginform_style_ul.'">';
		echo '<li class="'.$w3all_loginform_style_li_class.'">' . get_avatar(get_current_user_id(), $w3all_last_t_avatar_dim) . '</li>';
		echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Hello ', 'wp-w3all-phpbb-integration' ) . $phpbb_user_session[0]->username . '</li>';
		if($phpbb_user_session[0]->user_unread_privmsg > 0){
		echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'You have ', 'wp-w3all-phpbb-integration' ) . '<a href="'.$wp_w3all_forum_folder_wp.'/?iu=pm&folder=inbox">' . $phpbb_user_session[0]->user_unread_privmsg . '</a>' . __( ' unread forum\'s pm' , 'wp-w3all-phpbb-integration' ) . '</li>';
	  }
	  echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Forum\'s posts count: ' , 'wp-w3all-phpbb-integration' ) . $phpbb_user_session[0]->user_posts . '</li>';
	  echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Registered on: ', 'wp-w3all-phpbb-integration' ) . date_i18n( 'd M Y', $phpbb_user_session[0]->user_regdate + ( 3600 * get_option( 'gmt_offset' )) ) . '</li>';
	  echo '</ul>';
}
	?>
	
	  <?php $logout_button = false; 
	  if (!$logout_button){ ?>
			<a class="button" href="<?php echo wp_logout_url(); ?>"><?php echo __('Logout' , 'wp-w3all-phpbb-integration' ); ?></a>
    <?php } else { ?>
     <form action="<?php echo wp_logout_url(); ?>">
      <input type="submit" value="<?php echo __('Logout' , 'wp-w3all-phpbb-integration' ); ?>" />
     </form>
    <?php } ?>
    
    <?php } ?>
