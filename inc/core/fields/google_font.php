        <div class="mb-input-field mb-input-field-google-font">
        	<# if( parseFloat(data.choices['font-family']) || _.isUndefined(data.choices['font-family']) ){ #>
			<div class="mb-if-gf-font-family">
				<label><?php esc_html_e('Font Family', 'mighty-builder'); ?></label>
				<select class="mb-gf-ff-input">
					<option value=""><?php esc_html_e('Select a font...', 'mighty-builder'); ?></option>
					<# for ( key in mb_google_fonts ) { #>
						<option value="{{ key }}"<# if ( key === data.value['font-family'] ) { #>selected<# } #>>{{ key }}</option>
					<# } #>
				</select>
			</div>
			<# } #>
			<# if( parseFloat(data.choices['font-weight']) || _.isUndefined(data.choices['font-weight']) ){ #>
			<div class="mb-if-gf-font-weight">
				<# var fontWeights = mb_google_fonts[data.value['font-family']]; #>
				<label><?php esc_html_e('Font Weight', 'mighty-builder'); ?></label>
				<select class="mb-gf-fw-input">
					<# for ( key in fontWeights ) { #>
						<option value="{{ fontWeights[ key ] }}"<# if ( fontWeights[ key ] === data.value['font-weight'] ) { #>selected<# } #>>{{ fontWeights[ key ] }}</option>
					<# } #>
				</select>
			</div>
			<# } #>
			<# if( parseFloat(data.choices['font-size']) || _.isUndefined(data.choices['font-size']) ){ #>
			<div class="mb-if-gf-font-size mb-input-field-dimension">
				<#
				var fzNumber = '';
        		var fzUnit = '';
        		
        		if( ! _.isUndefined(data.value['font-size']) ){
        			fzNumber = parseFloat( data.value['font-size'] );
        		}
        		
        		if( ! _.isUndefined(data.value['font-size']) ){
        			fzUnit = data.value['font-size'].replace( parseFloat( data.value['font-size'] ), '' );
        		}
        		
        		var units = ['px', '%', 'em'];
		        if( ! _.isEmpty(data.choices['units']) ){
		        	units = data.choices['units'];
		        }
				#>
				<label><?php esc_html_e('Font Size', 'mighty-builder'); ?></label>
				<div class="mb-input-dimension-number">
					<input type="number" value="{{ fzNumber }}" min="0" class="mb-gf-fz-value-input">
				</div>
				<div class="mb-input-dimension-select">
					<select class="mb-gf-fz-unit-input">
						<# for ( key in units ) { #>
						<option value="{{ units[ key ] }}"<# if ( units[ key ] === fzUnit ) { #>selected<# } #>>{{ units[ key ] }}</option>
						<# } #>
					</select>
				</div>
			</div>
			<# } #>
			<# if( parseFloat(data.choices['line-height']) || _.isUndefined(data.choices['line-height']) ){ #>
			<div class="mb-if-gf-line-height mb-input-field-dimension">
				<#
				var lhNumber = '';
        		var lhUnit = '';
 
        		
        		if( ! _.isUndefined(data.value['line-height']) ){
        			lhNumber = parseFloat( data.value['line-height'] );
        		}
        		
        		if( ! _.isUndefined(data.value['line-height']) ){
        			lhUnit = data.value['line-height'].replace( parseFloat( data.value['line-height'] ), '' );
        		}
        		
        		var units = ['px', '%', 'em'];
		        if( ! _.isEmpty(data.choices['units']) ){
		        	units = data.choices['units'];
		        }
				#>
				<label><?php esc_html_e('Line Height', 'mighty-builder'); ?></label>
				<div class="mb-input-dimension-number">
					<input type="number" value="{{ lhNumber }}" min="0" class="mb-gf-lh-value-input">
				</div>
				<div class="mb-input-dimension-select">
					<select class="mb-gf-lh-unit-input">
						<# for ( key in units ) { #>
						<option value="{{ units[ key ] }}"<# if ( units[ key ] === lhUnit ) { #>selected<# } #>>{{ units[ key ] }}</option>
						<# } #>
					</select>
				</div>
			</div>
			<# } #>
			<# if( parseFloat(data.choices['letter-spacing']) ){ #>
			<div class="mb-if-gf-letter-spacing mb-input-field-dimension">
				<#
				var lsNumber = parseFloat( data.value['letter-spacing'] );
        		var lsUnit = data.value['font-size'].replace( parseFloat( data.value['letter-spacing'] ), '' );
        		
        		var units = ['px', '%', 'em'];
		        if( ! _.isEmpty(data.choices['units']) ){
		        	units = data.choices['units'];
		        }
				#>
				<label><?php esc_html_e('Latter Spacing', 'mighty-builder'); ?></label>
				<div class="mb-input-dimension-number">
					<input type="number" value="{{ lsNumber }}" min="0" class="mb-gf-ls-value-input">
				</div>
				<div class="mb-input-dimension-select">
					<select class="mb-gf-ls-unit-input">
						<# for ( key in units ) { #>
						<option value="{{ units[ key ] }}"<# if ( units[ key ] === lsUnit ) { #>selected<# } #>>{{ units[ key ] }}</option>
						<# } #>
					</select>
				</div>
			</div>
			<# } #>
			<# if( parseFloat(data.choices['word-spacing']) ){ #>
			<div class="mb-if-gf-word-spacing mb-input-field-dimension">
				<#
				var wsNumber = parseFloat( data.value['word-spacing'] );
        		var wsUnit = data.value['font-size'].replace( parseFloat( data.value['word-spacing'] ), '' );
        		
        		var units = ['px', '%', 'em'];
		        if( ! _.isEmpty(data.choices['units']) ){
		        	units = data.choices['units'];
		        }
				#>
				<label><?php esc_html_e('Word Spacing', 'mighty-builder'); ?></label>
				<div class="mb-input-dimension-number">
					<input type="number" value="{{ wsNumber }}" min="0" class="mb-gf-ws-value-input">
				</div>
				<div class="mb-input-dimension-select">
					<select class="mb-gf-ws-unit-input">
						<# for ( key in units ) { #>
						<option value="{{ units[ key ] }}"<# if ( units[ key ] === wsUnit ) { #>selected<# } #>>{{ units[ key ] }}</option>
						<# } #>
					</select>
				</div>
			</div>
			<# } #>
			<input type="hidden" value="{{ JSON.stringify(data.value) }}" class="mb-gf-input-val" {{{ data.link }}}>
        </div>
