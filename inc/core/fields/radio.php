	<div class="mb-input-field mb-input-field-radio">
		<#
			var nameAttr = '';

			if( typeof isAddon == 'undefined' ){
				nameAttr = 'name="ctf_radio_input_'+data.id+'"';
			}
		#>
		<# for ( key in data.choices ) { #>
			<label>
				<input type="radio" value="{{ key }}" {{{ data.link }}} {{{ nameAttr }}} <# if ( key === data.value ) { #>checked="checked"<# } #> > 
				<span class="mb-input-radio-box"></span>
				<span class="mb-input-radio-title">{{ data.choices[ key ] }}</span>
			</label>
		<# } #>
	</div>