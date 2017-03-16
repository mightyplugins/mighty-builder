<?php

/**
* s
*/
class MB_CSS_Generator
{
	private $_content;

	private $_css_array = array();

	private $all_css;
	
	function __construct($content)
	{
		$this->_content = $content;

		$this->do_shortcode($content);

		$this->all_css = $this->array_to_css();
	}

	public function get_css()
	{
		return $this->all_css;
	}

	private function do_shortcode( $content )
	{
		global $shortcode_tags;

		if ( false === strpos( $content, '[' ) ) {
			return false;
		}

		if (empty($shortcode_tags) || !is_array($shortcode_tags)){
			return false;
		}

		preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );

		$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

		if ( empty( $tagnames ) ) {
			return false;
		}

		$content = do_shortcodes_in_html_tags( $content, false, $tagnames );

		$pattern = get_shortcode_regex( $tagnames );

		preg_replace_callback( "/$pattern/", array($this, 'do_shortcode_tag'), $content );
	}

	public function do_shortcode_tag( $m ) {
		global $shortcode_tags;
	 
		// allow [[foo]] syntax for escaping a tag
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr($m[0], 1, -1);
		}
	 
		$tag = $m[2];
		$attr = $this->shortcode_parse_atts( $m[3] );


		if (is_array($attr)) {
			$attr['_print_css'] = true;
		} else {
			$attr = array();

			$attr['_print_css'] = true;
		}
	 
		if ( ! is_callable( $shortcode_tags[ $tag ] ) ) {
			/* translators: %s: shortcode tag */
			$message = sprintf( __( 'Attempting to parse a shortcode without a valid callback: %s' ), $tag );
			_doing_it_wrong( __FUNCTION__, $message, '4.3.0' );
			return $m[0];
		}
	 

	 
		$content = isset( $m[5] ) ? $m[5] : false;
	 
		$output = call_user_func( $shortcode_tags[ $tag ], $attr, null, $tag );

		if ($content) {
			$this->do_shortcode($content);
		}
		
		if (is_array($output)) {
			$this->_css_array[$output['class']] = $output['css'];
			return false;
		} else {
			return false;
		}
	}

	public function shortcode_parse_atts($text) {
	    $atts = array();
	    $pattern = get_shortcode_atts_regex();
	    $text = stripslashes($text);
	    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
	    if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
	        foreach ($match as $m) {
	            if (!empty($m[1]))
	                $atts[strtolower($m[1])] = stripcslashes($m[2]);
	            elseif (!empty($m[3]))
	                $atts[strtolower($m[3])] = stripcslashes($m[4]);
	            elseif (!empty($m[5]))
	                $atts[strtolower($m[5])] = stripcslashes($m[6]);
	            elseif (isset($m[7]) && strlen($m[7]))
	                $atts[] = stripcslashes($m[7]);
	            elseif (isset($m[8]))
	                $atts[] = stripcslashes($m[8]);
	        }
	 
	        // Reject any unclosed HTML elements
	        foreach( $atts as &$value ) {
	            if ( false !== strpos( $value, '<' ) ) {
	                if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
	                    $value = '';
	                }
	            }
	        }
	    } else {
	        $atts = ltrim($text);
	    }
	    return $atts;
	}


	public function array_to_css()
	{
		$main_css = '';
		$md_css = '';
		$sm_css = '';
		$xs_css = '';
		if (!empty($this->_css_array)) {
			foreach ($this->_css_array as $selector => $css) {
				if (isset($css['main']) && !empty($css['main'])) {
					$main_css .= '.'.$selector.'{'.$css['main'].'}';
				}

				if (isset($css['md']) && !empty($css['md'])) {
					$md_css .= '.'.$selector.'{'.$css['md'].'}';
				}

				if (isset($css['sm']) && !empty($css['sm'])) {
					$sm_css .= '.'.$selector.'{'.$css['sm'].'}';
				}

				if (isset($css['xs']) && !empty($css['xs'])) {
					$xs_css .= '.'.$selector.'{'.$css['xs'].'}';
				}
			}
		}

		$output = '';

		$output .= $main_css;

		if (!empty($md_css)) {
			$output .= '@media (min-width: 992px) and (max-width: 1199px) {'.$md_css.'}';
		}

		if (!empty($sm_css)) {
			$output .= '@media (min-width: 768px) and (max-width: 991px ){'.$sm_css.'}';
		}

		if (!empty($xs_css)) {
			$output .= '@media (max-width: 767px) {'.$xs_css.'}';
		}


		return $output;
	}
}