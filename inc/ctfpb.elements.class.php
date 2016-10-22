<?php

/**
* 
*/
class CTPB_Element
{
	public static $_elements = array();

	function __construct(){}


	public static function add($shortcode_data){
		self::$_elements[$shortcode_data['code']] = $shortcode_data;
	}
}