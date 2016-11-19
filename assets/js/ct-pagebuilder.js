(function($, CTF) {

	var CTF_PB = {};
	
	CTF_Core.CTF_PageBuilder = CTF_Core.Opts.extend({
        initialize: function ( container, args, tag ){
	    	this.inputArgs = args;
	    	this.tag = tag;
	    	this.containerObj = container;

	    	this.renderContent();
	    },
	    renderInput: function(args){
	    	var inputFieldClss = CTF_Core.Api['ctf_'+args.type],
	    		inputField;


	    	if ( typeof inputFieldClss !== 'undefined' ) {
	    		inputField = new inputFieldClss(args.type, args, this.containerObj);

		    	inputField.inputNameAttr = 'name="'+this.getNameAttr( args.type, args.id )+'"';

		    	inputField.inputValue = this.getInputValue( args.type, args.id, args.default);

		    	inputField.renderContent();
		    	
		    	if(args.type == 'editor'){
		    		
		    		this.containerObj.parents('[data-remodal-id=modal-pb]').on('closing', function (){
		    			inputField.removeEditor();
		    		});
		    	}
	    	}
	    },
        getNameAttr: function ( type, id ){
            var nameAttrValue = this.tag+'['+id+']';

            if ( 
                (type == 'checkbox') ||
                (type == 'checkbox_image') ||
                (type == 'checkbox_button') ||
                (type == 'font_style') ||
                (type == 'dimension')
                ) {
                nameAttrValue  = this.tag+'['+id+'][]';
            } else if ( type == 'text_multi' ) {
                nameAttrValue  = this.tag+'['+id+'][]';
                // this.inputArgs.btnext  = 'data-name="'+this.container+'['+id+'][]"';
            }

            return nameAttrValue;
        },
        getInputValue: function ( type, id, defValue ){
            var value = defValue;
            return value;
        }
    });

	/**
	 * CantoPageBuilder Core Class
	 *
	 * @since 1.0
	 */
	CTF_PB.Core = CTF.Class.extend({
		args: {}, // ct_pb_args
		pbSwitch: $('#ct-pb-switch-button'),
		tabContainer: $('.ct-pb-tab-cont'),
		classicEditor: $('.ct-pb-tab-cont .ct-pb-classic-editor'),
		visualEditor: $('.ct-pb-tab-cont .ct-pb-container'),
		sectionHolder: $('#ct-pb-container > .ct-pb-elem-container'),
		pbContainer: $('#ct-pb-container'),
		sectionTmpl: wp.template('layout-section'),
		rowTmpl: wp.template('layout-row'),
		colTmpl: wp.template('layout-col'),
		modalItemTmpl: wp.template('ctpb-modal-item'),
		elementItemTmpl: wp.template('ctpb-element'),
		elementContainerTmpl: wp.template('ctpb-element-container'),
		rowHolderSelector: '.ct-pb-section-container',
		colHolderSelector: '.ct-pb-row-container',
		pagebuilderModalObj: $('[data-remodal-id=modal-pb]'),
		pagebuilderModal: {},
		pagebuilderModalForm: $('[data-remodal-id=modal-pb]').find('.mpb-inputs'),

		/**
		 * Constructor Method of core class
		 */
		initialize: function ( args ){
			var self = this;

			if (typeof args !== 'undefined') {
				self.args = args;
			}

			// modal for page builder
			self.pagebuilderModal = self.pagebuilderModalObj.remodal({hashTracking:false});

			// Switch page pagebuilder 
			self.pbSwitchAction();

			// Init sortable to Section Container
			self.containerSortableInit();

			// Add section for pagebuilder
			self.addSectionAction();

			self.onSectionEditInit();

			self.onSectionCopyInit();
			
			// Add row for pagebuilder
			self.addRowInit();

			self.onRowEditInit();

			self.onRowCopyInit();
			
			// Add column for pagebuilder
			self.addColumnInit();

			self.onColumnEditInit();

			self.deleteInit();

			self.colSizeControl();

			self.addElementInit();


			self.onElementEditInit();

			self.onElementCopyInit();

			self.onElementDeleteInit();

			self.onClickChildItemAdd();
			
			
			$('body').on('ctpbchanged', self.getAllShortcodesForPage);
		},

		/**
		 * PageBuilder Switch action on click event
		 * It will active and deactivate page builder.
		 *
		 * @since 1.0
		 */
		pbSwitchAction: function (){
			var self = this;

			self.pbSwitch.on('click', function (e) {
				e.preventDefault();

				if (self.classicEditor.hasClass('ct-pb-active')) {

					// Remove Class from Clasic Editor
					self.classicEditor.removeClass('ct-pb-active');

					// Add Class to visual editor
					self.visualEditor.addClass('ct-pb-active');

					// Chnage button text
					$(this).html('<i class="fa fa-times"></i> '+self.args.pb_disable_text);

					self.initByEditorData();

				} else if (self.visualEditor.hasClass('ct-pb-active')) {

					// Remove class from visual editor 
					self.visualEditor.removeClass('ct-pb-active');

					// Add class to classic editor
					self.classicEditor.addClass('ct-pb-active');

					// Chnage button text
					$(this).html('<i class="fa fa-check"></i> '+self.args.pb_enable_text);

					self.sectionHolder.html("");
				}
			});
		},

		initByEditorData: function(){
			var editor_content = $('#content').val(),
				self = this,
				allScTags = [],
				editor;
				
			if(typeof tinyMCE !== 'undefined'){
				editor = tinyMCE.get('content');
				
				if(editor !== null && typeof editor !== 'undefined'){
					editor_content = editor.getContent();
				} else {
					editor_content = switchEditors.wpautop(editor_content);
					editor_content = editor_content.replace(/(?:\r\n|\r|\n)/g, '');
				}
			} else {
				editor_content = switchEditors.wpautop(editor_content);
				editor_content = editor_content.replace(/(?:\r\n|\r|\n)/g, '');
			}



			if ( !$('#content').length || _.isEmpty(editor_content)) {
				return;
			}

			_.each(ctf_pb_elements_data, function(sc_data, tag){
				if((typeof sc_data.parent === 'undefined') && (typeof sc_data.layout === 'undefined')){
					allScTags.push(tag);
				}
				
			});

			var matchedSections = editor_content.match(new RegExp("(\\["+self.args.section_sc+".*?\\].*?\\[\\/"+self.args.section_sc+"\\])", "g"));



			console.log(editor_content);

			if ( typeof matchedSections !== 'undefined' && !_.isEmpty(matchedSections) ) {
				_.each(matchedSections, function (section) {
					var matchedRows = section.match(new RegExp("(\\["+self.args.row_sc+".*?\\].*?\\[\\/"+self.args.row_sc+"\\])", "g")),
						sectionScStart = section.match(new RegExp(/\[ct_section.*?\]/)),
						sectionObj = self.getSection(sectionScStart);

					if ( typeof matchedRows !== 'undefined' && !_.isEmpty(matchedRows) ) {
						_.each(matchedRows, function (row) {
							var matchedCols = row.match(new RegExp("(\\[ct_col.*?\\].*?\\[\\/ct_col\\])", "g")),
								rowScStart = row.match(new RegExp(/\[ct_row.*?\]/)),
								rowObj = self.getRow(rowScStart);
								
							if ( typeof matchedCols !== 'undefined' && !_.isEmpty(matchedCols) ) {
								_.each(matchedCols, function (column) {
									var matchedItems = column.match(new RegExp("(\\[("+allScTags.join("|")+")(?![\\w-]).*?\\](?![\"])(.*?\\[\\/(\\2)\\])?)", "g")),
										colSize = column.match(new RegExp(/col="(\d+)"/))[1],
										colScStart = column.match(new RegExp(/\[ct_col.*?\]/)),
										columnObj = self.getColumn(colSize, colScStart);
										

									if ( typeof matchedItems !== 'undefined' && !_.isEmpty(matchedItems) && allScTags.length) {
										_.each(matchedItems, function (item) {
											var itemScTag = item.match(new RegExp("\\[("+allScTags.join("|")+")(?![\\w-])"))[1],
												itemObj;


											if (typeof ctf_pb_elements_data[itemScTag].child !== 'undefined') {
												itemObj = self.getContainerItem(itemScTag, item);
											} else {
												itemObj = self.getItem(itemScTag, item);
											}
												
												
											if (typeof itemObj !== 'undefined') {
												columnObj.find('.ct-pb-col-container').append(itemObj);
											}
											
											
										});
									}
										
									rowObj.find('.ct-pb-row-container').append(columnObj);
								});
							}

							sectionObj.find('.ct-pb-section-container').append(rowObj);
						});
					}

					self.sectionHolder.append(sectionObj);

				});
			}

			
		},

		/**
		 * Add section on click event including
		 * 1 row and 1 column.
		 *
		 * @since 1.0
		 */
		addSectionAction: function(){
			var self = this;

			self.pbContainer.on('click', 'a.ct-pb-add-sec', function(e){
				e.preventDefault();
				var sectionObj = self.getSection(),
					rowObj = self.getRow(),
					columnObj = self.getColumn(12);
					
				

				rowObj.find('.ct-pb-row-container').append(columnObj);

				sectionObj.find('.ct-pb-section-container').append(rowObj)
				self.sectionHolder.append(sectionObj);

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		addRowInit: function(){
			var self = this;
			
			self.pbContainer.on('click', 'a.ct-pb-add-row', function(e){
				e.preventDefault();
				
				var section = $(this).parents('.ct-pb-section-layout'),
					rowHolder = section.find(self.rowHolderSelector),
					rowObj = self.getRow(),
					columnObj = self.getColumn(12);
					
				rowObj.find('.ct-pb-row-container').append(columnObj);
				
				rowHolder.append(rowObj);

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		addColumnInit: function(){
			var self = this;
			
			self.pbContainer.on('click', 'a.ct-pb-add-col', function(e){
				e.preventDefault();
				
				var row = $(this).parents('.ct-pb-row-layout'),
					colHolder = row.find(self.colHolderSelector),
					currentCols = colHolder.find('.ct-pb-col-layout'),
					numberOfCols= (currentCols.length + 1),
					col_size = (12/numberOfCols),
					sizeAddition = 0,
					resizeOtherCols = true;
					
				if(numberOfCols == 5){
					col_size = 2;
					sizeAddition = 2;
				} else if(numberOfCols == 7){
					col_size = 1;
					sizeAddition = 5;
				} else if(numberOfCols == 8){
					col_size = 1;
					sizeAddition = 4;
				} else if(numberOfCols == 9){
					col_size = 1;
					sizeAddition = 3;
				} else if(numberOfCols == 10){
					col_size = 1;
					sizeAddition = 2;
				} else if(numberOfCols == 11){
					col_size = 1;
					sizeAddition = 1;
				} else if(numberOfCols == 12){
					col_size = 1;
				}
				
				if(numberOfCols === 13){
					return;
				}

				var allColsSize = 0;
				currentCols.each(function(){
					var colSizeData = parseInt($(this).attr('data-col-size'));

					allColsSize += colSizeData;
				});

				if (allColsSize <= 11) {
					col_size = 12 - allColsSize;
					resizeOtherCols = false;
				}
				
				var columnObj = self.getColumn(col_size);
				
				if (resizeOtherCols) {
					currentCols.each(function(){
						var colSizeData = parseInt($(this).attr('data-col-size')),
							oldScColumnStart = $(this).find('> .ct-pb-sc-start').html(),
							newColSize = col_size;

						$(this).removeClass('ct_pb_col-'+colSizeData);
						
						if(sizeAddition){
							newColSize += 1;
							
							sizeAddition -= 1;
						}

						var newScColumnStart =  oldScColumnStart.replace('col="'+colSizeData+'"', 'col="'+newColSize+'"');

						// var tempShortcodeStart = '['+self.args.col_sc+' col="'+newColSize+'"]';

						$(this).find('> .ct-pb-sc-start').html(newScColumnStart);

						$(this).find('.ct-pb-col-sz-txt').html(newColSize+'/12');
						
						$(this).attr('data-col-size', newColSize);
						$(this).addClass('ct_pb_col-'+newColSize);
					});
				}
				
				
				colHolder.append(columnObj);

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		getSection: function(sc_start, sc_end){
			var self = this,
				data = {
					sc_start:'['+self.args.section_sc+']',
					sc_end:'[/'+self.args.section_sc+']'
				},
				sectionObj;

			if (typeof sc_start !== 'undefined') {
				data.sc_start = sc_start;
			}

			if (typeof sc_end !== 'undefined') {
				data.sc_end = sc_end;
			}

			sectionObj = $(self.sectionTmpl(data));

			self.sectionContainerSortable(sectionObj);

			return sectionObj;
		},
		onSectionEditInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-edit-section', function(e){
				e.preventDefault();
				
				self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');

				var elementObj = $(this).parents('.ct-pb-section-layout');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');
				}

				var tag = self.args.section_sc,
					shortcode = elementObj.find('> .ct-pb-sc-code.ct-pb-sc-start').html(),
					options = self.getScOptionsFromSc(_.unescape(shortcode), tag);




				// Clear Element List and Element Form HTML
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');
				self.pagebuilderModalForm.html('');

				var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, options, tag);

				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
					var formData = self.pagebuilderModalForm.serializeObject(),
						shortcode = '';
					
					if(typeof formData[tag] !== 'undefined'){
						shortcode = self.getShortcodeByData(tag, formData[tag]);
					}
					

					elementObj.find("> .ct-pb-sc-code.ct-pb-sc-start").html(shortcode);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
					
					self.pagebuilderModal.close();
				});

				self.pagebuilderModal.open();
			});
		},
		onSectionCopyInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-copy-section', function(e){
				e.preventDefault();

				var elementObj = $(this).parents('.ct-pb-section-layout');

				if (elementObj.length == 0) {
					return;
				}

				var newElementObj = elementObj.clone();

				// self.childElemContSortable(newElementObj);

				newElementObj.insertAfter(elementObj);

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		getRow: function(sc_start, sc_end){
			var self = this,
				data = {
					sc_start:'['+self.args.row_sc+']',
					sc_end:'[/'+self.args.row_sc+']'
				},
				rowObj;

			if (typeof sc_start !== 'undefined') {
				data.sc_start = sc_start;
			}

			if (typeof sc_end !== 'undefined') {
				data.sc_end = sc_end;
			}

			rowObj = $(self.rowTmpl(data));

			self.rowContainerSortable( rowObj );

			return rowObj;
		},
		onRowEditInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-edit-row', function(e){
				e.preventDefault();
				
				self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');

				var elementObj = $(this).parents('.ct-pb-row-layout');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');
				}

				var tag = self.args.row_sc,
					shortcode = elementObj.find('> .ct-pb-sc-code.ct-pb-sc-start').html(),
					options = self.getScOptionsFromSc(_.unescape(shortcode), tag);




				// Clear Element List and Element Form HTML
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');
				self.pagebuilderModalForm.html('');

				var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, options, tag);

				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
					var formData = self.pagebuilderModalForm.serializeObject(),
						shortcode = '';
					
					if(typeof formData[tag] !== 'undefined'){
						shortcode = self.getShortcodeByData(tag, formData[tag]);
					}
					

					elementObj.find("> .ct-pb-sc-code.ct-pb-sc-start").html(shortcode);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
					
					self.pagebuilderModal.close();
				});

				self.pagebuilderModal.open();
			});
		},
		onRowCopyInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-copy-row', function(e){
				e.preventDefault();

				var elementObj = $(this).parents('.ct-pb-row-layout');

				if (elementObj.length == 0) {
					return;
				}

				var newElementObj = elementObj.clone();

				// self.childElemContSortable(newElementObj);

				newElementObj.insertAfter(elementObj);

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		getColumn: function( size, sc_start, sc_end){
			var self = this,
				data = {
					sc_start:'['+self.args.col_sc+' col="'+size+'"]',
					sc_end:'[/'+self.args.col_sc+']',
					col_size: size
				},
				columnObj;
				
			if (typeof sc_start !== 'undefined') {
				data.sc_start = sc_start;
			}

			if (typeof sc_end !== 'undefined') {
				data.sc_end = sc_end;
			}
			
			columnObj = $(self.colTmpl(data));

			self.colContainerSortable(columnObj);
			
			return columnObj;
		},
		onColumnEditInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-edit-column', function(e){
				e.preventDefault();
				
				self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');

				var elementObj = $(this).parents('.ct-pb-col-layout');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');
				}

				var tag = self.args.col_sc,
					shortcode = elementObj.find('> .ct-pb-sc-code.ct-pb-sc-start').html(),
					options = self.getScOptionsFromSc(_.unescape(shortcode), tag);




				// Clear Element List and Element Form HTML
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');
				self.pagebuilderModalForm.html('');

				var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, options, tag);

				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
					var formData = self.pagebuilderModalForm.serializeObject(),
						shortcode = '';

						console.log(formData);
					
					if(typeof formData[tag] !== 'undefined'){
						shortcode = self.getShortcodeByData(tag, formData[tag]);
					}
					

					elementObj.find("> .ct-pb-sc-code.ct-pb-sc-start").html(shortcode);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
					
					self.pagebuilderModal.close();
				});

				self.pagebuilderModal.open();
			});
		},
		getItem: function(tag, shortcode){
			
			var self = this,
				element_data = ctf_pb_elements_data[tag],
				data = {
					code: tag,
					icon: element_data.icon,
					color: element_data.color,
					name: element_data.title,
					sc_start: shortcode
				},
				itemObj;
				
			
			itemObj = $(self.elementItemTmpl(data));
			
			return itemObj;
		},
		getContainerItem: function(tag, shortcode){
			
			var self = this,
				parentData = ctf_pb_elements_data[tag],
				matchedChildItems = shortcode.match(new RegExp("(\\[("+parentData.child+")(?![\\w-]).*?\\](?![\"])(.*?\\[\\/(\\2)\\])?)", "g")),
				containerData = {
					code: tag,
					icon: parentData.icon,
					name: parentData.title,
					sc_start: shortcode.match(new RegExp("(\\[("+tag+")(?![\\w-]).*?\\](?![\"]))", "g")),
					sc_end: '[/'+tag+']'
				},
				containerObj = $(self.elementContainerTmpl(containerData));


			self.childElemContSortable(containerObj);


			if ( typeof matchedChildItems !== 'undefined' && !_.isEmpty(matchedChildItems) ) {
				_.each(matchedChildItems, function (childItem) {
					var itemObj = self.getItem(parentData.child, childItem);
						

					containerObj.find('.ct-pb-elem-cont-container').append(itemObj);
					
					
				});
			}

			return containerObj;

		},
		deleteInit: function(){
			var self = this;
			
			self.pbContainer.on('click', 'a.ct-pb-delete', function(e){
				e.preventDefault();
				var itemName = $(this).data('item'),
					parentSelector = '';

				if (itemName == 'section') {
					parentSelector = '.ct-pb-section-layout';
				} else if (itemName == 'row'){
					parentSelector = '.ct-pb-row-layout';
				} else if (itemName == 'column'){
					parentSelector = '.ct-pb-col-layout';
				}


				if (parentSelector) {
					if (itemName == 'column'){
						var row = $(this).parents('.ct-pb-row-layout'),
							colHolder = row.find(self.colHolderSelector),
							currentCols = colHolder.find('.ct-pb-col-layout'),
							numberOfCols= (currentCols.length - 1),
							col_size = (12/numberOfCols),
							sizeAddition = 0;

						if(numberOfCols == 5){
							col_size = 2;
							sizeAddition = 2;
						} else if(numberOfCols == 7){
							col_size = 1;
							sizeAddition = 5;
						} else if(numberOfCols == 8){
							col_size = 1;
							sizeAddition = 4;
						} else if(numberOfCols == 9){
							col_size = 1;
							sizeAddition = 3;
						} else if(numberOfCols == 10){
							col_size = 1;
							sizeAddition = 2;
						} else if(numberOfCols == 11){
							col_size = 1;
							sizeAddition = 1;
						} else if(numberOfCols == 12){
							col_size = 1;
						}

						currentCols.each(function(){
							var colSizeData = parseInt($(this).attr('data-col-size')),
								newColSize = col_size;
							
							$(this).removeClass('ct_pb_col-'+colSizeData);
							
							if(sizeAddition){
								newColSize += 1;
								
								sizeAddition -= 1;
							}

							var tempShortcodeStart = '['+self.args.col_sc+' col="'+newColSize+'"]', 
								tempShortcodeEnd = '[/'+self.args.col_sc+']';

							$(this).find('> .ct-pb-sc-start').html(tempShortcodeStart);
							$(this).find('> .ct-pb-sc-end').html(tempShortcodeEnd);

							$(this).find('.ct-pb-col-sz-txt').html(newColSize+'/12');
							
							$(this).attr('data-col-size', newColSize);
							$(this).addClass('ct_pb_col-'+newColSize);
						});

						$(this).parents(parentSelector).remove();


					} else {
						$(this).parents(parentSelector).remove();
					}
					
				}

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
				
			});


		},
		colSizeControl: function (){
			this.colSizeControlPlus();
			this.colSizeControlMinus();
		},
		colSizeControlPlus: function (){
			var self = this;

			self.pbContainer.on('click', 'a.ct-pb-col-sz-plus', function(e){
				e.preventDefault();

				var colContainer = $(this).parents('.ct-pb-row-container'),
					column = $(this).parents('.ct-pb-col-layout'),
					columnSize = parseInt(column.attr('data-col-size')),
					allCols = colContainer.find('.ct-pb-col-layout'),
					minusCol = column.next(),
					minusMode = 1;



				if ( columnSize >= 12 ) {
					return;
				}

				if (allCols.length == 12) {
					return;
				}

				if (!minusCol.length) {
					minusCol = column.prev();
					minusMode = 0;
				}

				if (!minusCol.length) {
					return;
				}



				for (var i = 1; i < allCols.length; i++) {

					
					if(parseInt(minusCol.attr('data-col-size')) == 1){
						if (minusMode) {
							var minusColTemp = minusCol.next();

							if (column.is(minusColTemp)) {
								minusColTemp = column.next();
							}

							if (minusColTemp.length) {
								minusCol = minusColTemp;
							} else if(!minusColTemp.length) {
								minusMode = 0;
							}
						} else {
							var minusColTemp = minusCol.prev();

							if (column.is(minusColTemp)) {
								minusColTemp = column.prev();
							}

							if (minusColTemp.length) {
								minusCol = minusColTemp;
							} else if(!minusColTemp.length) {
								minusMode = 1;
							}
						}
					} else {
						break;
					}
				}

				if (!minusCol.length || parseInt(minusCol.attr('data-col-size')) == 1) {
					return;
				}


				self.plusColSize(column, columnSize);
				self.minusColSize(minusCol, parseInt(minusCol.attr('data-col-size')));


				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');

			});
		},
		plusColSize: function ( column, columnSize ) {
			column.removeClass('ct_pb_col-'+columnSize);
			column.attr('data-col-size', ( columnSize + 1));
			column.addClass('ct_pb_col-'+( columnSize + 1));

			var oldScColumnStart = column.find('> .ct-pb-sc-start').html(),
				newScColumnStart = oldScColumnStart.replace('col="'+columnSize+'"', 'col="'+(columnSize + 1)+'"');

			column.find('> .ct-pb-sc-start').html(newScColumnStart);

			column.find('.ct-pb-col-sz-txt').html((columnSize + 1)+'/12');
		},
		colSizeControlMinus: function (){
			var self = this;

			self.pbContainer.on('click', 'a.ct-pb-col-sz-minus', function(e){
				e.preventDefault();

				var colContainer = $(this).parents('.ct-pb-row-container'),
					column = $(this).parents('.ct-pb-col-layout'),
					columnSize = parseInt(column.attr('data-col-size')),
					allCols = colContainer.find('.ct-pb-col-layout'),
					plusCol = column.next(),
					plusMode = 1;


				if ( columnSize <= 1 ) {
					return;
				}


				if (allCols.length == 12) {
					return;
				}

				if (!plusCol.length) {
					plusCol = column.prev();
					plusMode = 0;
				}

				if (!plusCol.length) {
					return;
				}



				/*for (var i = 1; i < allCols.length; i++) {

					
					if(parseInt(plusCol.attr('data-col-size')) == 1){
						if (plusMode) {
							var plusColTemp = plusCol.next();

							if (column.is(plusColTemp)) {
								plusColTemp = column.next();
							}

							if (plusColTemp.length) {
								plusCol = plusColTemp;
							} else if(!plusColTemp.length) {
								plusMode = 0;
							}
						} else {
							var plusColTemp = plusCol.prev();

							if (column.is(plusColTemp)) {
								plusColTemp = column.prev();
							}

							if (plusColTemp.length) {
								plusCol = plusColTemp;
							} else if(!plusColTemp.length) {
								plusMode = 1;
							}
						}
					} else {
						break;
					}
				}

				if (!plusCol.length || parseInt(plusCol.attr('data-col-size')) == 1) {
					return;
				}*/



				
				self.minusColSize(column, columnSize);
				self.plusColSize(plusCol, parseInt(plusCol.attr('data-col-size')));

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');


			});
		},
		minusColSize: function ( column, columnSize ) {
			column.removeClass('ct_pb_col-'+columnSize);
			column.attr('data-col-size', ( columnSize - 1));
			column.addClass('ct_pb_col-'+( columnSize - 1));


			var oldScColumnStart = column.find('> .ct-pb-sc-start').html(),
				newScColumnStart = oldScColumnStart.replace('col="'+columnSize+'"', 'col="'+(columnSize - 1)+'"');

			column.find('> .ct-pb-sc-start').html(newScColumnStart);

			column.find('.ct-pb-col-sz-txt').html((columnSize - 1)+'/12');
		},
		addElementInit: function(){
			var self = this;

			$('body').on('click', 'a.ct-pb-add-element', function (e){
				e.preventDefault();
				
				self.pagebuilderModalForm.html('');
				
				self.pagebuilderModalObj.addClass('ct-pb-remodal-form-off');

				var columnInner = $(this).parents('.ct-pb-col-inner'),
					container = columnInner.find('.ct-pb-col-container');

				// Clear all html from shortcode list container
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');

				self.pagebuilderModalObj.find('#modal-pb-title').text(self.args.pb_elements_title);

				_.each(ctf_pb_elements_data, function (element_data){
					
					if((typeof element_data.parent === 'undefined') && (typeof element_data.layout === 'undefined')){

						//modalItemTmpl
						var elementItem = self.modalItemTmpl({
							code: element_data.code,
							name: element_data.title,
							icon: element_data.icon,
							desc: element_data.subtitle,
							color: element_data.color
						});
	
						var elementItemObj = $(elementItem),
							allAtts = self.getAttsFromOptions(element_data.options),
							shortcode = self.getShortcodeByData(element_data.code, allAtts),
							elemData = {
								code: element_data.code,
								icon: element_data.icon,
								color: element_data.color,
								name: element_data.title,
								sc_start: shortcode
							};

						if(typeof element_data.child !== 'undefined'){
							elemData.sc_end = '[/'+element_data.code+']';
						}
							
						self.onClickModalItem(elementItemObj, container, elemData);
	
						self.pagebuilderModalObj.find('.mpb-elements-list').append(elementItemObj);
					
					}
				});


				self.pagebuilderModal.open();
			});
		},
		getShortcodeByData: function (code, atts){
			var shortcode = "["+code,
				shortcodeData = ctf_pb_elements_data[code],
				self = this,
				content;
			
			if( typeof atts !== 'undefined' && _.isObject(atts) && !_.isEmpty(atts) ){
				_.each(atts, function (value, name){
					var option = self.getFieldDataByID(name, shortcodeData.options);

					if ( typeof option.roll === 'undefined' ) {
						if( option.type == 'google_font' ){
							if(!_.isEmpty(value)){
								value = JSON.parse(value);
							}
						}
						
						if(option.type == 'dimension' && _.isArray(value)){
							if (!_.isEmpty(value[0])) {
								shortcode += ' '+name+'="'+value[0]+value[1]+'"';
							}
							
						} else if( _.isObject(value) && !_.isArray(value) ){
							_.each(value, function (newValue, newName){
								if (!_.isEmpty(newValue)) {
									shortcode += ' '+name+'_'+newName+'="'+newValue+'"';
								}
							});
						} else if( option.type == 'image' ){
							if(!_.isEmpty(value)){
								value = JSON.parse(value);
							}
							
							if( typeof value.url !== 'undefined' ){
								shortcode += ' '+name+'="'+value.url+'"';
							}
							
						} else if( option.type == 'image_multi' ){
							if(!_.isEmpty(value)){
								value = JSON.parse(value);
							}
							
							var imagesValue = [];
							
							if(_.isArray(value)){
								_.each(value, function(image){
									if( typeof image.url !== 'undefined' ){
										imagesValue.push(image.url);
									}
								});
							}
							
							shortcode += ' '+name+'="'+imagesValue+'"';
							
						} else {
							if (!_.isEmpty(value)) {
								shortcode += ' '+name+'="'+value+'"';
							}
							
						}
					} else {
						content = value;

					}
					
					
					
				});
			}
			
			shortcode += "]";
			
			if( typeof content !== 'undefined'){
				shortcode += content;
				shortcode += "[/"+code+"]";
			}

			
			return shortcode;
		},
		getAttsFromOptions: function(options){
			var atts = {};
			if( typeof options !== 'undefined' && !_.isEmpty(options) ){
				_.each(options, function (option){
					atts[option['id']] = option['default'];
				});
			}
			
			return atts;
		},
		getFieldDataByID: function(name, options){
			var optionReturn = false;
			
			_.each(options, function (option){
				if(option.id == name){
					optionReturn = option;
				}
			});
			
			return optionReturn;
		},
		onClickModalItem: function(selector, container, data){
			var self = this;

			selector.on('click', function (e){
				e.preventDefault();

				if(typeof ctf_pb_elements_data[data.code].child === 'undefined'){

					var elementHtml = self.elementItemTmpl(data),
						elementObj = $(elementHtml),
						element_args = ctf_pb_elements_data[data.code];
						
					// Clear Element List and Element Form HTML
					self.pagebuilderModalObj.find('.mpb-elements-list').html('');
					self.pagebuilderModalForm.html('');
					
					self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');
					
					var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, element_args.options, data.code);


					self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
					self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
						var formData = self.pagebuilderModalForm.serializeObject(),
							shortcode = '';
						
						if(typeof formData[data.code] !== 'undefined'){
							shortcode = self.getShortcodeByData(data.code, formData[data.code]);
						}
						
						

						elementObj.find("> .ct-pb-sc-code").html(shortcode);
						
						/**
						 * Event: ctpbchanged
						 * Input page builder shortcodes to tinyMCE
						 */
						$('body').trigger('ctpbchanged');
						
						self.pagebuilderModal.close();
					});
					

					container.append(elementObj);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
				} else {
					var elementHtml = self.elementContainerTmpl(data),
						elementObj = $(elementHtml),
						element_args = ctf_pb_elements_data[data.code];

					// Clear Element List and Element Form HTML
					self.pagebuilderModalObj.find('.mpb-elements-list').html('');
					self.pagebuilderModalForm.html('');

					self.childElemContSortable(elementObj);

					container.append(elementObj);


					self.pagebuilderModal.close();

					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
				}
			});
		},
		onElementEditInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-item-edit-btn', function(e){
				e.preventDefault();
				
				self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');

				var elementObj = $(this).parents('.ct-pb-element-item');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');
				}

				var tag = elementObj.data('code'),
					shortcode = elementObj.find('> .ct-pb-sc-code.ct-pb-sc-start').html(),
					options = self.getScOptionsFromSc(_.unescape(shortcode), tag),
					elementTitle = ctf_pb_elements_data[tag].title;


				console.log(ctf_pb_elements_data[tag].title);

				// Clear Element List and Element Form HTML
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');
				self.pagebuilderModalForm.html('');

				self.pagebuilderModalObj.find('#modal-pb-title').text(elementTitle);

				var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, options, tag);

				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
					var formData = self.pagebuilderModalForm.serializeObject(),
						shortcode = '';
					
					if(typeof formData[tag] !== 'undefined'){
						shortcode = self.getShortcodeByData(tag, formData[tag]);
					}
					

					elementObj.find("> .ct-pb-sc-code.ct-pb-sc-start").html(shortcode);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
					
					self.pagebuilderModal.close();
				});

				self.pagebuilderModal.open();
			});
		},
		onElementCopyInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-item-copy-btn', function(e){
				e.preventDefault();

				var elementObj = $(this).parents('.ct-pb-element-item');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');


				}

				var newElementObj = elementObj.clone();

				self.childElemContSortable(newElementObj);

				newElementObj.insertAfter(elementObj);
				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			});
		},
		onElementDeleteInit: function(){
			var self = this;

			$('body').on('click', '.ct-pb-item-delete', function(e){
				e.preventDefault();

				var elementObj = $(this).parents('.ct-pb-element-item');

				if (elementObj.length == 0) {
					elementObj = $(this).parents('.ct-pb-element-container');
				}

				if (elementObj.length != 0) {
					elementObj.remove();

					$('body').trigger('ctpbchanged');
				}
			});
		},
		getScOptionsFromSc: function(shortcode, tag){
			var element_args = ctf_pb_elements_data[tag],
				options = _.map(element_args.options, _.clone);

				

			_.each(options, function(option, i){
				if (typeof option.roll === 'undefined') {
					// var scAttrPatt =  new RegExp(option.id+'=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?'),
					var scAttrPatt =  new RegExp(option.id+'="(.*?)"'),
						machedVal = shortcode.match(scAttrPatt);
					
					if (
						option.type == 'text_multi' ||
						option.type == 'checkbox' ||
						option.type == 'checkbox_image' ||
						option.type == 'checkbox_button'
						) {
						options[i].default = machedVal[1].split(",");
					} else if(option.type == 'font_style'){
						scAttrPatt =  new RegExp(option.id+'_(bold|italic|underline|strikethrough)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?', 'g'),
						machedVal = shortcode.match(scAttrPatt);
						var valueAll = {};
						
						_.each(machedVal, function(machedValNew){
							var scAttrPattNew =  new RegExp(option.id+'_(bold|italic|underline|strikethrough)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?'),
								newMachedVal = machedValNew.match(scAttrPattNew);
								
							valueAll[newMachedVal[1]] = (newMachedVal[2]) ? 'on' : 'off';
						});
						
						if(typeof valueAll.bold == 'undefined'){
							valueAll['bold'] = 'off';
						}
						
						if(typeof valueAll.italic == 'undefined'){
							valueAll['italic'] = 'off';
						}
						
						if(typeof valueAll.underline == 'undefined'){
							valueAll['underline'] = 'off';
						}
						
						if(typeof valueAll.strikethrough == 'undefined'){
							valueAll['strikethrough'] = 'off';
						}
						
						options[i].default = valueAll;
						
						
					} else if(option.type == 'google_font'){
						scAttrPatt =  new RegExp(option.id+'_(font-family|font-weight|font-size|line-height|letter-spacing|word-spacing)="(.*?)"', 'g'),
						machedVal = shortcode.match(scAttrPatt);
						
						var valueAll = {};
						
						_.each(machedVal, function(machedValNew){
							var scAttrPattNew =  new RegExp(option.id+'_(font-family|font-weight|font-size|line-height|letter-spacing|word-spacing)="(.*?)"'),
								newMachedVal = machedValNew.match(scAttrPattNew);
								
							valueAll[newMachedVal[1]] = newMachedVal[2];
						});
						
						options[i].default = valueAll;
						
					} else if(option.type == 'image'){
						options[i].default = {};

						if( !_.isNull(machedVal) ){
							options[i].default['url'] = machedVal[1];
						}
						
					} else if(option.type == 'image_multi'){
						options[i].default = [];

						if( !_.isNull(machedVal) ){
							_.each(machedVal[1].split(','), function(imageUrl){
								var image = {
									url: imageUrl
								};
								
								options[i].default.push(image);
							});
						}
						
					} else if(option.type == 'color'){
						if (!_.isNull(machedVal)) {
							options[i].defaultValue = options[i].default;
							options[i].default = machedVal[1];
						}
					} else {
						
						if (!_.isNull(machedVal)) {
							options[i].default = machedVal[1];
						}
						//options[i].default = machedVal[1];
					}
				} else {
					// var scAttrPatt =  new RegExp('\\[('+tag+').*?\\](.*?)\\[\\/\\1\\]'),
					var scAttrPatt =  new RegExp('\\[('+tag+').*?\\]([^()]+)\\[\\/\\1\\]'),
						machedVal = shortcode.match(scAttrPatt);

					if (_.isNull(machedVal)) {
						options[i].default = '';
					} else {
						options[i].default = machedVal[2];
					}
					
				}
				
			});

			return options;
		},
		onClickChildItemAdd: function(){
			var self = this;

			$('body').on('click', '.ct-pb-add-sub-element', function(e){
				e.preventDefault();
				
				self.pagebuilderModalObj.removeClass('ct-pb-remodal-form-off');

				var elemContainer = $(this).parents('.ct-pb-element-container'),
					parentTag = elemContainer.data('code'),
					parentItemData = ctf_pb_elements_data[parentTag],
					childTag = parentItemData.child,
					childItemData = ctf_pb_elements_data[parentItemData.child],
					childAllAtts = self.getAttsFromOptions(childItemData.options),
					childShortcode = self.getShortcodeByData(childItemData.code, childAllAtts),
					childElemData = {
						code: childItemData.code,
						icon: childItemData.icon,
						name: childItemData.title,
						sc_start: childShortcode
					};

				var childElementObj = $(self.elementItemTmpl(childElemData))

				

				// Clear Element List and Element Form HTML
				self.pagebuilderModalObj.find('.mpb-elements-list').html('');
				self.pagebuilderModalForm.html('');

				var field_obj = new CTF_Core.CTF_PageBuilder(self.pagebuilderModalForm, childItemData.options, childItemData.code);

				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').off('click');
				self.pagebuilderModalObj.find('.modal-pb-buttons #add_ctpb_sc_to_item').on('click', function(){
					var formData = self.pagebuilderModalForm.serializeObject(),
						shortcode = '';
					
					if(typeof formData[childItemData.code] !== 'undefined'){
						shortcode = self.getShortcodeByData(childItemData.code, formData[childItemData.code]);
					}

					

					childElementObj.find("> .ct-pb-sc-code.ct-pb-sc-start").html(shortcode);
					
					/**
					 * Event: ctpbchanged
					 * Input page builder shortcodes to tinyMCE
					 */
					$('body').trigger('ctpbchanged');
					
					self.pagebuilderModal.close();
				});


				elemContainer.find('.ct-pb-elem-cont-container').append(childElementObj);

				self.pagebuilderModal.open();

				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');

			});
		},
		containerSortableInit: function(){
			var self = this;

			self.sortableInit(self.sectionHolder, {
				cursor: "move",
				axis: "y",
				handle: ".ct-pb-drag",
			});
		},
		sectionContainerSortable: function( section ){
			var self = this;

			self.sortableInit(section.find('.ct-pb-section-container'), {
				cursor: "move",
				axis: "y",
				handle: ".ct-pb-drag",
				connectWith: ".ct-pb-section-container"
			});
		},
		rowContainerSortable: function( row ){
			var self = this;

			self.sortableInit(row.find('.ct-pb-row-container'), {
				cursor: "move",
				//axis: "x",
				handle: ".ct-pb-drag",
				connectWith: '.ct-pb-row-container',
				sort: function(event, ui) {
	                var sortInst = $(this).sortable('instance');

	                if(typeof sortInst.ctpbOffset === 'undefined' || !sortInst.ctpbOffset){
	                    sortInst.offset.click = {
	                        left: Math.floor(ui.helper.width() / 2),
	                        top: Math.floor(ui.helper.height() / 2)
	                    };

	                    sortInst.ctpbOffset = true;
	                }
	            },
	            stop: function (event, ui){
	                var sortInst = $(this).sortable('instance');
	                if(typeof sortInst.ctpbOffset !== 'undefined'){
	                    sortInst.ctpbOffset = false;
	                }
	            }
			});
		},
		colContainerSortable: function( col ){
			var self = this;

			self.sortableInit(col.find('.ct-pb-col-container'), {
				cursor: "move",
				// handle: ".ct-pb-drag",
				connectWith: '.ct-pb-col-container',
				cursorAt:{top:25,left:75},
				tolerance:"pointer",
				revert: true,
				distance: 5
			});
		},
		childElemContSortable: function( container ){
			var self = this;

			self.sortableInit(container.find('.ct-pb-elem-cont-container'), {
				cursor: "move",
				// handle: ".ct-pb-drag",
				connectWith: '.ct-pb-elem-cont-container',
				cursorAt:{top:25,left:75},
				tolerance:"pointer",
				revert: true,
				distance: 5
			});
		},
		sortableInit: function(selector, args){
			var sortableArgs = {};

			if (typeof args !== 'undefined') {
				sortableArgs = args;
			}

			sortableArgs.stop = function(event, ui){
				/**
				 * Event: ctpbchanged
				 * Input page builder shortcodes to tinyMCE
				 */
				$('body').trigger('ctpbchanged');
			};

			if (typeof selector === 'string') {
				$(selector).sortable(sortableArgs);
			} else if(typeof selector === 'object'){
				selector.sortable(sortableArgs);
			}
			
		},
		getAllShortcodesForPage: function (){

			var content_sc = '';

			$('.ct-pb-sc-code').each(function (){
				content_sc += $(this).html();
			});

			if(typeof tinyMCE !== 'undefined'){
				var editor = tinyMCE.get('content');

				if(!_.isNull(editor)){
					editor.setContent(_.unescape(content_sc));
				} else {
					$('#content').html(content_sc);
				}
			} else {
				$('#content').html(content_sc);
			}
		}
	});
	
	


	if (typeof ct_pb_args !== 'undefined') {
		var pb = new CTF_PB.Core(ct_pb_args);
	}

	$('body').on('click', 'button#ct-pb-fullscreen', function(e){
        e.preventDefault();


        $('#ct-pb-container').toggleClass('ct-pb-fs-active');
        $('body').toggleClass('ct-pb-fs-active-body');
        $(this).toggleClass('ct-pb-btn-active');
    });

})(jQuery, CTF_Core);