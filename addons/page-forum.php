<?php 
/**
 * The default basic template to display content for WP_w3all embedded phpBB
 * @package WordPress
 * @subpackage wp_w3all
 */
 // - axew3.com - //

// MAY DO NOT MODIFY

// For compatibility with all the rest, if the case, here these vars are switched
if( isset($_GET["f"]) ){
	$_GET["forum_id"] = $_GET["f"];
}
if( isset($_GET["t"]) ){
	$_GET["topic_id"] = $_GET["t"];
}

$w3forum_id = isset($_GET["forum_id"]) ? $_GET["forum_id"] : '';
$w3topic_id = isset($_GET["topic_id"]) ? $_GET["topic_id"] : ''; 
$w3post_id  = isset($_GET["post_id"]) ? $_GET["post_id"] : ''; // p reserved
$w3mode     = isset($_GET["mode"]) ? $_GET["mode"] : '';
$w3phpbbuid = isset($_GET["u"]) ? $_GET["u"] : '';
$w3phpbbsid = isset($_GET["sid"]) ? $_GET["sid"] : '';
$w3phpbbwatch = isset($_GET["watch"]) ? $_GET["watch"] : '';
$w3phpbbunwatch = isset($_GET["unwatch"]) ? $_GET["unwatch"] : '';
$w3phpbb_viewforum = isset($_GET["viewforum"]) ? $_GET["viewforum"] : '';// not more used
$w3phpbb_viewtopic = isset($_GET["viewtopic"]) ? $_GET["viewtopic"] : '';// not more used
$w3phpbb_start  = isset($_GET["start"]) ? $_GET["start"] : '';
$w3iu = isset($_GET["i"]) ? $_GET["i"] : ''; 
$w3iu_folder = isset($_GET["folder"]) ? $_GET["folder"] : '';

$w3logout = $w3mode; // not more used

$w3allhomeurl = get_home_url();
$current_user = wp_get_current_user();
$dd = get_option('w3all_phpbb_cookie');
$w3wp_forum_page = get_option('w3all_forum_template_wppage');

if( preg_match('/[^0-9]/',$w3phpbbuid) OR preg_match('/[^a-z]/',$w3phpbbwatch) OR preg_match('/[^a-z]/',$w3phpbbunwatch) OR preg_match('/[^A-Za-z]/',$w3iu_folder) OR preg_match('/[^A-Za-z]/',$w3iu) OR preg_match('/[^0-9]/',$w3phpbb_start) OR preg_match('/[^0-9]/',$w3topic_id) OR preg_match('/[^0-9]/',$w3phpbb_viewtopic) OR preg_match('/[^0-9]/',$w3phpbb_viewforum) OR preg_match('/[^0-9]/',$w3forum_id) OR preg_match('/[^0-9]/',$w3post_id) OR preg_match('/[^0-9A-Za-z]/',$w3mode) OR preg_match('/[^0-9A-Za-z]/',$w3phpbbsid) ){

	die("Something goes wrong with your URL request, <a href=\"$w3allhomeurl\">please leave this page</a>.");
}

if(!empty($dd)){
  $p = strpos($dd, '.');
   if($p == 0){
	  $document_domain = substr($dd, 1);
   } else {
   	  $document_domain = $dd;
     }
} else {
	$document_domain = 'localhost';
}

// $document_domain = 'mysite.com'; // in case, setup manually here. ex:  mysite.com

// build and pass links x iframe //

