/* global MB_Core */
window.MB_Core = window.MB_Core || {};

(function( exports, $ ){
    "use strict";
    
    // Shared empty constructor function to aid in prototype-chain creation.
	var ctor = function() {};
    
    /**
	 * Helper function to correctly set up the prototype chain, for subclasses.
	 * Similar to `goog.inherits`, but uses a hash of prototype properties and
	 * class properties to be extended.
	 *
	 * @param  object parent      Parent class constructor to inherit from.
	 * @param  object protoProps  Properties to apply to the prototype for use as class instance properties.
	 * @param  object staticProps Properties to apply directly to the class constructor.
	 * @return child              The subclassed constructor.
	 */
	var inherits = function( parent, protoProps, staticProps ) {
		var child;

		// The constructor function for the new subclass is either defined by you
		// (the "constructor" property in your `extend` definition), or defaulted
		// by us to simply call `super()`.
		if ( protoProps && protoProps.hasOwnProperty( 'constructor' ) ) {
			child = protoProps.constructor;
		} else {
			child = function() {
				// Storing the result `super()` before returning the value
				// prevents a bug in Opera where, if the constructor returns
				// a function, Opera will reject the return value in favor of
				// the original object. This causes all sorts of trouble.
				var result = parent.apply( this, arguments );
				return result;
			};
		}

		// Inherit class (static) properties from parent.
		$.extend( child, parent );

		// Set the prototype chain to inherit from `parent`, without calling
		// `parent`'s constructor function.
		ctor.prototype  = parent.prototype;
		child.prototype = new ctor();

		// Add prototype properties (instance properties) to the subclass,
		// if supplied.
		if ( protoProps )
			$.extend( child.prototype, protoProps );

		// Add static properties to the constructor function, if supplied.
		if ( staticProps )
			$.extend( child, staticProps );

		// Correctly set child's `prototype.constructor`.
		child.prototype.constructor = child;

		// Set a convenience property in case the parent's prototype is needed later.
		child.__super__ = parent.prototype;

		return child;
	};
    
   
   /**
	 * Base class for object inheritance.
	 */
	MB_Core.Class = function( applicator, argsArray, options ) {
		var magic, args = arguments;

		if ( applicator && argsArray && MB_Core.Class.applicator === applicator ) {
			args = argsArray;
			$.extend( this, options || {} );
		}

		magic = this;

		/*
		 * If the class has a method called "instance",
		 * the return value from the class' constructor will be a function that
		 * calls the "instance" method.
		 *
		 * It is also an object that has properties and methods inside it.
		 */
		if ( this.instance ) {
			magic = function() {
				return magic.instance.apply( magic, arguments );
			};

			$.extend( magic, this );
		}

		magic.initialize.apply( magic, args );
		return magic;
	};
	
	/**
	 * Creates a subclass of the class.
	 *
	 * @param  object protoProps  Properties to apply to the prototype.
	 * @param  object staticProps Properties to apply directly to the class.
	 * @return child              The subclass.
	 */
	MB_Core.Class.extend = function( protoProps, classProps ) {
		var child = inherits( this, protoProps, classProps );
		child.extend = this.extend;
		return child;
	};

	MB_Core.Class.applicator = {};

	/**
	 * Initialize a class instance.
	 *
	 * Override this function in a subclass as needed.
	 */
	MB_Core.Class.prototype.initialize = function() {};

	/*
	 * Checks whether a given instance extended a constructor.
	 *
	 * The magic surrounding the instance parameter causes the instanceof
	 * keyword to return inaccurate results; it defaults to the function's
	 * prototype instead of the constructor chain. Hence this function.
	 */
	MB_Core.Class.prototype.extended = function( constructor ) {
		var proto = this;

		while ( typeof proto.constructor !== 'undefined' ) {
			if ( proto.constructor === constructor )
				return true;
			if ( typeof proto.constructor.__super__ === 'undefined' )
				return false;
			proto = proto.constructor.__super__;
		}
		return false;
	};

	// Gen. Class
	MB_Core.Opts = MB_Core.Class.extend({
		inputArgs: {},
		container: '',
		containerObj: '',
		initialize: function ( container, args ){
	    	this.inputArgs = args;
	    	this.container = container;
	    	this.containerObj = container;

	    	this.renderContent();
	    },
	    renderContent: function(){
	    	var tmpThis = this;
	    	_.each(this.inputArgs, function ( field, key, mbox_full ) {
	    		tmpThis.renderInput(field);
	    	});
	    },
	    renderInput: function(args){
	    	var inputFieldClss = MB_Core.Api['mb_'+args.type],
	    		inputField;


	    	if ( typeof inputFieldClss !== 'undefined' ) {
	    		inputField = new inputFieldClss(args.type, args, this.containerObj);

		    	inputField.inputNameAttr = 'name="'+this.getNameAttr( args.type, args.id )+'"';

		    	inputField.inputValue = this.getInputValue( args.type, args.id, args.default);

		    	inputField.renderContent();
	    	}
	    },
	    getNameAttr: function ( type, id ){
	    	return '';
	    },
	    getInputValue: function ( type, id, defValue ){
	    	return '';
	    }
	});

	// Input Class
	MB_Core.Input = MB_Core.Class.extend({
		inputId: '',
		inputArgs: {},
		inputNameAttr: '',
		inputValue: '',
		container: '',
		containerId: '',
		inputObj: {},
	    initialize: function ( id, args, container, containerId ){
	    	var input = this;
	    	input.inputId = id;
	    	input.inputArgs = args;
	    	input.container = container;
	    	input.containerId = containerId;

	    	input.deferred = {
				renderContent: new $.Deferred()
			};

			input.deferred.renderContent.done( function () {
				input.ready();
			});

	    },
	    ready: function(){},
	    renderContent: function(){
	    	var field_tmpl = wp.template( 'mb-field-'+this.inputId );


            
            
            

            if ( typeof this.inputArgs.choices == 'undefined' ) {
                this.inputArgs.choices = '';
            } else {
                this.inputArgs.choices = this.inputArgs.choices;
            }

            /* Value of input */
            this.inputArgs.value = this.inputValue;

            /* Set input linking value (example Name attr) */
            this.inputArgs.link = this.inputNameAttr;

            if (typeof this.inputArgs.defaultValue !== 'undefined') {
            	this.inputArgs.defaultValue  = this.inputArgs.defaultValue;
            } else {
            	this.inputArgs.defaultValue  = this.inputArgs.default;
            }

            this.inputObj = $(field_tmpl(this.inputArgs));

	    	this.container.append(this.inputObj);

	    	this.deferred.renderContent.resolve();
	    }
	});
	
	MB_Core.Api = {};
	
	// Text input
	MB_Core.Api.mb_text = MB_Core.Input.extend({});

	MB_Core.Api.mb_readonly = MB_Core.Input.extend({});

	// Email input
	MB_Core.Api.mb_email = MB_Core.Input.extend({});

	// Textarea input
	MB_Core.Api.mb_textarea = MB_Core.Input.extend({});

	// URL input
	MB_Core.Api.mb_url = MB_Core.Input.extend({});

	// Password Input
	MB_Core.Api.mb_password = MB_Core.Input.extend({});

	// Radio Input
	MB_Core.Api.mb_radio = MB_Core.Input.extend({});

	// Radio Image Input
	MB_Core.Api.mb_radio_image = MB_Core.Input.extend({});

	// Radio Button Input
	MB_Core.Api.mb_radio_button = MB_Core.Input.extend({});

	// Checkbox Input
	MB_Core.Api.mb_checkbox = MB_Core.Input.extend({});

	// Checkbox Image Input
	MB_Core.Api.mb_checkbox_image = MB_Core.Input.extend({});

	// Checkbox Button Input
	MB_Core.Api.mb_checkbox_button = MB_Core.Input.extend({});

	// Number input
	MB_Core.Api.mb_number = MB_Core.Input.extend({
		ready: function (){
			var numberInput = this.inputObj.find( 'input' ),
	            inputStep = numberInput.attr('step');

			if (!inputStep) {
				inputStep = 1
			}

			$( numberInput ).spinner({
				step: inputStep
			});
		}
	});

	MB_Core.Api.mb_date = MB_Core.Input.extend({
		ready: function (){
			var dateInput = this.inputObj.find('input.mb-date-field'),
				optionsEn = dateInput.data('options'),
				options = {};

			if (!_.isEmpty(optionsEn)) {
				options = JSON.parse(window.atob(optionsEn));
			}

	        setTimeout(function () {
	        	dateInput.datepicker(options);
	        }, 50);
		}
	});

	// Color input
	MB_Core.Api.mb_color = MB_Core.Input.extend({
		ready: function (){
			var colorInput = this.inputObj.find('input.mb-color-field');

	        setTimeout(function () {
	        	colorInput.wpColorPicker({
	        		width: 310,
	        	});
	        }, 50);
		}
	});


	// RGBA color input
	MB_Core.Api.mb_color_rgba = MB_Core.Input.extend({
		ready: function (){
			var colorInput = this.inputObj.find('input.mb-rgba-color-field'),
	            value = colorInput.val().replace(/\s+/g, '');


	        if ( typeof Color !== "undefined" ) {
	            Color.prototype.toString = function(remove_alpha) {
	                if (remove_alpha == 'no-alpha') {
	                    return this.toCSS('rgba', '1').replace(/\s+/g, '');
	                }
	                if (this._alpha < 1) {
	                    return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
	                }
	                var hex = parseInt(this._color, 10).toString(16);
	                if (this.error) return '';
	                if (hex.length < 6) {
	                    for (var i = 6 - hex.length - 1; i >= 0; i--) {
	                        hex = '0' + hex;
	                    }
	                }
	                return '#' + hex;
	            }

	            colorInput.wpColorPicker({ // change some things with the color picker
	            	width: 310,
	                clear: function(event, ui) {
	                    // TODO reset Alpha Slider to 100
	                    colorInput.val('');
	                },
	                change: function(event, ui) {
	                    // send ajax request to wp.customizer to enable Save & Publish button
	                    var _new_value = colorInput.val();

	                    // change the background color of our transparency container whenever a color is updated
	                    var $transparency = colorInput.parents('.wp-picker-container:first').find('.transparency');
	                    // we only want to show the color at 100% alpha
	                    $transparency.css('backgroundColor', ui.color.toString('no-alpha'));
	                },
	            });


	            $('<div class="mb-alpha-container"><div class="mb-alpha-container-inner"><div class="slider-alpha"></div><div class="transparency"></div></div></div>').appendTo(colorInput.parents('.wp-picker-container'));

	            var alphaSlider = colorInput.parents('.wp-picker-container:first').find('.slider-alpha');

	            // if in format RGBA - grab A channel value
	            if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
	                var alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
	                var alpha_val = parseInt(alpha_val);
	            } else {
	                var alpha_val = 100;
	            }
	            alphaSlider.slider({
	                slide: function(event, ui) {
	                    $(this).find('.ui-slider-handle').html('<span class="mb-rgba-val-pop">'+(ui.value / 100)+'</span>'); // show value on slider handle
	                    // send ajax request to wp.customizer to enable Save & Publish button
	                    var _new_value = colorInput.val();
	                },
	                create: function(event, ui) {
	                    var v = $(this).slider('value'),
	                        bgColor = colorInput.parents('.wp-picker-container:first').find('.transparency'),
	                        iris = colorInput.data('a8cIris');
	                    bgColor.css('backgroundColor', iris._color.toString('no-alpha'));

	                    $(this).find('.ui-slider-handle').html('<span class="mb-rgba-val-pop">'+(v / 100)+'</span>');
	                },
	                value: alpha_val,
	                range: "max",
	                step: 1,
	                min: 1,
	                max: 100
	            }); // slider
	            alphaSlider.slider().on('slidechange', function(event, ui) {
	                var new_alpha_val = parseFloat(ui.value),
	                    iris = colorInput.data('a8cIris'),
	                    color_picker = colorInput.data('wpWpColorPicker');
	                iris._color._alpha = new_alpha_val / 100.0;
	                colorInput.val(iris._color.toString());
	                color_picker.toggler.css({
	                    backgroundColor: colorInput.val()
	                });
	                // fix relationship between alpha slider and the 'side slider not updating.
	                var get_val = colorInput.val();
	                $(colorInput).wpColorPicker('color', get_val);
	            });
	            
	            
	            colorInput.data('wpWpColorPicker').button.on( 'click', function() {
	                
	                if($(this).hasClass( 'wp-picker-default' )){
	                    var iris = colorInput.data('a8cIris');

	                    alphaSlider.slider( "value", iris._color._alpha*100 );
	                    alphaSlider.find('.ui-slider-handle').html('<span class="mb-rgba-val-pop">'+(iris._color._alpha)+'</span>');
	                }
	            });
	        }
		}
	});

	// Icon Picker Input
	MB_Core.Api.mb_icon = MB_Core.Input.extend({
		ready: function (){
			var control = this,
	            iconPicker = this.inputObj.find('.ct-iconpicker'),
	            iconHolerIcon = this.inputObj.find( '.ct-iconpicker .ct-ip-holder .ct-ip-icon' ),
	            iconPickerPopup = this.inputObj.find( '.ct-iconpicker .ct-ip-popup' ),
	            inputField = this.inputObj.find('.ct-icon-value'),
	            iconHolderI = this.inputObj.find('.ct-ip-icon i'),
	            iconPickerPopupUl = this.inputObj.find('.ct-iconpicker .ct-ip-popup ul'),
	            inputSearch = iconPickerPopup.find('input.ct-ip-search-input');


	        iconHolerIcon.on('click', function(){
	            iconPickerPopup.slideToggle();
	            
	            inputSearch.val('');
	            inputSearch.trigger('change');
	        });
	        
	        iconPickerPopup.on('change keyup paste', 'input.ct-ip-search-input', function (e) {
	            var searchVal = $(this).val();
	            
	            if( _.isEmpty(searchVal) ){
	                iconPickerPopupUl.find('li').removeClass('mb-hidden');
	            } else {
	                iconPickerPopupUl.find('li').addClass('mb-hidden');
	            
	                var found = iconPickerPopupUl.find('li a[data-tooltip*="'+searchVal.toLowerCase()+'"]');
	                found.parent('li').removeClass('mb-hidden');
	            }
	        });
	        
	        iconPickerPopup.on('click', 'a', function (e) {
	            e.preventDefault();
	            
	            var iconClass = $(this).data('icon');
	            
	            iconHolderI.attr('class', '');
	            iconHolderI.addClass(iconClass);
	            inputField.val(iconClass);
	            
	            iconPickerPopup.find('.mb-selected').removeClass('mb-selected');
	            
	            $(this).addClass('mb-selected');
	            
	        });
	        
	        $(document).mouseup(function (e){
	            if ( ( ! iconPicker.is(e.target) && iconPicker.has(e.target).length === 0 ) ){
	                iconPickerPopup.slideUp();
	                
	                inputSearch.val('');
	                inputSearch.trigger('change');
	            }
	        });
		}
	});

	// Multi-Text Input
	MB_Core.Api.mb_text_multi = MB_Core.Input.extend({
		ready: function (){
			var inputItemsContainer = this.inputObj.find('.mb-mt-input-container'),
	            inputItemsTmpl = this.inputObj.find('.mb-mt-tmpl'),
	            inputItemAddNew = this.inputObj.find('.mb-mt-add-new');

	        inputItemAddNew.on('click', function (e) {
	            e.preventDefault();
	            var nameFullAttr = $(this).data('name'),
	            	regExpNameAttr = /name="(.*?)"/g,
	            	nameAttrVal = regExpNameAttr.exec(nameFullAttr)[1];

	            var newInput = $(inputItemsTmpl.clone());

	            newInput.removeClass('mb-hidden');
	            newInput.removeClass('mb-mt-tmpl');
	            newInput.find('input[type="text"]').attr('name', nameAttrVal);
	            inputItemsContainer.append(newInput);
	            
	        });

	        inputItemsContainer.on( 'click', '.mb-mt-input-delete', function(e) {
	            e.preventDefault();

	            var thisContainer = $(this).parent('.mb-mt-input-item');

	            thisContainer.remove();

	        });
		}
	});

	// Dimension Input
	MB_Core.Api.mb_dimension = MB_Core.Input.extend({
		ready: function (){
			var numberInput = this.inputObj.find( 'input' ),
	            selectUnit = this.inputObj.find( 'select' ),
	            inputStep = numberInput.attr('step');

	        if (!inputStep) {
	            inputStep = 1;
	        }

	        $( numberInput ).spinner({
	            step: inputStep
	        });


	        $( selectUnit ).selectize();
		}
	});

	// Range Input
	MB_Core.Api.mb_range = MB_Core.Input.extend({
		ready: function (){
			var rangeInput = this.inputObj.find('input[type="range"]'),
	            textInput = this.inputObj.find('input[type="number"]'),
	            inputStep = textInput.attr('step');

	        if (!inputStep) {
	            inputStep = 1;
	        }

	        $( textInput ).spinner({
	            step: inputStep
	        });

	        this.inputObj.on( 'change keyup paste', 'input[type="number"]', function() {
	            rangeInput.val(textInput.val());
	        });

	        this.inputObj.on( 'click', '.ui-spinner-button', function() {
	            rangeInput.val(textInput.val());
	        });

	        this.inputObj.on( 'change keyup', 'input[type="range"]', function() {
	            textInput.val($( this ).val());
	        });
		}
	});

	// Select Input
	MB_Core.Api.mb_select = MB_Core.Input.extend({
		ready: function (){
			var selectInput = this.inputObj.find( 'select' );

        	$( selectInput ).selectize();
		}
	});
	
	MB_Core.Api.mb_google_font = MB_Core.Input.extend({
		ready: function (){
			var ffInput = this.inputObj.find('.mb-gf-ff-input'),
				fwInput = this.inputObj.find('.mb-gf-fw-input'),
				fzValueInput = this.inputObj.find('.mb-gf-fz-value-input'),
				fzUnitInput = this.inputObj.find('.mb-gf-fz-unit-input'),
				lhValueInput = this.inputObj.find('.mb-gf-lh-value-input'),
				lhUnitInput = this.inputObj.find('.mb-gf-lh-unit-input'),
				lsValueInput = this.inputObj.find('.mb-gf-ls-value-input'),
				lsUnitInput = this.inputObj.find('.mb-gf-ls-unit-input'),
				wsValueInput = this.inputObj.find('.mb-gf-ws-value-input'),
				wsUnitInput = this.inputObj.find('.mb-gf-ws-unit-input'),
				inputValInput = this.inputObj.find('.mb-gf-input-val'),
				allNewVals = {};


			$( ffInput ).selectize();
			$( fwInput ).selectize();

			$( fzUnitInput ).selectize();
			$( lhUnitInput ).selectize();
			$( lsUnitInput ).selectize();
			$( wsUnitInput ).selectize();

			$( fzValueInput ).spinner();
			$( lhValueInput ).spinner();
			$( lsValueInput ).spinner();
			$( wsValueInput ).spinner();
			
			if (ffInput.size()) {
				allNewVals['font-family'] = ffInput.val();
			}

			if (fwInput.size()) {
				allNewVals['font-weight'] = fwInput.val();
			}

			if (fzValueInput.size() && fzValueInput.val()) {
				allNewVals['font-size'] = fzValueInput.val()+fzUnitInput.val();
			}

			if (lhValueInput.size() && lhValueInput.val()) {
				allNewVals['line-height'] = lhValueInput.val()+lhUnitInput.val();
			}

			if (lsValueInput.size() && lsValueInput.val()) {
				allNewVals['letter-spacing'] = lsValueInput.val()+lsUnitInput.val();
			}

			if (wsValueInput.size() && wsValueInput.val()) {
				allNewVals['word-spacing'] = wsValueInput.val()+wsUnitInput.val();
			}




			ffInput.on( 'change', function() {
				var fwInputVal = fwInput.val(),
					fwArray = mb_google_fonts[ffInput.val()], /* global mb_google_fonts */
					fwNewOption = '';

				// control.renderContent()

				if ( ! _.isEmpty( fwArray ) ) {
					_.each(fwArray, function ( value, key, list ) {
						var selected = '';
						if ((fwInputVal == value) || (_.isEmpty(fwInputVal) && key == 0)) {
							selected = 'selected';
						};
						console.log((fwInputVal == value) || (_.isEmpty(fwInputVal) && key == 0));
						fwNewOption += '<option value="'+value+'" '+selected+'>'+value+'</option>';
					});

					

					var refreshData = $(fwInput).data('selectize');

					refreshData.destroy();
					
					fwInput.html(fwNewOption);

					$( fwInput ).selectize();

				}
				
				allNewVals['font-family'] = ffInput.val();
				allNewVals['font-weight'] = fwInput.val();
				
				inputValInput.val( JSON.stringify(allNewVals) );
			});
			
			this.inputObj.on( 'change keyup paste', 'input[type="number"]', function() {
				if (ffInput.size()) {
					allNewVals['font-family'] = ffInput.val();
				}

				if (fwInput.size()) {
					allNewVals['font-weight'] = fwInput.val();
				}

				if (fzValueInput.size() && fzValueInput.val()) {
					allNewVals['font-size'] = fzValueInput.val()+fzUnitInput.val();
				}

				if (lhValueInput.size() && lhValueInput.val()) {
					allNewVals['line-height'] = lhValueInput.val()+lhUnitInput.val();
				}

				if (lsValueInput.size() && lsValueInput.val()) {
					allNewVals['letter-spacing'] = lsValueInput.val()+lsUnitInput.val();
				}

				if (wsValueInput.size() && wsValueInput.val()) {
					allNewVals['word-spacing'] = wsValueInput.val()+wsUnitInput.val();
				}
	            inputValInput.val( JSON.stringify(allNewVals) );
			});

			this.inputObj.on( 'click', '.ui-spinner-button', function() {
				if (ffInput.size()) {
					allNewVals['font-family'] = ffInput.val();
				}

				if (fwInput.size()) {
					allNewVals['font-weight'] = fwInput.val();
				}

				if (fzValueInput.size() && fzValueInput.val()) {
					allNewVals['font-size'] = fzValueInput.val()+fzUnitInput.val();
				}

				if (lhValueInput.size() && lhValueInput.val()) {
					allNewVals['line-height'] = lhValueInput.val()+lhUnitInput.val();
				}

				if (lsValueInput.size() && lsValueInput.val()) {
					allNewVals['letter-spacing'] = lsValueInput.val()+lsUnitInput.val();
				}

				if (wsValueInput.size() && wsValueInput.val()) {
					allNewVals['word-spacing'] = wsValueInput.val()+wsUnitInput.val();
				}

	            inputValInput.val( JSON.stringify(allNewVals) );
			});

			this.inputObj.on( 'change', 'select:not(.mb-gf-ff-input)', function() {
				if (ffInput.size()) {
					allNewVals['font-family'] = ffInput.val();
				}

				if (fwInput.size()) {
					allNewVals['font-weight'] = fwInput.val();
				}

				if (fzValueInput.size() && fzValueInput.val()) {
					allNewVals['font-size'] = fzValueInput.val()+fzUnitInput.val();
				}

				if (lhValueInput.size() && lhValueInput.val()) {
					allNewVals['line-height'] = lhValueInput.val()+lhUnitInput.val();
				}

				if (lsValueInput.size() && lsValueInput.val()) {
					allNewVals['letter-spacing'] = lsValueInput.val()+lsUnitInput.val();
				}

				if (wsValueInput.size() && wsValueInput.val()) {
					allNewVals['word-spacing'] = wsValueInput.val()+wsUnitInput.val();
				}
				
				inputValInput.val( JSON.stringify(allNewVals) );
			});
		}
	});
	MB_Core.Api.mb_font_style = MB_Core.Input.extend({});
	MB_Core.Api.mb_text_align = MB_Core.Input.extend({});
	MB_Core.Api.mb_image = MB_Core.Input.extend({
		ready: function (){
			var $this = this;
			
			this.addBtn = this.inputObj.find('.image-upload-button');
			this.removeBtn = this.inputObj.find('.image-remove-button');
			this.changeBtn = this.inputObj.find('.image-change-button');
			this.ImageView = this.inputObj.find('.mb-ifi-view-image');
			this.inputData = this.inputObj.find('.mb-ii-data-field');
			
			
			
			
			this.addBtn.on( 'click', function() {
				$this.createFrame();
				$this.frame.open();
			});
			
			this.changeBtn.on( 'click', function() {
				$this.createFrame();
				$this.frame.open();
			});
			
			this.removeBtn.on( 'click', function() {
				$this.ImageView.find('img').remove();
				
				$this.allVals = {};
				
				$this.addBtn.removeClass('mb-hidden');
			      
			    $this.removeBtn.addClass('mb-hidden');
			    $this.changeBtn.addClass('mb-hidden');
	
	            $this.inputData.val('');
				
			});
			
			
		},
		createFrame: function (){
			this.frame = wp.media({
				title: 'Select Image',
				button: {text: 'Set Image'},
				library: {type: 'image'},
				multiple: false
			});
			
			this.selectImage();
		},
		selectImage: function (){
			var $this = this;
			if( typeof this.frame !== 'undefined' ){
				$this.frame.on( 'select', function() {
					var attachment = $this.frame.state().get('selection').first().toJSON();
					
					$this.ImageView.find('img').remove();

					var imgThumbUrl = '';

					if (typeof attachment.sizes.thumbnail !== 'undefined') {
						imgThumbUrl = attachment.sizes.thumbnail.url;
					} else {
						imgThumbUrl = attachment.url;
					}

					$this.ImageView.append('<img class="mb-ifi-vimg" src="'+imgThumbUrl+'" alt="'+attachment.alt+'" />');
					
					$this.allVals = {};
					
					$this.allVals['thumbnail'] = imgThumbUrl;
					$this.allVals['url'] = attachment.url;
					$this.allVals['id'] = attachment.id;
					$this.allVals['title'] = attachment.title;
					$this.allVals['alt'] = attachment.alt;
					$this.allVals['width'] = attachment.width;
					$this.allVals['height'] = attachment.height;
					
					$this.addBtn.addClass('mb-hidden');
			
					$this.removeBtn.removeClass('mb-hidden');
					$this.changeBtn.removeClass('mb-hidden');
					
					$this.inputData.val(JSON.stringify($this.allVals));
				});
			}
			
		}
	});
	MB_Core.Api.mb_image_multi = MB_Core.Input.extend({
		ready: function (){
			var $this = this;
			
			this.addBtn = this.inputObj.find('.image-upload-button');
			this.ImageView = this.inputObj.find('.mb-ifi-view-image-multi');
			this.inputData = this.inputObj.find('.mb-img-multi-data-all');
			
			
			
			
			this.addBtn.on( 'click', function() {
				$this.createFrame();
				$this.frame.open();
			});
			
			this.ImageView.sortable({
				update: function( event, ui ) {
					var all_vals = [];
				
					$this.inputObj.find('.mb-img-multi-data').each(function (){
						var temp_data = JSON.parse($(this).val());
						all_vals.push(temp_data);
					});
					
					$this.inputData.val(JSON.stringify(all_vals));
	
				}
			});
			
			
			$this.inputObj.on( 'click', '.mb-mi-item-close', function( e ) {
			
				e.preventDefault();
				
				$(this).parent('.mb-multi-img-item').remove();
				
				var all_vals = [];
				
				$this.inputObj.find('.mb-img-multi-data').each(function (){
					var temp_data = JSON.parse($(this).val());
					all_vals.push(temp_data);
				});
				
				$this.inputData.val(JSON.stringify(all_vals));
	
			});
			
			
		},
		createFrame: function (){
			this.frame = wp.media({
				title: 'Select Images',
				button: {text: 'Set Images'},
				library: {type: 'image'},
				multiple: true
			});
			
			this.selectImage();
		},
		selectImage: function (){
			var $this = this;
			$this.frame.on( 'select', function() {
				
				$this.allVals = [];
				
				$this.frame.state().get('selection').map(function (attachment) {
				
					attachment = attachment.toJSON();
					
					
					
					var tmp_img = {};
					
					tmp_img['thumbnail'] = attachment.sizes.thumbnail.url;
					tmp_img['url'] = attachment.url;
					tmp_img['id'] = attachment.id;
					tmp_img['title'] = attachment.title;
					tmp_img['alt'] = attachment.alt;
					tmp_img['width'] = attachment.width;
					tmp_img['height'] = attachment.height;
					
					$this.ImageView.append('<div class="mb-multi-img-item"><img class="" src="'+attachment.sizes.thumbnail.url+'" alt="" /><button class="mb-mi-item-close">x</button><input type="hidden" class="mb-img-multi-data" value="'+_.escape(JSON.stringify(tmp_img))+'" ></div>');
				

					
				});
				
				$this.inputObj.find('.mb-img-multi-data').each(function (){
					var temp_data = JSON.parse($(this).val());
					$this.allVals.push(temp_data);
				});
				
				$this.inputData.val(JSON.stringify($this.allVals));
				
			});
		}
	});

	MB_Core.Api.mb_editor = MB_Core.Input.extend({
		ready: function (){
			var init,
				id,
				wrap,
				control = this,
				editorSelect = control.inputObj.find('.wp-editor-wrap'),
				textareaSelector = control.inputObj.find('.wp-editor-area');
				
			
			this.editorID = textareaSelector.attr('id');


			if ( typeof tinymce !== 'undefined' ) {

				_.each(mb_mce_var.mb_mce_external_plugins, function (url, name, ext_plugins) {
	                tinymce.PluginManager.load(name, url);
	            });

				wrap = tinymce.$( '#'+editorSelect.attr('id') );
				
				init = {
					selector: '#'+textareaSelector.attr('id'),
					resize: 'vertical',
	                menubar: false,
	                wpautop: true,
	                toolbar1: mb_mce_var.mb_mce_buttons,
	                toolbar2: mb_mce_var.mb_mce_buttons_2,
	                toolbar3: mb_mce_var.mb_mce_buttons_3,
	                toolbar4: mb_mce_var.mb_mce_buttons_4,
	                //theme: 'modern',
	                skin: 'lightgray',
	                relative_urls: false,
	                remove_script_host: false,
	                convert_urls: false,
	                browser_spellcheck: true,
	                fix_list_elements: true,
	                entities: '38,amp,60,lt,62,gt',
	                entity_encoding: 'raw',
	                keep_styles: false,
	                content_css: mb_mce_var.mb_mce_css,
	                plugins: mb_mce_var.mb_plugins,
	                external_plugins: mb_mce_var.mb_mce_external_plugins,
					setup: function(editor) {
						editor.on('change', function(e) {
							textareaSelector.val(editor.getContent());
						});
					}
				};

				if ( wrap.hasClass( 'tmce-active' ) ) {
					
					setTimeout(function () {
	                	tinymce.init( init );
	                },50);
	                
	            }

			}
				
			
			if ( typeof quicktags !== 'undefined' ) {
				quicktags({
					id: textareaSelector.attr('id'),
					buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
				});
				QTags._buttonsInit();
			}

			
			$('#'+editorSelect.attr('id')).on('click', function () {
				if($(this).hasClass('html-active')){
					var editor = tinymce.get(wpActiveEditor),
						newContent = textareaSelector.val();
						
					if(_.isNull(editor)){
						tinymce.init( init );
						
						editor = tinymce.get(wpActiveEditor);
						
						editor.setContent( newContent ? switchEditors.wpautop( newContent ) : '' );
					} else {
						editor.setContent( newContent ? switchEditors.wpautop( newContent ) : '' );
					}
					
				}
			});
			
			this.inputObj.on( 'change keyup paste', 'textarea', function() {
				var editor = tinymce.get(wpActiveEditor),
					newContent = textareaSelector.val();
				editor.setContent( newContent ? switchEditors.wpautop( newContent ) : '' );
			});
		},
		removeEditor: function (){
			var theEditor = tinymce.get(this.editorID);

			if (!_.isNull(theEditor)) {
				theEditor.remove();
			};
		}
	});

})( wp, jQuery );