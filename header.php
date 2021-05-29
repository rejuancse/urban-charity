<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    } else {
        do_action( 'wp_body_open' );
    } ?>

    <?php 
        $layout = get_theme_mod( 'boxfull_en', 'fullwidth' );
        $headerlayout = get_theme_mod( 'head_style', 'solid' );
    ?> 

    <div id="page" class="hfeed site <?php echo esc_attr($layout); ?>">
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'urban-charity' ); ?></a>
        <!-- header top area start -->
        <?php if ( get_theme_mod( 'topbar_enable', false ) ) { get_template_part('lib/topbar'); } ?>
        <!-- header top area end -->
        <header id="masthead" class="site-header header header-<?php echo esc_attr($headerlayout);?>">
            <div class="container">
                <div class="main-menu-wrap row clearfix">
                    <div class="col-sm-6 col-md-3 col-6 align-self-center">
                        <div class="charity-navbar-header">
                            <div class="logo-wrapper">
                                <?php if( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                                    the_custom_logo();
                                }else { ?>
                                    <h1 class="site-title">
                                        <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(get_bloginfo('name'));?> </a>
                                    </h1>
                                    <?php $tagline = get_bloginfo('description'); ?>
                                    <?php if ( $tagline!='' ) { ?>
                                        <p class="logo_tagline"><?php bloginfo('description'); ?></p><!-- Site Tagline --> 
                                    <?php } ?>
                                <?php } ?>
                            </div>     
                        </div><!--/#charity-navbar-header-->   
                    </div><!--/.col-sm-2-->

                    <!-- Mobile Menu in Search -->
                    <div class="mobile-register col-sm-6 col-md-9 col-6 hidden-lg-up align-self-center align-self-end"> 
                        <div id="site-navigation" class="main-navigation toggled">
                            <div class="navbar-header clearfix">
                                <button id="charity-navmenu" class="menu-toggle navbar-toggle charity-navmenu-button" aria-controls="primary-menu" aria-expanded="false" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="slicknav_icon charity-navmenu-button-open"></span>
                                </button>
                            </div>
                        </div><!-- #site-navigation -->
                    </div>

                    <div class="col-md-9 common-menu hidden-md-down">
                        <?php if ( has_nav_menu( 'primary' ) ) { ?>
                            <div id="main-menu" class="common-menu-wrap">
                                <?php 
                                    wp_nav_menu(  array(
                                            'theme_location' => 'primary',
                                            'container'      => '', 
                                            'menu_class'     => 'nav',
                                            'fallback_cb'    => 'wp_page_menu',
                                            'depth'          => 4,
                                        )
                                    ); 
                                ?>      
                            </div><!--/#main-menu-->
                        <?php } ?>

                        <?php if( get_theme_mod( 'header_search', true ) ): ?>
                        <div class="charity-login-register">
                            <?php if( get_theme_mod( 'header_search', true ) ): ?>
                                
                                <div class="charity-search-wrap ">
                                    <div class="top-search-input-wrap top-bar-search open">
                                        <div class="top-search-overlay"></div>
                                        <form action="<?php echo esc_url(home_url( '/' )); ?>" method="get">
                                            <div class="search-wrap"> 
                                                <div class="search  pull-right charity-top-search">
                                                    <div class="sp_search_input">
                                                        <input type="search" value="<?php echo get_search_query(); ?>" name="s" id="s" class="form-control search-btn" placeholder="<?php esc_attr_e('Search . . . . .','urban-charity'); ?>" autocomplete="off" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <a href="#" class="charity-search search-close-icon skip-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>

                                    <a href="#" class="search-icon-box charity-search search-open-icon skip-link">
                                        <i class="fa fa-search"></i>
                                    </a> 
                                </div>
                            <?php endif; ?>
                        </div> 

                        <?php endif; ?>
                    </div><!--/.col-sm-9--> 

                    <div id="site-navigation" class="main-navigation toggled">
                        <ul id="primary-menu" class="nav navbar-nav nav-menu">
                            <div id="mobile-menu" class="hidden-lg-up">
                                <div class="collapse navbar-collapse">
                                    <?php 
                                        if ( has_nav_menu( 'primary' ) ) {
                                            wp_nav_menu( array(
                                                'theme_location'      => 'primary',
                                                'container'           => false,
                                                'menu_class'          => 'nav navbar-nav',
                                                'fallback_cb'         => 'wp_page_menu',
                                                'depth'               => 3,
                                                'walker'              => new urban_charity_mobile_navwalker()
                                                )
                                            ); 
                                        }
                                    ?>
                                </div>
                            </div><!--/.#mobile-menu-->
                        </ul>
                    </div><!-- #site-navigation -->

                </div><!--/.main-menu-wrap-->     
            </div><!--/.container--> 
        </header><!--/.header-->
