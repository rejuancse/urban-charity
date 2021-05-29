<div class="col-md-3 col-sm-3 col-xs-12">
	<!-- sidebar area start -->
	<div class="sidebar">
        <?php 
	        if ( is_active_sidebar( 'sidebar' ) ) {
	        	dynamic_sidebar('sidebar');
	        }
         ?>
    </div>
</div> <!-- #sidebar -->