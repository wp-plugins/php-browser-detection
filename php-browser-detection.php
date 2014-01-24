<?php
/*
Plugin Name: PHP Browser Detection
Plugin URI: http://wordpress.org/extend/plugins/php-browser-detection/
Description: Use PHP to detect browsers for conditional CSS or to detect mobile phones.
Version: 2.2.3
Author: Mindshare Studios, Inc.
Author URI: http://mind.sh/are
License: GNU General Public License v3
License URI: license.txt
Text Domain: php-browser-detection
*/

/**
 *
 * Copyright 2009-2014 Marty Thornley / Mindshare Studios, Inc.
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

// add support for Windows systems without PHP's fnmatch()
if(!function_exists('fnmatch')) {
	function fnmatch($pattern, $string) {
		return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
	}
}

/**
 * Returns array of all browser info.
 *
 * @usage $browserInfo = php_browser_info();
 *
 * @return array
 */
function php_browser_info() {
	$agent = $_SERVER['HTTP_USER_AGENT'];

	$x = dirname(__FILE__);
	$browscap = $x.'/php_browser_detection_browscap.ini';
	if(!is_file(realpath($browscap))) {
		return array('error' => 'No php_browser_detection_browscap.ini file found.');
	}
	$agent = $agent ? $agent : $_SERVER['HTTP_USER_AGENT'];
	$yu = array();
	$q_s = array("#\.#", "#\*#", "#\?#");
	$q_r = array("\.", ".*", ".?");

	if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
		$brows = parse_ini_file(realpath($browscap), TRUE, INI_SCANNER_RAW);
	} else {
		$brows = parse_ini_file(realpath($browscap), TRUE);
	}

	foreach($brows as $k => $t) {
		if(fnmatch($k, $agent)) {
			$yu['browser_name_pattern'] = $k;
			$pat = preg_replace($q_s, $q_r, $k);
			$yu['browser_name_regex'] = strtolower("^$pat$");
			foreach($brows as $g => $r) {
				if(isset($t['Parent']) && $t['Parent'] == $g) {
					foreach($brows as $a => $b) {
						if($r['Parent'] == $a) {
							$yu = array_merge($yu, $b, $r, $t);
							foreach($yu as $d => $z) {
								$l = strtolower($d);
								$hu[$l] = $z;
							}
						}
					}
				}
			}
			break;
		}
	}
	if(isset($hu)) {
		return $hu;
	} else {
		return FALSE;
	}
}

/**
 * Returns the name of the browser.
 *
 * @return string
 */
function get_browser_name() {

	$browserInfo = php_browser_info();

	if(is_firefox()) {
		return 'Firefox';
	} elseif(is_safari()) {
		return 'Safari';
	} elseif(is_opera()) {
		return 'Opera';
	} elseif(is_chrome()) {
		return 'Chrome';
	} elseif(is_ie()) {
		return 'Internet Explorer'; // The browser to download another browser with
	} elseif(is_ipad()) {
		return 'iPad';
	} elseif(is_ipod()) {
		return 'iPod';
	} elseif(is_iphone()) {
		return 'iPhone';
	} else {
		return 'Unknown Browser: '.$browserInfo['browser'].' - Version: '.get_browser_version();
	}
}

/**
 *
 * Returns the browser version number.
 *
 * @return mixed
 */
function get_browser_version() {
	$browserInfo = php_browser_info();
	return $browserInfo['version'];
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
	if(isset($browserInfo['browser']) && $browserInfo['browser'] == 'Firefox') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['majorver'] == $version) :
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
	if(isset($browserInfo['browser']) && $browserInfo['browser'] == 'Safari') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['majorver'] == $version) :
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
	if(isset($browserInfo['browser']) && $browserInfo['browser'] == 'Chrome') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['majorver'] == $version) :
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
	if(isset($browserInfo['browser']) && $browserInfo['browser'] == 'Opera') {
		if($version == '') {
			return TRUE;
		} elseif($browserInfo['majorver'] == $version) {
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
	if(isset($browserInfo['browser']) && $browserInfo['browser'] == 'IE') {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['majorver'] == $version) :
			return TRUE;
		else :
			return FALSE;
		endif;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for mobile devices.
 *
 * @return bool
 */
function is_mobile() {
	$browserInfo = php_browser_info();
	if(isset($browserInfo['ismobiledevice'])) {
		if($browserInfo['ismobiledevice'] == 1 || $browserInfo['ismobiledevice'] == "true") {
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
	if((isset($browserInfo['browser']) && $browserInfo['browser'] == 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
		if($version == '') :
			return TRUE;
		elseif($browserInfo['majorver'] == $version) :
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
		elseif($browserInfo['majorver'] == $version) :
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
		elseif($browserInfo['majorver'] == $version) :
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
	if(isset($browserInfo['javascript'])) {
		if($browserInfo['javascript'] == 1 || $browserInfo['javascript'] == "true") {
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
	if(isset($browserInfo['cookies'])) {
		if($browserInfo['cookies'] == 1 || $browserInfo['cookies'] == "true") {
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
	if(isset($browserInfo['cssversion'])) {
		if($browserInfo['cssversion'] >= 1) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Conditional to test for IE6.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie(6)) { }
 */
function is_ie6() {
	return is_ie(6);
}

/**
 *
 * Conditional to test for IE7.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie(7)) { }
 */
function is_ie7() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	return is_ie(7);
}

/**
 *
 * Conditional to test for IE8.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie(8)) { }
 */
function is_ie8() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	return is_ie(8);
}

/**
 *
 * Conditional to test for IE9.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie(9)) { }
 */
function is_ie9() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	return is_ie(9);
}

/**
 *
 * Conditional to test for IE10.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie(10)) { }
 */
function is_ie10() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	return is_ie(10);
}

/**
 *
 * Conditional to test for less than IE8.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 6) { }
 */
function is_lt_IE6() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 6) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for less than IE7.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 7) { }
 */
function is_lt_IE7() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 7) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for less than IE8.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 8) { }
 */
function is_lt_IE8() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 8) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for less than IE9.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 9) { }
 */
function is_lt_IE9() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 9) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for less than IE10.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 10) { }
 *
 */
function is_lt_IE10() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 10) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Conditional to test for less than IE11.
 *
 * @return bool
 *
 * @deprecated Use the future-proof syntax instead of this function: if(is_ie() && get_browser_version() < 11) { }
 *
 */
function is_lt_IE11() {
	_deprecated_function(__FUNCTION__, '3.6', 'is_ie');
	if(is_ie() && get_browser_version() < 11) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Add links to the plugins screen
 *
 * @param $data
 * @param $page
 *
 * @return array
 */
if(is_admin()) {
	if(!empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == sprintf('%s.php', 'plugins')) {
		add_filter('plugin_row_meta', 'php_browser_detection_plugin_links', 10, 2);
	}
}
function php_browser_detection_plugin_links($data, $page) {
	if($page == plugin_basename(__FILE__)) {
		$data = array_merge(
			$data,
			array(
				sprintf(
					'and by <a href="http://martythornley.com/" target="_blank">%s</a>',
					esc_html__('Marty Thornley', 'php-browser-detection')
				),
				sprintf(
					'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=11225299" target="_blank">%s</a>',
					esc_html__('Donate', 'php-browser-detection')
				)
			)
		);
	}
	return $data;
}
