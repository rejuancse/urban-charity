/*global $:false
*  --------------------------------------
*         Table of Content
*  --------------------------------------
*   1. Menu Close Button2. search
    3. Sticky Nav
    4. Sticky Menu
    5. preloader
    6. show hide Language
    7. Sticky Nav
*   8. Keyboard nevigations.
*  -------------------------------------- 
*  -------------------------------------- */

jQuery(document).ready(function($){'use strict';


    /* --------------------------------------
    *       1. //Menu Close Button
    *  -------------------------------------- */
    
    if ($('#charity-navmenu').length > 0) {
        var button = document.getElementById('charity-navmenu');
        var span = button.getElementsByTagName('span')[0];

        button.onclick =  function() {
            span.classList.toggle('charity-navmenu-button-close');
        };

        $('#charity-navmenu').on('click', toggleOnClass);
        function toggleOnClass(event) {
            var toggleElementId = '#' + $(this).data('toggle'),
            element = $(toggleElementId);
            element.toggleClass('on');
        }

        // close hamburger menu after click a
        $( '.menu li a' ).on("click", function(){
            $('#charity-navmenu').click();
        });

        // Menu Toggler Rotate
        $('#mobile-menu ul li span.menu-toggler').click(function(){
            $(this).toggleClass('toggler-rotate');
        })
    }



    /* --------------------------------------
    *       2. search
    *  -------------------------------------- */
    $(".search-open-icon").on('click',function(e){
        e.preventDefault();
        $('.top-bar-search').addClass('open');
        $('.top-bar-search form input[type="search"]').focus();
        var focusableEls = $('.top-bar-search a[href]:not([disabled]), .top-bar-search button:not([disabled]), .top-bar-search input:not([disabled])');
        var firstFocusableEl = focusableEls[0];
        var lastFocusableEl = focusableEls[focusableEls.length - 1];
        var KEYCODE_TAB = 9;

        $('.top-bar-search').on('keydown', function (e) {
            if (e.key === 'Tab' || e.keyCode === KEYCODE_TAB) {
                if (e.shiftKey) /* shift + tab */ {
                    if (document.activeElement === firstFocusableEl) {
                        lastFocusableEl.focus();
                        e.preventDefault();
                    }
                } else /* tab */ {
                    if (document.activeElement === lastFocusableEl) {
                        firstFocusableEl.focus();
                        e.preventDefault();
                    }
                }
            }
        });
        $(".top-search-input-wrap, .top-search-overlay").fadeIn(200);
        $(".search-btn").focus();
        $(this).hide();
        $('.search-close-icon').show().css('display','inline-block');
    });
    $(".search-close-icon, .top-search-overlay").on('click',function(e){
        e.preventDefault();
        
        $(".top-search-input-wrap, .top-search-overlay").fadeOut(200);
        $('.search-close-icon').hide();
        $('.search-open-icon').show();
    });
    $(document).keydown(function(e){
        var code = e.keyCode || e.which;
        if( code == 27 ){
            $(".top-search-input-wrap, .top-search-overlay").fadeOut(200);
            $('.search-close-icon').hide();
            $('.search-open-icon').show();
        }
    });


    /* --------------------------------------
    *       3. Sticky Nav
    *  -------------------------------------- */
    jQuery(window).on('scroll', function(){'use strict';
        if ( jQuery(window).scrollTop() > 66 ) {
            jQuery('#masthead').addClass('sticky');
        } else {
            jQuery('#masthead').removeClass('sticky');
        }
    });

    /* --------------------------------------
    *       4. Sticky Menu
    *  -------------------------------------- */
    $(window).on('scroll', function() {
        var header_top_hieght = $('.header-top').height();
        var navbar_fixed_top = 'navbar-fixed-top';
        var header_style_one = $('.hb_style_one');

        if ($(window).scrollTop() > header_top_hieght) {
            header_style_one.addClass(navbar_fixed_top);
        } else {
            header_style_one.removeClass(navbar_fixed_top);
        }

        var header_style_two = $('.header-bottom');
        if ($('.hb_style_two').length) {
            var header_style_two_offset = $('.hb_style_two').offset().top;

            if ($(window).scrollTop() > header_style_two_offset) {
                header_style_two.addClass(navbar_fixed_top);
            } else {
                header_style_two.removeClass(navbar_fixed_top);
            }
        }
    });

     /* --------------------------------------
    *       5. preloader
    *  -------------------------------------- */
    $(window).on('load', function() {
        $('#preloader').delay(1000).fadeOut('slow', function() { $(this).remove(); });
    });


    /* --------------------------------------
    *       6. show hide Language
    *  -------------------------------------- */
    $('.lang_btn').on('click', function() {
        $('.language').toggleClass('show_hide_lang');
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });


    
     /* --------------------------------------
    *       7. Sticky Nav
    *  -------------------------------------- */
    /**
     * File main.js.
     *
     * Helps with accessibility for keyboard only users.
     *
     * This is the source file for what is minified in the urban_charity_skip_link_focus_fix() PHP function.
     *
     * Learn more: https://git.io/vWdr2
     */
    var isIe = /(trident|msie)/i.test( navigator.userAgent );

    if ( isIe && document.getElementById && window.addEventListener ) {
        window.addEventListener( 'hashchange', function() {
            var id = location.hash.substring( 1 ),
                element;

            if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
                return;
            }

            element = document.getElementById( id );

            if ( element ) {
                if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
                    element.tabIndex = -1;
                }

                element.focus();
            }
        }, false );
    }

    /* --------------------------------------
    *       8. Keyboard nevigations.
    *  -------------------------------------- */
    $('.main-navigation').on('keydown', function (e) {
        if ($('.main-navigation').hasClass('toggled')) {
            var focusableEls = $(' .main-navigation .menu-toggle, .main-navigation a[href]:not([disabled]), .main-navigation li');
            var firstFocusableEl = focusableEls[0];
            var lastFocusableEl = focusableEls[focusableEls.length - 1];
            var KEYCODE_TAB = 9;
            if (e.key === 'Tab' || e.keyCode === KEYCODE_TAB) {
                if (e.shiftKey) /* shift + tab */ {
                    if (document.activeElement === firstFocusableEl) {
                        lastFocusableEl.focus();
                        e.preventDefault();
                    }
                } else /* tab */ {
                    if (document.activeElement === lastFocusableEl) {
                        firstFocusableEl.focus();
                        e.preventDefault();
                    }
                }
            }
        }
    });


});

