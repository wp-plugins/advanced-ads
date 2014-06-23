/* saving ad group ad settings */

var inlineEditAdGroupAds;
(function($) {
    inlineEditAdGroupAds = {
        init: function() {
            var t = this;
            $('#the-list').on('click', 'a.edit-ad-group-ads', function() {
                inlineEditAdGroupAds.edit(this);
                return false;
            });
            $('#the-list').on('click', 'a.cancel', function() {
                return inlineEditAdGroupAds.revert();
            });
            $('#the-list').on('click', 'a.save', function() {
                return inlineEditAdGroupAds.save(this);
            });
            $('#the-list').on('keydown', 'input, select', function() {
                if (e.which === 13) {
                    return inlineEditAdGroupAds.save(this);
                }
            });
            $('#posts-filter input[type="submit"]').mousedown(function() {
                t.revert();
            });
        },
        edit: function(link) {
            var td, id, t = this;
            // get group id
            id = t.getId(link);
            // get container
            td = $(link).parents('td');
            // load form with information
            params = {
                action: 'advads-ad-group-ads-form',
                group_id: id,
            };
            $.post(ajaxurl, params,
                    // append return
                            function(r) {
                                if (r) {
                                    // hide all child elements
                                    td.children('*').hide();
                                    // display the form
                                    $(r).appendTo(td);
                                }
                            }
                    );
                    return false;
                },
        save: function(link) {
            var params, td, id, t = this;
            // get group id
            id = t.getId(link);
            // get container
            td = $(link).parents('td');
            td.find('.ad-group-ads-form .spinner').show();
            params = {
                action: 'advads-ad-group-ads-form-save',
                group_id: id,
                fields: ''
            };
            params.fields = td.find(':input').serialize();
            // make ajax request
            $.post(ajaxurl, params, function(r) {
                td.find('.ad-group-ads-form .spinner').hide();
                if (r) {
                    t.revert(); // show normal table

                    $.each(r, function(key, value){
                        // search the field with the ad weight and change the value
                        td.find('.ad-weight-' + key).html(value);
                    })
                }

            }, "json"
                    );
            return false;
        },
        // remove edit form and display the other elements again
        revert: function() {
            var td = $('table.widefat .ad-group-ads-form').parents('td');
            if (td) {
                $('table.widefat .spinner').hide();
                td.find('.ad-group-ads-form').remove();
                td.find('*').show();
            }

            return false;
        },
        // get the id of the group from the link clicked
        getId: function(link) {
            groupid = $(link).parents('td').find('.ad-group-id').val();
            return parseInt(groupid);
            ;
        }
    };
    $(document).ready(function() {
        inlineEditAdGroupAds.init();
    });
})(jQuery);
