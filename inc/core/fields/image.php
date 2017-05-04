        <#
        
          var addbtnHiddenClass = '';
          var hiddenClass = '';

          if ( _.isNaN(data.value) ) {
            hiddenClass = 'mb-hidden';
          } else {
            addbtnHiddenClass = 'mb-hidden';
          }

        #>
        <div class="mb-input-field mb-input-field-image clearfix">
          <input type="hidden" class="mb-ii-data-field" value="{{ JSON.stringify(data.value) }}" {{{ data.link }}} >
          <div class="mb-ifi-view-image">
          </div>
          <div class="mb-ifi-btn-set">
            <button type="button" class="mb-btn mb-btn-small image-change-button {{ hiddenClass }}"><i class="fa fa-pencil" aria-hidden="true"></i> <?php esc_html_e('Change Image', 'mighty-builder'); ?></button>
            <button type="button" class="mb-btn mb-btn-small mb-btn-cancel image-remove-button {{ hiddenClass }}"><i class="fa fa-times" aria-hidden="true"></i> <?php esc_html_e('Remove Image', 'mighty-builder'); ?></button>
          </div>
          
          <button type="button" class="mb-btn image-upload-button {{ addbtnHiddenClass }}"><i class="fa fa-picture-o" aria-hidden="true"></i> <?php esc_html_e('Add Image', 'mighty-builder'); ?></button>
          
        </div>
