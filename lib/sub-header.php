<?php 
    if ( ! get_theme_mod( 'enable_sub_header', true ) && !is_page() ) {
        return;
    }

    global $post;

    /**
    *   Subheader Banner Background Image 
    */ 
    if(!function_exists('urban_charity_sub_header')){
        function urban_charity_sub_header(){
            $banner_img = get_theme_mod( 'sub_header_banner_img', false );
            $banner_color = get_theme_mod( 'sub_header_banner_color', '#333' );
            if( $banner_img ){
                $urban_charity_output = 'style="background-image:url('.esc_url( $banner_img ).'); background-size: cover; background-position: 50% 50%;"';
                return $urban_charity_output;
            }else{
                $urban_charity_output = 'style="background-color:'.esc_attr( $banner_color ).';"';
                return $urban_charity_output;
            }
        }
    }
?> 

<?php if (!is_front_page()) { ?>

<div class="header-crumbs crumbs-bg-three" <?php print wp_kses_post(urban_charity_sub_header());?>>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="crumbs">
                    <div class="crumbs-title">
                        <?php
                        global $wp_query;
                        if(isset($wp_query->queried_object->name)){
                            if (get_theme_mod( 'header_title_enable', true )) {
                                if($wp_query->queried_object->name != ''){
                                    if($wp_query->queried_object->name == 'product' ){
                                        echo '<h2>'.esc_html__('Shop','urban-charity').'</h2>';
                                    }else{
                                        echo '<h2 class="page-leading">'.wp_kses_post($wp_query->queried_object->name).'</h2>';    
                                    }
                                }else{
                                    echo '<h2 class="page-leading">'.esc_html(get_the_title()).'</h2>';
                                }
                            }
                        }else{

                            if( is_search() ){
                                if (get_theme_mod( 'subtitle_enable', true )) {
                                    if (get_theme_mod( 'header_subtitle_text', '' )){
                                        echo '<h3 class="page-subleading">'. wp_kses_post(get_theme_mod( 'header_subtitle_text','' )).'</h3>';
                                    }
                                }
                                if (get_theme_mod( 'header_title_enable', true )) {
                                    echo '<h2 class="page-leading">'.esc_html__('Search','urban-charity').'</h2>';
                                }
                            }
                            else if( is_home() ){
                                if (get_theme_mod( 'subtitle_enable', true )) {
                                    if (get_theme_mod( 'header_subtitle_text', '' )){
                                        echo '<h3 class="page-subleading">'. wp_kses_post(get_theme_mod( 'header_subtitle_text','' )).'</h3>';
                                    }
                                }
                                if (get_theme_mod( 'header_title_enable', true )) {
                                    if (get_theme_mod( 'header_title_text', 'Latest Blog' )){
                                        echo '<h2 class="page-leading">'. wp_kses_post(get_theme_mod( 'header_title_text','Latest Blog' )).'</h2>';
                                    }
                                }
                            }
                            else if( is_single()){

                                if (get_theme_mod( 'subtitle_enable', true )) {
                                    if (get_theme_mod( 'header_subtitle_text', '' )){
                                        echo '<h3 class="page-subleading">'. wp_kses_post(get_theme_mod( 'header_subtitle_text','' )).'</h3>';
                                    }
                                }
                                if (get_theme_mod( 'header_title_enable', true )) {
                                    if (get_post_type() == 'event') {
                                        echo '<h2 class="page-leading">'. esc_html__( 'Event Details','urban-charity' ).'</h2>';
                                    } elseif (get_post_type() == 'album') {
                                        echo '<h2 class="page-leading">'. esc_html__( 'Albums','urban-charity' ).'</h2>';
                                    } elseif (get_post_type() == 'gallery') {
                                        echo '<h2 class="page-leading">'. esc_html__( 'Gallery','urban-charity' ).'</h2>';
                                    } elseif (get_post_type() == 'performer') {
                                        echo '<h2 class="page-leading">'. esc_html__( 'Performer','urban-charity' ).'</h2>';
                                    }elseif(get_post_type() == 'product'){
                                        echo '<h2>'.esc_html__('Product Details','urban-charity').'</h2>';
                                    }else {
                                        if (get_theme_mod( 'header_title_text', 'Latest Blog' )){
                                            echo '<h2 class="page-leading">'. wp_kses_post(get_theme_mod( 'header_title_text','Latest Blog' )).'</h2>';
                                        }
                                    }
                                }
                            }
                            else{
                                if (get_theme_mod( 'header_title_enable', true )) {
                                    echo '<h2 class="page-leading">'.esc_html(get_the_title()).'</h2>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <ul class="crumbs-list urban_charity_breadcrumbs">
                        <?php urban_charity_breadcrumbs(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div><!--/.sub-title-->
<?php } ?>
