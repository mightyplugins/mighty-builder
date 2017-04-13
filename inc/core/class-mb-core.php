<?php

if ( ! class_exists( 'MB_Core' ) ) :

/**
* Mighty Builder Core
*/
class MB_Core
{

	public $all_fields = array();
	
	function __construct(){
		$this->all_fields = MB_Data::get_all_fields_name();

		add_action('admin_enqueue_scripts', array($this, 'load_admin_js'), 99);
		add_action('admin_enqueue_scripts', array($this, 'load_admin_css'));

		add_action('admin_footer', array($this,'print_js_templates'));

		add_action( 'edit_form_after_title', array( $this, 'hook_after_title' ) );

        add_action( 'edit_form_after_editor', array( $this, 'hook_after_editor' ) );

        add_action( 'save_post',  array( $this, 'save_mb_active_meta' ), 10, 2 );
	}

	public function load_admin_js()
	{
		global $tinymce_version;
		
		wp_enqueue_script( 'editor' );
		wp_enqueue_script( 'quicktags' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-spinner' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
		wp_enqueue_script('mb-selectize', MP_PB_URL.'assets/vendor/selectize/js/standalone/selectize.min.js', array('jquery'));
		wp_enqueue_script('mb-tinymce', includes_url( 'js/tinymce' ).'/tinymce.min.js', array('jquery'), $tinymce_version, true);
		wp_enqueue_script('mb-tinymce-compat3x', includes_url( 'js/tinymce' ).'/plugins/compat3x/plugin.min.js', array('jquery'), $tinymce_version, true);
        wp_enqueue_script( 'mb-core-script', MP_PB_URL . 'assets/js/main.js', array('jquery', 'underscore'), '1.0', true );
        
        $mb_google_fonts_json = array(
			'l10n_print_after' => 'mb_google_fonts = ' . MB_Data::get_google_font_json(),
		);
		wp_localize_script('mb-core-script', 'mb_google_fonts', $mb_google_fonts_json);
		
		$mb_fa_icons_json = array(
			'l10n_print_after' => 'mb_fa_icons = ' . MB_Data::get_icons_json(),
		);
		wp_localize_script('mb-core-script', 'mb_fa_icons', $mb_fa_icons_json);
		wp_localize_script('mb-core-script', 'mb_mce_var', $this->get_editor_vars());


		wp_enqueue_script( 'mb-remodal', MP_PB_URL . 'assets/vendor/remodal/remodal.min.js', array('jquery'), '1.0', true );
        
        wp_enqueue_script( 'mb-serialize-object', MP_PB_URL . 'assets/js/jquery.serialize-object.js', array('jquery'), '1.0', true );

        wp_enqueue_script( 'mb-pagebuilder', MP_PB_URL . 'assets/js/mb-pagebuilder.js', array('jquery', 'underscore', 'mb-core-script'), '1.0', true );

        $mb_pb_args = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'lib_items' => MB_Library::get_libs_items(),
            'section_sc' => apply_filters( 'mb_pb_section_sortcode_tag', 'mb_section' ),
            'row_sc' => apply_filters( 'mb_pb_row_sortcode_tag', 'mb_row' ),
            'col_sc' => apply_filters( 'mb_pb_column_sortcode_tag', 'mb_col' ),
            'pb_enable_text' => esc_html__( 'Active Page Builder', 'mighty-builder' ),
            'pb_disable_text' => esc_html__( 'Disable Page Builder', 'mighty-builder' ),
            'pb_elements_title' => esc_html__( 'Select an Element', 'mighty-builder' ),
            'mb_confirm' => esc_html__( 'Are you sure?', 'mighty-builder' ),
            'lib_inputs' => array(
                array(
                    'id' => 'title',
                    'label' => esc_html__( 'Title', 'mighty-builder' ),
                    'subtitle' => '',
                    'type' => 'text',
                    'default' => '',
                ),
                array(
                    'id' => 'subtitle',
                    'label' => esc_html__( 'Sub-Title', 'mighty-builder' ),
                    'subtitle' => '',
                    'type' => 'text',
                    'default' => '',
                ),
                array(
                    'id' => 'type',
                    'label' => esc_html__( 'Template Type', 'mighty-builder' ),
                    'subtitle' => '',
                    'type' => 'text',
                    'default' => '',
                ),
                array(
                    'id' => 'image',
                    'label' => esc_html__( 'Image', 'mighty-builder' ),
                    'subtitle' => '',
                    'type' => 'image',
                    'default' => '',
                )
            )
        );

        wp_localize_script( 'mb-pagebuilder', 'mb_elements_data', MB_Element::$_elements );
        wp_localize_script( 'mb-pagebuilder', 'mb_pb_args', $mb_pb_args );
	}

