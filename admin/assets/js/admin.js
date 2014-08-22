jQuery(document).ready(function($) {
    "use strict";

    function advads_load_ad_type_parameter_metabox(ad_type) {
        $('#advanced-ads-ad-parameters').html('<span class="spinner advads-ad-parameters-spinner"></span>');
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'load_ad_parameters_metabox',
                'ad_type': ad_type,
                'ad_id': $('#post_ID').val()
            },
            success: function(data, textStatus, XMLHttpRequest) {
                // toggle main content field
                if (data) {
                    $('#advanced-ads-ad-parameters').html(data);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown) {
                $('#advanced-ads-ad-parameters').html(errorThrown);
            }
        });
    }
    ;

    $(document).on('change', '#advanced-ad-type input', function() {
        var ad_type = $(this).val()
        advads_load_ad_type_parameter_metabox(ad_type);
    });

    // empty / clear input condition fields in the same row as the clear button
    $('#advanced-ad-conditions .clear-radio').click(function() {
        $(this).closest('tr').find('input[type="radio"]').prop('checked', false);
        $(this).closest('tr').find('input[type="text"]').val('');
    });

    // toggle display conditions
    $('#advanced-ad-conditions-enable input').click(function(){
        advads_toggle_display_conditions(this.value);
    })
    // display on load
    advads_toggle_display_conditions($('#advanced-ad-conditions-enable input:checked').val());
})

/**
 * toggle content elements (hide/show)
 *
 * @param selector jquery selector
 */
function advads_toggle(selector) {
    jQuery(selector).slideToggle();
}

/**
 * toggle display conditions
 * @param {bool} value
 */
function advads_toggle_display_conditions(value){
    if(value == 1){
        jQuery('#advanced-ad-conditions').fadeIn();
    } else {
        jQuery('#advanced-ad-conditions').fadeOut();
    }
}