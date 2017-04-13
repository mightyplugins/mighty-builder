	<div class="mb-input-field mb-input-field-checkbox">
		<#
			var nameAttr = '';

			if( typeof isAddon == 'undefined' ){
				nameAttr = 'name="ctf_checkbox_input_'+data.id+'"';
			}
		#>
		<# for ( key in data.choices ) { #>
			<label>
				<input type="checkbox" value="{{ key }}" {{{ data.link }}} {{{ nameAttr }}} <# if ( _.contains(data.value, key) ) { #>checked="checked"<# } #> > 
				<span class="mb-input-checkbox-box"></span>
				<span class="mb-input-checkbox-title">{{ data.choices[ key ] }}</span>
			</label>
		<# } #>
	</div>