	public function load_admin_css()
	{
    	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_style( 'editor-buttons' );
    	wp_enqueue_style( 'mb-selectize', MP_PB_URL.'assets/vendor/selectize/css/selectize.css' );
		wp_enqueue_style( 'mb-font-awesome', MP_PB_URL.'assets/vendor/font-awesome/css/font-awesome.min.css' );
        wp_enqueue_style( 'mb-core-style', MP_PB_URL.'assets/css/main.css' );

        wp_enqueue_style( 'mb-remodal', MP_PB_URL.'assets/vendor/remodal/remodal.css' );
        wp_enqueue_style( 'mb-remodal-theme', MP_PB_URL.'assets/vendor/remodal/remodal-default-theme.css' );
        wp_enqueue_style('mighty-builder-icons', MP_PB_URL.'assets/vendor/mighty-builder-icons/mighty-builder-icons.css'  );
        wp_enqueue_style('mb-pagebuilder', MP_PB_URL . 'assets/css/mb-pagebuilder.css', array(), '1.0');
	}

	function hook_after_title(){
        if ( get_post_type() == 'page' ) {

            global $post;

            $is_enable = get_post_meta( $post->ID, 'mb_pb_enabled_key', true );
            $is_enable = (int) $is_enable;
            $active_class = 'mb-pb-active';
            $btn_icon = 'check';
            $enable_button_txt = esc_html__( 'Active Page Builder', 'mighty-builder' );

            if ($is_enable) {
                $active_class = '';
                $enable_button_txt = esc_html__( 'Disable Page Builder', 'mighty-builder' );
                $btn_icon = 'times';
            }
            ?>
            <div class="mb-pb-tab-cont">
                <div class="mb-pb-switch">
                    <button class="mb-pb-switch-button" id="mb-pb-switch-button"  type="button" role="presentation"><i class="fa fa-<?php echo esc_attr($btn_icon); ?>"></i> <?php echo esc_html($enable_button_txt); ?></button>
                </div>
                <div class="mb-pb-classic-editor <?php echo esc_attr($active_class); ?>">
            <?php
        }
    }

    function hook_after_editor(){
        if ( get_post_type() == 'page' ) {

            global $post;

            $is_enable = get_post_meta( $post->ID, 'mb_pb_enabled_key', true );
            $is_enable = (int) $is_enable;
            $active_class = '';
            $active_input = 0;


            if ($is_enable) {
                $active_class = 'mb-pb-active';
                $active_input = 1;
            }

            ?>
                </div>
                <div class="mb-pb-container <?php echo esc_attr($active_class); ?>" id="mb-pb-container">
                    <div class="mb-pb-header">
                        <h3><i class="fa fa-cogs" aria-hidden="true"></i> <span>Mighty Builder</span></h3>
                        <button type="button" role="presentation" class="mb-pb-library" id="mb-pb-library"><i class="fa fa-columns"></i> Templates</button>
                        <button type="button" role="presentation" class="mb-pb-fullscreen" id="mb-pb-fullscreen"><i class="mce-ico mce-i-dfw"></i></button>
                    </div>
                    <div class="mb-pb-elem-container">
                    </div>
                    <a class="mb-pb-add-sec" href="#" data-pb-shortcode="mb_section" data-pb-type="layout">Add Section</a>
                </div>
                <input type="hidden" name="mb_pb_enabled" class="mb-status-input" value="<?php echo esc_attr($active_input); ?>">
            </div>
            <?php
        }
    }


