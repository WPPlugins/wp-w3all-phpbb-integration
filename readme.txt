=== WP w3all phpBB ===
Contributors: axewww
Donate link: http://www.paypal.me/alessionanni/
Tags: wordpress, phpbb, integration, bridge, template, embed, iframe, include, users, transfer, cms, bulletin, board, forum, phpbb3, wp
Stable tag: 1.7.3
License: GPLv2 or later
Requires at least: 4.0.0
Tested up to: 4.8

WordPress w3all phpBB integration - easy, light, secure, powerful

== Description ==
WP w3all phpBB provide free integration - users login/registration between a phpBB bulletin board and WordPress cms.
Easy, fast, light and secure.

= Wp w3all phpBB integration =
Integration cookie based between WordPress and phpBB installed on same and subdomains.

= Widgets =
* Login/logout widget (but users can login/out/register on both WordPress or phpBB).
* Last Topics Posts widget, with Links or Links and Text, with or without avatars, Read/Unread Topics/Posts.

= WP w3all auto embed phpBB into WordPress template =
You can completely ignore this part. Or choose if WP w3all phpBB run in iframe mode and auto embed phpBB into WordPress template: complete the iframe responsive embedded procedure in few steps.

= Wp to phpBB users transfer =
WordPress users to phpBB transfer, to transfer pre existent WordPress users into phpBB! (phpBB users are auto added in WordPress when they login on WP first time or when they visit WP side, as logged into phpBB)

= phpBB avatars into WordPress options =
Use phpBB avatars to replace WP Gravatars options!

= WordPress MUMS ready =
It is possible to integrate a WP Multisite network, but linking the same phpBB into each subsite.

= Help pages =
A WP w3all phpBB help page with common questions, easy how to use and answers to be up and running in minutes is available here:
[WP w3all phpBB help page](https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/).

== Installation ==
* Please: [Read this page at axew3.com for installation helps](https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/)
* Set the path of phpBB cookie to be available over all the domain
* Upload the WP w3all phpBB plugin to your blog, Activate it, then go under 'Settings - WP w3all' and setup path and url settings: these are REQUIRED values
* Transfer WP existent users into phpBB: activate the WP w3all transfer, and under WP Settings menu (you can so deactivate the option when finished, removing it from WP menu) choose WP w3all transfer, follow instructions
* Activate, configure and save wp_w3all widgets login and last forums topics
* Detailed instructions at [WP phpBB integration help install docs page](https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/)

