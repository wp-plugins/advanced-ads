/*
 * advanced ads functions to be used directly within ad codes
 */

advads = {
	/**
	 * check if the ad is displayed more than {max} times per session
	 * every check increases the counter
	 *
	 * @param {string} name (no id needed, just any id-formated string)
	 * @param {type} max number of maximum times the ad can be displayed within the period
	 * @returns {bool} true if limit is reached
	 */
	max_per_session: function(name, max){
		var num = 1;
		if(max === undefined || parseInt( max ) === 0) { max = 1; }

		// check if cookie exists and get the value
		if(this.cookie_exists( name )){
			if(this.get_cookie( name ) >= max) { return true; }
			num = num + parseInt( this.get_cookie( name ) );
		}
		this.set_cookie( name, num );
		return false;
	},
	/**
	 * increase a cookie with an integer value by 1
	 *
	 * @param {str} name of the cookie
	 * @param {int} exdays days until cookie expires
	 */
	count_up: function( name, exdays ){
		var num = 1;

		// check if cookie exists and get the value
		if(this.cookie_exists( name )){
			num = num + parseInt( this.get_cookie( name ) );
		}
		this.set_cookie( name, num );
	},
	/**
	 * return true, if cookie exists
	 * return false, if not
	 * if not exists, create it
	 * use case: to check if something already happened in this page impression
	 *
	 * @param {type} name
	 * @returns {unresolved}
	 */
	set_cookie_exists: function( name ){
		if( get_cookie(name) ){
		    return true;
		}
		set_cookie( name, '', 0 );
		return false;
	},
	/**
	 * get a cookie value
	 *
	 * @param {str} name of the cookie
	 */
	get_cookie: function (name) {
		var i, x, y, ADVcookies = document.cookie.split( ";" );
		for (i = 0; i < ADVcookies.length; i++)
		{
			x = ADVcookies[i].substr( 0, ADVcookies[i].indexOf( "=" ) );
			y = ADVcookies[i].substr( ADVcookies[i].indexOf( "=" ) + 1 );
			x = x.replace( /^\s+|\s+$/g, "" );
			if (x === name)
			{
				return unescape( y );
			}
		}
	},
	/**
	 * set a cookie value
	 *
	 * @param {str} name of the cookie
	 * @param {str} value of the cookie
	 * @param {int} exdays days until cookie expires
	 *  set 0 to expire cookie immidiatelly
	 *  set null to expire cookie in the current session
	 */
	set_cookie: function (name, value, exdays, path, domain, secure) {
		var exdate = new Date();
		exdate.setDate( exdate.getDate() + exdays );
		document.cookie = name + "=" + escape( value ) +
				((exdays == null) ? "" : "; expires=" + exdate.toUTCString()) +
				((path == null) ? "; path=/" : "; path=" + path) +
				((domain == null) ? "" : "; domain=" + domain) +
				((secure == null) ? "" : "; secure");
	},
	/**
	 * check if a cookie is set and contains a value
	 *
	 * @param {str} name of the cookie
	 * @returns {bool} true, if cookie is set
	 */
	cookie_exists: function (name)
	{
		var c_value = this.get_cookie( name );
		if (c_value !== null && c_value !== "" && c_value !== undefined)
		{
			return true;
		}
		return false;
	},
	/**
	 * move one element into another
	 *
	 * @param {str} element selector of the element that should be moved
	 * @param {str} target selector of the element where to move
	 * @param {arr} options
	 */
	move: function( element, target, options )
	{

		var el = jQuery(element);

		if( typeof options === 'undefined' ){
		    options = {};
		}
		if( typeof options.css === 'undefined' ){
		    options.css = {};
		}
		if( typeof options.method === 'undefined' ){
		    options.method = 'prependTo';
		}

		// search for abstract target element
		if( target === '' && typeof options.target !== 'undefined' ){
		    switch( options.target ){
			case 'wrapper' : // wrapper
			    var offset = 'left';
			    if( typeof options.offset !== 'undefined' ){
				    offset = options.offset;
			    }
			    target = this.find_wrapper( element, offset );
			    break;
		    }
		}

		// switch insert method
		switch( options.method ){
		    case 'insertBefore' :
			el.insertBefore(target);
			break;
		    case 'insertAfter' :
			el.insertAfter(target);
			break;
		    case 'appendTo' :
			el.appendTo(target);
			break;
		    case 'prependTo' :
			el.prependTo(target);
			break;
		    default :
			el.prependTo(target);
		}
	},
	/**
	 * make an absolute position element fixed at the current position
	 * hint: use only after DOM is fully loaded in order to fix a wrong position
	 *
	 * @param {str} element selector
	 */
	fix_element: function( element ){
		var el = jQuery(element);
		// give "position" style to parent element, if missing
		var parent = el.parent();
		if(parent.css('position') === 'static' || parent.css('position') === ''){
			parent.css('position', 'relative');
		}

		// fix element at current position
		var topoffset = parseInt(el.offset().top);
		var leftoffset = parseInt(el.offset().left);
		el.css('position', 'fixed').css('top', topoffset + 'px').css('left', leftoffset + 'px');
	},
	/**
	 * find the main wrapper
	 *  either id or first of its class
	 *
	 *  @param {str} element selector
	 *  @param {str} offset which position of the offset to check (left or right)
	 *  @return {str} selector
	 */
	find_wrapper: function( element, offset ){
		// first margin: auto element after body
		var returnValue;
		jQuery('body').children().each(function(key, value){
			// exclude current element
			// TODO exclude <script>
			if( value.id !== element.substring(1) ){
				// check offset value
				var checkedelement = jQuery( value );
				// check if there is space left or right of the element
				if( ( offset === 'right' && ( checkedelement.offset().left + jQuery(checkedelement).width() < jQuery(window).width() ) ) ||
					( offset === 'left' && checkedelement.offset().left > 0 ) ){
					// fix element
					if( checkedelement.css('position') === 'static' || checkedelement.css('position') === ''){
						checkedelement.css('position', 'relative');
					}
					// set return value
					returnValue = value;
					return false;
				}
			}
		});
		return returnValue;
	},
	/**
	 * center fixed element on the screen
	 *
	 * @param {str} element selector
	 */
	center_fixed_element: function( element ){
		var el = jQuery(element);
		// half window width minus half element width
		var left = ( jQuery(window).width() / 2 ) - ( parseInt( el.css('width')) / 2 );
		el.css('left', left + 'px');
	}
};