jQuery('document').ready(function($) {

    "use strict";
    // check all banners with item conditions on load page load
    $.each(advads_items.conditions, function(key, value) {
        // iterate through conditions
        advads_check_item_conditions(key);
    });

});

/**
 * check item conditions and display the ad if all conditions are true
 *
 * @param {string} id id of the ad, without #
 * @returns {undefined}
 */
function advads_check_item_conditions(id) {
    var item = jQuery('#' + id);
    if (item.length == 0)
        return;

    var display = true;
    jQuery.each(advads_items.conditions[id], function(method, flag) {
        if (flag === false) {
            // display the banner
            display = false;
        }
    });
    if (display) {
        var ad = jQuery('#' + id);
        // iterate through display callbacks
        jQuery.each(advads_items.display_callbacks, function(adid, callbacks){
            // iterate through all callback function and call them
            jQuery.each(callbacks, function(key, funcname){
                var callback = window[funcname];
                callback(adid);
            })
        })
        ad.show();
    }
}
