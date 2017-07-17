<?php 
/**
 * The default basic template to display content for WP_w3all embedded phpBB
 * @package WordPress
 * @subpackage wp_w3all
 */
 // - axew3.com - //

// START DO NOT MODIFY

$w3forum_id  = isset($_GET["forum_id"]) ? $_GET["forum_id"] : '';
$w3topic_id   = isset($_GET["topic_id"]) ? $_GET["topic_id"] : ''; 
$w3post_id   = isset($_GET["post_id"]) ? $_GET["post_id"] : '';
$w3mode      = isset($_GET["mode"]) ? $_GET["mode"] : '';
$w3phpbbsid  = isset($_GET["sid"]) ? $_GET["sid"] : '';
$w3phpbb_viewforum  = isset($_GET["viewforum"]) ? $_GET["viewforum"] : '';
$w3phpbb_viewtopic  = isset($_GET["viewtopic"]) ? $_GET["viewtopic"] : '';
$w3phpbb_start  = isset($_GET["start"]) ? $_GET["start"] : '';
$w3allhomeurl = get_home_url();
   
if( preg_match('/[^0-9]/',$w3phpbb_start) OR preg_match('/[^0-9]/',$w3topic_id) OR preg_match('/[^0-9]/',$w3phpbb_viewtopic) OR preg_match('/[^0-9]/',$w3phpbb_viewforum) OR preg_match('/[^0-9]/',$w3forum_id) OR preg_match('/[^0-9]/',$w3post_id) OR preg_match('/[^0-9A-Za-z]/',$w3mode) OR preg_match('/[^0-9A-Za-z]/',$w3phpbbsid) ){

	die("Something goes wrong with your URL request, <a href=\"$w3allhomeurl\">please leave this page</a>.");
}

$w3logout = $w3mode;

$w3urlscheme = parse_url($w3all_url_to_cms);
$w3urlscheme = $w3urlscheme['scheme'];

$w3all_target_server = preg_replace('/^[^\.]*\.([^\.]*)\.(.*)$/', '\1.\2',$w3all_url_to_cms); // REVIEW this

// build correct links x iframe

  if (!empty($w3forum_id) && empty($w3phpbb_viewforum)){
    $uiframe = "/viewtopic.php?f=".$w3forum_id."&amp;p=".$w3post_id."#p".$w3post_id."";
    $w3all_url_to_cms .= $uiframe;
  }  elseif (!empty($w3phpbb_viewforum) && !empty($w3post_id) ) {
     $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3phpbb_viewforum ."&amp;p=".$w3post_id."#p".$w3post_id."";//exit;
    
}
  elseif (!empty($w3forum_id) && !empty($w3topic_id)) {
    $w3all_url_to_cms . "/viewtopic.php?f=". $w3phpbb_viewforum ."&amp;t=".$w3topic_id."";
} elseif (!empty($w3phpbb_viewforum) && empty($w3phpbb_viewtopic)) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewforum.php?f=". $w3phpbb_viewforum ."";
} elseif (!empty($w3phpbb_viewtopic) && empty($w3phpbb_start)) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3phpbb_viewforum ."&amp;t=".$w3phpbb_viewtopic."";
} elseif (!empty($w3phpbb_viewtopic) && !empty($w3phpbb_start)) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3phpbb_viewforum ."&amp;t=".$w3phpbb_viewtopic."&amp;start=".$w3phpbb_start."";
} elseif (stristr($w3mode, "register")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=register";
} elseif (stristr($w3mode, "sendpassword")) {
   $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=sendpassword";
} elseif (stristr($w3mode, "login")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=login";
}  elseif (stristr($w3mode, "logout")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=logout&amp;sid=". $w3phpbbsid ."";
} 
 else {
	$w3all_url_to_cms = $w3all_url_to_cms;
}

// the modal screen // css

