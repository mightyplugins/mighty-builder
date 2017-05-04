        <# var defaultValue = '';
        if ( data.defaultValue ) {

            defaultValue = data.defaultValue;

            defaultValue = ' data-default-color=' + defaultValue; // Quotes added automatically.
        } #>
        <div class="mb-input-field mb-input-field-color-rgba">
          <input type="text" class="mb-rgba-color-field" value="{{ data.value }}" placeholder="<?php esc_attr_e( 'RGBA Value', 'mighty-builder' ); ?>" {{ defaultValue }} {{{ data.link }}} >
        </div>
