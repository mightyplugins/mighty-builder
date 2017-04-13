	<div class="mb-input-field mb-input-field-text">
		<select {{{ data.link }}} >
			<# for ( key in data.choices ) { #>
				<option value="{{ key }}"<# if ( key === data.value ) { #>selected<# } #>>{{ data.choices[ key ] }}</option>
			<# } #>
		</select>
	</div>