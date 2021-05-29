<?php

if( class_exists( 'WP_Customize_Control' ) && !class_exists( 'Urban_Charity_Switch_Title_Control' ) ):
	/**
	* 
	*/
	class Urban_Charity_Switch_Title_Control extends WP_Customize_Control
	{
		public $urban_charity_type = 'switch';
		
		public function render_content() {

			$name = '_customize-switch-button-' . $this->id;

			if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title title-control"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
		}
	}
endif;