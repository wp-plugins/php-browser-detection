<?php
/*
Plugin Name: PHP Browser Detection
Plugin URI: http://wordpress.org/extend/plugins/php-browser-detection/
Description: Use PHP to detect browsers for conditional CSS or to detect mobile phones.
Version: 3.0 BETA
Author: Mindshare Studios, Inc.
Author URI: http://mind.sh/are
License: GNU General Public License v3
License URI: license.txt
Text Domain: php-browser-detection
*/

/**
 *
 * Copyright 2009-2014 Mindshare Studios, Inc. / Marty Thornley / Garet Jax
 *
 * Based on code originally by Marty Thornley
 * Since version 3 making use of the BROWSCAP-PHP library by Garet Jax
 * @link https://github.com/browscap/browscap-php
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */


if(!defined('PBD_DIR_PATH')) {
	define('PBD_DIR_PATH', plugin_dir_path(__FILE__)); // /.../wp-content/plugins/php-browser-detection/
}

require_once('lib/Browscap.php');
require_once('inc/deprecated.php');
require_once('inc/admin.php');

$browscap = new \phpbrowscap\Browscap(PBD_DIR_PATH.'/cache');

/**
 * Returns array of all browser info.
 *
 * @usage $browserInfo = php_browser_info();
 *
 * @return array
 */
function php_browser_info() {
	global $browscap;
	return $browscap->getBrowser(NULL, TRUE);
}

/**
 * Returns the name of the browser.
 *
 * @return string
 */
function get_browser_name() {
	$browserInfo = php_browser_info();
	return $browserInfo['Browser'];
}

/**
 *
 * Returns the browser version number.
 *
 * @return mixed
 */
function get_browser_version() {
	$browserInfo = php_browser_info();
	return $browserInfo['Version'];
}

/**
 *
 * Conditional to test for Firefox.
 *
 * @param string $version
 *
 * @return bool
 */
function is_firefox($version = '') {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'Firefox') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 *
 * Conditional to test for Safari.
 *
 * @param string $version
 *
 * @return bool
 */
function is_safari($version = '') {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'Safari') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for Chrome.
 *
 * @param string $version
 *
 * @return bool
 */
function is_chrome($version = '') {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'Chrome') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for Opera.
 *
 * @param string $version
 *
 * @return bool
 */
function is_opera($version = '') {
	$browserInfo = php_browser_info();

	if(isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'Opera') {
		if($version == '') {
			return TRUE;
		} elseif($browserInfo['MajorVer'] == $version) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for IE.
 *
 * @param string $version
 *
 * @return bool
 */
function is_ie($version = '') {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'IE') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for tablet devices.
 *
 * @return bool
 */
function is_tablet() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['isTablet'])) {
		if($browserInfo['isTablet'] == 1 || $browserInfo['isTablet'] == "true" || $browserInfo['Device_Type'] == "Tablet") {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Conditional to test for mobile devices.
 *
 * @return bool
 */
function is_mobile() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['isMobileDevice'])) {
		if($browserInfo['isMobileDevice'] == 1 || $browserInfo['isMobileDevice'] == "true") {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Conditional to test for iPhone.
 *
 * @param string $version
 *
 * @return bool
 */
function is_iphone($version = '') {
	$browserInfo = php_browser_info();
	if((isset($browserInfo['Browser']) && $browserInfo['Browser'] == 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for iPad.
 *
 * @param string $version
 *
 * @return bool
 */
function is_ipad($version = '') {
	$browserInfo = php_browser_info();
	if(preg_match("/iPad/", $browserInfo['browser_name_pattern'], $matches) || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for iPod.
 *
 * @param string $version
 *
 * @return bool
 */
function is_ipod($version = '') {
	$browserInfo = php_browser_info();
	if(preg_match("/iPod/", $browserInfo['browser_name_pattern'], $matches)) {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['MajorVer'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for JavaScript support.
 *
 * @return bool
 */
function browser_supports_javascript() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['JavaScript'])) {
		if($browserInfo['JavaScript'] == 1 || $browserInfo['JavaScript'] == "true") {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Conditional to test for cookie support.
 *
 * @return bool
 */
function browser_supports_cookies() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['Cookies'])) {
		if($browserInfo['Cookies'] == 1 || $browserInfo['Cookies'] == "true") {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Conditional to test for CSS support.
 *
 * @return bool
 */
function browser_supports_css() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['supportscss'])) {
		if($browserInfo['supportscss'] == 1 || $browserInfo['supportscss'] == "true") {
			return TRUE;
		}
	}
	if(isset($browserInfo['CssVersion'])) {
		if($browserInfo['CssVersion'] >= 1) {
			return TRUE;
		}
	}
	return FALSE;
}
