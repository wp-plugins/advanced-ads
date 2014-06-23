jQuery(document).ready(function($) {
    "use strict";
    /*
     function load_content_editor( ad_type ) {
     $.ajax({
     type: 'POST',
     url: ajaxurl,
     data: {
     'action': 'load_content_editor',
     'type'  : ad_type,
     'ad_id' : $('#post_ID').val()
     },
     success:function(data, textStatus, XMLHttpRequest){
     // toggle main content field
     if(data == 'content') {
     $('#advanced_ad_content_others').html(''); // clear other editors
     $('#advanced_ad_content').show();
     } else {
     $('#advanced_ad_content').hide();
     $('#advanced_ad_content_others').html(data);
     }
     },
     error: function(MLHttpRequest, textStatus, errorThrown){
     $('#advanced_ad_content_others').html(errorThrown);
     }
     });
     };

     // load content editor on page load
     if($('#advanced_ad_type input').length > 0) {
     var ad_type = $('#advanced_ad_type input').val();
     load_content_editor(ad_type);
     }

     $(document).on('change', '#advanced_ad_type input', function(){
     var ad_type = $(this).val()
     load_content_editor( ad_type );
     });
     */

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

})

/**
 * toggle content elements (hide/show)
 *
 * @param selector jquery selector
 */
function advads_toggle(selector) {
    jQuery(selector).slideToggle();
}