if ( !empty($w3forum_id) && empty($w3topic_id) && empty($w3phpbb_start) && empty($w3post_id) ){
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewforum.php?f=". $w3forum_id ."";
} elseif ( !empty($w3forum_id) && !empty($w3phpbb_start) && empty($w3topic_id) ) {
	   $w3all_url_to_cms = $w3all_url_to_cms . "/viewforum.php?f=". $w3forum_id ."&amp;start=".$w3phpbb_start."";
} elseif ( !empty($w3forum_id) && !empty($w3topic_id) && empty($w3post_id) && empty($w3phpbb_start) ) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3forum_id ."&amp;t=".$w3topic_id."";
} elseif ( !empty($w3forum_id) && !empty($w3topic_id) && !empty($w3post_id) ) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3forum_id ."&amp;t=".$w3topic_id."&amp;p=".$w3post_id."#p".$w3post_id."";
} elseif ( !empty($w3forum_id) && empty($w3topic_id) && !empty($w3post_id) ) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3forum_id ."&amp;p=".$w3post_id."#p".$w3post_id."";
} elseif ( !empty($w3forum_id) && !empty($w3topic_id) && !empty($w3phpbb_start) ) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?f=". $w3forum_id ."&amp;t=".$w3topic_id."&amp;start=".$w3phpbb_start."";
} elseif ( empty($w3forum_id) && !empty($w3post_id) && empty($w3iu) ) { 
    $w3all_url_to_cms = $w3all_url_to_cms . "/viewtopic.php?p=".$w3post_id."#p".$w3post_id."";
} elseif (stristr($w3mode, "ucp")) { // custom to ucp 
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php";
} elseif (stristr($w3mode, "register")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=register";
} elseif (stristr($w3mode, "sendpassword")) {
   $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=sendpassword";
} elseif (stristr($w3mode, "login")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=login";
} elseif (stristr($w3mode, "logout")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?mode=logout&amp;sid=". $w3phpbbsid ."";
} elseif (stristr($w3mode, "memberlist")) { // custom to memberlist 
    $w3all_url_to_cms = $w3all_url_to_cms . "/memberlist.php";
} elseif (stristr($w3mode, "viewprofile")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/memberlist.php?mode=viewprofile&amp;u=". $w3phpbbuid ."";
} elseif (stristr($w3mode, "contactadmin")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/memberlist.php?mode=contactadmin";
} elseif (stristr($w3mode, "team")) {
    $w3all_url_to_cms = $w3all_url_to_cms . "/memberlist.php?mode=team";
} elseif (stristr($w3iu, "pm") && $w3iu_folder == 'inbox') {
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";
} elseif (stristr($w3mode, "view") && $w3iu == 'pm') { 
    $w3all_url_to_cms = $w3all_url_to_cms . "/ucp.php?i=pm&mode=view&f=0&p=".$w3post_id."";
} else {
	$w3all_url_to_cms = $w3all_url_to_cms;
}

function w3all_enqueue_scripts() { 
wp_enqueue_script("jquery");
}

function wp_w3all_add_ajax() {
	global $w3all_url_to_cms;
	$w3all_url_to_phpbb_ib = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";
$s = "<script type=\"text/javascript\" src=\"".plugins_url()."/wp-w3all-phpbb-integration/addons/resizer/iframeResizer.min.js\"></script>
<script type=\"text/javascript\">
function w3all_ajaxup_from_phpbb_do(res){
jQuery(document).ready(function($) {
if ( parseInt(res,10) > 0 && null == (document.getElementById('wp-admin-bar-w3all_phpbb_pm')) ){
var resp = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + parseInt(res,10) + '".__( ' unread forum PM', 'wp-w3all-phpbb-integration' )."';
 $('#wp-admin-bar-root-default').append('<li id=\"wp-admin-bar-w3all_phpbb_pm\"><a class=\"ab-item\" href=\"".$w3all_url_to_phpbb_ib."\">' + resp + '</li>');
 // window.location.reload(true);// this could be a work around for different themes, but lead to loop in this way
} else if (parseInt(res,10) > 0){
	var r = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + parseInt(res,10) + '".__( ' unread forum PM', 'wp-w3all-phpbb-integration' )."';
  jQuery( 'li.w3all_phpbb_pmn' ).children().text( r );
} else {
 if( parseInt(res,10) == 0 && null !== (document.getElementById('wp-admin-bar-w3all_phpbb_pm'))){
  jQuery('li[id=wp-admin-bar-w3all_phpbb_pm]').remove();
 }
}
});
}
</script>
";
	echo $s;
}

 add_action('wp_enqueue_scripts', 'w3all_enqueue_scripts');
 add_action('wp_head','wp_w3all_add_ajax');
 
// END MAY DO NOT MODIFY

// Start a default WordPress page

// NOTE FOR CUSTOM phpBB iframe embedded PAGES (two columns etc)
// If you need to have a custom page forum you just need:
// Copy all the above until here, before the call to get_header();
// Than copy paste all the code inside <!-- START iframe div --> and <!-- END iframe div -->
// inside whatever element the iframe need to display (for example into <div id="primary" class="content-area"> on default WP twentysixteen theme)

 get_header(); 

 ?>

