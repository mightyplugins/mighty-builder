<?php


if(!class_exists('CTF_Addon')){
    return;
}

class CTFPB_Addon extends CTF_Addon
{

    function __construct()
	{
		parent::__construct();
		

		$this->add_js_tmlp_to_admin_footer();
		

        add_action( 'edit_form_after_title', array( &$this, 'ctpb_hook_after_title' ) );

        add_action( 'edit_form_after_editor', array( &$this, 'ctpb_hook_after_editor' ) );

        add_action( 'admin_footer', array( &$this, 'print_pagebuilder_tmpls' ) );
	}
	
	function load_admin_js(){
        parent::load_admin_js();

        wp_enqueue_script( 'ctf-remodal', CTPB_URL . 'assets/remodal/remodal.min.js', array('jquery'), '1.0', true );
        
        wp_enqueue_script( 'ctf-serialize-object', CTPB_URL . 'assets/js/jquery.serialize-object.js', array('jquery'), '1.0', true );

        wp_enqueue_script( 'ct-pagebuilder', CTPB_URL . 'assets/js/ct-pagebuilder.js', array('jquery', 'underscore', 'ctf-core-script'), '1.0', true );

        $ct_pb_args = array(
            'section_sc' => apply_filters( 'ct_pb_section_sortcode_tag', 'ct_section' ),
            'row_sc' => apply_filters( 'ct_pb_row_sortcode_tag', 'ct_row' ),
            'col_sc' => apply_filters( 'ct_pb_column_sortcode_tag', 'ct_col' ),
            'pb_enable_text' => esc_html__( 'Active Page Builder', 'ctpb' ),
            'pb_disable_text' => esc_html__( 'Disable Page Builder', 'ctpb' ),
        );

        wp_localize_script( 'ct-pagebuilder', 'ct_pb_args', $ct_pb_args );
    }
    
    function load_admin_css(){
        parent::load_admin_css();
        wp_enqueue_style( 'ctf-remodal', CTPB_URL.'assets/remodal/remodal.css' );
        wp_enqueue_style( 'ctf-remodal-theme', CTPB_URL.'assets/remodal/remodal-default-theme.css' );
        wp_enqueue_style('ct-pagebuilder', CTPB_URL . 'assets/css/ct-pagebuilder.css', array(), '1.0');
    }

    function ctpb_hook_after_title(){
        if ( get_post_type() == 'page' ) {

            global $post;

            $is_enable = get_post_meta( $post->ID, 'ct_pb_enabled_key', true );
            $active_class = 'ct-pb-active';
            $enable_button_txt = esc_html__( 'Active Page Builder', 'ctpb' );

            if ($is_enable) {
                $active_class = '';
                $enable_button_txt = esc_html__( 'Disable Page Builder', 'ctpb' );
            }

            echo '<div class="ct-pb-tab-cont">'
                    .'<div class="ct-pb-switch">'
                        .'<button class="ct-pb-switch-button" id="ct-pb-switch-button"  type="button" role="presentation"><i class="fa fa-check"></i> '.$enable_button_txt.'</button>'
                    .'</div>'
                    .'<div class="ct-pb-classic-editor '.$active_class.'">';
        }
    }

    function ctpb_hook_after_editor(){
        if ( get_post_type() == 'page' ) {

            global $post;

            $is_enable = get_post_meta( $post->ID, 'ct_pb_enabled_key', true );
            $active_class = '';


            if ($is_enable) {
                $active_class = 'ct-pb-active';
            }

            echo    '</div>'
                    .'<div class="ct-pb-container '.$active_class.'" id="ct-pb-container">'
                        .'<div class="ct-pb-header">'
                            .'<h3><i class="fa fa-cogs" aria-hidden="true"></i> <span>Page Builder</span></h3>'
                            .'<button type="button" role="presentation" class="ct-pb-fullscreen" id="ct-pb-fullscreen"><i class="mce-ico mce-i-dfw"></i></button>'
                        .'</div>'
                        .'<div class="ct-pb-elem-container">'
                        .'</div>'
                        .'<a class="ct-pb-add-sec" href="#" data-pb-shortcode="ct_section" data-pb-type="layout">Add Section</a>'
                    .'</div>'
                .'</div>';
        }
    }


