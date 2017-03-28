<?php
/**
 * Plugin Name: Mighty Builder
 * Plugin URI:  https://mightyplugins.com/
 * Description: Mighty Builder is best page builder for WordPress 
 * Author:      Mighty Plugins
 * Author URI:  https://mightyplugins.com/
 * Version:     1.0.2
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

if ( ! defined( 'CTF_PATH' ) ){
	define('CTF_PATH',  plugin_dir_path( __FILE__ ).'/framework/');
}
if ( ! defined( 'CTF_URL' ) ){
	define('CTF_URL', plugin_dir_url( __FILE__ ).'framework/');
}

if ( ! defined( 'MP_PB_VER' ) ){
	define('MP_PB_VER', '1.0.2');
}

// Load Framework
require_once MP_PB_PATH .'/framework/cantoframework.php';


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
				
				if(class_exists('MB_Addon')){
					$opt_addon = new MB_Addon();
				}

			}

			if (!get_option( 'mighty_builder_ver' ) || get_option( 'mighty_builder_ver' ) < MP_PB_VER) {
				update_option( 'mighty_builder_ver', MP_PB_VER );
			}

			return self::$instance;
		}

		/**
		 * Include all class and functions.
		 * 
		 * @since		1.0.0
		 */
		private function includes() {
			require_once MP_PB_PATH .'/inc/functions.php';
			require_once MP_PB_PATH .'/inc/class-mb-element.php';
			require_once MP_PB_PATH .'/inc/class-mb-addon.php';
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

			add_filter( 'wp_footer', array($this,'load_google_font') );

			add_filter( 'init', array($this,'load_textdomain') );
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
			wp_enqueue_style('bootstrap', MP_PB_URL.'assets/bootstrap/css/bootstrap.min.css'  );
			wp_enqueue_style('font-awesome', MP_PB_URL.'assets/font-awesome/css/font-awesome.min.css'  );
			wp_enqueue_style('mighty-builder', MP_PB_URL.'assets/css/mighty-builder.css'  );


			wp_enqueue_script( 'bootstrap', MP_PB_URL.'assets/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'counterup', MP_PB_URL.'assets/countup/jquery.counterup.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'waypoints', MP_PB_URL.'assets/js/waypoints.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'mighty-builder', MP_PB_URL.'assets/js/mighty-builder.js', array('jquery', 'waypoints', 'counterup', 'bootstrap'), '', true );
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
	if( class_exists( 'CTF_Init' ) ) {
		return MP_Page_Builder::instance();
	}
}
add_action( 'init', 'mp_page_builder_init' );