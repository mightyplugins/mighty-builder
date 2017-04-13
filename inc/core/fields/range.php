        <#

        var miniAttr = '',
            maxAttr = '',
            stepAttr = '';

        if ( typeof data.choices != 'undefined' ){
          
          if (! _.isUndefined(data.choices[ 'min' ]) ){
          	miniAttr = 'min="'+data.choices[ 'min' ]+'"';
          }

          if (! _.isUndefined(data.choices[ 'max' ]) ){
          	maxAttr = 'max="'+data.choices[ 'max' ]+'"';
          }

          if (! _.isUndefined(data.choices[ 'step' ]) ){
          	stepAttr = 'step="'+data.choices[ 'step' ]+'"';
          }
        }
        #>
        <div class="mb-input-field mb-input-field-range">
          <div class="mb-if-range-cont">
          	<input type="range" value="{{ data.value }}" {{{ miniAttr }}} {{{ maxAttr }}} {{{ stepAttr }}} {{{ data.link }}}>
          </div>
          <div class="mb-if-txt-cont">
          	<input type="number" value="{{ data.value }}" {{{ miniAttr }}} {{{ maxAttr }}} {{{ stepAttr }}} >
          </div>
        </div>
