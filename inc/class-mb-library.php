<?php 




/**
* Mighty Builder Library
*/
class MB_Library
{
	
	function __construct()
	{
		add_action( 'wp_ajax_mb_add_to_lib', array($this, 'add_to_library') );
		add_action( 'wp_ajax_mb_remove_from_lib', array($this, 'remove_from_lib') );
	}

	public function add_to_library()
	{
		$lib_items = get_option( 'mb_lib_items', array() );

		$data = $_POST['data'];

		$id = $data['id'];

		$lib_items[$id] = $data;

		echo update_option( 'mb_lib_items', $lib_items );

		die();
	}

	public function remove_from_lib()
	{
		$lib_items = get_option( 'mb_lib_items', array() );

		$id = $_POST['id'];

		if (!empty($id) && isset($lib_items[$id])) {
			unset($lib_items[$id]);

			echo update_option( 'mb_lib_items', $lib_items );
		} else {
			echo 0;
		}

		die();
	}

	public static function get_libs_items()
	{
		$mb_lib_items = get_option( 'mb_lib_items', array() );

		$mb_lib_items = apply_filters( 'mb_lib_items_get', $mb_lib_items );

		return $mb_lib_items;
	}

	
}