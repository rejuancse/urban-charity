<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

# Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$column = get_theme_mod( 'shop_column', 3 ); 
$crowdfunding = get_theme_mod( 'shop_product', 'true' ); ?>

<?php if ($crowdfunding == 'only_crowdfunding'): ?>
    <?php if( $product->get_type() == 'crowdfunding' ){ ?>
        <?php  
            # Backer Count
            $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
                'numberposts' => 10, # Chnage Number
                'meta_key'    => '_customer_user',
                'post_type'   => wc_get_order_types( 'view-orders' ),
                'post_status' => array_keys( wc_get_order_statuses() )
            ) ) );
            $total_item = '0';
            if ( $customer_orders ) {
                foreach ( $customer_orders as $customer_order ) {
                    $order      = wc_get_order( $customer_order );
                    $item_count = $order->get_item_count();
                    $total_item += $item_count; 
                }
            }
        ?>
        <div class="col-md-<?php echo esc_attr($column); ?> col-sm-4 col-xs-12">
            <div class="rc-causes-item causes-style-two shop-single">
                <div class="rc-thumb">
                    <?php if ( has_post_thumbnail() ){ ?>
                        <a class="review-item-image"  href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('backer-medium', array('class' => 'img-fluid')); ?>    
                        </a>
                    <?php } ?>
                    <div class="donate-btn">
                        <a href="#"><i class="fa fa-heart"></i><?php echo esc_html($total_item); ?> <?php esc_html_e('Loves', 'urban-charity') ?></a>
                    </div>
                </div>
                <div class="wrap-info"> 
                    <div class="rc-causes-info">
                        <div class="causes-progress" data-sr="enter">
                            <?php $css_width = WPNEOCF()->getFundRaisedPercent(); if( $css_width >= 100 ){ $css_width = 100; } ?>
                            <div class="progress-bar left-anim" role="progressbar" style="width: <?php echo esc_attr($css_width); ?>%;">
                                <span><?php echo esc_html($css_width); ?>%</span>
                            </div>
                        </div>
                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>

                        <p><?php echo urban_charity_excerpt_max_charlength( 123 ); ?></p>

                        <div class="raised">
                            <?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_fund_raised_by_campaign()); ?> 
                            <span class="theme__text"><?php _e('Funds Raised','urban-charity'); ?></span>
                        </div>

                        <div class="goal">
                            <?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_goal_by_campaign(get_the_ID())); ?> 
                            <span class="theme__text"><?php _e('Goal','urban-charity'); ?></span>
                        </div>
                    </div>
                    <div class="rc-donate-btn">
                        <a href="<?php the_permalink(); ?>"><?php _e('More Details','urban-charity'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?> 
<?php else: ?>
	<div class="col-md-<?php echo esc_attr($column); ?> col-sm-4 col-xs-12">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="single-item">
            <div class="thumb">
            	<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                <div class="shop-info">
                    <?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
					<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
                    <ul>
                        <li>
		                    <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		                </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
