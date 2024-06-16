<?php

/**
 * Urban Charity Customizer
 */


if (!class_exists('URBAN_CHARITY_Framework')):

	class URBAN_CHARITY_Framework
	{
		/**
		 * Instance of WP_Customize_Manager class
		 */
		public $wp_customize;


		private $fields_class = array();

		private $google_fonts = array();

		/**
		 * Constructor of 'URBAN_CHARITY_Framework' class
		 *
		 * @wp_customize (WP_Customize_Manager) Instance of 'WP_Customize_Manager' class
		 */
		function __construct( $wp_customize )
		{
			$this->wp_customize = $wp_customize;

			$this->fields_class = array(
				'text'            => 'WP_Customize_Control',
				'checkbox'        => 'WP_Customize_Control',
				'textarea'        => 'WP_Customize_Control',
				'radio'           => 'WP_Customize_Control',
				'select'          => 'WP_Customize_Control',
				'email'           => 'WP_Customize_Control',
				'url'             => 'WP_Customize_Control',
				'number'          => 'WP_Customize_Control',
				'range'           => 'WP_Customize_Control',
				'hidden'          => 'WP_Customize_Control',
				'date'            => 'Urban_Charity_Date_Control',
				'color'           => 'WP_Customize_Color_Control',
				'upload'          => 'WP_Customize_Upload_Control',
				'image'           => 'WP_Customize_Image_Control',
				'radio_button'    => 'Urban_Charity_Radio_Button_Control',
				'checkbox_button' => 'Urban_Charity_Checkbox_Button_Control',
				'switch'          => 'Urban_Charity_Switch_Button_Control',
				'multi_select'    => 'Urban_Charity_Multi_Select_Control',
				'radio_image'     => 'Urban_Charity_Radio_Image_Control',
				'checkbox_image'  => 'Urban_Charity_Checkbox_Image_Control',
				'color_palette'   => 'Urban_Charity_Color_Palette_Control',
				'rgba'            => 'Urban_Charity_Rgba_Color_Picker_Control',
				'title'           => 'Urban_Charity_Switch_Title_Control',
			);

			$this->load_custom_controls();

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_scripts' ), 100 );
		}

		public function customizer_scripts()
		{
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'thmc-select2', URBAN_CHARITY_URI.'lib/customizer/assets/select2/css/select2.min.css' );

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'thmc-select2', URBAN_CHARITY_URI.'lib/customizer/assets/select2/js/select2.min.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'thmc-rgba-colorpicker', URBAN_CHARITY_URI.'lib/customizer/assets/js/thmc-rgba-colorpicker.js', array('jquery', 'wp-color-picker'), '1.0', true );
			wp_enqueue_script( 'thmc-customizer', URBAN_CHARITY_URI.'lib/customizer/assets/js/customizer.js', array('jquery', 'jquery-ui-datepicker'), '1.0', true );

			wp_localize_script( 'thmc-customizer', 'thm_customizer', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'import_success' => esc_html__('Success! Your theme data successfully imported. Page will be reloaded within 2 sec.', 'urban-charity'),
				'import_error' => esc_html__('Error! Your theme data importing failed.', 'urban-charity'),
				'file_error' => esc_html__('Error! Please upload a file.', 'urban-charity')
			) );
		}

		private function load_custom_controls(){
			get_template_part('lib/customizer/controls/radio-button');
            get_template_part('lib/customizer/controls/radio-image');
            get_template_part('lib/customizer/controls/checkbox-button');
            get_template_part('lib/customizer/controls/checkbox-image');
            get_template_part('lib/customizer/controls/switch');
            get_template_part('lib/customizer/controls/date');
            get_template_part('lib/customizer/controls/multi-select');
            get_template_part('lib/customizer/controls/color-palette');
            get_template_part('lib/customizer/controls/rgba-colorpicker');
            get_template_part('lib/customizer/controls/title');

            // Load Sanitize class
            get_template_part('lib/customizer/libs/sanitize');
		}

		public function add_option( $options ){
			if (isset($options['sections'])) {
				$this->panel_to_section($options);
			}
		}
		private function panel_to_section( $options )
		{
			$panel = $options;
			$panel_id = $options['id'];

			unset($panel['sections']);
			unset($panel['id']);

			// Register this panel
			$this->add_panel($panel, $panel_id);

			$sections = $options['sections'];

			if (!empty($sections)) {
				foreach ($sections as $section) {
					$fields = $section['fields'];
					$section_id = $section['id'];

					unset($section['fields']);
					unset($section['id']);

					$section['panel'] = $panel_id;

					$this->add_section($section, $section_id);

					if (!empty($fields)) {
						foreach ($fields as $field) {
							if (!isset($field['settings'])) {
								var_dump($field);
							}
							$field_id = $field['settings'];

							$this->add_field($field, $field_id, $section_id);
						}
					}
				}
			}
		}

		private function add_panel($panel, $panel_id){
			$this->wp_customize->add_panel( $panel_id, $panel );
		}

		private function add_section($section, $section_id)
		{
			$this->wp_customize->add_section( $section_id, $section );
		}

		private function add_field($field, $field_id, $section_id){
			$setting_args = array(
				'default'        => isset($field['default']) ? $field['default'] : '',
				'type'           => isset($field['setting_type']) ? $field['setting_type'] : 'theme_mod',
				'transport'     => isset($field['transport']) ? $field['transport'] : 'refresh',
				'capability'     => isset($field['capability']) ? $field['capability'] : 'edit_theme_options',
			);

			if (isset($field['type']) && $field['type'] == 'switch') {
				$setting_args['sanitize_callback'] = array('Urban_Charity_Sanitize', 'switch_sntz');
			} elseif (isset($field['type']) && ($field['type'] == 'checkbox_button' || $field['type'] == 'checkbox_image')) {
				$setting_args['sanitize_callback'] = array('Urban_Charity_Sanitize', 'multi_checkbox');
			} elseif (isset($field['type']) && $field['type'] == 'multi_select') {
				$setting_args['sanitize_callback'] = array('Urban_Charity_Sanitize', 'multi_select');
				$setting_args['sanitize_js_callback'] = array('Urban_Charity_Sanitize', 'multi_select_js');
			}

			$control_args = array(
				'label'       => isset($field['label']) ? $field['label'] : '',
				'section'     => $section_id,
				'settings'    => $field_id,
				'type'        => isset($field['type']) ? $field['type'] : 'text',
				'priority'    => isset($field['priority']) ? $field['priority'] : 10,
			);

			if (isset($field['choices'])) {
				$control_args['choices'] = $field['choices'];
			}

			// Register the settings
			$this->wp_customize->add_setting( $field_id, $setting_args );
			$control_class = isset($this->fields_class[$field['type']]) ? $this->fields_class[$field['type']] : 'WP_Customize_Control';
			// Add the controls
			$this->wp_customize->add_control( new $control_class( $this->wp_customize, $field_id, $control_args ) );
		}
	}

