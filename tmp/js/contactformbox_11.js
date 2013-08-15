/**
 * ------------------------------------------------------------------------
 * Plugin ContactFormPro for Joomla! 1.7 - 2.5
 * Based on MediaboxAdvanced by iaian7 (http://iaian7.com/webcode/mediaboxAdvanced)
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

(function($) {

    var statuses = {
        opened: 1,
        closed: 2
    };

    // ContactFormBox specific vars
    // (see defaultOptions for available options...)
    var mediaWidth,
        mediaHeight,
        options;

    // Global variables, accessible to ContactFormBox only
    var defaultOptions,
        top,
        mTop,
        left,
        mLeft,
        winWidth,
        winHeight,
        fx,
        style,
        status;

    // DOM Elements
    var overlay,
        center,
        container,
        bottom,
        media,
        preload,
        closeLink,
        mainForm;

    /*	Initialization	*/
    window.addEvent("domready", function() {

        status = statuses.closed;
        defaultOptions = {
            style: 'light',
            close_button: '&times;',
            center: true,
            keyboard: true,					// Enables keyboard control; escape key, left arrow, and right arrow
            keyboardAlpha: false,			// Adds 'x', 'c', 'p', and 'n' when keyboard control is also set to true
            keyboardStop: false,			// Stops all default keyboard actions while overlay is open (such as up/down arrows)
            overlayOpacity: 0.8,			// 1 is opaque, 0 is completely transparent (change the color in the CSS file)
            resizeOpening: true,			// Determines if box opens small and grows (true) or starts at larger size (false)
            resizeDuration: 240,			// Duration of each of the box resize animations (in milliseconds)
            initialWidth: 320,				// Initial width of the box (in pixels)
            initialHeight: 180,				// Initial height of the box (in pixels)
            sending_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SENDING_MSG', 'The message is being sent'), //'Sending message',
            correct_errors: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_CORRECT_ERRORS', 'Please correct errors'), //'Please correct error(s)',
            go_back: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_BACK_LINK', 'Back'),
            success_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SUCCESS', 'The message has been sent successfully'),
            error_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_GENERIC_ERROR', 'There was an error when sending message'),
            sender_email_invalid: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SENDER_EMAIL_INVALID', 'Please enter a valid email address'), //'Please correct error(s)',
            sender_name_missing: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SENDER_NAME_MISSING', 'Please enter your name'),
            message_missing: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SUBJECT_MISSING', 'Please enter a subject'),
            subject_missing: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_MESSAGE_MISSING', 'Please enter a message')
        };

        // Create and append the ContactFormBox HTML code at the bottom of the document
        $(document.body).adopt(
            $$([
                overlay = new Element("div", {
                    id: "cfpOverlay"
                }).addEvent("click", close),
                center = new Element("div", {
                    id: "cfpCenter"
                })
                ])
            );


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

        if (Browser.Platform.ios) {
            defaultOptions.keyboard = false;
            defaultOptions.resizeOpening = false;	// Speeds up interaction on small devices (mobile) or older computers (IE6)
            overlay.className = 'cfpMobile';
            bottom.className = 'cfpMobile';
        }

        if (Browser.name == 'ie' && Browser.version < 9) {
            defaultOptions.resizeOpening = false;	// Speeds up interaction on small devices (mobile) or older computers (IE6)
            overlay.className = 'cfpOverlayAbsolute';
            center.addClass('ie8');
        }
    });

    /*	API		*/

    ContactFormBox = {
        ajax: function(_url, _options) {
            center.addClass('cfpLoading');
            setOptions(_options);

            _media = new Element('div').setStyle('display', 'none');
            $(document.body).adopt(_media);
            mainForm = _media;

            new Request.HTML({
                url: _url,
                data: options,
                onSuccess: function(responseTree){
                    _media.adopt(responseTree);
                    return ContactFormBox.open(_media, false);
                }
            }).send();
        },

        close: function(){
            close();	// Thanks to Yosha on the google group for fixing the close function API!
        },

        recenter: function(){	// Thanks to Garo Hussenjian (Xapnet Productions http://www.xapnet.com) for suggesting this addition
            if(status != statuses.opened)
                return;

            if (center && !Browser.Platform.ios) {
                left = window.getScrollLeft() + (window.getWidth()/2);
                center.setStyles({
                    left: left,
                    marginLeft: -(mediaWidth/2)
                });
            }
        },

        open: function(_media, _options) {
            setOptions(_options);

            if(status == statuses.opened)
                return this.changeMedia(_media, false);

            closeLink.set('html', options.close_button);

            size();
            setup(true);

            top = window.getScrollTop() + (window.getHeight()/2);
            left = window.getScrollLeft() + (window.getWidth()/2);

            center.setStyles({
                top: top,
                left: left,
                width: options.initialWidth,
                height: options.initialHeight,
                marginTop: -(options.initialWidth/2),
                marginLeft: -(options.initialHeight/2),
                display: ""
            });

            fx.resize = new Fx.Morph(center, {
                duration: options.resizeDuration,
                onComplete: mediaAnimate
            });
            fx.overlay.start(options.overlayOpacity);

            status = statuses.opened;

            return this.changeMedia(_media, false);
        },

        changeMedia: function(_media, _options) {
            stop();

            setOptions(_options);

            center.addClass('cfpLoading');
            if(typeof preload != 'undefined') preload.adopt(media.getChildren());	// prevents loss of adopted data
            media.set('html', '');

            mediaWidth = 0;
            if(typeof options.width != 'undefined'){
                if(is_int(options.width)){
                    mediaWidth = options.width;
                }else if(options.width.match("%")){
                    mediaWidth = window.getWidth() * (options.width.replace("%", "")*0.01);
                }else if(options.width.match("px")){
                    mediaWidth = parseInt( options.width.replace("px", "") );
                }
            }

            mediaHeight = 0;
            if(typeof options.height != 'undefined'){
                if(is_int(options.height)){
                    mediaHeight = options.height;
                }else if(options.height.match("%")){
                    mediaHeight = window.getHeight()*(options.height.replace("%", "")*0.01);
                }else if(options.height.match("px")){
                    mediaHeight = parseInt( options.height.replace("px", "") );
                }
            }

            preload = _media;
            startEffect();
            return false;
        },

        sendMessage: function(_form, _options){
            var form = $(_form);

            setOptions(_options);

            if (document.formvalidator.isValid(form)) {
                showMessages([options.sending_message], {
                    classes: 'cfpLoading'
                });
                new Request.JSON({
                    url: _form.get('action'),
                    data: _form,
                    onSuccess: function(response){
                        showMessages([response.message], {
                            success: (response.status == 1)
                            });
                    },
                    onError: function(text, error){
                        showMessages([error], {
                            success: 0
                        });
                    }
                }).send();
            }
            else {
                var messages = [options.correct_errors];
                var els = form.getElements('input[class~=invalid]');
                messages.append(
                    $$(els).map(function(el){
                        return el.get('title');
                    }).clean()
                    );

                showMessages(messages, {
                    success: 0
                });
            }

            return false;
        }


    };

    /*	Internal functions	*/
    function position() {

        if (options.resizeOpening) {
            fx.resize.cancel();
            fx.resize.start({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop,
                marginLeft: mLeft
            });
        } else {
            center.setStyles({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop,
                marginLeft: mLeft
            });
        }
    }

    function size() {
        winWidth = document.body.offsetWidth; //window.getWidth();
        winHeight = document.body.offsetHeight; //window.getHeight();
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
        /*if (Browser.Platform.ios || (Browser.name = 'ie' && Browser.version < 9) ) window[fn]("scroll", position);	// scroll position is updated only after movement has stopped*/
        window[fn]("resize", ContactFormBox.recenter);
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

    function showMessages(_messages, _options){
        var content = '<p>' + _messages.shift() + '</p>';
        if(_messages.length){
            content += '<ul>';
            _messages.each(function(error){
                content += '<li>' + error + '</li>';
            });
            content += '</ul>';
        }

        center.addClass('cfp_msg_container');

        _options.success = (typeof _options.success != 'undefined') ? _options.success:-1;

        var theClass = 'cfp_msg ' + (_options.classes?_options.classes:'');
        if(_options.success > -1){
            theClass += _options.success ? ' success':' error';
        }
        var _container = new Element('div', {
            'class': 'clearfix',
            'html':
        });
        message_div.set('html', );

        if(options.display != 'form' && !_options.success){
            message_div.grab(new Element('a', {
                html: options.go_back,
                'class': 'button',
                Events: {
                    click: function(e){
                        e.stop();
                        center.removeClass('cfp_msg_container');
                        ContactFormBox.open(mainForm, false);
                    }
                }
            }));
        }
        ContactFormBox.open(message_div, false);
    }

    function startEffect() {
        containerWidth = 'auto';
        if(mediaWidth){
            containerWidth = mediaWidth
            - container.getStyle('margin-left').toInt()
            - container.getStyle('margin-right').toInt()
            - container.getStyle('padding-left').toInt()
            - container.getStyle('padding-right').toInt()
            - center.getStyle('padding-left').toInt()
            - center.getStyle('padding-right').toInt();
        /*
            mediaWidth = containerWidth
                - media.getStyle('margin-left').toInt()
                - media.getStyle('margin-right').toInt()
                - container.getStyle('padding-left').toInt()
                - container.getStyle('padding-right').toInt();*/
        }

        containerHeight = 'auto';
        if(mediaHeight){
            containerHeight = mediaHeight
            - container.getStyle('margin-top').toInt()
            - container.getStyle('margin-bottom').toInt()
            - container.getStyle('padding-top').toInt()
            - container.getStyle('padding-bottom').toInt()
            - center.getStyle('padding-top').toInt()
            - center.getStyle('padding-bottom').toInt();
        /*
            mediaHeight = containerHeight
                - media.getStyle('margin-top').toInt()
                - media.getStyle('margin-bottom').toInt()
                - container.getStyle('padding-top').toInt()
                - container.getStyle('padding-bottom').toInt();*/
        }

        container.setStyles({
            backgroundImage: "none",
            display: "",
            width: containerWidth,
            height: containerHeight
        });

        media.adopt(preload.getChildren());
        /*
        marginLeft = center.getStyle('padding-left').toInt()+container.getStyle('margin-left').toInt();
        marginTop = center.getStyle('padding-top').toInt()+container.getStyle('margin-top').toInt();
        */
        /*paddingTop = container.getStyle('padding-top', 0).toInt();*/
        mediaWidth = mediaWidth ? mediaWidth : container.scrollWidth + center.getStyle('padding-left').toInt() + center.getStyle('padding-right').toInt();
        mediaHeight = mediaHeight ? mediaHeight : container.scrollHeight + center.getStyle('padding-top').toInt() + center.getStyle('padding-bottom').toInt();

        if (mediaHeight >= window.getHeight()) {
            mTop = -(window.getHeight() / 2);
        } else {
            mTop = -(mediaHeight/2);
        };

        if (mediaWidth >= window.getWidth()) {
            mLeft = -(window.getWidth() / 2);
        } else {
            mLeft = -(mediaWidth/2);
        };

        if (options.resizeOpening) {
            fx.resize.cancel();
            fx.resize.start({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop,
                marginLeft: mLeft
            });
        } else {
            center.setStyles({
                width: mediaWidth,
                height: mediaHeight,
                marginTop: mTop,
                marginLeft: mLeft
            });
        }
        mediaAnimate();
    }

    function setOptions(_options){
        if(!_options || status == statuses.opened)
            return;

        options = Object.merge(Object.clone(defaultOptions), _options);

        overlay.set('class', '');
        center.set('class', 'cfp_contact_form');

        if(typeof options.style != 'undefined'){
            style = options.style;
            overlay.addClass(style);
            center.addClass(style);
        }
    }

    function mediaAnimate() {
        center.removeClass('cfpLoading');
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
})(document.id);
