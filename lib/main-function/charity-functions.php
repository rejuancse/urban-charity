<?php

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function urban_charity_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    } 
}
add_action( 'wp_head', 'urban_charity_pingback_header' );


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function urban_charity_skip_link_focus_fix() {
    ?>
    <script>
    /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
    </script>
    <?php
}
add_action( 'wp_print_footer_scripts', 'urban_charity_skip_link_focus_fix' );



/*-----------------------------------------------------
*              Custom Excerpt Length
*----------------------------------------------------*/
if(!function_exists('urban_charity_excerpt_max_charlength')):
    function urban_charity_excerpt_max_charlength($charlength) {
        $excerpt = get_the_excerpt();
        $charlength++;

        if ( mb_strlen( $excerpt ) > $charlength ) {
            $subex = mb_substr( $excerpt, 0, $charlength - 5 );
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ( $excut < 0 ) {
                return mb_substr( $subex, 0, $excut );
            } else {
                return $subex;
            }
        } else {
            return $excerpt;
        }
    }
endif;


/*-------------------------------------------*
 *      Urban charity Pagination
 *------------------------------------------*/
if ( ! function_exists( 'urban_charity_pagination' ) ) :
    /**
     * Documentation for function.
     */
    function urban_charity_pagination() {
        echo '<div class="charity-pagination">';
        the_posts_pagination(
            array(
                'mid_size'      => 2,
                'type'          => 'list',
                'prev_text'     => __('<i class="fa fa-angle-left" aria-hidden="true"></i>','urban-charity'),
                'next_text'     => __('<i class="fa fa-angle-right" aria-hidden="true"></i>','urban-charity'),
            )
        );
        echo '</div>';
    }
endif;



/*-------------------------------------------*
 *      Urban charity Widget Registration
 *------------------------------------------*/
if(!function_exists('urban_charity_widdget_init')):

    function urban_charity_widdget_init()
    {
        $bottomcolumn = get_theme_mod( 'bottom_column', '3' );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'urban-charity' ),
                'id'            => 'sidebar',
                'description'   => esc_html__( 'Widgets in this area will be shown on Sidebar.', 'urban-charity' ),
                'before_title'  => '<h3 class="widget_title">',
                'after_title'   => '</h3>',
                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                'after_widget'  => '</div>'
            )
        );      

        register_sidebar(
            array( 
                'name'          => esc_html__( 'Footer', 'urban-charity' ),
                'id'            => 'bottom1',
                'description'   => esc_html__( 'Widgets in this area will be shown in footer section.' , 'urban-charity'),
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
                'before_widget' => '<div class="col-sm-6 col-md-'.esc_attr($bottomcolumn).' col-xs-12 tb-height"><div class="widget-title">',
                'after_widget'  => '</div></div>'
            )
        );

    }

    add_action('widgets_init','urban_charity_widdget_init');
endif;


