		<div class="mb-input-field mb-input-field-text-multi">
			<div class="mb-mt-input-container">
				<# for ( key in data.value ) { #>
					<div class="mb-mt-input-item">
						<input type="text" class="mb-txt-field" value="{{ data.value[key] }}" {{{ data.link }}} >
						<button class="mb-mt-input-delete">x</button>
					</div>
				<# } #>
			</div>
			<button class="mb-mt-add-new mb-btn mb-btn-small" data-name="{{{ _.escape(data.link) }}}"><i class="fa fa-plus"></i> <?php esc_html_e('Add', 'mighty-builder'); ?></button>
			<div class="mb-mt-input-item mb-hidden mb-mt-tmpl">
				<input type="text" class="mb-txt-field" >
				<button class="mb-mt-input-delete">x</button>
			</div>
		</div>