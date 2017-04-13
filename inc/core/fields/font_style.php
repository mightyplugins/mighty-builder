	<#
		var nameAttr = '',
			boldLink = data.link,
			italicLink = data.link,
			underlineLink = data.link,
			strikethroughLink = data.link;

		if( typeof isAddon === 'undefined' ){
			nameAttr = 'name="ctf_checkbox_input_'+data.id+'"';
		}
		
		if( typeof isAddon !== 'undefined' ){
			boldLink = boldLink.replace('[]', '[bold]');
			italicLink = italicLink.replace('[]', '[italic]');
			underlineLink = underlineLink.replace('[]', '[underline]');
			strikethroughLink = strikethroughLink.replace('[]', '[strikethrough]');
		}
	#>
	<div class="mb-input-field mb-input-field-checkbox-button mb-input-field-font-style clearfix">
		<label>
			<input type="checkbox" class="mb-fstl-bold"  value="on" {{{ boldLink }}} {{{ nameAttr }}} <# if ( 'on' === data.value['bold'] ) { #>checked="checked"<# } #> > 
			<span class="mb-input-checkbox-button"><i class="fa fa-bold"></i></span>
		</label>
		
		<label>
			<input type="checkbox" class="mb-fstl-italic"  value="on" {{{ italicLink }}} {{{ nameAttr }}} <# if ( 'on' === data.value['italic'] ) { #>checked="checked"<# } #> > 
			<span class="mb-input-checkbox-button"><i class="fa fa-italic"></i></span>
		</label>
		<label>
			<input type="checkbox" class="mb-fstl-underline"  value="on" {{{ underlineLink }}} {{{ nameAttr }}} <# if ( 'on' === data.value['underline'] ) { #>checked="checked"<# } #> > 
			<span class="mb-input-checkbox-button"><i class="fa fa-underline"></i></span>
		</label>
		
		<label>
			<input type="checkbox" class="mb-fstl-strikethrough"  value="on" {{{ strikethroughLink }}} {{{ nameAttr }}} <# if ( 'on' === data.value['strikethrough'] ) { #>checked="checked"<# } #> > 
			<span class="mb-input-checkbox-button"><i class="fa fa-strikethrough"></i></span>
		</label>
	</div>