if ( ! function_exists( 'urban_charity_fonts_url' ) ) :
    function urban_charity_fonts_url() {
    $fonts_url = '';

    $open_sans = _x( 'on', 'Poppins font: on or off', 'urban-charity' );

    if ( 'off' !== $open_sans ) {
    $font_families = array();

    if ( 'off' !== $open_sans ) {
    $font_families[] = 'Poppins:300,400,500,600,700';
    }

    $query_args = array(
    'family'  => urlencode( implode( '|', $font_families ) ),
    'subset'  => urlencode( 'latin,latin-ext' ),
    );

    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url_raw( $fonts_url );
    }
endif;


/*-------------------------------------------------------
*           Urban Charity Breadcrumb
*-------------------------------------------------------*/
if(!function_exists('urban_charity_breadcrumbs')):
    function urban_charity_breadcrumbs(){ ?>
        <li><a href="<?php echo esc_url(home_url()); ?>" class="breadcrumb_home"><?php esc_html_e('Home', 'urban-charity') ?></a></li>
        <?php
            if(function_exists('is_product')){
                $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
                if(is_product()){
                    echo "<li><a class='skip-link' href='".esc_url($shop_page_url)."'>Shop</a></li>";
                }
            }
        ?>
        <li class="active">
            <span>
                <?php if(function_exists('is_shop')){ if(is_shop()){ esc_html_e('shop','urban-charity'); } } ?>

                <?php if( is_tag() ) { ?>
                <?php esc_html_e('Posts Tagged ', 'urban-charity') ?><span class="raquo">/</span><?php single_tag_title(); echo('/'); ?>
                <?php } elseif (is_day()) { ?>
                <?php esc_html_e('Posts made in', 'urban-charity') ?> <?php the_time('F jS, Y'); ?>
                <?php } elseif (is_month()) { ?>
                <?php esc_html_e('Posts made in', 'urban-charity') ?> <?php the_time('F, Y'); ?>
                <?php } elseif (is_year()) { ?>
                <?php esc_html_e('Posts made in', 'urban-charity') ?> <?php the_time('Y'); ?>
                <?php } elseif (is_search()) { ?>
                <?php esc_html_e('Search results for', 'urban-charity') ?> <?php the_search_query() ?>
                <?php } elseif (is_single()) { ?>
                <?php $category = get_the_category();
                    if ( $category ) {
                        $catlink = get_category_link( $category[0]->cat_ID );
                        echo ('<a class="skip-link" href="'.esc_url($catlink).'">'.esc_html($category[0]->cat_name).'</a> '.'<span class="raquo">Single Post</span> ');
                    } elseif (get_post_type() == 'product'){
                        echo esc_attr(get_the_title());
                    }
                ?>
                <?php } elseif (is_category()) { ?>
                <?php single_cat_title(); ?>
                <?php } elseif (is_tax()) { ?>
                <?php
                $charity_taxonomy_links = array();
                $charity_term = get_queried_object();
                $charity_term_parent_id = $charity_term->parent;
                $charity_term_taxonomy = $charity_term->taxonomy;
                while ( $charity_term_parent_id ) {
                    $charity_current_term = get_term( $charity_term_parent_id, $charity_term_taxonomy );
                    $charity_taxonomy_links[] = '<a class="skip-link" href="' . esc_url( get_term_link( $charity_current_term, $charity_term_taxonomy ) ) . '" title="' . esc_attr( $charity_current_term->name ) . '">' . esc_html( $charity_current_term->name ) . '</a>';
                    $charity_term_parent_id = $charity_current_term->parent;
                }
                if ( !empty( $charity_taxonomy_links ) ) echo implode( ' <span class="raquo">/</span> ', esc_url(array_reverse( $charity_taxonomy_links ) )) . ' <span class="raquo">/</span> ';
                    echo esc_html( $charity_term->name );
                } elseif (is_author()) {
                    global $wp_query;
                    $curauth = $wp_query->get_queried_object();
                    esc_html_e('Posts by ', 'urban-charity'); echo ' ',esc_attr($curauth->nickname);
                } elseif (is_page()) {
                    echo esc_attr(get_the_title());
                } elseif (is_home()) {
                    esc_html_e('Blog', 'urban-charity');
                } ?>
            </span>
        </li> 
    <?php
    }
endif;


/*-------------------------------------------------------
*           Urban Charity CSS Generator
*-------------------------------------------------------*/
if(!function_exists('urban_URBAN_CHARITY_CSS_generator')){
    function urban_URBAN_CHARITY_CSS_generator(){

        $output = '';

            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $major_color = get_theme_mod( 'major_color', '#f8c218' );
                if($major_color){
                    $output .= 'a,.footer-wrap .social-share li a:hover,.bottom-widget .contact-info i,.bottom-widget .widget ul li a:hover, .latest-blog-content .latest-post-button:hover,.widget ul li a:hover,.widget-blog-posts-section .entry-title  a:hover,.entry-header h2.entry-title.blog-entry-title a:hover,.entry-summary .wrap-btn-style a.btn-style:hover,.main-menu-wrap .navbar-toggle:hover,.topbar .social-share ul >li a:hover,.woocommerce .star-rating span:before,.charity-post .blog-post-meta li a:hover,.charity-post .content-item-title a:hover, .woocommerce ul.products li.product .added_to_cart, .woocommerce ul.products li.product:hover .button.add_to_cart_button,.woocommerce ul.products li.product:hover .added_to_cart, .woocommerce div.product p.price, .woocommerce div.product span.price, .product_meta .sku_wrapper span.sku, .woocommerce-message::before, .charity-campaign-post .entry-category a:hover, .charity-campaign-post .entry-author a:hover,.charity-campaign-post h3 a:hover, .crumbs ul.crumbs-list li span, #mobile-menu ul li.active>a,#mobile-menu ul li a:hover, .btn.btn-border-charity,.entry-summary .wrap-btn-style a.btn-style, .woocommerce ul.products li.product .price,
                    .social-share-wrap ul li a:hover, .product-timeline ul li,.charity-campaign-post h3 a:hover, .wp-calendar-table tr th, .logo-wrapper .logo_tagline { color: '. esc_attr($major_color) .'; }';
                }
                if($major_color){
                    $output .= '.campaign-image .camp-title:hover,.charity-campaign-wrapper a:hover,.charity-campaign-post h3 a:hover, .comment-author .comment-metadata a.comment-edit-link:hover, .logo-wrapper h1 a:hover { color:'. esc_attr($major_color) .'!important; }';
                }

                if($major_color){
                    $output .= '.woocommerce-tabs .wc-tabs>li.active:before, .team-content4, .classic-slider .owl-dots .active>span,
                    .project-navigator a.prev:hover,.project-navigator a.next:hover,.woocommerce #respond input#submit:hover,
                    .charity-pagination .page-numbers li a:hover,.charity-pagination .page-numbers li span.current,
                    .woocommerce nav.woocommerce-pagination ul li a:hover,.woocommerce nav.woocommerce-pagination ul li span.current,
                    .form-submit input[type=submit], .woocommerce div.product span.onsale, .woocommerce-tabs .nav-tabs>li.active>a, .woocommerce-tabs .nav-tabs>li.active>a:focus, .woocommerce-tabs .nav-tabs>li.active>a:hover, .woocommerce a.button:hover, .woocommerce span.onsale, .woocommerce .product-thumbnail-outer a.ajax_add_to_cart:hover, .woocommerce .woocommerce-info, .woocommerce a.added_to_cart, .charity-product-slider .slick-prev:hover, .charity_wooshop_widgets .ui-slider-range, .htop-donate-btn a, .woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider-horizontal,.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content, .charity_wooshop_widgets .widget .button,.thm-progress-bar .progress-bar,.order-view .label-info,.post-meta-info-list-in a:hover,.progressbar-content-wrapper .thm-progress-bar .progress .progress-bar, .footer-inner .widget-title h2:before, .footer-inner .widget-title h2:after { background: '. esc_attr($major_color) .'; }';
                } 

                if($major_color){

                    $output .= 'input:focus, textarea:focus, keygen:focus, select:focus,.classic-slider.layout2 .classic-slider-btn:hover,
                    .classic-slider.layout3 .classic-slider-btn:hover,.classic-slider.layout4 .classic-slider-btn:hover,.portfolio-slider .portfolio-slider-btn:hover,.info-wrapper a.white, .charity-pagination ul li .page-numbers { border-color: '. esc_attr($major_color) .'; }';

                    $output .= '.wpcf7-submit,.project-navigator a.prev,.project-navigator a.next,.charity-pagination .page-numbers li a:hover,.charity-pagination .page-numbers li span.current, .woocommerce nav.woocommerce-pagination ul li a:hover,.woocommerce nav.woocommerce-pagination ul li span.current,.portfolio-slider .portfolio-slider-btn,.wpcf7-form input:focus,
                        .btn.btn-border-charity, .btn.btn-border-white:hover{ border: 2px solid '. esc_attr($major_color) .'; } 
                        
                        .wpcf7-submit:hover, .classic-slider.layout2, .blog-post-content .readmore-btn a:hover, .classic-slider-btn:hover,.classic-slider.layout3 .classic-slider-btn:hover,.classic-slider.layout4 .classic-slider-btn:hover,.classic-slider.layout4 .container >div,.portfolio-slider .portfolio-slider-btn:hover, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,.mc4wp-form-fields .send-arrow button, .charity-woo-product-details .addtocart-btn .add_to_cart_button:hover, .charity-woo-product-details .addtocart-btn .added_to_cart:hover,.post-meta-info-list-in a:hover, .widget ul.charity-social-share li a:hover, .sidebar h3.widget_title:before, .social-link a:hover, .charity-social-share li a:hover, .charity-error-wrapper .btn.btn-charity {   background-color: '. esc_attr($major_color) .'; border-color: '. esc_attr($major_color) .'; }';
                }

                if($major_color){
                    $output .= '.carousel-woocommerce .owl-nav .owl-next:hover,.carousel-woocommerce .owl-nav .owl-prev:hover,.charity-latest-post-content .entry-title a:hover,.common-menu-wrap .nav>li.current>a,
                    .header-solid .common-menu-wrap .nav>li.current>a,.portfolio-filter a:hover,.portfolio-filter a.active,.latest-review-single-layout2 .latest-post-title a:hover, .blog-arrows a:hover{ border-color: '. esc_attr($major_color) .';  }';
                }

                if($major_color){
                    $output .= '.woocommerce-MyAccount-navigation ul li.is-active, .woocommerce-MyAccount-navigation ul li:hover,.carousel-woocommerce .owl-nav .owl-next:hover,.carousel-woocommerce .owl-nav .owl-prev:hover, .portfolio-thumb-wrapper-layout4 .portfolio-thumb:before, .btn.btn-slider:hover, .btn.btn-slider:focus, .leave-comment form input#submit { background-color: '. esc_attr($major_color) .'; border-color: '. esc_attr($major_color) .'; }';
                }
            }

            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $hover_color = get_theme_mod( 'hover_color', '#000000' );
                if( $hover_color ){
                    $output .= 'a:hover, .widget.widget_rss ul li a,.main-menu-wrap .navbar-toggle:hover,.footer-copyright a:hover,.entry-summary .wrap-btn-style a.btn-style:hover, .blog-post-meta li a:hover{ color: '.esc_attr( $hover_color ) .'; }';
                    $output .= '.error-page-inner a.btn.btn-primary.btn-lg:hover,.btn.btn-primary:hover,input[type=button]:hover,
                    .widget.widget_search #searchform .btn-search:hover,.team-content2,
                    .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.order-view .label-info:hover{ background-color: '.esc_attr( $hover_color ) .'; }';

                    $output .= '.woocommerce a.button:hover{ border-color: '.esc_attr( $hover_color ) .'; }';
                }
            }

    
        /* --------------------------------------
        ------------- Quick Style ---------------
        ----------------------------------------- */

        $bstyle = $mstyle = $h1style = $h2style = $h3style = $h4style = $h5style = '';
        //body
        if ( get_theme_mod( 'body_font_size', '14' ) ) {
            $body_font_size = get_theme_mod( 'body_font_size', '14' );
            $bstyle .= 'font-size:'.(int) esc_attr($body_font_size) .'px;';
        }
        if ( get_theme_mod( 'body_google_font', 'Open Sans' ) ) {
            $body_google_font = get_theme_mod( 'body_google_font', 'Open Sans' );
            $bstyle .= 'font-family:'.esc_attr($body_google_font).';';
        }
        if ( get_theme_mod( 'body_font_weight', '400' ) ) {
            $body_font_weight = get_theme_mod( 'body_font_weight', '400' );
            $bstyle .= 'font-weight: '.esc_attr($body_font_weight).';';
        }
        if ( get_theme_mod('body_font_height', '24') ) {
            $body_font_height = get_theme_mod('body_font_height', '24');
            $bstyle .= 'line-height: '.(int) esc_attr($body_font_height).'px;';
        }
        if ( get_theme_mod('body_font_color', '#191919') ) {
            $body_font_color = get_theme_mod('body_font_color', '#191919');
            $bstyle .= 'color: '.esc_attr($body_font_color).';';
        }

        //menu
        $mstyle = '';
        if ( get_theme_mod( 'menu_font_size', '14' ) ) {
            $menu_font_size = get_theme_mod( 'menu_font_size', '14' );
            $mstyle .= 'font-size:'.(int) esc_attr($menu_font_size).'px;';
        }
        if ( get_theme_mod( 'menu_google_font', 'Open Sans' ) ) {
            $menu_google_font = get_theme_mod( 'menu_google_font', 'Open Sans' );
            $mstyle .= 'font-family:'.esc_attr($menu_google_font).';';
        }
        if ( get_theme_mod( 'menu_font_weight', '400' ) ) {
            $menu_font_weight = get_theme_mod( 'menu_font_weight', '400' );
            $mstyle .= 'font-weight: '.esc_attr($menu_font_weight).';';
        }
        if ( get_theme_mod('menu_font_height', '18') ) {
            $menu_font_height = get_theme_mod('menu_font_height', '18');
            $mstyle .= 'line-height: '.(int) esc_attr($menu_font_height).'px;';
        }
        // if ( get_theme_mod('menu_font_color', '#fff') ) {
        //     $menu_font_color = get_theme_mod('menu_font_color', '#fff');
        //     $mstyle .= 'color: '.esc_attr($menu_font_color).';';
        // }

        //heading1
        $h1style = '';
        if ( get_theme_mod( 'h1_font_size', '42' ) ) {
            $h1_font_size = get_theme_mod( 'h1_font_size', '42' );
            $h1style .= 'font-size:'.(int) esc_attr($h1_font_size).'px;';
        }
        if ( get_theme_mod( 'h1_google_font', 'Open Sans' ) ) {
            $h1_google_font = get_theme_mod( 'h1_google_font', 'Open Sans' );
            $h1style .= 'font-family:'.esc_attr($h1_google_font).';';
        }
        if ( get_theme_mod( 'h1_font_weight', '600' ) ) {
            $h1_font_weight = get_theme_mod( 'h1_font_weight', '700' );
            $h1style .= 'font-weight: '.esc_attr($h1_font_weight).';';
        }
        if ( get_theme_mod('h1_font_height', '42') ) {
            $h1_font_height = get_theme_mod('h1_font_height', '42');
            $h1style .= 'line-height: '.(int) esc_attr($h1_font_height).'px;';
        }
        if ( get_theme_mod('h1_font_color', '#333') ) {
            $h1_font_color = get_theme_mod('h1_font_color', '#333');
            $h1style .= 'color: '.esc_attr($h1_font_color).';';
        }

        # heading2
        $h2style = '';
        if ( get_theme_mod( 'h2_font_size', '36' ) ) {
            $h2_font_size = get_theme_mod( 'h2_font_size', '36' );
            $h2style .= 'font-size:'.(int) esc_attr($h2_font_size).'px;';
        }
        if ( get_theme_mod( 'h2_google_font', 'Open Sans' ) ) {
            $h2_google_font = get_theme_mod( 'h2_google_font', 'Open Sans' );
            $h2style .= 'font-family:'.esc_attr($h2_google_font).';';
        }
        if ( get_theme_mod( 'h2_font_weight', '700' ) ) {
            $h2_font_weight = get_theme_mod( 'h2_font_weight', '600' );
            $h2style .= 'font-weight: '.esc_attr($h2_font_weight).';';
        }
        if ( get_theme_mod('h2_font_height', '42') ) {
            $h2_font_height = get_theme_mod('h2_font_height', '42');
            $h2style .= 'line-height: '.(int) esc_attr($h2_font_height).'px;';
        }
        if ( get_theme_mod('h2_font_color', '#333') ) {
            $h2_font_color = get_theme_mod('h2_font_color', '#333');
            $h2style .= 'color: '.esc_attr($h2_font_color).';';
        }

        //heading3
        $h3style = '';
        if ( get_theme_mod( 'h3_font_size', '26' ) ) {
            $h3style .= 'font-size:'.(int) esc_attr(get_theme_mod( 'h3_font_size', '26' ) ).'px;';
        }
        if ( get_theme_mod( 'h3_google_font', 'Open Sans' ) ) {
            $h3style .= 'font-family:'.esc_attr(get_theme_mod( 'h3_google_font', 'Open Sans' ) ).';';
        }
        if ( get_theme_mod( 'h3_font_weight', '700' ) ) {
            $h3style .= 'font-weight: '.esc_attr(get_theme_mod( 'h3_font_weight', '600' ) ).';';
        }
        if ( get_theme_mod('h3_font_height', '28') ) {
            $h3style .= 'line-height: '.(int) esc_attr(get_theme_mod('h3_font_height', '28') ).'px;';
        }
        if ( get_theme_mod('h3_font_color', '#333') ) {
            $h3style .= 'color: '.esc_attr(get_theme_mod('h3_font_color', '#333') ).';';
        }

        //heading4
        $h4style = '';
        if ( get_theme_mod( 'h4_font_size', '18' ) ) {
            $h4style .= 'font-size:'.(int) esc_attr(get_theme_mod( 'h4_font_size', '18' ) ).'px;';
        }
        if ( get_theme_mod( 'h4_google_font', 'Open Sans' ) ) {
            $h4style .= 'font-family:'.esc_attr(get_theme_mod( 'h4_google_font', 'Open Sans' ) ).';';
        }
        if ( get_theme_mod( 'h4_font_weight', '700' ) ) {
            $h4style .= 'font-weight: '.esc_attr(get_theme_mod( 'h4_font_weight', '600' ) ).';';
        }
        if ( get_theme_mod('h4_font_height', '26') ) {
            $h4style .= 'line-height: '.(int) esc_attr(get_theme_mod('h4_font_height', '26') ).'px;';
        }
        if ( get_theme_mod('h4_font_color', '#333') ) {
            $h4style .= 'color: '.esc_attr(get_theme_mod('h4_font_color', '#333') ).';';
        }

        //heading5
        $h5style = '';
        if ( get_theme_mod( 'h5_font_size', '16' ) ) {
            $h5style .= 'font-size:'.(int) esc_attr(get_theme_mod( 'h5_font_size', '16' ) ).'px;';
        }
        if ( get_theme_mod( 'h5_google_font', 'Open Sans' ) ) {
            $h5style .= 'font-family:'.esc_attr(get_theme_mod( 'h5_google_font', 'Open Sans' ) ).';';
        }
        if ( get_theme_mod( 'h5_font_weight', '700' ) ) {
            $h5style .= 'font-weight: '.esc_attr(get_theme_mod( 'h5_font_weight', '600' ) ).';';
        }
        if ( get_theme_mod('h5_font_height', '24') ) {
            $h5style .= 'line-height: '.(int) esc_attr(get_theme_mod('h5_font_height', '24') ).'px;';
        }
        if ( get_theme_mod('h5_font_color', '#333') ) {
            $h5style .= 'color: '.esc_attr(get_theme_mod('h5_font_color', '#333') ).';';
        }

        $output .= 'body {'.$bstyle.'}';
        $output .= '.common-menu-wrap .nav>li>a{'.$mstyle.'}';
        $output .= 'h1{'.$h1style.'}';
        $output .= 'h2{'.$h2style.'}';
        $output .= 'h3{'.$h3style.'}';
        $output .= 'h4{'.$h4style.'}';
        $output .= 'h5{'.$h5style.'}';

        //Header
        if ( get_theme_mod( 'header_color', '#454545' ) ) {
            $output .= '.site-header{ background-color: '.esc_attr( get_theme_mod( 'header_color', '#454545' ) ) .'; }';
        }

        $output .= '.site-header{ padding-top: '. (int) esc_attr( get_theme_mod( 'header_padding_top', '0' ) ) .'px; }';

        $output .= '.site-header{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'header_padding_bottom', '0' ) ) .'px; }';

        //sticky Header
        if ( get_theme_mod( 'header_fixed', false ) ){
            $output .= '.site-header.sticky{ position:fixed;top:0;left:auto; z-index:99999;margin:0 auto; width:100%;-webkit-animation: fadeInDown 300ms;animation: fadeInDown 300ms;}';
            $output .= '.site-header.sticky.header-transparent .main-menu-wrap{ margin-top: 0;}';
            if ( get_theme_mod( 'sticky_header_color', '#000' ) ){
                $sticybg = get_theme_mod( 'sticky_header_color', '#000');
                $output .= '.site-header.sticky{ background-color: '.esc_attr($sticybg).';}';
            }
            $output .= '@keyframes fadeInDown {
              from {
                opacity: 0;
                transform: translate3d(0, -50%, 0);
              }

              to {
                opacity: 1;
                transform: none;
              }
            }';
        }

        // sub header
        $output .= '.crumbs .crumbs-title h2 { color:'.get_theme_mod( 'sub_header_title_color', '#ffffff' ).';}';
        $output .= '.breadcrumb>li+li:before, .subtitle-cover .breadcrumb, .subtitle-cover .breadcrumb>.active{color:'.esc_attr(get_theme_mod( 'breadcrumb_text_color', '#000' )).';}';
        $output .= '.header-crumbs{padding:'.(int) esc_attr(get_theme_mod( 'sub_header_padding_top', '70' ) ).'px 0 '.(int) esc_attr(get_theme_mod( 'sub_header_padding_bottom', '40' ) ).'px; margin-bottom: '.(int) esc_attr(get_theme_mod( 'sub_header_margin_bottom', '60' )).'px;}';
        $output .= '.crumbs ul.crumbs-list li a { color:'.esc_attr( get_theme_mod( 'sub_header_title_color', '#ffffff' ) ).';}';

        //body
        if (get_theme_mod( 'body_bg_img')) {
            $output .= 'body{ background-image: url("'.esc_attr( get_theme_mod( 'body_bg_img' ) ) .'");background-size: '.esc_attr( get_theme_mod( 'body_bg_size', 'cover' ) ) .';    background-position: '.esc_attr( get_theme_mod( 'body_bg_position', 'left top' ) ) .';background-repeat: '.esc_attr( get_theme_mod( 'body_bg_repeat', 'no-repeat' ) ) .';background-attachment: '.esc_attr( get_theme_mod( 'body_bg_attachment', 'fixed' ) ) .'; }';
        }
        $output .= 'body{ background-color: '.esc_attr( get_theme_mod( 'body_bg_color', '#fff' ) ) .'; }';

        // Button color setting...
        $output .= '.btn.btn-charity,input[type=submit], input[type="button"]#addreward,
                    .woocommerce-page #payment #place_order,.btn.btn-white:hover,
                    .btn.btn-border-charity:hover,.btn.btn-border-white:hover,.woocommerce input.button { background-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#f8c218' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#f8c218' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_text_color', '#fff' ) ) .' !important; border-radius: '.(int) esc_attr(get_theme_mod( 'button_radius', 4 )).'px; }';

         $output .= '.charity-login-register a.charity-dashboard{ background-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#f8c218' ) ) .'; }';


        if ( get_theme_mod( 'button_hover_bg_color', '#4F95FF' ) ) {
            $output .= '.btn.btn-charity:hover,input[type=submit]:hover
            .woocommerce-page #payment #place_order:hover { background-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#636c72' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#636c72' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_hover_text_color', '#fff' ) ) .' !important; }';

            $output .= '.charity-login-register a.charity-dashboard:hover{ background-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#636c72' ) ) .'; }';
        }

        //menu color
        if ( get_theme_mod( 'navbar_text_color', '#fff' ) ) {
            $output .= '.header-solid .common-menu-wrap .nav>li.menu-item-has-children:after, .header-borderimage .common-menu-wrap .nav>li.menu-item-has-children:after, .header-solid .common-menu-wrap .nav>li>a, .header-borderimage .common-menu-wrap .nav>li>a,
            .header-solid .common-menu-wrap .nav>li>a:after, .header-borderimage .common-menu-wrap .nav>li>a:after,.charity-search, .charity-login-register ul li a{ color: '.esc_attr( get_theme_mod( 'navbar_text_color', '#fff' ) ) .'; }';

            $output .= '.slicknav_icon span.slicknav_icon-bar { background: '.esc_attr( get_theme_mod( 'navbar_text_color', '#fff' ) ) .'; }';
        }

        $output .= '.header-solid .common-menu-wrap .nav>li>a:hover, .header-borderimage .common-menu-wrap .nav>li>a:hover,.header-solid .common-menu-wrap .nav>li>a:hover:after, .header-borderimage .common-menu-wrap .nav>li>a:hover:after,
        .charity-search-wrap a.charity-search:hover{ color: '.esc_attr( get_theme_mod( 'navbar_hover_text_color', '#f8c218' ) ) .'; }';

        $output .= '.header-solid .common-menu-wrap .nav>li.active>a, .common-menu-wrap .nav>li.current-menu-item a, .common-menu-wrap .nav>li.current-menu-item.menu-item-has-children > a:after, .header-borderimage .common-menu-wrap .nav>li.active>a{ color: '.esc_attr( get_theme_mod( 'navbar_active_text_color', '#f8c218' ) ) .'; }';

        //submenu color
        $output .= '.common-menu-wrap .nav>li ul{ background-color: '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .'; }';

        $output .= '.common-menu-wrap .nav>li>ul li a,.common-menu-wrap .nav > li > ul li.mega-child > a, .common-menu-wrap .nav>li.current-menu-item ul li a { color: '.esc_attr( get_theme_mod( 'sub_menu_text_color', '#191919' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'sub_menu_border', '#eef0f2' ) ) .'; }';
        
        $output .= '.common-menu-wrap .nav>li>ul li a:hover,
        .common-menu-wrap .sub-menu li.active a,.common-menu-wrap .sub-menu li.active.mega-child .active a,
        .common-menu-wrap .sub-menu.megamenu > li.active.mega-child > a,.common-menu-wrap .nav > li > ul li.mega-child > a:hover { color: '.esc_attr( get_theme_mod( 'sub_menu_text_color_hover', '#f8c218' ) ) .';}';
        $output .= '.common-menu-wrap .nav>li > ul::after{ border-color: transparent transparent '.esc_attr( get_theme_mod( 'sub_menu_bg', '#262626' ) ) .' transparent; }';

        //footer
        $output .= '.footer-area{ padding-top: '. (int) esc_attr( get_theme_mod( 'copyright_padding_top', '80' ) ) .'px; }';
        $output .= '.footer-area{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'copyright_padding_bottom', '40' ) ) .'px; }';
        $output .= '.footer-inner p.copyright { color: '.esc_attr( get_theme_mod( 'copyright_text_color', '#797979' ) ) .'; }';
        $output .= '.footer-area:before { background-color: '.esc_attr( get_theme_mod( 'footer_bg_color', '#3a3a3a' ) ) .'; }';

        # Topbar Color
        if (get_theme_mod( 'topbar_color' ) ) {
            $output .= '.header-top{ background-color: '.esc_attr( get_theme_mod( 'topbar_color' ) ) .'; }';
        }
        if (get_theme_mod( 'topbar_text_color' ) ) {
            $output .= '.htop-contact ul li, .htop-contact ul li a{ color: '.esc_attr( get_theme_mod( 'topbar_text_color' ) ) .'; }';
        }
        if (get_theme_mod( 'topbar_link_hover_color' ) ) {
            $output .= '.htop-contact ul li:hover, .htop-contact ul li a:hover{ color: '.esc_attr( get_theme_mod( 'topbar_link_hover_color' ) ) .'; }';
        }
        if (get_theme_mod( 'campaign_bg_color' ) ) {
            $output .= '.htop-donate-btn a{ background-color: '.esc_attr( get_theme_mod( 'campaign_bg_color' ) ) .'; }';
        }
        if (get_theme_mod( 'campaign_bg_hover_color' ) ) {
            $output .= '.htop-donate-btn a:hover{ background-color: '.esc_attr( get_theme_mod( 'campaign_bg_hover_color' ) ) .'; }';
        }
        if (get_theme_mod( 'campaign_text_color' ) ) {
            $output .= '.htop-donate-btn a{ color: '.esc_attr( get_theme_mod( 'campaign_text_color' ) ) .'; }';
        }

        $output .= "body.error404,body.page-template-404{
            width: 100%;
            height: 100%;
            min-height: 100%;
        }";

        return $output;
    }
}
