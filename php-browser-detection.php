<?php
/*
Plugin Name: PHP Browser Detection
Plugin URI: http://martythornley.com/downloads/php-browser-info
Description: Use PHP to detect browsers for conditional CSS or to detect mobile phones.
Version: 1.2
Author: Marty Thornley
Author URI: http://martythornley.com
*/

/*  Copyright 2009  Marty Thornley  (email : marty@martythornley.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* CREDITS

	php_browser_info() - uses php_get_browser() - from http://www.davidtavarez.com/archives/detect-mobile-browsers-using-php/
	php_browscap.ini is from php_browscap.ini - http://browsers.garykeith.com/downloads.asp

*/

/* USE

$browserInfo = php_browser_info();

$platform = $browserInfo[platform];				- MacOSX
$browser = $browserInfo[browser];				- Firefox
$version = $browserInfo[version];				- 3.6
$majorVersion = $browserInfo[majorver];     	- 3 (number to left of decimal)
$minorVersion = $browserInfo[minorver];     	- 6 (number to right of decimal)
$javascriptEnabled = $browserInfo[javascript]; 	- returns 1 if true
$ismobiledevice = $browserInfo[ismobiledevice];	- returns 1 if true

EXAMPLE ARRAY RETURNED:

[browser_name_pattern] =&gt; Mozilla/5.0 (Macintosh; *; *Mac OS X*; *; rv:1.9.2*) Gecko/* Firefox/3.6*     
[browser_name_regex] =&gt; ^mozilla/5\.0 (macintosh; .*; .*mac os x.*; .*; rv:1\.9\.2.*) gecko/.* firefox/3\.6.*$     
[browser] =&gt; Firefox     
[version] =&gt; 3.6     
[majorver] =&gt; 3     
[minorver] =&gt; 6     
[platform] =&gt; MacOSX     
[alpha] =&gt;      
[beta] =&gt; 1     
[win16] =&gt;      
[win32] =&gt;      
[win64] =&gt;      
[frames] =&gt; 1     
[iframes] =&gt; 1     
[tables] =&gt; 1     
[cookies] =&gt; 1     
[backgroundsounds] =&gt;      
[cdf] =&gt;      
[vbscript] =&gt;      
[javaapplets] =&gt; 1     
[javascript] =&gt; 1     
[activexcontrols] =&gt;      
[isbanned] =&gt;      
[ismobiledevice] =&gt;      
[issyndicationreader] =&gt;      
[crawler] =&gt;      
[cssversion] =&gt; 3     
[supportscss] =&gt; 1     
[aol] =&gt;      
[aolversion] =&gt; 0     
[parent] =&gt; Firefox 3.6 


CONDITIONAL STATEMENTS INCLUDED:

is_mobile ()
is_iphone ()

is_IE ()
is_IE6 ()
is_IE7 ()

is_lt_IE6 ()
is_lt_IE7 ()
is_lt_IE8 ()

is_firefox ()
is_safari ()

EXAMPLE:

if (is_IE()) :  DO SOMETHING ; else :  DO OTHER STUFF; endif; 

*/


function php_browser_info(){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	
	$x = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	$browscap = $x . '/php_browscap.ini';
	if(!is_file(realpath($browscap)))
		return array('error'=>'No browscap ini file founded.');
	$agent=$agent?$agent:$_SERVER['HTTP_USER_AGENT'];
	$yu=array();
	$q_s=array("#\.#","#\*#","#\?#");
	$q_r=array("\.",".*",".?");
	
	if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        $brows = parse_ini_file(realpath($browscap), true, INI_SCANNER_RAW);
	}else{
		$brows = parse_ini_file(realpath($browscap),true);
	}

	foreach($brows as $k=>$t){
	  if(fnmatch($k,$agent)){
	  $yu['browser_name_pattern']=$k;
	  $pat=preg_replace($q_s,$q_r,$k);
	  $yu['browser_name_regex']=strtolower("^$pat$");
	    foreach($brows as $g=>$r){
	      if($t['Parent']==$g){
	        foreach($brows as $a=>$b){
	          if($r['Parent']==$a){
	            $yu=array_merge($yu,$b,$r,$t);
	            foreach($yu as $d=>$z){
	              $l=strtolower($d);
	              $hu[$l]=$z;
	            }
	          }
	        }
	      }
	    }
	    break;
	  }
	}
	return $hu;
}

function is_mobile (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['ismobiledevice']) && $browserInfo['ismobiledevice']==1)
		return true;
	return false;	
}

function is_iphone (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='iPhone')
		return true;
	return false;	
}


function is_IE (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE')
		return true;
	return false;	
}

function is_IE6 (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE' && $browserInfo['majorver'] == '6')
		return true;
	return false;	
}

function is_IE7 (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE' && $browserInfo['majorver'] == '7')
		return true;
	return false;	
}

function is_lt_IE6 (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE' && (int)$browserInfo['majorver'] < 6)
		return true;
	return false;	
}

function is_lt_IE7 (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE' && (int)$browserInfo['majorver'] < 7)
		return true;
	return false;	
}

function is_lt_IE8 (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='IE' && (int)$browserInfo['majorver'] < 8)
		return true;
	return false;	
}

function is_firefox (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='Firefox')
		return true;
	return false;	
}

function is_safari (){
	$browserInfo = php_browser_info();
	if(isset($browserInfo['browser']) && $browserInfo['browser']=='Safari')
		return true;
	return false;	
}

?>