endif;

/**
*
*/
class THM_Customize
{
	public $google_fonts = array();

	public $options;

	function __construct( $options ) {
		$this->options = $options;

		add_action('customize_register', array($this, 'customize_register'));
		add_action('wp_enqueue_scripts', array($this, 'urban_charity_get_google_fonts_data'));
	}

	public function customize_register( $wp_customize ){
		$urban_charity_framework = new URBAN_CHARITY_Framework( $wp_customize );
		$urban_charity_framework->add_option( $this->options );
	}

	public function urban_charity_get_google_fonts_data()
	{
		if (isset($this->options['sections']) && !empty($this->options['sections'])) {
			foreach ($this->options['sections'] as $section) {
				if (isset($section['fields']) && !empty($section['fields'])) {
					foreach ($section['fields'] as $field) {
						if (isset($field['google_font']) && $field['google_font'] == true) {
							$this->google_fonts[$field['settings']] = array();

							if (isset($field['default']) && !empty($field['default'])) {
								$this->google_fonts[$field['settings']]["default"] = $field['default'];
							}

							if (isset($field['google_font_weight']) && !empty($field['google_font_weight'])) {
								$this->google_fonts[$field['settings']]["weight"] = $field['google_font_weight'];
							}

							if (isset($field['google_font_weight_default']) && !empty($field['google_font_weight_default'])) {
								$this->google_fonts[$field['settings']]["weight_default"] = $field['google_font_weight_default'];
							}
						}
					}
				}
			}
		}

		$all_fonts = array();

		if (!empty($this->google_fonts)) {
			foreach ($this->google_fonts as $font_id => $font_data) {
				$font_family_default = isset($font_data['default']) ? $font_data['default'] : '';
				$font_family = get_theme_mod( $font_id, $font_family_default );

				if (!isset($all_fonts[$font_family])) {
					$all_fonts[$font_family] = array();
				}

				if (isset($font_data['weight']) && !empty($font_data['weight'])) {
					$font_weight_default = isset($font_data['weight_default']) ? $font_data['weight_default'] : '';

					$font_weight = get_theme_mod( $font_data['weight'], $font_weight_default );

					$all_fonts[$font_family][] = $font_weight;
				}

			}
		}

		$font_url = "//fonts.googleapis.com/css?family=";

		if (!empty($all_fonts)) {

			$i = 0;

			foreach ($all_fonts as $font => $weights) {

				if ($i) {
					$font_url .= "%7C";
				}

				$font_url .= str_replace(" ", "+", $font);

				if (!empty($weights)) {
					$font_url .= ":";
					$font_url .= implode(",", $weights);
				}

				$i++;
			}

			wp_enqueue_style( "tm-google-font", $font_url );
		}
	}
}


