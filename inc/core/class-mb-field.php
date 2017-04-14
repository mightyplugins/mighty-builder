<?php

if ( ! class_exists( 'MB_Field' ) ) :

/**
 * Class for all type of input fileds used for Option
 */
class MB_Field
{

  /**
   * Private properties for assign value for input type
   */
  private $type = 'text';


  /**
   * construct method for init class
   */
  function __construct($type)
  {
    $this->type = $type;
  }

  /**
   * Put Underscore.js template input file based on field type
   */
  public function js_template()
  {
    $inputPath = apply_filters('mb_'.$this->type.'_input_murkup_path', MP_PB_PATH.'/inc/core/fields/'.$this->type.'.php');
    $this->js_template_render($inputPath);
  }

  /**
   *  Underscore.js template container
   */
  public function js_template_render( $inputPath )
  {
    ?>
    <div class="mb-cc-container mb-input-<?php echo esc_attr( $this->type ); ?> clearfix" id="mb-opt-{{ data.id }}">
      <div class="mb-title-container">
        <# if ( data.label ) { #>
          <span class="mb-control-title" data-mb-tooltip="{{ data.toltip }}">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.subtitle ) { #>
          <span class="mb-customize-control-subtitle">{{{ data.subtitle }}}</span>
        <# } #>
      </div>
        <div class="mb-input-field-container">
          <?php include $inputPath; ?>
          <# if ( data.description ) { #>
            <p class="mb-customize-control-description">{{{ data.description }}}</p>
          <# } #>
        </div>
        
    </div>
    <?php
  }


  public function print_js_template()
  {
    ?>
    <script type="text/html" id="tmpl-mb-field-<?php echo $this->type; ?>">
      <?php $this->js_template(); ?>
    </script>
    <?php
  }
}

endif; // End if class_exists check