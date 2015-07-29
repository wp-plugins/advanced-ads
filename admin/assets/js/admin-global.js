/*
 * global js functions for Advanced Ads
 */
jQuery( document ).ready(function () {

	/**
	 * ADMIN NOTICES
	 */
	// close button
	jQuery(document).on('click', '.advads-notices-button-close', function(){
	    if(this.dataset.notice === undefined) return;
	    var messagebox = jQuery(this).parents('.advads-admin-notice');

	    var query = {
		action: 'advads-close-notice',
		notice: this.dataset.notice
	    };
	    // send and close message
	    jQuery.post(ajaxurl, query, function (r) {
		messagebox.fadeOut();
	    });

	});
	// autoresponder button
	jQuery('.advads-notices-button-subscribe').click(function(){
	    if(this.dataset.notice === undefined) return;
	    var messagebox = jQuery(this).parents('.advads-admin-notice');
	    messagebox.find('p').append( '<span class="spinner advads-spinner"></span>' );

	    var query = {
		action: 'advads-subscribe-notice',
		notice: this.dataset.notice
	    };
	    // send and close message
	    jQuery.post(ajaxurl, query, function (r) {
		if(r === '1'){
		    messagebox.fadeOut();
		} else {
		    messagebox.find('p').html(r);
		    messagebox.removeClass('updated').addClass('error');
		}
	    });

	});

});