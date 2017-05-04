<?php
/**
 * Plugin Name: Mighty Builder
 * Plugin URI:  https://mightyplugins.com/
 * Description: Mighty Builder is best page builder for WordPress 
 * Author:      Mighty Plugins
 * Author URI:  https://mightyplugins.com/
 * Version:     1.1.0
 * Text Domain: mighty-builder
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'MP_PB_PATH' ) ){
	define('MP_PB_PATH', plugin_dir_path( __FILE__ ));
}
if ( ! defined( 'MP_PB_URL' ) ){
	define('MP_PB_URL', plugin_dir_url( __FILE__ ));
}

/*if ( ! defined( 'CTF_PATH' ) ){
	define('CTF_PATH',  plugin_dir_path( __FILE__ ).'/framework/');
}
if ( ! defined( 'CTF_URL' ) ){
	define('CTF_URL', plugin_dir_url( __FILE__ ).'framework/');
}

// Load Framework
require_once MP_PB_PATH .'/framework/cantoframework.php';*/

if ( ! defined( 'MP_PB_VER' ) ){
	define('MP_PB_VER', '1.1');
}


if ( ! class_exists('MP_Page_Builder') ) {

	/**
	 * MP_Page_Builder is main class for Mighty Builder.
	 *
	 * @since 1.0.0
	 */
	class MP_Page_Builder
	{
		private static $sc_array = array();

		/**
		 * MP_Page_Builder $instance The one true MP_Page_Builder
		 *
		 * @access      private
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true MP_Page_Builder
		 */
		public static function instance() {
			if( !self::$instance ) {

				self::$instance = new MP_Page_Builder();
				self::$instance->includes();
				self::$instance->hooks();

				if(class_exists('MB_Core')){
					$mb_core = new MB_Core();
				}

				if(class_exists('MB_Library')){
					$mb_libs = new MB_Library();
				}

			}

			return self::$instance;
		}

		/**
		 * Include all class and functions.
		 * 
		 * @since		1.0.0
		 */
		private function includes() {
			// Load Core Files
			require_once MP_PB_PATH .'/inc/core/class-mb-core.php';
			require_once MP_PB_PATH .'/inc/core/class-mb-data.php';
			require_once MP_PB_PATH .'/inc/core/class-mb-field.php';


			require_once MP_PB_PATH .'/inc/functions.php';
			require_once MP_PB_PATH .'/inc/class-mb-library.php';
			require_once MP_PB_PATH .'/inc/class-mb-element.php';
			require_once MP_PB_PATH .'/inc/class-mb-css-generator.php';
			require_once MP_PB_PATH .'/shortcodes/shortcodes.php';
		}

		/**
		 * This method will be init all hooks need for Mighty Builder.
		 * 
		 * @since		1.0.0
		 */
		private function hooks() {

			add_action( 'wp_enqueue_scripts', array($this,'front_end_assets') );

			add_filter( 'the_content', array($this,'shortcode_empty_paragraph_fix') );

			if (!class_exists('WP_Font_Manager')) {
				add_filter( 'wp_footer', array($this,'load_google_font') );
			}

			add_filter( 'init', array($this,'load_textdomain') );

			add_action( 'save_post', array($this,'generator_css'), 10, 2 );

			add_action( 'admin_menu', array($this, 'register_pages') );
		}

		public function register_pages()
		{
			$menu = add_menu_page(__('Mighty Builder', 'mighty-builder'), __('Mighty Builder', 'mighty-builder'), 'manage_options', 'mb-about', array($this, 'mighty_builder_about_view_cb'), $this->get_svg_icon(), 99);

			add_action( 'admin_print_styles-' . $menu, array($this, 'mighty_builder_about_css') );
			add_action( 'admin_print_scripts-' . $menu, array($this, 'mighty_builder_about_js') );
		}

		public function get_svg_icon(){
			$svg = '<svg width="100%" height="100%" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" ><defs><style type="text/css"><![CDATA[ #iconpath{fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;} ]]></style></defs><path d="M2.417,11.908l0,0c0,0.617 -0.501,1.117 -1.117,1.117c-0.616,0 -1.116,-0.5 -1.116,-1.117l0,0l0,0c0,0 0,-3.048 0,-5.583c0,-1.85 1.499,-3.35 3.349,-3.35c0,0 0,0 0,0c0.827,0 1.621,0.305 2.234,0.853c0.592,-0.53 1.375,-0.853 2.233,-0.853c0,0 0,0 0,0c0.888,0 1.741,0.353 2.369,0.981c0.628,0.628 0.981,1.48 0.981,2.369l0,0.191c0.349,-0.124 0.725,-0.191 1.117,-0.191c1.848,0 3.349,1.501 3.349,3.35c0,1.849 -1.501,3.35 -3.349,3.35c-1.849,0 -3.35,-1.501 -3.35,-3.35c0,0 0,-3.35 0,-3.35c0,-0.296 -0.118,-0.58 -0.327,-0.79c-0.21,-0.209 -0.494,-0.327 -0.79,-0.327c0,0 0,0 0,0c-0.296,0 -0.58,0.118 -0.79,0.327c-0.209,0.21 -0.327,0.494 -0.327,0.79l0,2.233l0,0c0,0.616 -0.5,1.116 -1.116,1.116c-0.616,0 -1.116,-0.5 -1.117,-1.116l0,0l0,-0.001c0,0 0,-2.232 0,-2.232c0,-0.296 -0.118,-0.58 -0.327,-0.79c-0.209,-0.209 -0.493,-0.327 -0.79,-0.327c0,0 0,0 0,0c-0.296,0 -0.58,0.118 -0.789,0.327c-0.21,0.21 -0.327,0.494 -0.327,0.79l0,5.583l0,0Zm10.05,-3.35c0.616,0 1.116,0.501 1.116,1.117c0,0.616 -0.5,1.116 -1.116,1.116c-0.617,0 -1.117,-0.5 -1.117,-1.116c0,-0.616 0.5,-1.117 1.117,-1.117Z" id="iconpath" style="fill:#eee"/></svg>';

			return 'data:image/svg+xml;base64,' . base64_encode( $svg );
		}

		public function mighty_builder_about_view_cb()
		{
			include MP_PB_PATH .'/about.php';
		}

		public function mighty_builder_about_css()
		{
			wp_enqueue_style('mb-admin', MP_PB_URL.'assets/css/admin.css'  );
		}

		public function mighty_builder_about_js()
		{
			wp_enqueue_script( 'mb-admin', MP_PB_URL.'assets/js/admin.js', array('jquery'), '', true );
		}

		public function load_google_font()
		{
			global $mb_google_fonts;

			$fonts = array();

			if (is_array($mb_google_fonts) && !empty($mb_google_fonts)) {
				foreach ($mb_google_fonts as $font => $font_weights) {
					$_font = str_replace(' ', '+', $font);

					if (!empty($font_weights)) {
						$_font .= ':'.implode(',', $font_weights);
					}
					$fonts[] = $_font;
				}
			}

			if (!empty($fonts)) {
				wp_enqueue_style( 'mb_google_fonts', '//fonts.googleapis.com/css?family=' . implode('|', $fonts) );
			}
		}


		public function load_textdomain(){
			load_plugin_textdomain( 'mighty-builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Load all front-end assets (CSS and JS)
		 * 
		 * @since		1.0.0
		 */
		public function front_end_assets()
		{
			wp_enqueue_style('bootstrap', MP_PB_URL.'assets/vendor/bootstrap/css/bootstrap.min.css'  );
			wp_enqueue_style('font-awesome', MP_PB_URL.'assets/vendor/font-awesome/css/font-awesome.min.css'  );
			wp_enqueue_style('mighty-builder', MP_PB_URL.'assets/css/mighty-builder.css'  );

			$page_css = get_post_meta( get_the_id(), 'mb_pb_page_css', true );

			if ($page_css) {
				wp_add_inline_style( 'mighty-builder', $page_css );
			}


			wp_enqueue_script( 'bootstrap', MP_PB_URL.'assets/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'counterup', MP_PB_URL.'assets/vendor/countup/jquery.counterup.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'waypoints', MP_PB_URL.'assets/js/waypoints.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'mighty-builder', MP_PB_URL.'assets/js/mighty-builder.js', array('jquery', 'waypoints', 'counterup', 'bootstrap'), '', true );
		}

		public function generator_css( $post_id, $post )
		{
			$post_type = get_post_type_object( $post->post_type );

			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
				return $post_id;
			}

			$page_css_class  = new MB_CSS_Generator($_POST['content']);
			$page_css  = $page_css_class->get_css();

			$meta_key = 'mb_pb_page_css';

			$meta_value = get_post_meta( $post_id, $meta_key, true );

			if ( !empty($page_css) && empty($meta_value) ){
				add_post_meta( $post_id, $meta_key, $page_css, true );
			} elseif ( !empty($page_css) && $page_css != $meta_value ) {
				update_post_meta( $post_id, $meta_key, $page_css );
			} elseif ( empty($page_css) && !empty($meta_value) ) {
				delete_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		/**
		 * Fix auto p tag from shrtcode to avoid empety space in page
		 *
		 * @since		1.0.0
		 * 
		 * @param  (string) $content content to filter
		 * @return (string)          filtered content
		 */
		public function shortcode_empty_paragraph_fix( $content ) {

			$array = array (
				'<p>[' => '[',
				']</p>' => ']',
				']<br />' => ']'
			);

			$content = strtr( $content, $array );

			return $content;
		}
	}

}

/**
 * Add Shortcode map to builder
 *
 * @param  (array) $map shortcode map array
 * 
 * @since		1.0.0
 */
function mb_add_map( $map )
{
	$map = apply_filters( $map['code'].'_map', $map );

	if (class_exists('MB_Element')) {
		MB_Element::add($map);
	}
	
}

/**
 * Init Mighty Builder
 *
 * @since		1.0.0
 */
function mp_page_builder_init() {
	return MP_Page_Builder::instance();
}
add_action( 'init', 'mp_page_builder_init' );