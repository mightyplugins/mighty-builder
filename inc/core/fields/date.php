				<#
					var optionsAttr = '';

					if ( typeof data.choices != 'undefined' && typeof data.choices != 'string' ){
						optionsAttr = 'data-options="'+window.btoa(JSON.stringify(data.choices))+'"';
					}
				#>

				<div class="mb-input-field mb-input-field-date">
					<input type="text" class="mb-date-field" value="{{ data.value }}" {{{ optionsAttr }}}  {{{ data.link }}} >
				</div>
