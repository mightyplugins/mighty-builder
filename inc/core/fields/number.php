        <#

        var miniAttr = '',
            maxAttr = '',
            stepAttr = '',
            value = '';
            
        if ( !_.isUndefined(data.choices) && !_.isUndefined(data.choices[ 'min' ]) ){
        	miniAttr = 'min="'+data.choices[ 'min' ]+'"';
        }

        if ( !_.isUndefined(data.choices) && !_.isUndefined(data.choices[ 'max' ]) ){
        	maxAttr = 'max="'+data.choices[ 'max' ]+'"';
        }

        if ( !_.isUndefined(data.choices) && !_.isUndefined(data.choices[ 'step' ]) ){
        	stepAttr = 'step="'+data.choices[ 'step' ]+'"';
        }

        if( data.value !== '' && !_.isNaN(data.value) ){
            value = 'value="'+data.value+'"';
        }
        #>
        <div class="mb-input-field mb-input-field-number">
          <input type="number" {{{ value }}} {{{ miniAttr }}} {{{ maxAttr }}} {{{ stepAttr }}} {{{ data.link }}}>
        </div>
