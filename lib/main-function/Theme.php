<?php

namespace urban_charity;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Urban_Charity_Theme {

    protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_filter( 'body_class', array($this, 'urban_charity_body_class'));
        add_action('wp_enqueue_scripts', array($this, 'urban_charity_style'));
        add_action('after_setup_theme', array($this, 'urban_charity_setup'));
    }

    public function urban_charity_setup(){
        load_theme_textdomain( 'urban-charity', get_template_directory() . '/languages' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'urban-charity-large', 1140, 570, true );
        add_image_size( 'urban-charity-medium', 370, 250, true );
        add_image_size( 'urban-charity-blog', 385, 314, true );
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
        add_theme_support( 'automatic-feed-links' );

        # Custom Logo.
        add_theme_support( 'custom-logo');

        if ( ! isset( $content_width ) ){
            $content_width = 660;
        }
    }

    public function urban_charity_style() {
        wp_enqueue_style( 'default-google-font', '//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i' );

        wp_enqueue_style( 'urban-charity-font', urban_charity_fonts_url(), array(), null );

        wp_enqueue_media();
        wp_enqueue_style( 'bootstrap.min', URBAN_CHARITY_CSS . 'bootstrap.min.css',false,'all');
        wp_enqueue_style( 'font-awesome.min', URBAN_CHARITY_CSS . 'font-awesome.min.css',false,'all');
        wp_enqueue_style( 'urban-charity-woo', URBAN_CHARITY_CSS . 'woocommerce.css',false,'all');
        wp_enqueue_style( 'urban-charity-main', URBAN_CHARITY_CSS . 'main.css',false,'all');
        wp_enqueue_style( 'urban-charity-responsive', URBAN_CHARITY_CSS . 'responsive.css',false,'all');
        wp_enqueue_style( 'urban-charity-style',get_stylesheet_uri());
        wp_add_inline_style( 'urban-charity-style', urban_URBAN_CHARITY_CSS_generator() );

        # JS.
        wp_enqueue_script('bootstrap',URBAN_CHARITY_JS.'bootstrap.min.js',array(),false,true);
        if ( is_singular() ) {wp_enqueue_script( 'comment-reply' );}
        wp_enqueue_script('urban-charity-navigation',URBAN_CHARITY_JS.'navigation.js',array(),false,true);
        wp_enqueue_script('urban-charity-main',URBAN_CHARITY_JS.'main.js',array(),false,true);
    }

    /* -------------------------------------------
     *              Custom body class
     * ------------------------------------------- */
    public function urban_charity_body_class( $classes ) {
        if ( is_singular() ) {
            // Adds `singular` to singular pages.
            $classes[] = 'singular';
        } else {
            // Adds `hfeed` to non singular pages.
            $classes[] = 'hfeed';
        }

        return $classes;
    }
}
new Urban_Charity_Theme();