function wp_w3all_css_modal_login() {
 	
 $w3all_cssmodal = "<style type=\"text/css\">
 .w3allmodalDialog {
	position: fixed;
	font-family: Arial, Helvetica, sans-serif;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	background: rgba(0,0,0,0.8);
	z-index: 99999;
	opacity:0;
	-webkit-transition: opacity 400ms ease-in;
	-moz-transition: opacity 400ms ease-in;
	transition: opacity 400ms ease-in;
	pointer-events: none;
}
.w3allmodalDialog:target {
	opacity:1;
	pointer-events: auto;
}

.w3allmodalDialog > div {
	width: 400px;
	position: relative;
	margin: 10% auto;
	padding: 5px 20px 13px 20px;
	border-radius: 10px;
	background: #fff;
	background: -moz-linear-gradient(#fff, #999);
	background: -webkit-linear-gradient(#fff, #999);
	background: -o-linear-gradient(#fff, #999);
}
.w3allclose {
	background: #606061;
	color: #FFFFFF;
	line-height: 25px;
	position: absolute;
	right: -12px;
	text-align: center;
	top: -10px;
	width: 24px;
	text-decoration: none;
	font-weight: bold;
	-webkit-border-radius: 12px;
	-moz-border-radius: 12px;
	border-radius: 12px;
	-moz-box-shadow: 1px 1px 3px #000;
	-webkit-box-shadow: 1px 1px 3px #000;
	box-shadow: 1px 1px 3px #000;
}

.w3allclose:hover { background: #333; }
</style>
";

	echo $w3all_cssmodal;
 	
}

 add_action('wp_head','wp_w3all_css_modal_login');
 wp_enqueue_script("jquery");

  function wp_w3all_hook_jresizer() {
 	// <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js\"></script>
  $s = "<script type=\"text/javascript\" src=\"".plugins_url()."/wp-w3all-phpbb-integration/addons/resizer/iframeResizer.min.js\"></script>
";

	echo $s;
 	
}
 
 add_action('wp_head','wp_w3all_hook_jresizer');
 
// END DO NOT MODIFY

// Start a default WordPress page

 get_header(); 

 ?>
 <!-- START w3all_cssmodal_login div -->
<div id="w3allopenModal" class="w3allmodalDialog">
	<div>
		<a href="#w3allclose" title="Close" class="w3allclose">X</a>
		<form method="post" action="<?php echo $w3all_url_to_cms; ?>/ucp.php?mode=login" class="">
	<h3><a href="<?php echo $wp_w3all_forum_folder_wp; ?>/?mode=register">Register</a></h3>
			<label for="username"><span>Username:</span> <input type="text" tabindex="1" name="username" id="username" size="10" class="" title="Username"></label>
			<label for="password"><span>Password:</span> <input type="password" tabindex="2" name="password" id="password" size="10" class="" title="Password" autocomplete="off"></label>
							<br /><br /><a href="<?php echo $wp_w3all_forum_folder_wp; ?>/?mode=sendpassword">I forgot my password</a>
										<span class="">|</span> <label for="autologin">Remember me <input type="checkbox" tabindex="4" name="autologin" id="autologin" checked="checked"></label>
						<input type="submit" tabindex="5" name="login" value="Login" class="">
			<input type="hidden" name="redirect" value="<?php echo home_url().'/index.php/'.get_option( 'w3all_forum_template_wppage' ); ?>">
	</form>
	</div>
</div><!-- END w3all_cssmodal_login div -->
<!-- START iframe div -->

<div class="">
<noscript><h3>Your browser seem to have Javascript disabled, you can't load correctly the forum page at this Url. Please enable Javascript on your browser or <a href="<?php echo $w3all_url_to_cms;?>">visit the full forum page here</a>.<br /><br /></h3></noscript>
<iframe style="width:100%;border:0 !important;" src="<?php echo $w3all_url_to_cms; ?>"></iframe>

		<?php

		echo "<script type=\"text/javascript\">

    	iFrameResize({
    
				log                     : false,
				inPageLinks             : true,
			  targetOrigin: '".$w3urlscheme."://".$w3all_target_server."', 
       // heightCalculationMethod:'bodyScroll', // if page not resize to phpBB template bottom, uncomment this

				messageCallback         : function(messageData){ // Callback fn when message is received
				// $ has been replaced with jQuery global object // use wp jquery libs without loading the lib from external resources
					jQuery('p#callback').html(
						'<b>Frame ID:</b> '    + messageData.iframe.id +
						' <b>Message:</b> '    + messageData.message
					);
		
				// w3all simple js check and redirects
				
				var w3all_passed_url = messageData.message.toString();
				
			  var w3all_ck = \"".$_SERVER['SERVER_NAME']."\";
        
         var w3all_pass_ext  = (w3all_passed_url.indexOf(w3all_ck) > -1);

    if (w3all_pass_ext == true) {
     	window.location.replace(w3all_passed_url); 
     }

   if (/^(f|ht)tps?:\/\//i.test(w3all_passed_url)) {
    window.location.replace(w3all_passed_url); 
   }
  
  var   w3all_ck2 = 'ucp.php?mode=login';
  var w3all_pass_login  = (w3all_passed_url.indexOf(w3all_ck2) > -1);

 if (w3all_pass_login == true) {

   var w3_login_modallink = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?#w3allopenModal';
    window.location.replace(w3_login_modallink);
  } 
  
   var   w3all_ck3 = 'ucp.php?mode=logout';
   var w3all_pass_login_out  = (w3all_passed_url.indexOf(w3all_ck3) > -1);
 
 if (w3all_pass_login_out == true) {
    window.location.replace('".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/'); 
   }
   
    var   w3all_ck4 = 'quickmod';
   var w3all_phpBBquickmod  = (w3all_passed_url.indexOf(w3all_ck4) > -1);
 
 if (w3all_phpBBquickmod == true) {
 window.scrollTo(0, 200);
   }
   
       var   w3all_ck_reply = 'mode=reply';
   var w3all_1_ck_reply  = (w3all_passed_url.indexOf(w3all_ck_reply) > -1);
 
 if (w3all_1_ck_reply == true) {
 window.scrollTo(0, 200);
   }

// if phpBB lightbox // UNCOMMENT more below if lightbox installed on phpBB
   var   w3all_ck5 = 'getw3all_lightbox';
   var w3all_phpBB_lightbox  = (w3all_passed_url.indexOf(w3all_ck5) > -1);
 
     if (w3all_phpBB_lightbox == true) {
    // -> UNCOMMENT THE FOLLOWING LINE right here below to enable page scroll if you use phpBB_lightbox in phpBB
    // window.scrollTo(0, 150);  
   }
   
  
// push passed url to the browser history if on index.php
       var   w3all_ck8 = 'index.php';
       var w3all_viewmainindex_push  = (w3all_passed_url.indexOf(w3all_ck8) > -1);
      if (w3all_viewmainindex_push == true) {
        var w3matches = /index\.php$/ig.exec(w3all_passed_url);
       if (w3matches) {  
        w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."';
        history.replaceState('', 'Index', w3all_passed_url_push); 
      }  
     }
   
// push passed url to the browser history if on viewforum.php
       var   w3all_ck7 = 'viewforum.php';
    var w3all_viewforum_push  = (w3all_passed_url.indexOf(w3all_ck7) > -1);
    if (w3all_viewforum_push == true) {

    var w3matches = /viewforum\.php\?f=([0-9]+)/ig.exec(w3all_passed_url);
       
      w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?viewforum=' + w3matches[1];
      history.replaceState('', 'Forum', w3all_passed_url_push); 
     }
     
// push passed url to the browser history if on viewtopic.php

    var   w3all_ck6 = 'viewtopic.php';
    var w3all_viewtopic_push  = (w3all_passed_url.indexOf(w3all_ck6) > -1);
    
  if (w3all_viewtopic_push == true) {

       var w3matches = /viewtopic\.php\?.*([0-9]+).*&(p|t)=([0-9]+)(&start=|#p)?([0-9]+)?/ig.exec(w3all_passed_url);
       
    if(!w3matches[4]){
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?viewforum=' + w3matches[1] + '&viewtopic=' + w3matches[3];
     	history.replaceState('', 'Topic', w3all_passed_url_push); 
     } else if (w3matches[4] == '#p'){
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?viewforum=' + w3matches[1] + '&post_id=' + w3matches[5];
          	history.replaceState('', 'Topic', w3all_passed_url_push); 
      } else {
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?viewforum=' + w3matches[1] + '&viewtopic=' + w3matches[3] + w3matches[4] + w3matches[5];
     	history.replaceState('', 'Topic', w3all_passed_url_push); 

   } }
   

				}
			});


 var w3allogout = '".$w3logout."'; 
   if (w3allogout == 'logout') {
    window.location.replace('".$w3allhomeurl."' + '/wp-login.php?action=logout');
    
   }

</script>";
?>

</div>
<!-- END iframe div -->
<?php get_footer(); ?>