<!-- START iframe div -->
<div class="">
<noscript><h3>Your browser seem to have Javascript disabled, you can't load correctly the forum page at this Url. Please enable Javascript on your browser or <a href="<?php echo $w3all_url_to_cms;?>">visit the full forum page here</a>.<br /><br /></h3></noscript>
<iframe id="w3all_phpbb_iframe" style="width:1px;min-width:100%;*width:100%;border:0;" scrolling="no" src="<?php echo $w3all_url_to_cms; ?>"></iframe>
<?php

		echo "<script type=\"text/javascript\">
		
		document.domain = '".$document_domain."';
		// document.domain = 'mydomain.com'; // NOTE: Un-comment and reset/setup this with YOUR domain if js error when WP is like/on wp.domain.com and phpBB on domain.com: js origin error come out when WP is on subdomain install and phpBB on domain. The origin fix is needed in case: (do this also on phpBB overall_footer.html added code)
		var wp_u_logged = ".$current_user->ID.";
		
		function w3all_ajaxup_from_phpbb(res){
			var w3all_phpbb_u_logged  = /#w3all_phpbb_u_logged=1/ig.exec(res);
			 if( w3all_phpbb_u_logged == null && wp_u_logged > 0  ){
			 document.location.replace('".$w3allhomeurl."/index.php/".$w3wp_forum_page."/');
        //window.location.reload(true);
       }
       if(wp_u_logged == 0 && res.indexOf('#w3all_phpbb_u_logged=1') > -1){
       document.location.replace('".$w3allhomeurl."/index.php/".$w3wp_forum_page."/');
        //window.location.reload(true);
       }
       
			var w3all_phpbbpmcount = /.*(#w3all_phpbbpmcount)=([0-9]+).*/ig.exec(res);
      if(w3all_phpbbpmcount !== null){
         w3all_ajaxup_from_phpbb_do(w3all_phpbbpmcount[2]);
       }
      if(res.indexOf('#w3allScrollBottom') > -1) {
        setTimeout(w3Resizehs, 300); // delay to scroll
       function w3Resizehs(){
        var ih = jQuery('iframe').contents().height();
             this.window.scrollTo(0, ih);
		   }
		  }    
       
   } // END function w3all_ajaxup_from_phpbb(res){
     
    iFrameResize({
				log                     : false,
				inPageLinks             : true,
        targetOrigin: '".$w3all_url_to_cms."', 
        checkOrigin : '".$document_domain."', // if js error: 'Failed to execute 'postMessage' on 'DOMWindow': The target origin provided does not match the recipient window's origin. Need to fit YOUR domain, ex: mydomain.com
       // heightCalculationMethod: 'bodyOffset', // If page not resize to phpBB template bottom, un-comment (or change with one of others available resize methods) 
       // see: https://github.com/davidjbradshaw/iframe-resizer#heightcalculationmethod

				messageCallback         : function(messageData){ // Callback fn when message is received
	      	
				// w3all simple js check and redirects
				
				var w3all_passed_url = messageData.message.toString();
			
				// to scroll or not // adjust 200 here to fit your theme if needed
					if(messageData.message.indexOf('#scroll') > -1) {
			  jQuery('#w3all_phpbb_iframe').ready(function() {
				//	jQuery(document).ready(function() {
             window.scrollTo(0, 200);
            });
		      }
		      	
					if(messageData.message.indexOf('#nscroll') > -1) {
             window.scrollTo(0, 200);
          }	

			  var w3all_ck = \"".$_SERVER['SERVER_NAME']."\";
        
         var w3all_pass_ext  = (w3all_passed_url.indexOf(w3all_ck) > -1);
      
   var w3all_ck_preview = (w3all_passed_url.indexOf('preview') > -1);  

if (w3all_ck_preview == false) { // or the phpBB passed preview link, will be recognized as external, and preview will redirect to full forum url instead
 // so these are maybe, external iframe redirects
 
    if (w3all_pass_ext == true) {
     	window.location.replace(w3all_passed_url); 
     }

   if (/^(f|ht)tps?:\/\//i.test(w3all_passed_url)) {
    window.location.replace(w3all_passed_url); 
   }
}

  // PUSH phpBB URLs //

// Push passed url to the browser history if on memberlist.php //

      if (true == w3all_passed_url.indexOf('memberlist.php') > -1) {
        var w3matches = /memberlist\.php\?(mode=)?([a-z_]+)?(&u=)?([0-9]+)?/ig.exec(w3all_passed_url);
        if(w3matches == null){
         w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=memberlist';
        } else if (w3matches !== null && w3matches[2] == 'contactadmin') {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=contactadmin';
          } else if (w3matches !== null && w3matches[2] == 'team') {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=team';
          } else if (w3matches !== null && w3matches[2] == 'viewprofile') {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=viewprofile&u=' + w3matches[4];
          } else {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=memberlist';
          }
        history.replaceState('', 'User Control Panel', w3all_passed_url_push); 
      }

// Push passed url to the browser history if on ucp.php // partial //

      if (true == w3all_passed_url.indexOf('ucp.php') > -1) {
        var w3matches = /ucp\.php\?(i=)?([a-z_]+)?(&folder=)?([a-z]+)?/ig.exec(w3all_passed_url);
        if(w3matches !== null && w3matches[1] && w3matches[3]){
         w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?i=pm&folder=inbox';
        } else if (w3matches !== null && w3matches[2] == 'ucp_pm') {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?i=ucp_pm';
          } else {
           w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?mode=ucp';
          }
        history.replaceState('', 'User Control Panel', w3all_passed_url_push); 
      }
 
// Push passed url to the browser history if on index.php //
	
      if (true == w3all_passed_url.indexOf('index.php') > -1) {
        w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."';
        history.replaceState('', 'Index', w3all_passed_url_push); 
     }
   
// Push passed urls to the browser history if on viewforum.php //
    var w3all_viewforum_push0  = (w3all_passed_url.indexOf('viewforum.php') > -1);
    var w3all_viewforum_push1  = (w3all_passed_url.indexOf('start') > -1);
    var w3all_viewforum_push2  = (w3all_passed_url.indexOf('unwatch') > -1);
    var w3all_viewforum_push3  = (w3all_passed_url.indexOf('watch') > -1);
    
    if (w3all_viewforum_push0 == true && w3all_viewforum_push1 == true && w3all_viewforum_push2 == false && w3all_viewforum_push3 == false) {
     var w3matches = /viewforum\.php\?f=([0-9]+).*(start=)([0-9]+)/ig.exec(w3all_passed_url);
      w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[1] + '&start=' + w3matches[3];
      history.replaceState('', 'Forum', w3all_passed_url_push); 
     }
     
    if (w3all_viewforum_push0 == true && w3all_viewforum_push1 == false && w3all_viewforum_push2 == false && w3all_viewforum_push3 == false) {
     var w3matches = /viewforum\.php\?f=([0-9]+)/ig.exec(w3all_passed_url);
      w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[1];
      history.replaceState('', 'Forum', w3all_passed_url_push); 
     }
     
// Push passed urls to the browser history if on viewtopic.php //

if (true == w3all_passed_url.indexOf('viewtopic.php') > -1) {

   var w3matches = /viewtopic\.php\?(f=)([0-9]+)(&t=)?([0-9]+)?(&e=)?([0-9]+)?(&view=)?([a-z]+)?(&p=)?([0-9]+)?(&start=)?([0-9]+)?(#p)?([0-9]+)?/ig.exec(w3all_passed_url);

// #p31 // is passed as is, isn't recognized here ... to be added

 if( w3matches ){
 
 if(!w3matches[3] && w3matches[9]){ //f=2&p=64#p64
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&p=' + w3matches[10] + '#' + w3matches[10];
    	history.replaceState('', 'View Topic', w3all_passed_url_push); 
     } else if(w3matches[3] && !w3matches[5] && !w3matches[7] && !w3matches[9] && !w3matches[11] && !w3matches[13]){ // f=2&t=34
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&t=' + w3matches[4];
    	history.replaceState('', 'View Topic', w3all_passed_url_push); 
     } else if(w3matches[3] && w3matches[9] && w3matches[13]){ // f=2&t=34&p=64#p64
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&t=' + w3matches[4] + '&p=' + w3matches[10] + '#' + w3matches[10];
    	history.replaceState('', 'View Topic', w3all_passed_url_push); 
     }  else if(w3matches[3] && w3matches[11]){ // f=3&t=11&start=10
       w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&t=' + w3matches[4] + '&start=' + w3matches[12];
    	history.replaceState('', 'View Topic', w3all_passed_url_push); 
     } else if(w3matches[3] && w3matches[7]){ // f=3&t=11&e=1&view=unread#p82
                                              // f=3&t=11&view=unread#unread
                                              
         // go little more deep with this switch // add more in case here for view=someDifferent
          var dw3matches = /viewtopic\.php\?(.*)+(&e=)?([0-9]+)?(&view=)?([a-z]+)?(#p|#)?([0-9]+|[a-z]+)?/ig.exec(w3all_passed_url);
        if( dw3matches ){
          if( dw3matches[2] ){// f=3&t=11&e=1&view=unread#p82
          w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&t=' + w3matches[4] + '&e=' + dw3matches[3] + '&view=unread#p' + dw3matches[7];
    	    history.replaceState('', 'View Topic', w3all_passed_url_push); 
         }
          if( !dw3matches[2] ){// f=3&t=11&view=unread#unread
          w3all_passed_url_push = '".$w3allhomeurl."/index.php/".$wp_w3all_forum_folder_wp."/?f=' + w3matches[2] + '&t=' + w3matches[4] + '&view=unread#unread';
    	    history.replaceState('', 'View Topic', w3all_passed_url_push); 
         }
      } // end else if(w3matches[3] && w3matches[7]){
     }
 } // end if( w3matches ){
 
} // end push viewtopic

}
});

</script>";
?>
</div>
<!-- END iframe div -->
<?php get_footer(); ?>
