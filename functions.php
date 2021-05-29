<?php
define( 'URBAN_CHARITY_CSS', get_template_directory_uri().'/assets/css/' );
define( 'URBAN_CHARITY_JS', get_template_directory_uri().'/assets/js/' );
define( 'URBAN_CHARITY_DIR', get_template_directory() );
define( 'URBAN_CHARITY_URI', trailingslashit(get_template_directory_uri()) );

/*-------------------------------------------*
 *              Register Navigation
 *------------------------------------------*/
register_nav_menus( array(
    'primary' => esc_html__( 'Primary Menu', 'urban-charity' ),
) );

/* -------------------------------------------
*           	Include TGM Plugins
* -------------------------------------------- */
require_once( URBAN_CHARITY_DIR . '/lib/class-tgm-plugin-activation.php');

/* ------------------------------------------
*           	Customizer
* ------------------------------------------- */
require_once( URBAN_CHARITY_DIR . '/lib/customizer/libs/googlefonts.php');
require_once( URBAN_CHARITY_DIR . '/lib/customizer/customizer.php');

/*-------------------------------------------*
 *				navwalker
 *------------------------------------------*/
require_once( URBAN_CHARITY_DIR . '/lib/menu/mobile-navwalker.php');

/*-------------------------------------------*
 *				Startup Register
 *------------------------------------------*/
require_once( URBAN_CHARITY_DIR . '/lib/main-function/Theme.php');

/*-------------------------------------------------------
 *				Urban charity Core
 *-------------------------------------------------------*/
require_once( URBAN_CHARITY_DIR . '/lib/main-function/charity-functions.php');

// Comments
include( get_parent_theme_file_path('lib/Charity_Comments.php') );

// Comments Callback Function
include( get_parent_theme_file_path('lib/charity-comments.php') );