<?php
// WARNING COMMENT OUT ( remove chars // ) on the very last line of this file to get wp_w3all active and working!

// WP phpBB w3all - phpBB configuration file

// WARNING COMMENT OUT ( remove chars // ) on the very last line of this file to get wp_w3all active and working!

// note: vars have been renamed (respect a default phpBB config.php file) to avoid conflicts with external plugins that may have vars named as phpBB

// Open with a text editor your phpBB root config.php file
// Change values in this file according (as on) to your phpBB config.php file and remove the two // characters preceding the very last line here below

$w3all_dbms = ''; // maybe required
$w3all_dbhost = 'required value here';
$w3all_dbport = ''; // maybe required
$w3all_dbname = 'required value here';
$w3all_dbuser = 'required value here';
$w3all_dbpasswd = 'required value here';
$w3all_table_prefix = 'required value here';
$w3all_phpbb_adm_relative_path = ''; // maybe required
$w3all_acm_type = ''; // maybe required

@define('WP_W3ALL_MANUAL_CONFIG', true);

/* WARNING UNCOMMENT (REMOVE) // the two characters which precedes the very last line here below, to get wp_w3all active and working! 
you can (re)comment ( prepend with // ) the line below if you want to exclude completely (deactivate) WP_w3all (or setup wrong path value on wp_w3all cofing page to have the same result) */

// @define('PHPBB_INSTALLED', true);