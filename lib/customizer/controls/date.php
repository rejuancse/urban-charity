<?php

if( class_exists( 'WP_Customize_Control' ) && !class_exists( 'Urban_Charity_Date_Control' ) ):
	/**
	* 
	*/
	class Urban_Charity_Date_Control extends WP_Customize_Control
	{
		public $urban_charity_type = 'date';
		
		public function render_content() {


			if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description) ; ?></span>
			<?php endif; ?>
			<div class="thmc-date-picker clearfix">
				<input type="text" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?>>
			</div>
			<?php
		}
	}
endif;