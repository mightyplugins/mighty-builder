<?php

if (!function_exists('mb_get_image_id_by_url')) {

	function mb_get_image_id_by_url($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));

		if (!empty($attachment)) {
			return $attachment[0]; 
		}

		return false; 
	}
}