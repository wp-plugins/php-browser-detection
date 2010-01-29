=== Plugin Name ===
Contributors: martythornley
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=11225299
Tags: php, browser detection, browser, internet explorer, iphone, mobile
Tested up to: 2.9.1
Stable tag: trunk

PHP Browser Detection is a WordPress plugin used to detect a user's browser.

== Description ==

PHP Browser Detection is a WordPress plugin used to detect a user's browser. 
It could be used to send conditional CSS files for Internet Explorer, display different content or custom messages anywhere on the page, or to swap out flash for an image for iPhones.


**Using the Template Tag in your theme:**

`<?php $browserInfo = php_browser_info (); ?>`

This returns an array of all browser info.

*Some specific uses:*	

To access each part, just call the initial function...
`<?php $browserInfo=php_browser_info(); ?>`

... then use the following as examples:
`<?php $browser = $browserInfo[browser]; ?>`
`<?php $version = $browserInfo[version]; ?>`
`<?php $platform = $browserInfo[platform]; ?>`

Or use the conditional statements:
*is_mobile();
*is_iphone();

*is_firefox();
*is_webkit();

*is_IE();
*is_IE6();
*is_IE7();
*is_lt_IE6();
*is_lt_IE7();

EXAMPLE: 
`<?php if (is_IE) : /*add IE Fixes */;  else : /* do other stuff */ ; endif; ?>`

== Installation ==

1. Automatically install through the Plugin Browser or...
1. Upload entire `php-browser-detection` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.