// Customizer Section
$urban_charity_panel_to_section = array(
	'id'           => 'languageschool_panel_options',
	'title'        => esc_html__( 'Urban Charity Options', 'urban-charity' ),
	'description'  => esc_html__( 'Urban Charity Theme Options', 'urban-charity' ),
	'priority'     => 10,
	'sections'     => array(

		# Top Header
		array(
			'id'              => 'topbar_setting',
			'title'           => esc_html__( 'Topbar Settings', 'urban-charity' ),
			'description'     => esc_html__( 'Topbar Settings', 'urban-charity' ),
			'priority'        => 10,
			'fields'         => array(
				array(
					'settings' 		=> 'topbar_enable',
			      	'label' 		=> __( 'Enable Topbar', 'urban-charity' ),

			      	'section'  		=> 'default_controls_section',
			      	'priority' 		=> 10,
			      	'type'			=> 'checkbox',
			   	),
				array(
					'settings' => 'topbar_email',
					'label'    => esc_html__( 'Topbar Email', 'urban-charity' ),
					'type'     => 'email',
					'priority' => 10,
					'default'  => 'support@urbancharity.com',
				),
				array(
					'settings' => 'topbar_phone',
					'label'    => esc_html__( 'Topbar Phone Number', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '+00 44 123 456 78910',
				),
				array(
					'settings' => 'topbar_color',
					'label'    => esc_html__( 'Topbar BG color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' => 'topbar_text_color',
					'label'    => esc_html__( 'Topbar Text/Link Color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#969696',
				),
				array(
					'settings' => 'topbar_link_hover_color',
					'label'    => esc_html__( 'Topbar Link Hover color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#c3a444',
				),

				# Donate BTN...
				array(
					'settings' => 'donate_button_text',
					'label'    => esc_html__( 'Donate Button Text', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'Donate Button',
				),
				array(
					'settings' => 'donate_button_url',
					'label'    => esc_html__( 'Donate Button URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '#',
				),
				array(
					'settings' => 'campaign_bg_color',
					'label'    => esc_html__( 'Donate BG color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#f8c218',
				),
				array(
					'settings' => 'campaign_bg_hover_color',
					'label'    => esc_html__( 'Donate Hover BG color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#cea527',
				),
				array(
					'settings' => 'campaign_text_color',
					'label'    => esc_html__( 'Donate Text Color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#fff',
				),

			) # fields
		), # topbar_setting

		# Header Setting
		array(
			'id'              => 'header_setting',
			'title'           => esc_html__( 'Header Settings', 'urban-charity' ),
			'description'     => esc_html__( 'Header Settings', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'header_color',
					'label'    => esc_html__( 'Header background Color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#454545',
				),
				array(
					'settings' => 'header_padding_top',
					'label'    => esc_html__( 'Header Top Padding', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 0,
				),
				array(
					'settings' => 'header_padding_bottom',
					'label'    => esc_html__( 'Header Bottom Padding', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 0,
				),
				array(
					'settings' 		=> 'header_fixed',
				  	'label' 		=> esc_html__( 'Sticky Header', 'urban-charity' ),
				  	'section'  		=> 'sticky_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				),
				array(
					'settings' => 'sticky_header_color',
					'label'    => esc_html__( 'Sticky background Color', 'urban-charity' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' 		=> 'header_search',
				  	'label' 		=> esc_html__( 'Header Search', 'urban-charity' ),
				  	'section'  		=> 'search_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  		=> true,
				),

			)//fields
		),//header_setting

		# Subheader Setting.
		array(
			'id'              => 'sub_header_banner',
			'title'           => esc_html__( 'Sub Header Banner', 'urban-charity' ),
			'description'     => esc_html__( 'sub header banner', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(

				array(
					'settings' 		=> 'enable_sub_header',
				  	'label' 		=> esc_html__( 'Header Search', 'urban-charity' ),
				  	'section'  		=> 'subheader_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				),
				array(
					'settings' => 'sub_header_banner_img',
					'label'    => esc_html__( 'Sub-Header Background Image', 'urban-charity' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_header_banner_color',
					'label'    => esc_html__( 'Sub-Header BG Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333333',
				),

				// end....
				array(
					'settings' => 'sub_header_padding_top',
					'label'    => esc_html__( 'Sub-Header Padding Top', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 70,
				),
				array(
					'settings' => 'sub_header_padding_bottom',
					'label'    => esc_html__( 'Sub-Header Padding Bottom', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 40,
				),
				array(
					'settings' => 'sub_header_margin_bottom',
					'label'    => esc_html__( 'Sub-Header Margin Bottom', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 60,
				),
				array(
					'settings' => 'sub_header_title',
					'label'    => esc_html__( 'Title Settings', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_header_title_size',
					'label'    => esc_html__( 'Header Title Font Size', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '58',
				),
				array(
					'settings' => 'sub_header_title_color',
					'label'    => esc_html__( 'Header Title Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#ffffff',
				),
			)//fields
		),//sub_header_banner

		array(
			'id'              => 'typo_setting',
			'title'           => esc_html__( 'Typography Setting', 'urban-charity' ),
			'description'     => esc_html__( 'Typography Setting', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(

				array(
					'settings' => 'font_title_body',
					'label'    => esc_html__( 'Body Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//body font
				array(
					'settings' => 'body_google_font',
					'label'    => esc_html__( 'Select Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'body_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'body_font_size',
					'label'    => esc_html__( 'Body Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'body_font_height',
					'label'    => esc_html__( 'Body Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'body_font_weight',
					'label'    => esc_html__( 'Body Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'body_font_color',
					'label'    => esc_html__( 'Body Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#4c4c4c',
				),
				array(
					'settings' => 'font_title_menu',
					'label'    => esc_html__( 'Menu Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//Menu font
				array(
					'settings' => 'menu_google_font',
					'label'    => esc_html__( 'Select Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '600'
				),
				array(
					'settings' => 'menu_font_size',
					'label'    => esc_html__( 'Menu Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'menu_font_height',
					'label'    => esc_html__( 'Menu Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '30',
				),
				array(
					'settings' => 'menu_font_weight',
					'label'    => esc_html__( 'Menu Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '600',
					'choices'  => array(
						'' 	  => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),

				array(
					'settings' => 'font_title_h1',
					'label'    => esc_html__( 'Heading 1 Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//Heading 1
				array(
					'settings' => 'h1_google_font',
					'label'    => esc_html__( 'Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '700'
				),
				array(
					'settings' => 'h1_font_size',
					'label'    => esc_html__( 'Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '42',
				),
				array(
					'settings' => 'h1_font_height',
					'label'    => esc_html__( 'Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '48',
				),
				array(
					'settings' => 'h1_font_weight',
					'label'    => esc_html__( 'Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '700',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'h1_font_color',
					'label'    => esc_html__( 'Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),

				array(
					'settings' => 'font_title_h2',
					'label'    => esc_html__( 'Heading 2 Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//Heading 2
				array(
					'settings' => 'h2_google_font',
					'label'    => esc_html__( 'Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '700'
				),
				array(
					'settings' => 'h2_font_size',
					'label'    => esc_html__( 'Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '36',
				),
				array(
					'settings' => 'h2_font_height',
					'label'    => esc_html__( 'Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '36',
				),
				array(
					'settings' => 'h2_font_weight',
					'label'    => esc_html__( 'Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '600',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'h2_font_color',
					'label'    => esc_html__( 'Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),

				array(
					'settings' => 'font_title_h3',
					'label'    => esc_html__( 'Heading 3 Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//Heading 3
				array(
					'settings' => 'h3_google_font',
					'label'    => esc_html__( 'Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '700'
				),
				array(
					'settings' => 'h3_font_size',
					'label'    => esc_html__( 'Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '26',
				),
				array(
					'settings' => 'h3_font_height',
					'label'    => esc_html__( 'Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '28',
				),
				array(
					'settings' => 'h3_font_weight',
					'label'    => esc_html__( 'Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '600',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'h3_font_color',
					'label'    => esc_html__( 'Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),

				array(
					'settings' => 'font_title_h4',
					'label'    => esc_html__( 'Heading 4 Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				//Heading 4
				array(
					'settings' => 'h4_google_font',
					'label'    => esc_html__( 'Heading4 Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '700'
				),
				array(
					'settings' => 'h4_font_size',
					'label'    => esc_html__( 'Heading4 Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '18',
				),
				array(
					'settings' => 'h4_font_height',
					'label'    => esc_html__( 'Heading4 Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '26',
				),
				array(
					'settings' => 'h4_font_weight',
					'label'    => esc_html__( 'Heading4 Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '600',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'h4_font_color',
					'label'    => esc_html__( 'Heading4 Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),

				array(
					'settings' => 'font_title_h5',
					'label'    => esc_html__( 'Heading 5 Font Options', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),

				//Heading 5
				array(
					'settings' => 'h5_google_font',
					'label'    => esc_html__( 'Heading5 Google Font', 'urban-charity' ),
					'type'     => 'select',
					'default'  => 'Open Sans',
					'choices'  => urban_charity_get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '600'
				),
				array(
					'settings' => 'h5_font_size',
					'label'    => esc_html__( 'Heading5 Font Size', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'h5_font_height',
					'label'    => esc_html__( 'Heading5 Font Line Height', 'urban-charity' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h5_font_weight',
					'label'    => esc_html__( 'Heading5 Font Weight', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '600',
					'choices'  => array(
						'' => esc_html__( 'Select', 'urban-charity' ),
						'100' => esc_html__( '100', 'urban-charity' ),
						'200' => esc_html__( '200', 'urban-charity' ),
						'300' => esc_html__( '300', 'urban-charity' ),
						'400' => esc_html__( '400', 'urban-charity' ),
						'500' => esc_html__( '500', 'urban-charity' ),
						'600' => esc_html__( '600', 'urban-charity' ),
						'700' => esc_html__( '700', 'urban-charity' ),
						'800' => esc_html__( '800', 'urban-charity' ),
						'900' => esc_html__( '900', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'h5_font_color',
					'label'    => esc_html__( 'Heading5 Font Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),

			)//fields
		),//typo_setting

		array(
			'id'              => 'layout_styling',
			'title'           => esc_html__( 'Layout & Styling', 'urban-charity' ),
			'description'     => esc_html__( 'Layout & Styling', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'boxfull_en',
					'label'    => esc_html__( 'Select BoxWidth of FullWidth', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'fullwidth',
					'choices'  => array(
						'boxwidth' => esc_html__( 'BoxWidth', 'urban-charity' ),
						'fullwidth' => esc_html__( 'FullWidth', 'urban-charity' ),
					)
				),

				array(
					'settings' => 'body_bg_color',
					'label'    => esc_html__( 'Body Background Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#FBFBFB',
				),
				array(
					'settings' => 'body_bg_img',
					'label'    => esc_html__( 'Body Background Image', 'urban-charity' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'body_bg_attachment',
					'label'    => esc_html__( 'Body Background Attachment', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'fixed',
					'choices'  => array(
						'scroll' => esc_html__( 'Scroll', 'urban-charity' ),
						'fixed' => esc_html__( 'Fixed', 'urban-charity' ),
						'inherit' => esc_html__( 'Inherit', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'body_bg_repeat',
					'label'    => esc_html__( 'Body Background Repeat', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'no-repeat',
					'choices'  => array(
						'repeat' => esc_html__( 'Repeat', 'urban-charity' ),
						'repeat-x' => esc_html__( 'Repeat Horizontally', 'urban-charity' ),
						'repeat-y' => esc_html__( 'Repeat Vertically', 'urban-charity' ),
						'no-repeat' => esc_html__( 'No Repeat', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'body_bg_size',
					'label'    => esc_html__( 'Body Background Size', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'cover',
					'choices'  => array(
						'cover' => esc_html__( 'Cover', 'urban-charity' ),
						'contain' => esc_html__( 'Contain', 'urban-charity' ),
					)
				),
				array(
					'settings' => 'body_bg_position',
					'label'    => esc_html__( 'Body Background Position', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'left top',
					'choices'  => array(
						'left top' => esc_html__('left top', 'urban-charity'),
						'left center' => esc_html__('left center', 'urban-charity'),
						'left bottom' => esc_html__('left bottom', 'urban-charity'),
						'right top' => esc_html__('right top', 'urban-charity'),
						'right center' => esc_html__('right center', 'urban-charity'),
						'right bottom' => esc_html__('right bottom', 'urban-charity'),
						'center top' => esc_html__('center top', 'urban-charity'),
						'center center' => esc_html__('center center', 'urban-charity'),
						'center bottom' => esc_html__('center bottom', 'urban-charity'),
					)
				),
				array(
					'settings' => 'major_color',
					'label'    => esc_html__( 'Major Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#f8c218',
				),
				array(
					'settings' => 'hover_color',
					'label'    => esc_html__( 'Hover Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000000',
				),

				# navbar color section start.
				array(
					'settings' => 'menu_color_title',
					'label'    => esc_html__( 'Menu Color Settings', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'navbar_text_color',
					'label'    => esc_html__( 'Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),

				array(
					'settings' => 'navbar_hover_text_color',
					'label'    => esc_html__( 'Hover Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#f8c218',
				),

				array(
					'settings' => 'navbar_active_text_color',
					'label'    => esc_html__( 'Active Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#f8c218',
				),

				array(
					'settings' => 'sub_menu_color_title',
					'label'    => esc_html__( 'Sub-Menu Color Settings', 'urban-charity' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_menu_bg',
					'label'    => esc_html__( 'Background Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#ffffff',
				),
				array(
					'settings' => 'sub_menu_text_color',
					'label'    => esc_html__( 'Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#191919',
				),
				array(
					'settings' => 'sub_menu_text_color_hover',
					'label'    => esc_html__( 'Hover Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#f8c218',
				),
				#End of the navbar color section
			)//fields
		),//Layout & Styling

		array(
			'id'              => 'social_media_settings',
			'title'           => esc_html__( 'Social Media', 'urban-charity' ),
			'description'     => esc_html__( 'Social Media', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'wp_facebook',
					'label'    => esc_html__( 'Add Facebook URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '#',
				),
				array(
					'settings' => 'wp_twitter',
					'label'    => esc_html__( 'Add Twitter URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '#',
				),
				array(
					'settings' => 'wp_pinterest',
					'label'    => esc_html__( 'Add Pinterest URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '#',
				),
				array(
					'settings' => 'wp_youtube',
					'label'    => esc_html__( 'Add Youtube URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_linkedin',
					'label'    => esc_html__( 'Add Linkedin URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_linkedin_user',
					'label'    => esc_html__( 'Linkedin Username( For Share )', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),

				array(
					'settings' => 'wp_instagram',
					'label'    => esc_html__( 'Add Instagram URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '#',
				),
				array(
					'settings' => 'wp_dribbble',
					'label'    => esc_html__( 'Add Dribbble URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_behance',
					'label'    => esc_html__( 'Add Behance URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_flickr',
					'label'    => esc_html__( 'Add Flickr URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_vk',
					'label'    => esc_html__( 'Add Vk URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_skype',
					'label'    => esc_html__( 'Add Skype URL', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
			)//fields
		),//social_media

		array(
			'id'              => '404_settings',
			'title'           => esc_html__( '404 Page', 'urban-charity' ),
			'description'     => esc_html__( '404 page background and text settings', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(

				array(
					'settings' => 'urban_charity_404_title',
					'label'    => esc_html__( '404 Page Title', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => esc_html__('Page Not Found - Lost Maybe?.', 'urban-charity')
				),
				array(
					'settings' => 'urban_charity_404_description',
					'label'    => esc_html__( '404 Page Description', 'urban-charity' ),
					'type'     => 'textarea',
					'priority' => 10,
					'default'  => esc_html__('The page you are looking for was moved, removed, renamed or might never existed..', 'urban-charity')
				),
				array(
					'settings' => 'urban_charity_404_btn_text',
					'label'    => esc_html__( '404 Button Text', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => esc_html__('Go Back Home', 'urban-charity')
				),
			)
		),
		array(
			'id'              => 'blog_setting',
			'title'           => esc_html__( 'Blog Setting', 'urban-charity' ),
			'description'     => esc_html__( 'Blog Setting', 'urban-charity' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' 		=> 'blog_sidebar',
				  	'label' 		=> esc_html__( 'Enable Blog Sidebar', 'urban-charity' ),
				  	'section'  		=> 'sidebar_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  => 'true',
				),
				array(
					'settings' => 'blog_column',
					'label'    => esc_html__( 'Select Blog Column', 'urban-charity' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '12',
					'choices'  => array(
						'12' => esc_html__( 'Column 1', 'urban-charity' ),
						'6' => esc_html__( 'Column 2', 'urban-charity' ),
						'4' => esc_html__( 'Column 3', 'urban-charity' ),
						'3' => esc_html__( 'Column 4', 'urban-charity' ),
					)
				),
				array(
					'settings' 		=> 'blog_date',
				  	'label' 		=> esc_html__( 'Enable Blog Date', 'urban-charity' ),
				  	'section'  		=> 'date_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  => 'true',
				),
				array(
					'settings' 		=> 'blog_author',
				  	'label' 		=> esc_html__( 'Enable Blog Author', 'urban-charity' ),
				  	'section'  		=> 'author_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  => 'true',
				),

				array(
					'settings' 		=> 'blog_category',
				  	'label' 		=> esc_html__( 'Enable Blog Category', 'urban-charity' ),
				  	'section'  		=> 'category_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  => 'true',
				),
				array(
					'settings' => 'blog_post_text_limit',
					'label'    => esc_html__( 'Post character Limit', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '280',
				),
				array(
					'settings' 		=> 'blog_continue_en',
				  	'label' 		=> esc_html__( 'Enable Blog Readmore', 'urban-charity' ),
				  	'section'  		=> 'blog_en_controls_section',
				  	'priority' 		=> 10,
				  	'type'			=> 'checkbox',
				  	'default'  => 'true',
				),

				array(
					'settings' => 'blog_continue',
					'label'    => esc_html__( 'Continue Reading', 'urban-charity' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'Read More',
				),
			)//fields
		),//blog_setting

		array(
			'id'              => 'footer_setting',
			'title'           => esc_html__( 'Footer Setting', 'urban-charity' ),
			'description'     => esc_html__( 'Footer Setting', 'urban-charity' ),
			'priority'        => 10,
			'fields'         => array(

				array(
					'settings' => 'footer_bg',
					'label'    => esc_html__( 'Upload BG Image', 'urban-charity' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'footer_bg_color',
					'label'    => esc_html__( 'Footer background Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#3a3a3a',
				),
				array(
					'settings' => 'copyright_text_color',
					'label'    => esc_html__( 'Copyright Text Color', 'urban-charity' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#797979',
				),
				array(
					'settings' => 'copyright_padding_top',
					'label'    => esc_html__( 'Copyright Top Padding', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 25,
				),
				array(
					'settings' => 'copyright_padding_bottom',
					'label'    => esc_html__( 'Copyright Bottom Padding', 'urban-charity' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 25,
				),
				array(
					'settings' => 'copyright_text',
					'label'    => esc_html__( 'Copyright Text', 'urban-charity' ),
					'type'     => 'textarea',
					'priority' => 10,
					'default'  => esc_html__( '2020 Urban Charity. All Rights Reserved.', 'urban-charity' ),
				),
			)//fields
		),//footer_setting
	),
);//wpestate-core_panel_options

$urban_charity_framework = new THM_Customize( $urban_charity_panel_to_section );
