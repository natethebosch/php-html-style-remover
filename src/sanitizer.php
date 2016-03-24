<?php
/**
 * @Author: Nate Bosscher (c) 2015
 * @Date:   2016-03-24 12:19:28
 * @Last Modified by:   Nate Bosscher
 * @Last Modified time: 2016-03-24 13:04:32
 */

namespace BlueGiraffeSystems;

class Sanitizer{
	public static function htmlRemoveStyle($string){
		$string = preg_replace("#style=[\"'].*?[\"']#", "", $string);
		$string = preg_replace("#<span>#", "", $string);
		$string = preg_replace("#</span>#", "", $string);
		$string = preg_replace("#<[/]{0,1}(b|i)>#", "", $string);
		$string = preg_replace("#class=[\"'].*?[\"']#", "", $string);
		$string = preg_replace("#id=[\"'].*?[\"']#", "", $string);

		return $string;
	}
}