    function print_pagebuilder_tmpls(){
        ?>
        <div class="remodal ct-pb-remodal ct-pb-remodal-form-off" data-remodal-id="modal-pb" role="dialog" aria-labelledby="modal-pb-title" aria-describedby="modal-pb-subtitle" data-remodal-options="hashTracking: false">
            
            <div class="modal-pb-header">
                <h2 id="modal-pb-title">Select an Element</h2>
                <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            </div>
            <div id="modal-pb-container">
                <div class="mpb-elements-list clearfix"></div>
                <form class="mpb-inputs ctf-fc"></form>
            </div>
            <br>
            <div class="ctf-fc modal-pb-buttons">
                <button data-remodal-action="cancel" class="ct-pb-modal-cancel-btn button button-ctpb-cancel">Cancel</button>
                <button class="button button-ctpb" id="add_ctpb_sc_to_item">Save Changes</button>
            </div>
        </div>
        <script type="text/html" id="tmpl-layout-section">
            <div class="ct-pb-section-layout">
                <textarea class="ct-pb-sc-code ct-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="ct-pb-layout-edit clearfix">
                    <ul>
                        <li><a href="#" class="ct-pb-drag"><i class="fa fa-arrows"></i> Section</a></li>
                        <li><a href="#" class="ct-pb-add-row"><i class="fa fa-plus"></i> Add Row</a></li>
                        <li><a href="#" class="ct-pb-edit-section"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="ct-pb-copy-section"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="ct-pb-delete" data-item="section"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                <div class="ct-pb-section-container">
                </div>
                
                <textarea class="ct-pb-sc-code ct-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-layout-row">
            <div class="ct-pb-row-layout">
                <textarea class="ct-pb-sc-code ct-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="ct-pb-layout-edit clearfix">
                    <ul>
                        <li><a href="#" class="ct-pb-drag"><i class="fa fa-arrows"></i> Row</a></li>
                        <li><a href="#" class="ct-pb-add-col"><i class="fa fa-plus"></i> Add Column</a></li>
                        <li><a href="#" class="ct-pb-edit-row"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="ct-pb-copy-row"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="ct-pb-delete" data-item="row"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                <div class="ct-pb-row-container clearfix">
                </div>
                
                <textarea class="ct-pb-sc-code ct-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-layout-col">
            <div class="ct-pb-col-layout ct_pb_col-{{ data.col_size }}" data-col-size="{{ data.col_size }}">
                <textarea class="ct-pb-sc-code ct-pb-sc-start">{{{ data.sc_start }}}</textarea>
                <div class="ct-pb-col-inner">
                    
                    <div class="ct-pb-layout-edit clearfix">
                        <ul class="ct-pb-col-edit-ctrl">
                            <li><a href="#" class="ct-pb-drag"><i class="fa fa-arrows"></i></a></li>
                            <li><a href="#" class="ct-pb-edit-column"><i class="fa fa-pencil"></i></a></li>
                            <li><a href="#" class="ct-pb-delete" data-item="column"><i class="fa fa-trash-o"></i></a></li>
                            <li><a href="#" class="ct-pb-drag"><i class="fa fa-ellipsis-h"></i></a></li>
                        </ul>
                        <ul class="ct-pb-col-size-ctrl">
                            <li><a href="#" class="ct-pb-col-sz-minus"><i class="fa fa-minus"></i></a></li>
                            <li><span class="ct-pb-col-sz-txt">{{ data.col_size }}/12</span></li>
                            <li><a href="#" class="ct-pb-col-sz-plus"><i class="fa fa-plus"></i></a></li>
                        </ul>
                    </div>
                    <div class="ct-pb-col-container"></div>
                    <a href="#" class="ct-pb-add-element"><i class="fa fa-plus"></i><span> Add Element</span></a>
                    
                    
                </div>
                <textarea class="ct-pb-sc-code ct-pb-sc-end">{{{ data.sc_end }}}</textarea>
            </div>
        </script>
        <script type="text/html" id="tmpl-ctpb-element"><div class="ct-pb-element-item" data-code="{{ data.code }}">
                <textarea class="ct-pb-sc-code ct-pb-sc-start">{{{ data.sc_start }}}</textarea>
                <# console.log(data.sc_start); #>
                <div class="ct-pb-element-edit">
                    <ul>
                        <li><a href="#" class="ct-pb-drag"><i class="fa fa-arrows"></i></a></li>
                        <li><a href="#" class="ct-pb-item-edit-btn"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="ct-pb-item-copy-btn"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="ct-pb-item-delete" data-item="element"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>

                <div class="ct-pb-item-container-ui">
                    <div class="ct-pb-elem-icon">
                        <i class="{{ data.icon }}"></i>
                    </div>
                    <h3 class="ct-pb-elem-item-name">{{ data.name }}</h3>
                </div>
                    
        </div></script>
        <script type="text/html" id="tmpl-ctpb-element-container"><div class="ct-pb-element-container" data-code="{{ data.code }}">
                
                <textarea class="ct-pb-sc-code ct-pb-sc-start">{{{ data.sc_start }}}</textarea>
                
                <div class="ct-pb-element-edit">
                    <ul>
                        <li><a href="#" class="ct-pb-drag"><i class="fa fa-arrows"></i></a></li>
                        <li><a href="#" class="ct-pb-item-edit-btn"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="ct-pb-item-copy-btn"><i class="fa fa-clone"></i></a></li>
                        <li><a href="#" class="ct-pb-item-delete" data-item="element"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>

                <div class="ct-pb-container-container-ui">
                    <h3 class="ct-pb-elem-item-name"><i class="{{ data.icon }}"></i> {{ data.name }}</h3>
                </div>
                <div class="ct-pb-elem-cont-container"></div>
                <a href="#" class="ct-pb-add-sub-element"><i class="fa fa-plus"></i><span> Add {{ data.name }} Item</span></a>

                <textarea class="ct-pb-sc-code ct-pb-sc-end">{{{ data.sc_end }}}</textarea>
                    
        </div></script>
        <script type="text/html" id="tmpl-ctpb-modal-item">
            <div class="ct-pb-modal-item" data-code="{{ data.code }}">
                <div class="ct-pb-mi-icon">
                    <i class="{{ data.icon }}"></i>
                </div>
                <div class="ct-pb-modal-item-label">
                    <strong>{{ data.name }}</strong>
                    <p>{{ data.desc }}</p>
                </div>
            </div>
        </script>
        <?php
    }
}