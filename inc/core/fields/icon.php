        <div class="mb-input-field mb-input-field-icon">
          
          <div class="ct-iconpicker">
            <div class="ct-ip-holder">
              <div class="ct-ip-icon"><i class="{{ data.value }}"></i></div>
              <input type="hidden" value="{{ data.value }}" class="ct-icon-value" {{{ data.link }}} />
            </div>
            <div class="ct-ip-popup clearfix">
              <div class="ct-ip-search">
                <input type="text" class="ct-ip-search-input" placeholder="<?php esc_html_e('Search icon', 'mighty-builder'); ?>" />
              </div>
              <ul>
                <#
                  var classAttr = '';
                  if( _.isEmpty(data.value) ) {
                    classAttr = 'class="mb-selected"';
                  }
                #>
                <li><a href="#" data-icon="" {{{ classAttr }}} data-tooltip="None">N/A</a></li>
                <# for ( key in mb_fa_icons ) { #>
                  <#
                    var classAttr = '';
                    if( key == data.value ) {
                      classAttr = 'class="mb-selected"';
                    }
                  #>
                  <li><a href="#" data-icon="{{ key }}" {{{ classAttr }}} data-tooltip="{{ mb_fa_icons[ key ].toLowerCase() }}"><i class="{{ key }}"></i></a></li>
                <# } #>
              </ul>
            </div>
          </div>
        </div>
