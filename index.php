<?php get_header(); ?>
<?php get_template_part('lib/sub-header')?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php if (get_theme_mod( 'blog_sidebar', true )): ?>
                <div id="content" class="site-content blog-index-wrap col-md-9" role="main">
            <?php else: ?>
                <div id="content" class="site-content blog-index-wrap col-md-12" role="main">
            <?php endif; ?>
            
                <?php
                    $index = 1;
                    $col = get_theme_mod( 'blog_column', 12 );
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post(); 
                            if ( $index == '1' ) { ?>
                                <div class="row">
                            <?php }?>
                                    <div class="blog-post-item-col col-md-<?php echo esc_attr($col);?>">
                                        <?php get_template_part( 'post-format/content', get_post_format() ); ?>
                                    </div>
                            <?php  if ( $index == (12/esc_attr($col) )) { ?>
                                </div><!--/row-->
                            <?php $index = 1;
                            }else{
                                $index++;   
                            }
                        endwhile;
                    else:
                        get_template_part( 'post-format/content', 'none' );
                    endif;
                    if($index !=  1 ){ ?>
                       </div><!--/row-->
                    <?php }
                ?>
                
               <?php                                 
                    $page_numb = max( 1, get_query_var('paged') );
                    $max_page = $wp_query->max_num_pages;
                    urban_charity_pagination( $page_numb, $max_page ); 
                ?>
            </div> <!-- .site-content -->
            <?php
                if (get_theme_mod( 'blog_sidebar', true )) {
                    get_sidebar();
                }
            ?>
        </div>
    </div> <!-- .container --> 
</section> <!-- #main -->

<?php get_footer();