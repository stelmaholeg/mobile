/**
 * ------------------------------------------------------------------------
 * Plugin ContactFormPro for Joomla! 1.7 - 2.5
 * ------------------------------------------------------------------------
 * @copyright   Copyright (C) 2011-2012 joomfever.com - All Rights Reserved.
 * @license     GNU/GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @author:     Sebastien Chartier
 * @link:       http://www.joomfever.com
 * ------------------------------------------------------------------------
 *
 * @package	Joomla.Plugin
 * @subpackage  ContactFormPro
 * @version     1.12 (February 20, 2012)
 * @since	1.7
 */

var ContactFormBox;

(function() {

    var statuses = {
        opened: 1,
        closed: 2
    };

    // Global variables, accessible to ContactFormBox only
    var options, media, top, mTop, left, mLeft, winWidth, winHeight, fx, preload, closeLink,
    // DOM elements
    overlay, center, response_div, sending_div, container, style, status, bottom,
    // ContactFormBox specific vars
    mediaWidth, mediaHeight, margin, mainForm;



    /*	Initialization	*/
    window.addEvent("domready", function() {

        status = statuses.closed;

        // Create and append the ContactFormBox HTML code at the bottom of the document
        document.id(document.body).adopt(
            $$([
                overlay = new Element("div", {
                    id: "cfpOverlay"
                }).addEvent("click", close),
                center = new Element("div", {
                    id: "cfpCenter",
                    'class': 'cfp_contact_form'
                }),
                response_div = new Element('div', {
                    id: 'cfpResponseDiv',
                    'class': 'cfp_inline'
                }).setStyle('text-align', 'center'),
                sending_div = new Element('div', {
                    'id': 'cfpSendingDiv',
                    'class': 'cfp_inline'
                }).setStyle('text-align', 'center')
                ]).setStyle("display", "none")
        );


        ;
        container = new Element("div", {
                    "class": "inner2"
            }).inject(
                new Element("div", {
                    "class": "inner1"
            }).inject(center));

        media = new Element("div", {
            id: "cfpMedia"
            }).inject(
                container
            );
        bottom = new Element("div", {
            id: "cfpBottom"
        }).inject(container).adopt(
            closeLink = new Element("a", {
                id: "cfpCloseLink",
                href: "#"
            }).addEvent("click", close)
        );

        fx = {
            overlay: new Fx.Tween(overlay, {
                property: "opacity",
                duration: 360
            }).set(0),
            media: new Fx.Tween(media, {
                property: "opacity",
                duration: 360
            })
        };
    });

    /*	API		*/

    ContactFormBox = {
        ajax: function(_url, _options) {
            center.addClass('mbLoading');

            _media = new Element('div').setStyle('display', 'none');
            document.id(document.body).adopt(_media);
            mainForm = _media;

            new Request.HTML({
                url: _url,
                data: _options,
                onSuccess: function(responseTree){
                    _media.adopt(responseTree); //set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res ' + ((response.status==1)?'success':'failure') + '">' + response.message + '</div></div></div>');
                    return ContactFormBox.open(_media, _options);
                },
                spinnerTarget: media  // 120721
            }).send();
        },

        close: function(){
            close();	// Thanks to Yosha on the google group for fixing the close function API!
        },

        recenter: function(){	// Thanks to Garo Hussenjian (Xapnet Productions http://www.xapnet.com) for suggesting this addition
            if (center && !Browser.Platform.ios) {
                left = window.getScrollLeft() + (window.getWidth()/2);
                center.setStyles({
                    left: left,
                    marginLeft: -(mediaWidth/2)-margin
                });
            //				top = window.getScrollTop() + (window.getHeight()/2);
            //				margin = center.getStyle('padding-left').toInt()+media.getStyle('margin-left').toInt()+media.getStyle('padding-left').toInt();
            //				center.setStyles({top: top, left: left, marginTop: -(mediaHeight/2)-margin, marginLeft: -(mediaWidth/2)-margin});
            }
        },

        open: function(_media, _options) {
            options = {
                style: 'light',
                close_button: '&times;',
                center: true,
                keyboard: true,					// Enables keyboard control; escape key, left arrow, and right arrow
                keyboardAlpha: false,			// Adds 'x', 'c', 'p', and 'n' when keyboard control is also set to true
                keyboardStop: false,			// Stops all default keyboard actions while overlay is open (such as up/down arrows)
                // Does not apply to iFrame content, does not affect mouse scrolling
                overlayOpacity: 0.8,			// 1 is opaque, 0 is completely transparent (change the color in the CSS file)
                resizeOpening: true,			// Determines if box opens small and grows (true) or starts at larger size (false)
                resizeDuration: 240,			// Duration of each of the box resize animations (in milliseconds)
                initialWidth: 320,				// Initial width of the box (in pixels)
                initialHeight: 180,				// Initial height of the box (in pixels)
                defaultWidth: 640,				// Default width of the box (in pixels) for undefined media (MP4, FLV, etc.)
                defaultHeight: 360,				// Default height of the box (in pixels) for undefined media (MP4, FLV, etc.)
                sending_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SENDING_MSG'), //'Sending message',
                correct_errors: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_CORRECT_ERRORS'), //'Please correct error(s)',
                go_back: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_BACK_LINK'),
                success_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SUCCESS'),
                error_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_GENERIC_ERROR') //'Go back'
            };

            options = Object.merge(options, _options);

            closeLink.set('html', options.close_button);

            if (Browser.firefox2) {	// Fixes Firefox 2 and Camino 1.6 incompatibility with opacity + flash
                options.overlayOpacity = 1;
                overlay.addClass('cfpOverlayOpaque');
            }

            if (Browser.Platform.ios) {
                options.keyboard = false;
                options.resizeOpening = false;	// Speeds up interaction on small devices (mobile) or older computers (IE6)
                overlay.addClass('mbMobile');
                //				options.overlayOpacity = 0.001;	// Helps ameliorate the issues with CSS overlays in iOS, leaving a clickable background, but avoiding the visible issues
                position();
            }

            if (Browser.ie6) {
                options.resizeOpening = false;	// Speeds up interaction on small devices (mobile) or older computers (IE6)
                overlay.addClass('cfpOverlayAbsolute');
                position();
            }

            size();
            setup(true);

            if(typeof style != 'undefined'){
                overlay.removeClass(style);
                center.removeClass(style);
            }

            top = window.getScrollTop() + (window.getHeight()/2);
            left = window.getScrollLeft() + (window.getWidth()/2);
            margin = center.getStyle('padding-left').toInt()+media.getStyle('margin-left').toInt()+media.getStyle('padding-left').toInt();


            center.setStyles({
                top: top,
                left: left,
                width: options.initialWidth,
                height: options.initialHeight,
                marginTop: -(options.initialWidth/2)-margin,
                marginLeft: -(options.initialHeight/2)-margin,
                display: ""
            });

            fx.resize = new Fx.Morph(center, {
                duration: options.resizeDuration,
                onComplete: mediaAnimate
            });
            fx.overlay.start(options.overlayOpacity);

            status = statuses.opened;

            return this.changeMedia(_media, _options);
        },

        changeMedia: function(_media, _options) {
            stop();

            Object.merge(options, _options);

            center.addClass('mbLoading');
            if(typeof preload != 'undefined') preload.adopt(media.getChildren());	// prevents loss of adopted data
            media.set('html', '');

            if(typeof options.style != 'undefined'){
                style = options.style;
                overlay.addClass(style);
                center.addClass(style);
            }

            mediaWidth = "";
            mediaHeight = "";
            options.title = "";
            if(typeof _options.width != 'undefined'){
                if(is_int(options.width)){
                    mediaWidth = options.width;
                }else if(options.width.match("%")){
                    mediaWidth = window.getWidth() * (options.width.replace("%", "")*0.01);
                }else if(_options.width.match("px")){
                    mediaWidth = parseInt( options.width.replace("px", "") );
                }
            }

            if(typeof _options.height != 'undefined'){
                if(is_int(options.height)){
                    mediaHeight = options.height;
                }else if(_options.height.match("%")){
                    mediaHeight = window.getHeight()*(options.height.replace("%", "")*0.01);
                }else if(_options.height.match("px")){
                    mediaHeight = parseInt( options.height.replace("px", "") );
                }
            }

            preload = _media;
            startEffect();
            return false;
        },

        sendMessage: function(_form, _options){
            var form = $(_form);

            title.set('html', '');

            if (document.formvalidator.isValid(form)) {
                if(status == statuses.closed){
                    sending_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><span class="cfp_msg_txt">'+_options.sending_message+'</span></div><div class="cfp_msg_inner_bot cfp_msg_sending"></div></div>');
                    ContactFormBox.open(sending_div, _options);
                }else{
                    sending_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><span class="cfp_msg_txt">'+options.sending_message+'</span></div><div class="cfp_msg_inner_bot cfp_msg_sending"></div></div>');
                    ContactFormBox.changeMedia(sending_div, {});
                }

                new Request.JSON({
                    url: _form.get('action'),
                    data: _form,
                    onSuccess: function(response){
                        response_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res ' + ((response.status==1)?'success':'failure') + '">' + response.message + '</div></div></div>');

                        if(_options.display != 'form' && response.status != 1){
                            response_div.grab(new Element('a', {
                                html: options.go_back,
                                click: function(e){
                                    e.stop();
                                    ContactFormBox.changeMedia(mainForm, {});
                                }
                            }));
                        }
                        ContactFormBox.changeMedia(response_div, {});
                    },
                    onError: function(text, error){
                        // HTTP error
                        response_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res failure"><span class="cfp_msg__res_txt">' + error + '</span></div></div></div>');
                        ContactFormBox.changeMedia(response_div, {});
                    }
                }).send();
            }
            else {
                if(status == statuses.closed){
                    response_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res failure"><span class="cfp_msg__res_txt">' + _options.correct_errors + '</span></div></div></div>');
                    ContactFormBox.open(response_div, {});
                }
                else{
                    title.set('html', '<span style="color:#f00;">' + _options.correct_errors + '</span>');
                }
            }

            return false;
        }


    };

    /*	Internal functions	*/

    function position() {
        overlay.setStyles({
            top: window.getScrollTop(),
            left: window.getScrollLeft()
        });
    }

    function size() {
        winWidth = window.getWidth();
        winHeight = window.getHeight();
        overlay.setStyles({
            width: winWidth,
            height: winHeight
        });
    }

    function setup(open) {
        // Hides on-page objects and embeds while the overlay is open, nessesary to counteract Firefox stupidity
        if (Browser.firefox) {
            ["object", window.ie ? "select" : "embed"].forEach(function(tag) {
                Array.forEach($$(tag), function(el) {
                    if (open) el._mediabox = el.style.visibility;
                    el.style.visibility = open ? "hidden" : el._mediabox;
                });
            });
        }

        overlay.style.display = open ? "" : "none";

        var fn = open ? "addEvent" : "removeEvent";
        if (Browser.Platform.ios || Browser.ie6 || Browser.ie7 ) window[fn]("scroll", position);	// scroll position is updated only after movement has stopped
        window[fn]("resize", size);
        if (options.keyboard) document[fn]("keydown", keyDown);
    }

    function keyDown(event) {
        if (options.keyboardAlpha) {
            switch(event.code) {
                case 27:	// Esc
                case 88:	// 'x'
                case 67:	// 'c'
                    close();
                    break;
                case 37:	// Left arrow
                case 80:	// 'p'
                    previous();
                    break;
                case 39:	// Right arrow
                case 78:	// 'n'
                    next();
            }
        } else {
            switch(event.code) {
                case 27:	// Esc
                    close();
                    break;
                case 37:	// Left arrow
                    previous();
                    break;
                case 39:	// Right arrow
                    next();
            }
        }
        if (options.keyboardStop) {
            return false;
        };
    }

    function is_int(value){
        if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
            return true;
        } else {
            return false;
        }
    }

    function startEffect() {
        media.setStyles({
            backgroundImage: "none",
            display: "",
            width: "auto",
            height: "auto"
        });

        media.adopt(preload.getChildren());

        mediaWidth = (mediaWidth != "") ? mediaWidth : container.offsetWidth;
        mediaHeight = (mediaHeight != "") ? mediaHeight : container.offsetHeight + bottom.offsetHeight;

        if (mediaHeight >= top+top) {
            mTop = -top
        } else {
            mTop = -(mediaHeight/2)
        };
        if (mediaWidth >= left+left) {
            mLeft = -left
        } else {
            mLeft = -(mediaWidth/2)
        };

        if (options.resizeOpening) {
            fx.resize.start({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop-margin,
                marginLeft: mLeft-margin
            });
        } else {
            center.setStyles({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop-margin,
                marginLeft: mLeft-margin
            });
        }
        mediaAnimate();
    }

    function mediaAnimate() {
        center.removeClass('mbLoading');
        fx.media.start(1);
    }

    function stop() {
        if (preload) {
            preload.adopt(media.getChildren());	// prevents loss of adopted data
            preload.onload = function(){}; // $empty replacement
        }
        fx.resize.cancel();
        fx.media.cancel().set(0);
    }

    function close() {
        preload.adopt(media.getChildren());	// prevents loss of adopted data
        preload.onload = function(){}; // $empty replacement
        media.empty();
        for (var f in fx) fx[f].cancel();
        center.setStyle("display", "none");
        fx.overlay.chain(setup).start(0);

        status = statuses.closed;

        return false;
    }
})();


window.addEvents({
    resize: ContactFormBox.recenter
}); // to recenter the overlay while scrolling, add "scroll: ContactFormBox.recenter" to the object

window.addEvent('domready', function(){
    $$('a.cfpLink').each(function(el){
        el.addEvent('click', function(evnt){
            evnt.stop();
            ContactFormBox.ajax(this.get('href'), window[this.get('rel')]);
        });
    });
});