== Frequently Asked Questions ==
[Read this page at axew3.com for further helps: it contain detailed easy how to install and faq](http://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/).

== Screenshots ==
1. Wp w3all phpBB integration main config
2. Wp w3all (raw) WP users transfer to phpBB
3. WP w3all auto embed phpBB into your WordPress template

== Changelog ==

= 1.7.3 =
*Release Date - 27 Giu, 2017*

* Maintenance release: just improve correct auto cookie setting for new installs, add hints on WP admin wp_w3all config page for correct installation and a fix latest option added.
* Fix add option 'Retrieve posts on Last Topics Widget based on phpBB user's permissions' html error due to not existent var and subsequent html error on related option in wp_w3all admin config page.
* Improve and fix cookie setting.

= 1.7.2 =
*Release Date - 20 Giu, 2017*

* Fix correct code flow, if registration on WP for the WP user fail (maybe due to a thrown WP error).
* Add option 'Retrieve posts on Last Topics Widget based on phpBB user's permissions'
* More easy procedure and code improvements/fixes for phpBB iframe emebedded page: you can follow use the old one page-forum.php. But if you like to update to the new version, the page-forum.php need to be rebuilt and the code of overall_footer.html also need to be updated to the last one: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Remove the modal login for iframe, fix correct url redirect for phpBB emailed notification links
* Minor security fixes
* Several code fixes
* Improve correct cookie value auto-setup

= 1.7.1 =
*Release Date - 8 May, 2017*

* Fix cookie setting to be detected and setup one time, not at 'runtime' when page load (that was since 1.7.0 to correct detect of cookie setting). Now the check is for any www top level domain (for advanced -> see new function: w3all_extract_cookie_domain() on wp_w3all.php).
* Fix x MUMS: user was not added into phpBB on creating site/blog and related new user subadmin via admin.
* Fix x MUMS: users addition error message: see https://wordpress.org/support/topic/1-7-0-released/#post-9106748
* Tested to fix adding/registering/login users into WP default, WP Network Multisite, Buddypress (and should fix any other plugin that use signups). Still not add all profile fields update for Buddypress, it will be done (hope) on 1.7.2. 
* Added manual setting on wp_w3all.php to force plugin deactivation if necessary, to make possible a clean uninstall of the plugin even on most worse situations or just for testing purpose (this is completely redundant, if the plugin is installed using phpBB custom manual config.php, because you can deactivate the plugin within this file instead (as explained on inline hints in file)). A documentation help for common problems about will be done asap (if never necessary for someone).
* Added manual setting on wp_w3all.php also for cookie to force cookie setting change (option about this will be added also into wp_w3all admin config on next version).

= 1.7.0 =
*Release Date - 4 May, 2017*

* !Important WP MU Multisite: fix correct user addition into WordPress of registered phpBB users. Please read the procedure necesssary to setup correctly the plugin on a WP MSMU installation here: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/
* !Important: Fix cookie domain fail on domains suffix like .co.uk or subdomains. Now cookies are properly configured before to be set, for any possible suffix on domain or subdomain. It was causing mainly, also, the most famous problem: loop on login/out on domain suffix like co.uk and all others similars.
* Include all long list of patch fixes applied on 1.6.8 (and more). See this topic: https://wordpress.org/support/topic/1-6-9-release-additions-and-improvements/ or/and this https://www.axew3.com/w3/index.php/forum/?f=2&t=506&p=1969#1969 for (not complete) list of fixed bugs.
* Iframe improvements: all the js code has been patched and the page-forum.php has been improved for correct scroll on main events. The documentation about has been rewrite to be more easy (and i hope) in an Eng more understandable! The iframe, with proper settings, work on any integration type like domain/subdomain or subdomain/subdomain.
* Correct users addition/activation/redirection on all possibles done tests.
* Several important more fixes.

= 1.6.9 =
*Release Date - 26 Apr, 2017*

* Security fix.
* Add Multisite installation compatibility (1st version): it is possible to link only the same phpBB install if the plugin is activated as network. Is not possible to link a different phpBB on sub-sites! Explanation article will be available here: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/ 
* Fix user addition as active into phpBB, if the user is added manually by admin.
* Fix correct redirect existent phpBB user when added onlogin in WordPress.
* Add Buddypress compatibility (no profile fields updates for fields like Google, Twitter etc. This aspect is moved to next versions)
* Iframe mode: (this is not required, you can still use the old one phpBB overall_footer.html code with old WP page-forum.php). The page-forum.php has been heavily modified/improved, js/ajax code has changed, as well the rewrite/push mode of iframe links. Rebuild on wp_w3all config or manually substitute page-forum.php (contained in the plugin VIEWS folder) and change overall_footer.html phpbb JS code with the new one. To switch to ajax 1.6.9 iframe features please follow here: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Iframe mode for subdomains installation: added code and inline code hints (into views/page-forum.php file and overall_footer.html file) to resolve any possible iframe compatibility issue.
* More relevant fixes.
* Minors code clean up.

= 1.6.8 =
*Release Date - 4 Apr, 2017*

* Fix/option language switch for users between WP/phpBB: it is now option on wp_w3all config page to be activated or not (explanation added on same option field).
* Add user's last visit time update in phpBB even if user visit WordPress, but not phpBB forum. Added into existent time/update query, do not add any call more to phpBB db.
* Fix display 'You have n unread phpBB PM': by default it display only if there are new pm messages and not if 0 (zero).
* Replace preg_match to check for email, with native WP function about.
* Some code change into iframe default page-forum.php code: if you wish to update to latest iframe changes, you need to rebuild page forum into wp_w3all Admin config page (or do it manually) and it is necessary to update the overall_footer.html code of phpBB template with latest: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Minors code clean up.


= 1.6.7 =
*Release Date - 2 Apr, 2017*

* Add profile language update between phpBB and WordPress for users
* Fix deactivated phpBB user re-added in WordPress (maybe deactivated in phpBB due to deletion of the user in WordPress). Now a deactivated phpBB user will not be added or re-added into WordPress, as default behavior
* Fix display correct number of topics for each different widget instance setup
* Add avatar row on wp login widget user's info
* Fix Last Topics Widget html output to be more like any WP widget default output: fix correct separation between code and text output on same file (views/phpbb_last_topics.php)
* Improve query execution time for Last Topics Widget when Avatar active: the query is executed by searching for 'phpBB email hash' which is Key Index into phpBB db, and not by username.
* Fix uninitialized variables bug in WP_w3all admin config page, that was leading WP_w3all admin config page to emit notice errors as html output into Config fields for Avatars or Preferences (where php ini were set to display php errors/notices: it was breaking options fields).
* More minor fixes

= 1.6.6 =
*Release Date - 23 Mar, 2017*

* Fix scroll for +- all phpBB iframe instances in the definitive way. The page-forum.php need to be rebuilt and overall_footer.html in phpBB need to be updated with the new code to apply all javascript fixes. Substitute the old code added into phpBB overall_footer.html with the new one you can found here: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Fix partial execution of code about Last Topics Notification, fired also when option not active
* Add User Info option into Login Widget: Name, Registration Date, Unread PMs, Number of posts on phpBB. Avatar will be added on next versions.
* Add Notification about new messages in phpBB, into WP admin bar menu for users (not suitable maybe for iframe mode, as it need to reload WP page to update state read/unreads. It will be improved soon with ajax to be responsive in this sense) 
* More minor code clean up and fixes

= 1.6.5 =
*Release Date - 14 Mar, 2017*

* Fix correct login for all WP Social plugins (and supposedly also for frontend ajax login plugins, but this has not been tested)
* With the above, should resolve also any login problem for all external plugins login pages
* Fix iframe scroll top for (+- all) phpBB events, except for Post Preview: onclick it is now correctly redirected inside iframe, but page will not scroll to top. To apply changes about correct scroll, it is required to rebuild the forum page on WP admin -> wp_w3all config page (or substitute it manually)
* Fix correct post's time for Last Topics Widget
* Remove the use of addons/ext_plugins_fixes.php file, since the fix above about Social plugins: this remove a bug related session keys on phpBB, that were duplicated WP onlogin event
* Correct WP error message 'user already exist on our forum' if user exist on WP registration
* More minor code fixes

= 1.6.4 =
*Release Date - 27 Feb, 2017*

* Security fixes
* Fix error mysql connect if wrong path set for the config.php file on wp_w3all config at first install (or maybe after, setting it as wrong for testing pourpose disabling wp_w3all)
* Speed up queries reducing to the minimum possible calls to phpBB (expecially when last topics and phpBB avatars active)
* Fixes Last Topic Widgets code execution
* More minor fixes


= 1.6.3 =
*Release Date - 22 Feb, 2017*

* Fix correct password check/set between phpBB and WordPress. All WordPress passwords are now hashed (and checked when required) as phpBB bcrypt(). Once the integration will be uninstalled, WP users with passwords containing certain chars, will have to reset their password to correctly login into WordPress.

= 1.6.2 =
*Release Date - 19 Feb, 2017*

* Fix correct redirect on login, where user was appearing as not logged (because redirected to the wp-login login page) also if correctly logged in, in certain conditions.
* Fix correct user activation/deactivation of the user in phpBB when user is set as No Role in WP admin, or is reactivated. When reactivated in WP, will be reactivated as normal user in phpBB, despite if you'll reactivate with different role in WP. Next version will fix also this.
* More minor fixes.

= 1.6.1 =
*Release Date - 16 Feb, 2017*

* !Important - Fix 'phpBB get config' function, which was called and repeated several times during plugin code execution. It is now exeuted only one time, on any possible plugin options configuration.
* !Important - Fix WP_w3all Preferences: no reports about this but, the only one option working was the 'Activate WordPress to phpBB users transfer', on 'WP_w3all Preferences' options section while all others fails. 
* Add - notify about Read/Unread Topics/Posts option (WP_w3all Preferences), for registered users in Last Topics Widgets.
* More important fixes.

= 1.6.0 =
*Release Date - 12 Feb, 2017*

* Fix first login fail on register in WP definitively.
* Update all iframe resizer files. It is Request to Update the old iframeResizer.contentWindow.min.js into phpBB root. Please replace the old phpBB root iframeResizer.contentWindow.min.js file with new one contained inside the /wp-content/plugins/wp-w3all-phpbb-integration/addons/resizer folder.
* Minor fixes.

= 1.5.9 =
*Release Date - 28 Gen, 2017*

* Fix minor security bug.
* Fix any PHP error notice and warning (NOTE: except Warnings if path setting wrong on wp_w3all config).
* Fix correct redirect in WP page where login has been performed, if login done using the WP_w3all widget Login/out.
* Several code fixes.
* More minor fixes.

= 1.5.8 =
*Release Date - 24 Gen, 2017*

* Fix correct phpBB 3.2> - phpBB 3.1> profiles fields update about url (and fix ready code for all other fields).
* Fix bug avatar about: if on phpBB option avatar.driver.remote enabled, avatar in WP was not included for the user.
* Fix language files, adding all configuration page hints and frontend into translated strings. The unique NOT translated part, is the Admin Users Transfer page of wp_w3all.
* Fix logout loop, if on phpBB there is a wrong phpBB cookie settings.
* More minor fixes.

= 1.5.7 =
*Release Date - 9 Gen, 2017*

* Fix bug 'first user login on WP fail'.
* Fix correct redirect for widgets, and get vars, using native WP functions.
* Resolve 'Iframe, post preview fail': for this fix, that is related to phpBB overall_footer.html code, please just read this post: https://wordpress.org/support/topic/phpbb3-post-preview/#post-8630131 .
* Fix correct redirection to WP forum page, onlogin, if login done via modal/iframe.
* Add translation files for all front end strings output (if not please report), but not for admin configuration back-end.
* Fix 'Warning unwanted chars detected' issue, that was wrongly fired on some path settings.
* Fix a bug on widget file inclusion: translate function on wrong place has been removed.
* Fix a php Warning, that was suppressed by WP on redirect (so maybe never seen) by fixing the - add_action('wp_login' - adding correct params call.
* More fixes.

= 1.5.6 =
*Release Date - 11 Nov, 2016*

* Resolve problem about login, related latest security bug fix reported on post https://wordpress.org/support/topic/locked-out-of-admin-by-latest-update/
* Correct sanitization, that should solve (maybe) many other compatibility problems.
* Resolve correct addition and no more loop redirect, if user register and login directly in iframe mode.
* Fix more hints on Admin config page.
* Fix more issues and updates.

= 1.5.5 =
*Release Date - 8 Nov, 2016*

* Fix a security bug.

= 1.5.4 =
*Release Date - 7 Nov, 2016*

* Fix avatars bug. Should be the definitive fix that should resolve all reported bugs about avatars.
* Fix more secondary bugs.
* Fix some English hints on WP_w3all admin config page.

= 1.5.3 =
*Release Date - 23 Oct, 2016*

* Fix error user already added on login when user added from phpBB into WP (on first time login on WP).
* Fix avatar on Last Topics Widget bug when no number for avatars to retrieve was set on admin WP_w3all config.

= 1.5.2 =
*Release Date - 23 Sep, 2016*

* Fix user insertion into WP for certain phpBB users with not allowed specific chars. Please take a look into this post for further info about: http://www.axew3.com/w3/forum/?forum_id=2&topic_id=188&post_id=828#p828
* Fix avatar on Last Topics Widget bug, due the same above related problem, if user was containing unwanted char in WP the query was return error.
* Fix error "user already added" message if a phpBB user was added in WP after login action, and correct auto login.
* More fixes

= 1.5.1 =
*Release Date - 16 Sep, 2016*

* Fix definitively users transfer, and wrong user addition if username contain certain characters
* Fix wrong WP user addition on phpBB for the same above reason (user exist but after not found on ACP: after this release, will follow an article on forum on how to easily fix this problem in phpBB ACP, will be posted, if there is any to resolve.
* Fix missed <ul> tag on Last Topics Widget
* More fixes

= 1.5.0 =
*Release Date - 12 Sep, 2016*

* Warning for WP_w3all installations using manual config.php on WP_w3all admin config page! Please READ this http://www.axew3.com/w3/index.php/forum/?viewforum=2&viewtopic=173 before update to 1.5.0!
* Add option: replace WordPress Gravatars with user's profile phpBB avatars, if there is not a phpBB avatar for the user, Gravatar will be displayed (Vers 1.0)
* Improved Last Forums Topics query by Sitmo2012 https://wordpress.org/support/users/sitmo2012/
* Fix all Php Notices and Warnings
* Fix groups assignement for users added in WordPress from phpBB
* Hints: improvement and corrections
* More code fixes

= 1.4.9 =
*Release Date - 14 Aug, 2016*

* Add - Deactivate phpBB user account (option) until WP confirmation
* Fix profile fields updates (if empty URL field on user profile, update was not executed correctly)
* Fix minor iframe bug

= 1.4.8 =
*Release Date - 20 Jul, 2016*

* Fix 1.4.7 just released, more profile bugs. Update as soon please!

= 1.4.7 =
*Release Date - 19 Jul, 2016*

* Add Manual phpBB configuration mode for compatibility with some external plugins (and for a more easy and fast config include, expecially on subdomains installation). See wp_w3all config admin page for easy instructions to switch to manual config (that mean just change path to the new edited config.php file)
* Fix several compatibility problems about external, users login/registration profile update, plugins
* Fix correct wordpress default profile fields updates
* Fix minor security bug
* Fix minor bugs

= 1.4.6 =
*Release Date - 15 Giu, 2016*

* Only an important security fix. Update as soon.

= 1.4.5 =
*Release Date - 14 Giu, 2016*

* Fix stay on index and viewforum when refresh browser iframe mode
* Fix correct config include and code execution (on rare possible scenario)
* Fix Last Topics Widget notice error and overall WP on certain php.ini configurations
* Fix more minor bugs
* Add compatibility with plugins that replace default WP login page (not ajax frontend login widgets)

= 1.4.4 =
*Release Date - 9 Giu, 2016*

* Fix remember me auto login that was Not solved since 1.4.0

= 1.4.3 =
*Release Date - 8 Giu, 2016*

* Fix users deactivation/activation in WordPress, that are at same time activated/deactivated in phpBB
* Fix correct number of topics to display for each Last Topics Widget instance

= 1.4.2 =
*Release Date - 7 Giu, 2016*

* Fix logout and remember me auto login

= 1.4.1 =
*Release Date - 6 Giu, 2016*

* Fix WP logout loop redirection and logout (remain minor fix)
* Fix Last Topics Post - Text Post Mode fix, where was failing to clean/remove tags
* Fix correct session user update
* Fix, user active, or not on register: registered phpBB user, not active in phpBB, added as deactivated in WP (ex: awaiting validation)
* Fix more 1.4.0 bugs


= 1.4.0 =
*Release Date - 31 Mag, 2016*

* Fix user profile fields update: email, password, URL. Direct update in phpBB if profile update done on WP side. If user profile is modified via phpBB, the update in WP is done when user visit WP side
* Add Posts Text option on Last Topics widget - Post Text option to display latest topics, only with title/link author and date, or title/link, post text (choose how many words for each widget), author and date
* Ready (on next version) for more profile fields updates like (signature on phpBB to Bio info on WP and viceversa), Facebook, Google etc.
* Fix WP to phpBB users transfer definitively: users are correctly added as activated on phpBB if with roles on WP, deactivated in phpBB if no Role on WP
* Fix for more security on login
* Fix several minor bugs
* Add https

= 1.3.9 =
*Release Date - 23 Mag, 2016*

* Remove latest additions about ajax plugins with login on frontend ajax widget, due to several bugs that cause onlogin/out
* English language corrections: thank to member raykaii at WordPress.org

= 1.3.8 =
*Release Date - 21 Mag, 2016*

* Fix compatibility with ajax login plugins, ReCaptcha ...
* More very important fixes

= 1.3.7 =
*Release Date - 19 Mag, 2016*

* Minor (but important to fix) bug about function wp_delete_user function not exist.
* Minor fixes

= 1.3.6 =
*Release Date - 19 Mag, 2016*

* !Important Fix add user into phpBB bug on register WP (since 1.3.4)

= 1.3.5 =
*Release Date - 15 Mag, 2016*

* Fix user email address update on WordPress if user email is modified on phpBB profile. Update is done when user visit WordPress. If instead, email address change is done via WordPress Admin or user profile, it is immediately applied on both WP and phpBB.

= 1.3.4 =
*Release Date - 15 Mag, 2016*

* Fix wp_error coming logged as newer user into WordPress

= 1.3.3 =
*Release Date - 14 Mag, 2016*

* Fix 1.3.2 user logout bug when login done on WP side

= 1.3.2 =
*Release Date - 13 Mag, 2016*

* Fix users transfer, that can be executed safely also more than one time consecutively
* Deactivated/activated/banned (no role user) user in WP admin, is deactivated/activated on phpBB. Deactivated/banned etc user in phpBB ACP is auto deactivated only on user WP onlogin
* More important fixes

= 1.3.1 =
*Release Date - 3 Mag, 2016*

* Fix cookie check and reload problems in some installations and remember me logins

= 1.3.0 =
*Release Date - 2 Mag, 2016*

* Fix wrong old phpBB user addition in WordPress when username was containing wrong chars. It solve so probably, any other reported redirect error.

= 1.2.9 =
*Release Date - 1 Mag, 2016*

* Fix bug subdomains login/out due to a wrong setcookie

= 1.2.8 =
*Release Date - 29 Apr, 2016*
* Fix presistent autologin

= 1.2.7 =
*Release Date - 27 Apr, 2016*
* Important! Fix old phpBB users, that login first time in WP and aren't added: now are added and logged on fly
* Fix redirect problems on some installations
* Fix stay on topic or page on browser refresh (iframe mode)
* More important fixes

= 1.2.6 =
*Release Date - 22 Apr, 2016*
* Fix ONLY 1.2.5 bug about path setting not correct and WP admin logout can't login error

= 1.2.5 =
*Release Date - 21 Apr, 2016*
* Fix persistent login
* Fix changed user passw on phpBB, if first login in WP instead of phpBB
* Fix WP users transfer to phpBB

= 1.2.4 =
*Release Date - 18 Apr, 2016*
* Fix correct username/nicename registration insert into phpBB
* Fix users transfer for correct username/nicename registration insert into phpBB

= 1.2.3 =
*Release Date - 17 Apr, 2016*
* Fix user post count on transfer process where was set to 1 and should be 0
* Fix persistent rememberme autologin
* Fix user post count on phpBB user registration when done on WP side

= 1.2.2 =
*Release Date - 15 Apr, 2016*
* Unique config include that fix numerous problems and increase script execution speed
* Cookie domain fix for subdomains
* Security fix
* More minor fixes

= 1.2.1 =
*Release Date - 8 Apr, 2016*
* Fix sessions +- definitively. Remain to unify queries
* Fix password/email change for WP admin to match phpBB pass if different on WP login
* Links for iframe: viewtopic and viewforum
* WP 4.5 more fix

= 1.2.0 =
*Release Date - 5 Apr, 2016*
* Fix sessions
* Fix password/email change for admin and users
* WP 4.5 ready

= 1.1.9 =
*Release Date - 29 March, 2016*
* Fix bug user password reset on WP
* First fix for installations on WP multisite

= 1.1.8 =
*Release Date - 28 March, 2016*
* Fix bug about when wrong path and warning messages
* More fast (action wp_loaded replace init for the wp_w3all_phpbb_init)
* Prepare for next release and definitive sessions fix onlogin, when login performed on WP side

= 1.1.7 =
*Release Date - 27 March, 2016*
* Fix phpBB session logic
* Login/register/lostpass by default in WP if actions are performed on WP side

= 1.1.6 =
*Release Date - 25 March, 2016*
* Fix login out of admin in front end

= 1.1.5 =
*Release Date - 24 March, 2016*
* Users can now login/out/register and change profile email or password on WP or phpBB. More on next versions.
* Fix iframe mode logout when performed from WP: required to rebuild the forum page on wp_w3all or manually modify adding the new logout fix. 
* Fix user transfer from WP to phpBB, where last insertid value was missed during insert query
* Fix several bugs

= 1.1.4 =
*Release Date - 10 March, 2016*
* Fix iframe mode
* Remove unuseful options on config wp_w3all
* Add modal CSS login for iframe mode
* Fix user transfer bug
* Fix several bugs

= 1.1.3 =
*Release Date - 7 March, 2016*
* Fix login/out

= 1.1.2 =
*Release Date - 5 March, 2016*
* Fix problems about correct links and inclusion for widgets
* Fix more bugs about iframe mode
* Added option for widget links iframe mode subdomains or not

= 1.1.1 =
*Release Date - 3 March, 2016*
* Fix problem for wordpress repo that do not correctly download the new inc folder, so it has been removed

= 1.1.0 =
*Release Date - 1 March, 2016*
* Solve more wp_w3all path setting bug

= 1.0.9 =
*Release Date - 29 Febrary, 2016*
* Solve wp_w3all path setting bug

= 1.0.8 =
*Release Date - 29 Febrary, 2016*
* Add responsive javascript or css iframe option that is created by default
* Added related documentation

= 1.0.7 =
*Release Date - 28 Febrary, 2016*
* Fix login/out widget links for iframe mode
* Fix correct template page name detection 
* Fix minor bugs

= 1.0.6 =
*Release Date - 23 Febrary, 2016*
* Fix config problems
* Add more friendly configuration interface
* Fix minor bugs

= 1.0.5 =
*Release Date - 21 Febrary, 2016*
* Added phpBB embedded into WordPress template feature
* Added js library
* Fix minor bugs

= 1.0.4 =
*Release Date - 15 Febrary, 2016*
* Fix cookie logout bug for subdomains and minor bugs

= 1.0.3 =
*Release Date - 14 Febrary, 2016*
* Added subdomain capability to the wp_w3all integration plugin. You need to change the relative path on wp_w3all config and substitute with ABSOLUTE path.

= 1.0.2 =
*Release Date - 13 Febrary, 2016*
* Added WordPress users transfer to phpBB forum
* Added exclude Forums from last Topics option
* Fix database cleanup on plugin deactivation
* Fix redirect after login on WordPress to correct WP page
* Fix lost config values on plugin update
* Fix minor bugs

= 1.0.1 =
*Release Date - 2 Febrary, 2016*
* Fix problem about default install administrators (Uid 1 on WP and Uid 2 in phpBB) with different usernames.
* Added to the widget w3all Login the option to choose different text to display on login/out.

= 1.0.0 =
*Release Date - 1 Febrary, 2016*
