<?php
/**
 * Plugin Name: Mighty Builder
 * Plugin URI:  http://mightyplugins.com/
 * Description: Mighty Builder is best page builder for WordPress 
 * Author:      Mighty Plugins
 * Author URI:  http://mightyplugins.com/
 * Version:     1.0.0
 * Text Domain: ct-pagebuilder
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

// Load Framework
require_once MP_PB_PATH .'/framework/cantoframework.php';


if ( ! class_exists('MP_Page_Builder') ) {

	class MP_Page_Builder
	{
		private static $sc_array = array();

		/**
		 * @var         CTFMB $instance The one true CTFMB
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true CTFMB
		 */
		public static function instance() {
			if( !self::$instance ) {
				self::$instance = new MP_Page_Builder();
				self::$instance->includes();
				self::$instance->hooks();
				
				if(class_exists('MP_PB_Addon')){
					$opt_addon = new MP_PB_Addon();
				}

			}

			return self::$instance;
		}

		private function includes() {
			require_once MP_PB_PATH .'/inc/functions.php';
			require_once MP_PB_PATH .'/inc/ctfpb.elements.class.php';
			require_once MP_PB_PATH .'/inc/ctfpb.addon.class.php';
			require_once MP_PB_PATH .'/shortcodes/shortcodes.php';
		}

		private function hooks() {
			add_action( 'admin_footer', array($this,'print_elements_as_json'), 10 );

			add_action( 'wp_enqueue_scripts', array($this,'front_end_assets') );

			add_filter( 'the_content', array($this,'shortcode_empty_paragraph_fix') );
		}


		public function print_elements_as_json(){
			?>
			<script type="text/javascript">
				window.mb_elements_data = <?php echo json_encode(CTPB_Element::$_elements); ?>;
			</script>
			<?php
		}


		public function front_end_assets()
		{
			wp_enqueue_style('bootstrap', MP_PB_URL.'assets/bootstrap/css/bootstrap.min.css'  );
			wp_enqueue_style('font-awesome', MP_PB_URL.'assets/font-awesome/css/font-awesome.min.css'  );
			wp_enqueue_style('pagebuilder', MP_PB_URL.'assets/css/pagebuilder.css'  );


			wp_enqueue_script( 'bootstrap', MP_PB_URL.'assets/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'counterup', MP_PB_URL.'assets/countup/jquery.counterup.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'waypoints', MP_PB_URL.'assets/js/waypoints.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'pagebuilder', MP_PB_URL.'assets/js/pagebuilder.js', array('jquery', 'waypoints', 'counterup', 'bootstrap'), '', true );
		}


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

function CTF_Page_Builder_Addon_Register() {
	if( class_exists( 'CTF_Init' ) ) {
		return MP_Page_Builder::instance();
	}
}
add_action( 'plugins_loaded', 'CTF_Page_Builder_Addon_Register' );