    function save_mb_active_meta($post_id, $post)
    {
        $post_type = get_post_type_object( $post->post_type );

        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
            return $post_id;
        }

        $new_meta_value = ( isset( $_POST['mb_pb_enabled'] ) ? sanitize_html_class( $_POST['mb_pb_enabled'] ) : '' );

        $meta_key = 'mb_pb_enabled_key';

        $meta_value = get_post_meta( $post_id, $meta_key, true );

        if ( !empty($new_meta_value) && empty($meta_value) ){
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );
        } elseif ( !empty($new_meta_value) && $new_meta_value != $meta_value ) {
            update_post_meta( $post_id, $meta_key, $new_meta_value );
        } elseif ( empty($new_meta_value) && !empty($meta_value) ) {
            delete_post_meta( $post_id, $meta_key, $meta_value );
        }
    }

	public function print_js_templates(){
	    $fields = $this->all_fields;
	    
	    foreach($fields as $field){
	    	$filed_obj = new MB_Field($field);
	    	
	    	$filed_obj->print_js_template();
	    }

	    ?>
        <div class="remodal mb-pb-remodal mb-pb-remodal-form-off" data-remodal-id="modal-pb" role="dialog" aria-labelledby="modal-pb-title" aria-describedby="modal-pb-subtitle" data-remodal-options="hashTracking: false">
            
            <div class="modal-pb-header">
                <h2 id="modal-pb-title"><?php esc_html_e('Select an Element', 'mighty-builder'); ?></h2>
                <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            </div>
            <div id="modal-pb-container">
                <div class="mpb-elements-list clearfix"></div>
                <form class="mpb-inputs mb-fc"></form>
            </div>
            <br>
            <div class="mb-fc modal-pb-buttons">
                <button data-remodal-action="cancel" class="mb-pb-modal-cancel-btn button button-ctpb-cancel"><?php esc_html_e('Cancel', 'mighty-builder'); ?></button>
                <button class="button button-ctpb" id="add_ctpb_sc_to_item"><?php esc_html_e('Save Changes', 'mighty-builder'); ?></button>
            </div>
        </div>
        <div class="remodal mb-pb-lib-remodal" data-remodal-id="modal-pb-lib" role="dialog" aria-labelledby="modal-pb-lib-title" aria-describedby="modal-pb-lib-subtitle" data-remodal-options="hashTracking: false">
            
            <div class="modal-pb-lib-header">
                <h2 id="modal-pb-lib-title"><?php esc_html_e('Template Library', 'mighty-builder'); ?></h2>
                <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            </div>
            <div class="mb-lib-new-wrap">
                <form class="mpb-lib-inputs mb-fc">
                    <h3 class="mb-lib-inputs-title">Save this page as a template</h3>
                    <div class="mpb-lib-inputs-inner"></div>
                    <div class="mp-pb-lib-buttons clearfix">
                        <button type="button" class="mb-pb-lib-cancel-btn button button-ctpb-cancel"><?php esc_html_e('Cancel', 'mighty-builder'); ?></button>
                        <button type="button" class="button button-ctpb" id="add-new-tmpl-lib"><?php esc_html_e('Add Template', 'mighty-builder'); ?></button>
                        <span class="spinner mb-lib-new-spin"></span>
                    </div>
                </form>
            </div>
            <div class="mb-lib-topbar clearfix">
                <div class="mb-lib-filter-view"></div>
                <button class="mpb-lib-new-item"><i class="fa fa-plus"></i> Add Template</button>
            </div>
            
            
            <div id="modal-pb-lib-container">
                <div class="mpb-lib-list clearfix"></div>
            </div>
        </div>
        <script type="text/html" id="tmpl-layout-section">
            <div class="mb-pb-section-layout">
                <textarea class="mb-pb-sc-code mb-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="mb-pb-layout-edit clearfix">
                    <ul>
                        <li><a href="#" class="mb-pb-drag"><i class="fa fa-arrows"></i> <?php esc_html_e('Section', 'mighty-builder'); ?></a></li>
                        <li><a href="#" class="mb-pb-add-row"><i class="fa fa-plus"></i> <?php esc_html_e('Add Row', 'mighty-builder'); ?></a></li>
                        <li><a href="#" class="mb-pb-edit-section"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="mb-pb-copy-section"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="mb-pb-delete" data-item="section"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                <div class="mb-pb-section-container">
                </div>
                
                <textarea class="mb-pb-sc-code mb-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-layout-row">
            <div class="mb-pb-row-layout">
                <textarea class="mb-pb-sc-code mb-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="mb-pb-layout-edit clearfix">
                    <ul>
                        <li><a href="#" class="mb-pb-drag"><i class="fa fa-arrows"></i> <?php esc_html_e('Row', 'mighty-builder'); ?></a></li>
                        <li><a href="#" class="mb-pb-add-col"><i class="fa fa-plus"></i> <?php esc_html_e('Add Column', 'mighty-builder'); ?></a></li>
                        <li><a href="#" class="mb-pb-edit-row"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="mb-pb-copy-row"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="mb-pb-delete" data-item="row"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                <div class="mb-pb-row-container clearfix">
                </div>
                
                <textarea class="mb-pb-sc-code mb-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-layout-col">
            <div class="mb-pb-col-layout mb_pb_col-{{ data.col_size }}" data-col-size="{{ data.col_size }}">
                <textarea class="mb-pb-sc-code mb-pb-sc-start">{{{ data.sc_start }}}</textarea>
                <div class="mb-pb-col-inner">
                    
                    <div class="mb-pb-layout-edit clearfix">
                        <ul class="mb-pb-col-edit-ctrl">
                            <li><a href="#" class="mb-pb-drag"><i class="fa fa-arrows"></i></a></li>
                            <li><a href="#" class="mb-pb-edit-column"><i class="fa fa-pencil"></i></a></li>
                            <li><a href="#" class="mb-pb-delete" data-item="column"><i class="fa fa-trash-o"></i></a></li>
                            <li><a href="#" class="mb-pb-drag"><i class="fa fa-ellipsis-h"></i></a></li>
                        </ul>
                        <ul class="mb-pb-col-size-ctrl">
                            <li><a href="#" class="mb-pb-col-sz-minus"><i class="fa fa-minus"></i></a></li>
                            <li><span class="mb-pb-col-sz-txt">{{ data.col_size }}/12</span></li>
                            <li><a href="#" class="mb-pb-col-sz-plus"><i class="fa fa-plus"></i></a></li>
                        </ul>
                    </div>
                    <div class="mb-pb-col-container"></div>
                    <a href="#" class="mb-pb-add-element"><i class="fa fa-plus"></i><span> <?php esc_html_e('Add Element', 'mighty-builder'); ?></span></a>
                    
                    
                </div>
                <textarea class="mb-pb-sc-code mb-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-ctpb-element"><div class="mb-pb-element-item" data-code="{{ data.code }}">
                <textarea class="mb-pb-sc-code mb-pb-sc-start">{{{ data.sc_start }}}</textarea>
                <div class="mb-pb-element-edit">
                    <ul>
                        <li><a href="#" class="mb-pb-drag"><i class="fa fa-arrows"></i></a></li>
                        <li><a href="#" class="mb-pb-item-edit-btn"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="mb-pb-item-copy-btn"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="mb-pb-item-delete" data-item="element"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                <#
                    var iconStyle = '';


                    if(typeof data.color !== 'undefined'){
                        iconStyle = 'style="background-color: '+data.color+';"';
                    }
                #>
                <div class="mb-pb-item-container-ui">
                    <div class="mb-pb-elem-icon"  {{{ iconStyle }}}>
                        <i class="{{ data.icon }}"></i>
                    </div>
                    <h3 class="mb-pb-elem-item-name">{{ data.name }}</h3>
                    <div class="mb-pb-elem-label">{{{ data.admin_label }}}</div>
                </div>
                    
        </div></script>
        <script type="text/html" id="tmpl-ctpb-element-container"><div class="mb-pb-element-container" data-code="{{ data.code }}">
                
                <textarea class="mb-pb-sc-code mb-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="mb-pb-element-edit">
                    <ul>
                        <li><a href="#" class="mb-pb-drag"><i class="fa fa-arrows"></i></a></li>
                        <li><a href="#" class="mb-pb-item-edit-btn"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="mb-pb-item-copy-btn"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="mb-pb-item-delete" data-item="element"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>

                <#
                    var iconStyle = '';


                    if(typeof data.color !== 'undefined'){
                        iconStyle = 'style="background-color: '+data.color+';"';
                    }
                #>

                <div class="mb-pb-container-container-ui">
                    <div class="mb-pb-elem-icon"  {{{ iconStyle }}}>
                        <i class="{{ data.icon }}"></i>
                    </div>
                    <h3 class="mb-pb-elem-item-name">{{ data.name }}</h3>
                </div>
                <div class="mb-pb-elem-cont-container"></div>
                <a href="#" class="mb-pb-add-sub-element"><i class="fa fa-plus"></i><span> <?php  echo sprintf( esc_html__('Add %s Item', 'mighty-builder' ), '{{ data.name }}'); ?></span></a>

                <textarea class="mb-pb-sc-code mb-pb-sc-end">{{{ data.sc_end }}}</textarea>
                    
        </div></script>
        <script type="text/html" id="tmpl-ctpb-modal-item">
            <div class="mb-pb-modal-item" data-code="{{ data.code }}">
                <#
                    var iconStyle = '';


                    if(typeof data.color !== 'undefined'){
                        iconStyle = 'style="background-color: '+data.color+';"';
                    }
                #>
                <div class="mb-pb-mi-icon" {{{ iconStyle }}}>
                    <i class="{{ data.icon }}"></i>
                </div>
                <div class="mb-pb-modal-item-label">
                    <strong>{{ data.name }}</strong>
                    <p>{{ data.desc }}</p>
                </div>
            </div>
        </script>
        <script type="text/html" id="tmpl-ctpb-responsive-input">
            <div class="mb-pb-responsive-input">
                <ul class="mb-responsive-tab-nav">
                    <li data-id="{{ data.id }}_lg" class="active"><i class="fa fa-desktop"></i></li>
                    <# if(typeof data.md !== 'undefined' && data.md == true){ #>
                        <li data-id="{{ data.id }}_md"><i class="fa fa-laptop"></i></li>
                    <# } #>
                    <# if(typeof data.sm !== 'undefined' && data.sm == true){ #>
                        <li data-id="{{ data.id }}_sm"><i class="fa fa-tablet"></i></li>
                    <# } #>
                    <# if(typeof data.xs !== 'undefined' && data.xs == true){ #>
                        <li data-id="{{ data.id }}_xs"><i class="fa fa-mobile"></i></li>
                    <# } #>
                </ul>

                <div id="{{ data.id }}_lg" class="mb-responsive-panel active"></div>
                <# if(typeof data.md !== 'undefined' && data.md == true){ #>
                    <div id="{{ data.id }}_md" class="mb-responsive-panel"></div>
                <# } #>
                <# if(typeof data.sm !== 'undefined' && data.sm == true){ #>
                    <div id="{{ data.id }}_sm" class="mb-responsive-panel"></div>
                <# } #>
                <# if(typeof data.xs !== 'undefined' && data.xs == true){ #>
                    <div id="{{ data.id }}_xs" class="mb-responsive-panel"></div>
                <# } #>
            </div>
        </script>
        <script type="text/html" id="tmpl-ctpb-input-tabs">
            <#
                var t_i = 0;
                var tp_i = 0;
            #>
            <div class="mb-input-tabs">
                <ul class="mb-input-tabs-nav clearfix">
                    <# _.each(data.tabs, function(tabLabel, tabId){ #>
                        <# if(t_i === 0){ #>
                            <li data-id="{{ tabId }}" class="active">{{ tabLabel }}</li>
                        <# } else { #>
                            <li data-id="{{ tabId }}">{{ tabLabel }}</li>
                        <# } #>
                    <# t_i++;  } ); #>
                </ul>
                <div class="tabs-container">
                    <# _.each(data.tabs, function(tabLabel, tabId){ #>
                        <# if(tp_i === 0){ #>
                            <div id="{{ tabId }}" class="mb-input-tab active"></div>
                        <# } else { #>
                            <div id="{{ tabId }}" class="mb-input-tab"></div>
                        <# } #>
                        
                    <# tp_i++; } ); #>
                </div>
                
            </div>
        </script>
        <script type="text/html" id="tmpl-mb-lib-item">
            <#
                var cls = '';

                if(typeof data.type !== 'undefined'){
                    cls = data.type.join(" ");
                }
            #>
            <div class="mb-lib-item-wrap {{ data.id }} {{ cls }}">
                <div class="mb-lib-item">
                    <div class="mb-lib-item-img">
                        <img src="{{ data.image }}" alt="">
                    </div>
                    <div class="mb-lib-item-data">
                        <h3>{{ data.title }}</h3>
                        <div class="mb-lib-item-st">{{ data.subtitle }}</div>
                        <div class="mb-lib-item-input clearfix">
                            <input type="button" class="mb-lib-item-in-action button button-ctpb" value="Import">
                            <span class="spinner"></span>
                            <# if(data.option){ #>
                            <input type="button" class="mb-lib-item-del-action button button-ctpb-cancel" value="Delete" data-id="{{ data.id }}">
                            <# } #>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/html" id="tmpl-mb-lib-filter">
            <div class="mb-lib-filter-wrap">
                <ul>
                    <li><a href="#" data-id="*" class="active">All</a></li>
                    <# _.each( data.types, function (type){ #>
                        <# var id = type.toLowerCase().replace(/ /g, '_') #>
                        <li><a href="#" data-id="{{ id }}">{{ type }}</a></li>
                    <# }); #>
                </ul>
            </div>
        </script>
        <?php
	}


	public function get_editor_vars (){

		global $wp_version, $tinymce_version, $concatenate_scripts, $compress_scripts;

		/**
		 * Filter "tiny_mce_version" is deprecated
		 *
		 * The tiny_mce_version filter is not needed since external plugins are loaded directly by TinyMCE.
		 * These plugins can be refreshed by appending query string to the URL passed to "mce_external_plugins" filter.
		 * If the plugin has a popup dialog, a query string can be added to the button action that opens it (in the plugin's code).
		 */
		$version = 'ver=' . $tinymce_version;
		
		$ext_plugins = '';

		$mce_locale = get_locale();
		
		/**
		 * Filter the list of teenyMCE buttons (Text tab).
		 *
		 * @since 2.7.0
		 *
		 * @param array  $buttons   An array of teenyMCE buttons.
		 * @param string $editor_id Unique editor identifier, e.g. 'content'.
		 */
		$mb_teeny_mce_buttons = apply_filters( 'teeny_mce_buttons', array('bold', 'italic', 'underline', 'blockquote', 'strikethrough', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo', 'link', 'unlink', 'fullscreen') );
		
		$mce_buttons = array( 'bold', 'italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr', 'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'wp_more', 'spellchecker' );
		
		if ( ! wp_is_mobile() ) {
			$mce_buttons[] = 'fullscreen';
		}

		$mce_buttons[] = 'wp_adv';
		
		
		/**
		 * Filter the first-row list of TinyMCE buttons (Visual tab).
		 *
		 * @since 2.0.0
		 *
		 * @param array  $buttons   First-row list of buttons.
		 * @param string $editor_id Unique editor identifier, e.g. 'content'.
		 */
		$mce_buttons = apply_filters( 'mce_buttons', $mce_buttons );
		
		$mce_buttons_2 = array( 'formatselect', 'underline', 'alignjustify', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo' );

		if ( ! wp_is_mobile() ) {
			$mce_buttons_2[] = 'wp_help';
		}

		/**
		 * Filter the second-row list of TinyMCE buttons (Visual tab).
		 *
		 * @since 2.0.0
		 *
		 * @param array  $buttons   Second-row list of buttons.
		 * @param string $editor_id Unique editor identifier, e.g. 'content'.
		 */
		$mce_buttons_2 = apply_filters( 'mce_buttons_2', $mce_buttons_2 );

		/**
		 * Filter the third-row list of TinyMCE buttons (Visual tab).
		 *
		 * @since 2.0.0
		 *
		 * @param array  $buttons   Third-row list of buttons.
		 * @param string $editor_id Unique editor identifier, e.g. 'content'.
		 */
		$mce_buttons_3 = apply_filters( 'mce_buttons_3', array() );

		/**
		 * Filter the fourth-row list of TinyMCE buttons (Visual tab).
		 *
		 * @since 2.5.0
		 *
		 * @param array  $buttons   Fourth-row list of buttons.
		 * @param string $editor_id Unique editor identifier, e.g. 'content'.
		 */
		$mce_buttons_4 = apply_filters( 'mce_buttons_4', array() );
		
		
		
		
		
		
		
		$mb_teeny_plugins = apply_filters( 'teeny_mce_plugins', array( 'colorpicker', 'lists', 'fullscreen', 'image', 'wordpress', 'wpeditimage', 'wplink' ) );
		
		
		/**
		 * Filter the list of TinyMCE external plugins.
		 *
		 * The filter takes an associative array of external plugins for
		 * TinyMCE in the form 'plugin_name' => 'url'.
		 *
		 * The url should be absolute, and should include the js filename
		 * to be loaded. For example:
		 * 'myplugin' => 'http://mysite.com/wp-content/plugins/myfolder/mce_plugin.js'.
		 *
		 * If the external plugin adds a button, it should be added with
		 * one of the 'mce_buttons' filters.
		 *
		 * @since 2.5.0
		 *
		 * @param array $external_plugins An array of external TinyMCE plugins.
		 */
		$mce_external_plugins = apply_filters( 'mce_external_plugins', array() );

		$plugins = array(
			'charmap',
			'colorpicker',
			'hr',
			'lists',
			'media',
			'paste',
			'tabfocus',
			'textcolor',
			'fullscreen',
			'wordpress',
			'wpautoresize',
			'wpeditimage',
			'wpemoji',
			'wpgallery',
			'wplink',
			'wpdialogs',
			'wptextpattern',
			'wpview',
			'wpembed',
		);

		$plugins[] = 'image';

		/**
		 * Filter the list of default TinyMCE plugins.
		 *
		 * The filter specifies which of the default plugins included
		 * in WordPress should be added to the TinyMCE instance.
		 *
		 * @since 3.3.0
		 *
		 * @param array $plugins An array of default TinyMCE plugins.
		 */
		$plugins = array_unique( apply_filters( 'tiny_mce_plugins', $plugins ) );

		if ( ( $key = array_search( 'spellchecker', $plugins ) ) !== false ) {
			// Remove 'spellchecker' from the internal plugins if added with 'tiny_mce_plugins' filter to prevent errors.
			// It can be added with 'mce_external_plugins'.
			unset( $plugins[$key] );
		}

		if ( ! empty( $mce_external_plugins ) ) {

			/**
			 * Filter the translations loaded for external TinyMCE 3.x plugins.
			 *
			 * The filter takes an associative array ('plugin_name' => 'path')
			 * where 'path' is the include path to the file.
			 *
			 * The language file should follow the same format as wp_mce_translation(),
			 * and should define a variable ($strings) that holds all translated strings.
			 *
			 * @since 2.5.0
			 *
			 * @param array $translations Translations for external TinyMCE plugins.
			 */
			$mce_external_languages = apply_filters( 'mce_external_languages', array() );

			$loaded_langs = array();
			$strings = '';

			if ( ! empty( $mce_external_languages ) ) {
				foreach ( $mce_external_languages as $name => $path ) {
					if ( @is_file( $path ) && @is_readable( $path ) ) {
						include_once( $path );
						$ext_plugins .= $strings . "\n";
						$loaded_langs[] = $name;
					}
				}
			}

			foreach ( $mce_external_plugins as $name => $url ) {
				if ( in_array( $name, $plugins, true ) ) {
					unset( $mce_external_plugins[ $name ] );
					continue;
				}

				$url = set_url_scheme( $url );
				$mce_external_plugins[ $name ] = $url;
				$plugurl = dirname( $url );
				$strings = '';

				// Try to load langs/[locale].js and langs/[locale]_dlg.js
				if ( ! in_array( $name, $loaded_langs, true ) ) {
					$path = str_replace( content_url(), '', $plugurl );
					$path = WP_CONTENT_DIR . $path . '/langs/';

					if ( function_exists('realpath') )
						$path = trailingslashit( realpath($path) );

					if ( @is_file( $path . $mce_locale . '.js' ) )
						$strings .= @file_get_contents( $path . $mce_locale . '.js' ) . "\n";

					if ( @is_file( $path . $mce_locale . '_dlg.js' ) )
						$strings .= @file_get_contents( $path . $mce_locale . '_dlg.js' ) . "\n";

					if ( 'en' != $mce_locale && empty( $strings ) ) {
						if ( @is_file( $path . 'en.js' ) ) {
							$str1 = @file_get_contents( $path . 'en.js' );
							$strings .= preg_replace( '/([\'"])en\./', '$1' . $mce_locale . '.', $str1, 1 ) . "\n";
						}

						if ( @is_file( $path . 'en_dlg.js' ) ) {
							$str2 = @file_get_contents( $path . 'en_dlg.js' );
							$strings .= preg_replace( '/([\'"])en\./', '$1' . $mce_locale . '.', $str2, 1 ) . "\n";
						}
					}

					if ( ! empty( $strings ) )
						$ext_plugins .= "\n" . $strings . "\n";
				}

				$ext_plugins .= 'tinymce.PluginManager.load("' . $name . '", "' . $url . '");' . "\n";
			}
		}


		// WordPress default stylesheet and dashicons
		$mce_css = array(
			includes_url( "css/dashicons.min.css" ),
			includes_url( 'js/tinymce' ) . '/skins/wordpress/wp-content.css?' . $version
		);

		$editor_styles = get_editor_stylesheets();
		if ( ! empty( $editor_styles ) ) {
			foreach ( $editor_styles as $style ) {
				$mce_css[] = $style;
			}
		}

		/**
		 * Filter the comma-delimited list of stylesheets to load in TinyMCE.
		 *
		 * @since 2.1.0
		 *
		 * @param string $stylesheets Comma-delimited list of stylesheets.
		 */
		$mce_css = trim( apply_filters( 'mce_css', implode( ',', $mce_css ) ), ' ,' );

		return array(
			'mb_teeny_mce_buttons' => implode( ',', $mb_teeny_mce_buttons ),
			'mb_mce_buttons' => implode( ',', $mce_buttons ),
			'mb_mce_buttons_2' => implode( ',', $mce_buttons_2 ),
			'mb_mce_buttons_3' => implode( ',', $mce_buttons_3 ),
			'mb_mce_buttons_4' => implode( ',', $mce_buttons_4 ),
			'mb_plugins' => implode( ',', $plugins ),
			'mb_mce_css' => $mce_css,
			'mb_mce_external_plugins' => $mce_external_plugins,
		);
    }


}

endif;