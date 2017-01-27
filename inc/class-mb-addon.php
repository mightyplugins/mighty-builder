<?php


if(!class_exists('CTF_Addon')){
    return;
}

if (!class_exists('MB_Addon')):

class MB_Addon extends CTF_Addon
{

    function __construct()
	{
		parent::__construct();
		

		$this->add_js_tmlp_to_admin_footer();
		

        add_action( 'edit_form_after_title', array( &$this, 'hook_after_title' ) );

        add_action( 'edit_form_after_editor', array( &$this, 'hook_after_editor' ) );

        add_action( 'admin_footer', array( &$this, 'print_pagebuilder_tmpls' ) );

        add_action( 'save_post',  array( &$this, 'save_mb_active_meta' ), 10, 2 );
	}
	
	function load_admin_js(){
        parent::load_admin_js();

        wp_enqueue_script( 'ctf-remodal', MP_PB_URL . 'assets/remodal/remodal.min.js', array('jquery'), '1.0', true );
        
        wp_enqueue_script( 'ctf-serialize-object', MP_PB_URL . 'assets/js/jquery.serialize-object.js', array('jquery'), '1.0', true );

        wp_enqueue_script( 'mb-pagebuilder', MP_PB_URL . 'assets/js/mb-pagebuilder.js', array('jquery', 'underscore', 'ctf-core-script'), '1.0', true );

        $mb_pb_args = array(
            'section_sc' => apply_filters( 'mb_pb_section_sortcode_tag', 'mb_section' ),
            'row_sc' => apply_filters( 'mb_pb_row_sortcode_tag', 'mb_row' ),
            'col_sc' => apply_filters( 'mb_pb_column_sortcode_tag', 'mb_col' ),
            'pb_enable_text' => esc_html__( 'Active Page Builder', 'mighty-builder' ),
            'pb_disable_text' => esc_html__( 'Disable Page Builder', 'mighty-builder' ),
            'pb_elements_title' => esc_html__( 'Select an Element', 'mighty-builder' ),
            'mb_confirm' => esc_html__( 'Are you sure?', 'mighty-builder' ),
        );

        wp_localize_script( 'mb-pagebuilder', 'mb_elements_data', MB_Element::$_elements );
        wp_localize_script( 'mb-pagebuilder', 'mb_pb_args', $mb_pb_args );
    }
    
    function load_admin_css(){
        parent::load_admin_css();
        wp_enqueue_style( 'ctf-remodal', MP_PB_URL.'assets/remodal/remodal.css' );
        wp_enqueue_style( 'ctf-remodal-theme', MP_PB_URL.'assets/remodal/remodal-default-theme.css' );
        wp_enqueue_style('mighty-builder-icons', MP_PB_URL.'assets/mighty-builder-icons/mighty-builder-icons.css'  );
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

            echo '<div class="mb-pb-tab-cont">'
                    .'<div class="mb-pb-switch">'
                        .'<button class="mb-pb-switch-button" id="mb-pb-switch-button"  type="button" role="presentation"><i class="fa fa-'.$btn_icon.'"></i> '.$enable_button_txt.'</button>'
                    .'</div>'
                    .'<div class="mb-pb-classic-editor '.$active_class.'">';
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

            echo    '</div>'
                    .'<div class="mb-pb-container '.$active_class.'" id="mb-pb-container">'
                        .'<div class="mb-pb-header">'
                            .'<h3><i class="fa fa-cogs" aria-hidden="true"></i> <span>Mighty Builder</span></h3>'
                            .'<button type="button" role="presentation" class="mb-pb-fullscreen" id="mb-pb-fullscreen"><i class="mce-ico mce-i-dfw"></i></button>'
                        .'</div>'
                        .'<div class="mb-pb-elem-container">'
                        .'</div>'
                        .'<a class="mb-pb-add-sec" href="#" data-pb-shortcode="mb_section" data-pb-type="layout">Add Section</a>'
                    .'</div>'
                    .'<input type="hidden" name="mb_pb_enabled" class="mb-status-input" value="'.$active_input.'">'
                .'</div>';
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


    function print_pagebuilder_tmpls(){
        ?>
        <div class="remodal mb-pb-remodal mb-pb-remodal-form-off" data-remodal-id="modal-pb" role="dialog" aria-labelledby="modal-pb-title" aria-describedby="modal-pb-subtitle" data-remodal-options="hashTracking: false">
            
            <div class="modal-pb-header">
                <h2 id="modal-pb-title"><?php esc_html_e('Select an Element', 'mighty-builder'); ?></h2>
                <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            </div>
            <div id="modal-pb-container">
                <div class="mpb-elements-list clearfix"></div>
                <form class="mpb-inputs ctf-fc"></form>
            </div>
            <br>
            <div class="ctf-fc modal-pb-buttons">
                <button data-remodal-action="cancel" class="mb-pb-modal-cancel-btn button button-ctpb-cancel"><?php esc_html_e('Cancel', 'mighty-builder'); ?></button>
                <button class="button button-ctpb" id="add_ctpb_sc_to_item"><?php esc_html_e('Save Changes', 'mighty-builder'); ?></button>
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
        <?php
    }
}

endif;