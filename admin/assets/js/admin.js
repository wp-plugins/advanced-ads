jQuery( document ).ready(function ($) {
	"use strict";

	function advads_load_ad_type_parameter_metabox(ad_type) {
		$( '#advanced-ads-ad-parameters' ).html( '<span class="spinner advads-ad-parameters-spinner"></span>' );
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'load_ad_parameters_metabox',
				'ad_type': ad_type,
				'ad_id': $( '#post_ID' ).val()
			},
			success: function (data, textStatus, XMLHttpRequest) {
				// toggle main content field
				if (data) {
					$( '#advanced-ads-ad-parameters' ).html( data ).trigger( 'paramloaded' );
				}
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				$( '#advanced-ads-ad-parameters' ).html( errorThrown );
			}
		});
	}
	;

	$( document ).on('change', '#advanced-ad-type input', function () {
		var ad_type = $( this ).val()
		advads_load_ad_type_parameter_metabox( ad_type );
	});

	// toggle display conditions
	$( '#advanced-ad-conditions-enable input' ).click(function () {
		advads_toggle_display_conditions( this.value );
	});
	// display on load
	advads_toggle_display_conditions( $( '#advanced-ad-conditions-enable input:checked' ).val() );

	// display / hide options if all-option is checked for display condition
	$( '.advanced-ad-display-condition .advads-conditions-all input' ).click(function () {
		advads_toggle_single_display_conditions( this );
	});
	// display / hide options if all-option is checked for display condition – on load
	$( '.advanced-ad-display-condition .advads-conditions-all input' ).each(function () {
		advads_toggle_single_display_conditions( this );
	});

	// toggle single display condition checkboxes that have a counterpart
	$(document).on('click', '.advads-conditions-single input[type="checkbox"]', function () {
                advads_toggle_single_display_condition_checkbox( this );
                // update buttons
                $('.advads-conditions-terms-buttons label').button('refresh');
	});
	// toggle single display condition checkboxes that have a counterpart on load
	$( '.advads-conditions-single input[type="checkbox"]' ).each(function () {
		advads_toggle_single_display_condition_checkbox( this );
	});
	// activate buttons
	$('.advads-conditions-terms-buttons').buttonset();

	$(document).on('click', '.advads-conditions-terms-buttons .button', function (e) {
		$(this).remove();
	});
	// display input field to search for terms
	$( '.advads-conditions-terms-show-search' ).click(function (e) {
		e.preventDefault();
		// display input field
		$(this).siblings('.advads-conditions-terms-search').show().focus();
		$(this).next('br').show();
		$(this).hide();
	});
	// register autocomplete to display condition posts
        var response = [];
	if($( ".advads-conditions-terms-search" ).length){
		$( ".advads-conditions-terms-search" ).each(function(){
                    var self = this;
                    $(this).autocomplete({
			source: function(request, callback){
				// var searchField  = request.term;
				advads_term_search(self, callback);
			},
			minLength: 2,
			select: function( event, ui ) {
				// append new line with input fields
				$( '<label class="button">' + ui.item.label + '<input type="hidden" name="advanced_ad[conditions]['+ self.dataset.group +']['+ self.dataset.includeType +'][]" value="' + ui.item.value + '"></label>' ).appendTo( $(self).siblings('.advads-conditions-terms-buttons'));

				// show / hide other elements
				// $( '#advads-display-conditions-individual-post' ).hide();
				// $( '.advads-conditions-postids-list .show-search a' ).show();
			},
			close: function( event, ui ) {
                                $(self).val( '' );
			}
                    });
		});
	};
	// display input field to search for post, page, etc.
	$( '.advads-conditions-postids-list .show-search a' ).click(function (e) {
		e.preventDefault();
		// display input field
		$( '#advads-display-conditions-individual-post' ).show();
		$( this ).hide();
	});
	// register autocomplete to display condition posts
	var response = [];
	if($( "#advads-display-conditions-individual-post" ).length){
		$( "#advads-display-conditions-individual-post" ).autocomplete({
			source: function(request, callback){
				var searchParam  = request.term;
				advads_post_search( searchParam, callback );
			},
			minLength: 2,
			select: function( event, ui ) {
				// append new line with input fields
				var newline = $( '<li></li>' );
				$( '<a class="remove" href="#">remove</a>' ).appendTo( newline );
				$( '<span>' + ui.item.label + '</span><input type="hidden" name="advanced_ad[conditions][postids][ids][]" value="' + ui.item.value + '">' ).appendTo( newline );
				newline.insertBefore( '.advads-conditions-postids-list .show-search' );

				// show / hide other elements
				$( '#advads-display-conditions-individual-post' ).hide();
				$( '.advads-conditions-postids-list .show-search a' ).show();
			},
			close: function( event, ui ) {
				$( '#advads-display-conditions-individual-post' ).val( '' );
			}
		});
	};

	// remove individual posts from the display conditions post list
	$( document ).on('click', '.advads-conditions-postids-list .remove', function(e){
		e.preventDefault();
		$( this ).parent( 'li' ).remove();
	});

});

