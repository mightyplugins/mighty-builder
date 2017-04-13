	<div class="mb-input-field mb-input-field-radio-image clearfix">
		<#
			var nameAttr = '';

			if( typeof isAddon == 'undefined' ){
				nameAttr = 'name="ctf_radio_input_'+data.id+'"';
			}
		#>
		<# for ( key in data.choices ) { #>
			<label>
				<input type="radio" value="{{ key }}" {{{ data.link }}} {{{ nameAttr }}} <# if ( key === data.value ) { #>checked="checked"<# } #> > 
				<img src="{{ data.choices[ key ] }}" alt="{{ key }}" class="mb-input-radio-img">
			</label>
		<# } #>
	</div>