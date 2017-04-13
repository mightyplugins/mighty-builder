        <#
        
          var addbtnHiddenClass = '';
          var hiddenClass = '';
          
          if ( _.isEmpty(data.value['url']) ) {
            hiddenClass = 'mb-hidden';
          } else {
            addbtnHiddenClass = 'mb-hidden';
          }

          var imgVal = '';
          if ( typeof data.value != 'undefined'){
            if( _.isEmpty(data.value['thumbnail']) ) {
              imgVal = data.value['url'];
            } else {
              imgVal = data.value['thumbnail'];
            }
          }

          
        #>
        <div class="mb-input-field mb-input-field-image clearfix">
          <input type="hidden" class="mb-ii-data-field" value="{{ JSON.stringify(data.value) }}" {{{ data.link }}} >
          <div class="mb-ifi-view-image">
            <# if ( ! _.isEmpty(imgVal) ) { #>
              <img class="" src="{{ imgVal }}" alt="" />
            <# } #>
          </div>
          <div class="mb-ifi-btn-set">
            <button type="button" class="mb-btn mb-btn-small image-change-button {{ hiddenClass }}"><i class="fa fa-pencil" aria-hidden="true"></i> <?php esc_html_e('Change Image', '_s'); ?></button>
            <button type="button" class="mb-btn mb-btn-small mb-btn-cancel image-remove-button {{ hiddenClass }}"><i class="fa fa-times" aria-hidden="true"></i> <?php esc_html_e('Remove Image', '_s'); ?></button>
          </div>
          
          <button type="button" class="mb-btn image-upload-button {{ addbtnHiddenClass }}"><i class="fa fa-picture-o" aria-hidden="true"></i> <?php esc_html_e('Add Image', '_s'); ?></button>
          
        </div>
