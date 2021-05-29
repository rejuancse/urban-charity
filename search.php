<?php get_header(); ?>
<?php get_template_part('lib/sub-header'); ?>

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
                    if ( have_posts() ) : ?>
                        <h1 class="search-page-title">
                            <?php esc_html_e( 'Search results for: ', 'urban-charity' ); ?>
                            <span class="page-description"><?php echo get_search_query(); ?></span>
                        </h1>
                        <?php while ( have_posts() ) : the_post(); 
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
                    else: ?>
                        <div class="charity-error-wrapper">
                            <div class="info-wrapper">
                                <h2 class="error-message-title"><?php  echo esc_html(get_theme_mod( 'urban_charity_404_title', esc_html__('Page Not Found - Lost Maybe?', 'urban-charity') )); ?></h2>
                                <p class="error-message"><?php  echo esc_html(get_theme_mod( 'urban_charity_404_description', esc_html__('The page you are looking for was moved, removed, renamed or might never existed...', 'urban-charity') )); ?></p>
                                <a class="btn btn-charity white skip-link" href="<?php echo esc_url( home_url('/') ); ?>" title="<?php  esc_html_e( 'HOME', 'urban-charity' ); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;<?php  echo esc_attr_e(get_theme_mod( 'urban_charity_404_btn_text', esc_html__('Go Back Home', 'urban-charity') )); ?></a>
                            </div>
                        </div>     
                    <?php endif;
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