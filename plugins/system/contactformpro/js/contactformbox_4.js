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
    /*	API		*/

    ContactFormBox = new Class({
        statuses: {
            opened: 1,
            closed: 2
        },
        status: 0,

        ui: {
            media: new Element("div", {
                id: "cfpMedia"
            }),
            preload: null,
            closeLink: new Element("a", {
                id: "cfpCloseLink",
                href: "#"
            }),
            overlay: new Element("div", {
                        id: "cfpOverlay"
                    }),
            center: new Element("div", {
                        id: "cfpCenter"
                    }),
            message_div: new Element('div', {
                        id: 'cfpResponseDiv',
                        'class': 'clearfix'
                    }),
            mainForm: null
        },
        top: 0,
        mTop: 0,
        left: 0,
        mLeft: 0,
        mediaWidth: 0,
        mediaHeight: 0,
        margin: 0,
        fx: {
            overlay: new Fx.Tween(this.ui.overlay, {
                property: "opacity",
                duration: 360
            }).set(0),
            media: new Fx.Tween(ContactFormBox.ui.media, {
                property: "opacity",
                duration: 360
            }),
            resize: new Fx.Morph(ContactFormBox.ui.center, {
                onComplete: this.mediaAnimate.bind(this)
            })
        },
        options: {},

        defaultOptions: {
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
            sending_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SENDING_MSG', 'The message is being sent'), //'Sending message',
            correct_errors: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_CORRECT_ERRORS', 'Please correct errors'), //'Please correct error(s)',
            go_back: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_BACK_LINK', 'Back'),
            success_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_SUCCESS', 'The message has been sent successfully'),
            error_message: Joomla.JText._('PLG_SYSTEM_CONTACTFORMPRO_GENERIC_ERROR', 'There was an error when sending message') //'Go back'
        },
        initialize:function(){
            document.id(document.body).adopt(
                $$([
                    ui.overlay.addEvent("click", close),
                    ui.center,
                    ui.message_div.setStyle('text-align', 'center')
                    ]).setStyle("display", "none")
                );

            var container = new Element("div", {
                style: 'position:relative;'
            }).inject(ui.center);

            ui.media.inject(container);

            ui.closeLink.addEvent("click", close).inject(container);

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
            window.addEvent('resize', recenter.bind(this));
        },

        ajax: function(_url, _options) {
            ui.center.addClass('cfpLoading');

            _media = new Element('div').setStyle('display', 'none');
            // Next line should be removed ?
            document.id(document.body).adopt(_media);
            ui.mainForm = _media;

            new Request.HTML({
                url: _url,
                data: _options,
                onSuccess: function(responseTree){
                    _media.adopt(responseTree); //set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res ' + ((response.status==1)?'success':'failure') + '">' + response.message + '</div></div></div>');
                    return open(_media, _options);
                },
                spinnerTarget: ui.media  // 120721
            }).send();
        },

        recenter: function(){	// Thanks to Garo Hussenjian (Xapnet Productions http://www.xapnet.com) for suggesting this addition
            if(status != statuses.opened)
                return;

            if (ui.center && !Browser.Platform.ios) {
                this.left = window.getScrollLeft() + (window.getWidth()/2);
                ui.center.setStyles({
                    left: this.left,
                    marginLeft: -(this.mediaWidth/2)-this.margin
                });
            }
        },

        open: function(_media, _options) {
            setOptions(defaultOptions, _options);

            ui.closeLink.set('html', options.close_button);
            fx.resize.set('duration', options.resizeDuration);

            size();
            setup(true);

            this.top = window.getScrollTop() + (window.getHeight()/2);
            this.left = window.getScrollLeft() + (window.getWidth()/2);
            this.margin = ui.center.getStyle('padding-left').toInt()+ui.media.getStyle('margin-left').toInt()+ui.media.getStyle('padding-left').toInt();


            ui.center.setStyles({
                top: this.top,
                left: this.left,
                width: options.initialWidth,
                height: options.initialHeight,
                marginTop: -(options.initialWidth/2)-this.margin,
                marginLeft: -(options.initialHeight/2)-this.margin,
                display: ""
            });

            status = statuses.opened;

            return changeMedia(_media, options);
        },

        changeMedia: function(_media, _options) {
            stop();

            setOptions(_options);

            ui.center.addClass('cfpLoading');
            if(typeof ui.preload != 'undefined') ui.preload.adopt(ui.media.getChildren());	// prevents loss of adopted data
            ui.media.set('html', '');

            this.mediaWidth = "";
            this.mediaHeight = "";
            if(typeof options.width != 'undefined'){
                if(is_int(options.width)){
                    this.mediaWidth = options.width;
                }else if(options.width.match("%")){
                    this.mediaWidth = window.getWidth() * (options.width.replace("%", "")*0.01);
                }else if(options.width.match("px")){
                    this.mediaWidth = parseInt( options.width.replace("px", "") );
                }
            }

            if(typeof options.height != 'undefined'){
                if(is_int(options.height)){
                    this.mediaHeight = options.height;
                }else if(options.height.match("%")){
                    this.mediaHeight = window.getHeight()*(options.height.replace("%", "")*0.01);
                }else if(options.height.match("px")){
                    this.mediaHeight = parseInt( options.height.replace("px", "") );
                }
            }

            ui.preload = _media;
            startEffect();
            return false;
        },

        sendMessage: function(_form, _options){
            var form = $(_form);

            if(status == statuses.closed){
                options = Object.merge(defaultOptions, _options);
            }

            if (document.formvalidator.isValid(form)) {
                ui.message_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><span class="cfp_msg_txt">'+options.sending_message+'</span></div><div class="cfp_msg_inner_bot cfp_msg_sending"></div></div>');
                new Request.JSON({
                    url: _form.get('action'),
                    data: _form,
                    onSuccess: function(response){
                        ui.message_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res ' + ((response.status==1)?'success':'failure') + '">' + response.message + '</div></div></div>');

                        if(options.display != 'form' && response.status != 1){
                            ui.message_div.grab(new Element('a', {
                                html: options.go_back,
                                Events: {
                                    click: function(e){
                                        e.stop();
                                        changeMedia(ui.mainForm, {});
                                    }
                                }
                            }));
                        }
                        open(ui.message_div, options);
                    },
                    onError: function(text, error){
                        // HTTP error
                        ui.message_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res failure"><span class="cfp_msg__res_txt">' + error + '</span></div></div></div>');
                        if(options.display != 'form'){
                            ui.message_div.grab(new Element('a', {
                                html: options.go_back,
                                events: {
                                    click: function(e){
                                        e.stop();
                                        changeMedia(ui.mainForm, {});
                                    }
                                }
                            }));
                        }
                        ContacFormBox.open(ui.message_div, options);
                    }
                }).send();
            }
            else {
                ui.message_div.set('html', '<div class="cfp_msg_inner"><div class="cfp_msg_inner_top"><div class="cfp_msg_res failure"><span class="cfp_msg__res_txt">' + options.correct_errors + '</span></div></div></div>');

                if(_options.display != 'form'){
                    ui.message_div.grab(new Element('a', {
                        html: options.go_back,
                        events: {
                            click: function(e){
                                e.stop();
                                changeMedia(ui.mainForm, {});
                            }
                        }
                    }));
                }
                open(ui.message_div, options);
            }

            return false;
        },


        setOptions: function(_options){
            if(status != statuses.closed)
                options = Object.merge(Object.clone(defaultOptions), _options);
        },

    /*	Internal functions	*/
    /*
    function position() {
        overlay.setStyles({
            top: window.getScrollTop(),
            left: window.getScrollLeft()
        });
    }*/

    size: function() {
        overlay.setStyles({
            width: document.body.offsetWidth,
            height: document.body.offsetHeight
        });
    },

    setup: function(open) {
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
        window[fn]("resize", size.bind(this));
        if (options.keyboard) document[fn]("keydown", keyDown.bind(this));
    },

    keyDown: function(event) {
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
    },

    is_int: function(value){
        if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
            return true;
        } else {
            return false;
        }
    },

    startEffect: function() {
        media.setStyles({
            backgroundImage: "none",
            display: "",
            width: "auto",
            height: "auto"
        });

        media.adopt(preload.getChildren());

        this.mediaWidth = (this.mediaWidth != "") ? this.mediaWidth : media.offsetWidth;
        this.mediaHeight = (this.mediaHeight != "") ? this.mediaHeight : media.offsetHeight;

        if (this.mediaHeight >= this.top+this.top) {
            this.mTop = -this.top
        } else {
            this.mTop = -(this.mediaHeight/2)
        };
        if (this.mediaWidth >= this.left+this.left) {
            this.mLeft = -this.left
        } else {
            this.mLeft = -(this.mediaWidth/2)
        };

        if (options.resizeOpening) {
            fx.resize.cancel();
            fx.resize.start({
                width: this.mediaWidth,
                height: this.mediaHeight,
                marginTop: this.mTop-this.margin,
                marginLeft: this.mLeft-this.margin
            });
        } else {
            ui.center.setStyles({
                width: this.mediaWidth,
                height: this.mediaHeight,
                marginTop: this.mTop-this.margin,
                marginLeft: this.mLeft-this.margin
            });
        }
        mediaAnimate();
    },

    mediaAnimate: function () {
        ui.center.removeClass('cfpLoading');
        fx.media.start(1);
    },

    stop: function() {
        if (ui.preload) {
            ui.preload.adopt(ui.media.getChildren());	// prevents loss of adopted data
            ui.preload.onload = function(){}; // $empty replacement
        }
        fx.resize.cancel();
        fx.media.cancel().set(0);
    },

    close: function() {
        ui.preload.adopt(ui.media.getChildren());	// prevents loss of adopted data
        ui.preload.onload = function(){}; // $empty replacement
        ui.media.empty();
        for (var f in fx) fx[f].cancel();
        ui.center.setStyle("display", "none");
        fx.overlay.chain(setup.bind(this)).start(0);

        status = statuses.closed;

        return false;
    }

    })();
})(document.id);