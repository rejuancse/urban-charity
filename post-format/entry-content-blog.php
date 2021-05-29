<?php the_tags( '<div class="post-meta-info-list-in tags-in">', '', '</div>' ); ?>

<?php
    if ( get_theme_mod( 'blog_single_comment_en', true ) ) {
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
    }
?>