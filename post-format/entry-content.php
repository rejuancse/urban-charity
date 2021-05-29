<?php 
if ( is_single() ) {
    the_content( 
        sprintf( 
            __( 'Continue reading%s', 'urban-charity' ), 
            '<span class="screen-reader-text">'.get_the_title().'</span>' 
        ) 
    );
} else {
    if ( get_theme_mod( 'blog_intro_en', true ) ) { 
        if ( get_theme_mod( 'blog_post_text_limit', 280 ) ) {
            echo wp_kses_post(urban_charity_excerpt_max_charlength(get_theme_mod( 'blog_post_text_limit', 280 )));
            if ( get_theme_mod( 'blog_continue_en', true ) ) { 
                if ( get_theme_mod( 'blog_continue', 'Read More' ) ) {
                    echo '<div class="readmore-btn"><a class="skip-link" href="'.esc_url(get_permalink()).'">'. esc_html(get_theme_mod( 'blog_continue', 'Read More' )) .'</a></div>';
                } 
            }
        } else {
            the_content( 
                sprintf( 
                    __( 'Continue reading%s', 'urban-charity' ), 
                    '<span>'.get_the_title().'</span>' 
                ) 
            );
        }
    }
}