<?php get_header();
/*
*Template Name: 404 Page Template
*/
?>

<?php 
$urban_charity_404_title = get_theme_mod( '404_title', esc_html__('Page Not Found - Lost Maybe?', 'urban-charity') );
$urban_charity_404_description = get_theme_mod( '404_description', esc_html__('The page you are looking for was moved, removed, renamed or might never existed...', 'urban-charity') );
$urban_charity_404_btn_text = get_theme_mod( '404_btn_text', esc_html__('Go Back Home', 'urban-charity') );
?>

<div class="container charity-error-wrapper">
	<div class="row">
    	<div class="col-sm-7 info-wrapper">
	    	<h2 class="error-message-title"><?php  echo esc_html($urban_charity_404_title); ?></h2>
	    	<p class="error-message"><?php  echo esc_html($urban_charity_404_description); ?></p>
	    	<a class="btn btn-charity white skip-link" href="<?php echo esc_url( home_url('/') ); ?>" title="<?php  esc_attr_e( 'HOME', 'urban-charity' ); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i><?php  echo esc_html($urban_charity_404_btn_text); ?></a>
    	</div>
    </div>
</div>
<?php get_footer(); ?>