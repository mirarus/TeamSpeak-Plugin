<?php

/**
 *
 * @author Name: Mirarus - Ali Güçlü
 * @author Mail: aliguclutr@gmail.com
 * @author WebSite: https://mirarus.com/
*/

if (!function_exists('formatBytes')) {
	function formatBytes($size, $precision = 2) {
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');   
		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}

if (!function_exists('conv_traffic')) {
	function conv_traffic($bytes) {
		if ($bytes < 1024) {
			$ret = $bytes . " B";
		} elseif($bytes < 1048576) {
			$ret = round(($bytes / 1024), 2) . " Kb";
		} elseif($bytes < 1073741824) {
			$ret = round(($bytes / 1048576), 2) . " Mb";
		} elseif($bytes < 1099511627776) {
			$ret = round(($bytes / 1073741824), 2) . " Gb";
		}
		return $ret;
	}
}