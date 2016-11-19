<?php
/**
 * Plugin Name: CT PageBuilder
 * Plugin URI:  https://www.cantothemes.com/
 * Description: CT PageBuilder is best page builder for WordPress 
 * Author:      CantoThemes
 * Author URI:  https://www.cantothemes.com/
 * Version:     1.0.0
 * Text Domain: ct-pagebuilder
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'CTPB_PATH' ) ){
	define('CTPB_PATH', plugin_dir_path( __FILE__ ));
}
if ( ! defined( 'CTPB_URL' ) ){
	define('CTPB_URL', plugin_dir_url( __FILE__ ));
}

if ( ! defined( 'CTF_PATH' ) ){
	define('CTF_PATH',  plugin_dir_path( __FILE__ ).'/framework/');
}
if ( ! defined( 'CTF_URL' ) ){
	define('CTF_URL', plugin_dir_url( __FILE__ ).'framework/');
}

// Load Framework
require_once CTPB_PATH .'/framework/cantoframework.php';


if ( ! class_exists('CT_Page_Builder') ) {

	class CT_Page_Builder
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
				self::$instance = new CT_Page_Builder();
				self::$instance->includes();
				self::$instance->hooks();
				
				if(class_exists('CTFPB_Addon')){
					$opt_addon = new CTFPB_Addon();
				}

			}

			return self::$instance;
		}

		private function includes() {
			require_once CTPB_PATH .'/inc/ctfpb.elements.class.php';
			require_once CTPB_PATH .'/inc/ctfpb.addon.class.php';
			require_once CTPB_PATH .'/shortcodes/shortcodes.php';
		}

		private function hooks() {
			add_action( 'admin_footer', array($this,'print_elements_as_json'), 10 );

			add_action( 'wp_enqueue_scripts', array($this,'front_end_assets') );

			add_filter( 'the_content', array($this,'shortcode_empty_paragraph_fix') );
		}


		public function print_elements_as_json(){
			?>
			<script type="text/javascript">
				window.ctf_pb_elements_data = <?php echo json_encode(CTPB_Element::$_elements); ?>;
			</script>
			<?php
		}


		public function front_end_assets()
		{
			wp_enqueue_style('bootstrap', CTPB_URL.'assets/bootstrap/css/bootstrap.min.css'  );
			wp_enqueue_style('font-awesome', CTPB_URL.'assets/font-awesome/css/font-awesome.min.css'  );
			wp_enqueue_style('pagebuilder', CTPB_URL.'assets/css/pagebuilder.css'  );


			wp_enqueue_script( 'bootstrap', CTPB_URL.'assets/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'counterup', CTPB_URL.'assets/countup/jquery.counterup.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'waypoints', CTPB_URL.'assets/js/waypoints.min.js', array('jquery'), '', true );
			wp_enqueue_script( 'pagebuilder', CTPB_URL.'assets/js/pagebuilder.js', array('jquery', 'waypoints', 'counterup', 'bootstrap'), '', true );
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
		return CT_Page_Builder::instance();
	}
}
add_action( 'plugins_loaded', 'CTF_Page_Builder_Addon_Register' );