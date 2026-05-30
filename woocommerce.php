<?php get_header(); ?>
<?php get_template_part('lib/sub-header')?>

<?php $col = (is_product()) ? 12 : 8; ?>

<section id="main" class="wrappers-content shop-details-area">
    <div class="container">
    	<div class="row">
            <?php $layout = get_theme_mod( 'shop_sidebar', 'fullwidth' ); ?>
            <?php if( $layout == 'left_sidebar' ): ?>
                <div id="shop" class="col-3 charity_wooshop_widgets" role="complementary">
                    <div class="sidebar">
                        <?php 
                            if ( is_active_sidebar( 'shopsidebar' ) ) {
                                dynamic_sidebar('shopsidebar');
                            }
                         ?>
                    </div>
                </div>
            <?php endif; ?>
            <div id="content" class="col-<?php echo esc_attr( $layout == 'fullwidth' ) ? '12':'9'; ?>" role="main">
                <div class="site-content">
                    <div class="shop-area">
                        <div class="shop-list-item">
                            <?php woocommerce_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if( $layout == 'right_sidebar' ): ?>
                <div id="shop" class="col-3 charity_wooshop_widgets" role="complementary">
                    <div class="sidebar">
                        <?php 
                            if ( is_active_sidebar( 'shopsidebar' ) ) {
                                dynamic_sidebar('shopsidebar');
                            }
                         ?>
                    </div>
                </div>
            <?php endif; ?>
        </div> <!-- .row -->
    </div> <!-- .container -->
</section>
 
<?php get_footer(); ?>