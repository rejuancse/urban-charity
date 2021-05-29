<?php get_header(); ?>
<?php get_template_part('lib/sub-header'); ?>
<section id="content">
    <div class="blog-classic ptb--70">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="blog-post-list">
                        <?php if ( have_posts() ) :  ?> 
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'post-format/content', get_post_format() ); ?>                            
                            <?php endwhile; ?>    
                        <?php else: ?>
                        <?php get_template_part( 'post-format/content', 'none' ); ?>
                        <?php endif; ?>
                        
                    </div> <!--blog-post-list -->
                </div> <!-- col-8 -->
                <?php get_sidebar( ); ?>
            </div> <!-- .row -->
        </div> <!-- .container -->
    </div> <!-- .container -->
</section>
<?php get_footer();