/**
 * callback for term search autocomplete
 *
 * @param {type} search term
 * @param {type} callback
 * @returns {obj} json object with labels and values
 */
function advads_term_search(field, callback) {

	// return ['post', 'poster'];
	var query = {
            action: 'advads-terms-search',
	};

	query.search = field.value;
	query.tax = field.dataset.tagName;

	var querying = true;

	var results = {};
	jQuery.post(ajaxurl, query, function (r) {
		querying = false;
		var results = [];
		if(r){
			r.map(function(element, index){
				results[index] = {
                                    value: element.term_id,
                                    label: element.name
				};
			});
		}
		callback( results );
	}, 'json');
}

/**
 * callback for post search autocomplete
 *
 * @param {type} query
 * @param {type} callback
 * @returns {obj} json object with labels and values
 */
function advads_post_search(query, callback) {

	// return ['post', 'poster'];
	var query = {
		action: 'wp-link-ajax',
		_ajax_linking_nonce: jQuery( '#_ajax_linking_nonce' ).val()
	};

	query.search = jQuery( '#advads-display-conditions-individual-post' ).val();

	var querying = true;

	var results = {};
	jQuery.post(ajaxurl, query, function (r) {
		querying = false;
		var results = [];
		if(r){
			r.map(function(element, index){
				results[index] = {
					label: element.title,
					value: element.ID
				};
			});
		}
		callback( results );
	}, 'json');
}

/**
 * toggle content elements (hide/show)
 *
 * @param selector jquery selector
 */
function advads_toggle(selector) {
	jQuery( selector ).slideToggle();
}

/**
 * toggle content elements with a checkbox (hide/show)
 *
 * @param selector jquery selector
 */
function advads_toggle_box(e, selector) {
	if (jQuery( e ).is( ':checked' )) {
		jQuery( selector ).slideDown();
	} else {
		jQuery( selector ).slideUp();
	}
}

/**
 * disable content of one box when selecting another
 *  only grey/disable it, don’t hide it
 *
 * @param selector jquery selector
 */
function advads_toggle_box_enable(e, selector) {
	if (jQuery( e ).is( ':checked' )) {
		jQuery( selector ).find( 'input' ).removeAttr( 'disabled', '' );
	} else {
		jQuery( selector ).find( 'input' ).attr( 'disabled', 'disabled' );
	}
}

/**
 * toggle display conditions
 * @param {bool} value
 */
function advads_toggle_display_conditions(value) {
	if (value == 1) {
		jQuery( '#advanced-ad-conditions' ).fadeIn();
	} else {
		jQuery( '#advanced-ad-conditions' ).fadeOut();
	}
}

/**
 * disable new display conditions
 * @param {string} checkbox element
 */
function advads_toggle_single_display_conditions(checkbox) {
	// console.log(jQuery(checkbox).parent('div').find('label:not(.advads-conditions-all) input').css('border', 'solid'));
	if (jQuery( checkbox ).is( ':checked' )) {
		jQuery( checkbox ).parents( '.advanced-ad-display-condition' ).find( '.advads-conditions-single' ).addClass( 'disabled' ).find( 'input' ).attr( 'disabled', 'disabled' );
	} else {
		jQuery( checkbox ).parents( '.advanced-ad-display-condition' ).find( '.advads-conditions-single' ).removeClass( 'disabled' ).find( 'input' ).removeAttr( 'disabled' );
	}
}

/**
 * toggle display condition checkboxes
 * @param {string} checkbox element
 */
function advads_toggle_single_display_condition_checkbox(checkbox) {
	// get the counterpart (same value, but not current element)
	var counterpart = jQuery( checkbox ).parents( '.advads-conditions-single' ).find( 'input[type="checkbox"][value="' + checkbox.value + '"]' ).not( checkbox );
	// toggle counterpart
	if (jQuery( checkbox ).is( ':checked' )) {
		counterpart.attr( 'checked', false );
		counterpart.attr( 'disabled', 'disabled' );
                // mark label
                console.log(jQuery('label[for="'+counterpart.attr('id')+'"]'));
                jQuery('label[for="'+counterpart.attr('id')+'"]').addClass('ui-button-disabled').addClass('ui-state-disabled');
	} else {
		counterpart.removeAttr( 'disabled' );
                jQuery('label[for="'+counterpart.attr('id')+'"]').removeClass('ui-button-disabled');
                jQuery('label[for="'+counterpart.attr('id')+'"]').removeClass('ui-state-disabled');
	}
}