<?php
/*
* Plugin Name: Arrivala - Online Business Reviews
* Description: Integrates Arrivala with your WordPress website
* Version: 2.3.2
* Author: Cabe Nolan
* Author URI: https://arrivala.com
*/

class Arrivala_Fields_Plugin {

    public function __construct() {
        // Hook into the admin menu
        add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'add_acf_variables' ) );
		
		if (!class_exists('ACF')) {
        	add_filter( 'acf/settings/path', array( $this, 'update_acf_settings_path' ) );
			add_filter( 'acf/settings/dir', array( $this, 'update_acf_settings_dir' ) );
        	include_once( plugin_dir_path( __FILE__ ) . 'vendor/advanced-custom-fields/acf.php' );
		}
        $this->setup_arrivala_options();
        
        function my_acf_google_map_api( $api ){
			$api['key'] = 'AIzaSyD2xNSEHMUN2dPzD5QfbEMwnrdLUTs6XF8';
			return $api;	
		}
		add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
		
		//Enqueue the Dashicons script
		add_action( 'wp_enqueue_scripts', 'arrivala_load_dashicons_front_end' );
		function arrivala_load_dashicons_front_end() {
			wp_enqueue_style( 'dashicons' );
		}
		
		function acf_load_arrivala_post_types( $field ) {
		    $field['choices'] = array();
		    $args = array(
			   'public'   => true,
			   '_builtin' => false
			);
		    $post_types = get_post_types($args);
		    foreach ( get_post_types( '', 'names' ) as $post_type ) {
			   $field['choices'][ $post_type ] = $post_type;
			}
		    return $field;
		}
		add_filter('acf/load_field/name=arrivala_select_post_types', 'acf_load_arrivala_post_types');
    }

    public function update_acf_settings_path( $path ) {
        $path = plugin_dir_path( __FILE__ ) . 'vendor/advanced-custom-fields/';
        return $path;
    }

    public function update_acf_settings_dir( $dir ) {
        $dir = plugin_dir_url( __FILE__ ) . 'vendor/advanced-custom-fields/';
        return $dir;
    }

    public function create_plugin_settings_page() {
    	// Add the menu item and page
    	$page_title = 'Arrivala Reviews';
    	$menu_title = 'Arrivala Reviews';
    	$capability = 'manage_options';
    	$slug = 'arrivala_fields';
    	$callback = array( $this, 'plugin_settings_page_content' );
    	$icon = 'dashicons-admin-plugins';
    	$position = 100;

    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function plugin_settings_page_content() {
        do_action('acf/input/admin_head');
        do_action('acf/input/admin_enqueue_scripts');
		echo '<div class="wrap"><h2>Arrivala Options</h2>';
        $options = array(
        	'id' => 'acf-form',
        	'post_id' => 'options',
        	'new_post' => false,
        	'field_groups' => array( 'group_acf_arrivala_options' ),
        	'return' => admin_url('admin.php?page=arrivala_fields'),
        	'submit_value' => 'Update',
        );
        acf_form( $options );
        echo '</div>';
    }

    public function add_acf_variables() {
        acf_form_head();
    }

    public function setup_arrivala_options() {

    	if( function_exists( 'acf_add_local_field_group' ) ) {
    		acf_add_local_field_group(array (
    			'key' => 'group_acf_arrivala_options',
				'title' => 'About',
				'fields' => array(
					array(
						'key' => 'field_520696ad3c5a6',
						'label' => 'Arrivala Account Number',
						'name' => 'arrivala_account',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => 25,
					),
					array(
						'key' => 'field_520696ab8c5a6',
						'label' => 'Business Name',
						'name' => 'arrivala_business_name',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => 100,
					),
					array(
						'key' => 'field_5c2cc7241edac',
						'label' => 'Business Type',
						'name' => 'arrivala_business_type',
						'type' => 'select',
						'instructions' => 'Select the best fit',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'AnimalShelter' => 'Animal Shelter',
							'AutomotiveBusiness' => 'Automotive Business',
							'ChildCare' => 'Child Care',
							'Dentist' => 'Dentist',
							'DryCleaningOrLaundry' => 'Dry Cleaning or Laundry',
							'EmergencyService' => 'Emergency Service',
							'EmploymentAgency' => 'Employment Agency',
							'EntertainmentBusiness' => 'Entertainment Business',
							'FinancialService' => 'Financial Service',
							'FoodEstablishment' => 'Food Establishment',
							'GovernmentOffice' => 'Government Office',
							'HealthAndBeautyBusiness' => 'Health & Beauty Business',
							'HomeAndConstructionBusiness' => 'Home or Construction Business',
							'InternetCafe' => 'Internet Cafe',
							'LegalService' => 'Legal Services',
							'Library' => 'Library',
							'LodgingBusiness' => 'Lodging Business',
							'ProfessionalService' => 'Professional Service',
							'RadioStation' => 'Radio Station',
							'RealEstateAgent' => 'Real Estate',
							'RecyclingCenter' => 'Recycling Center',
							'SelfStorage' => 'Self Storage',
							'ShoppingCenter' => 'Shopping Center',
							'SportsActivityLocation' => 'Sports Activity Location',
							'Store' => 'Store',
							'TelevisionStation' => 'Television Station',
							'TouristInformationCenter' => 'Tourist Information Center',
							'TravelAgency' => 'Travel Agency',
						),
						'default_value' => array(
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
					array(
						'key' => 'field_5c2cc8021edad',
						'label' => 'Business Address',
						'name' => 'arrivala_business_address',
						'type' => 'google_map',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'center_lat' => '30.3096',
						'center_lng' => '-81.39',
						'zoom' => '',
						'height' => '',
					),
					array(
						'key' => 'field_5c2cc8431edae',
						'label' => 'Phone Number',
						'name' => 'arrivala_phone_number',
						'type' => 'text',
						'instructions' => 'Please enter in the format of 999-999-9999',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5c2cc8b91edb1',
						'label' => 'Price Range',
						'name' => 'arrivala_price_range',
						'type' => 'select',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'$' => '$',
							'$$' => '$$',
							'$$$' => '$$$',
							'$$$$' => '$$$$',
							'$$$$$' => '$$$$$',
						),
						'default_value' => array(
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
					array(
						'key' => 'field_5c2cc9311edb2',
						'label' => 'Hours of Operation',
						'name' => 'arrivala_hours_of_operation',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 7,
						'layout' => 'table',
						'button_label' => '',
						'sub_fields' => array(
							array(
								'key' => 'field_5c2cc99f1edb5',
								'label' => 'Day',
								'name' => 'day',
								'type' => 'select',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'Mo' => 'Monday',
									'Tu' => 'Tuesday',
									'We' => 'Wednesday',
									'Th' => 'Thursday',
									'Fr' => 'Friday',
									'Sa' => 'Saturday',
									'Su' => 'Sunday',
								),
								'default_value' => array(
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
							array(
								'key' => 'field_5c2cca141edb6',
								'label' => 'Opening Time',
								'name' => 'opening_time',
								'type' => 'select',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'00:30' => '00:30',
									'01:00' => '01:00',
									'01:30' => '01:30',
									'02:00' => '02:00',
									'02:30' => '02:30',
									'03:00' => '03:00',
									'03:30' => '03:30',
									'04:00' => '04:00',
									'04:30' => '04:30',
									'05:00' => '05:00',
									'05:30' => '05:30',
									'06:00' => '06:00',
									'06:30' => '06:30',
									'07:00' => '07:00',
									'07:30' => '07:30',
									'08:00' => '08:00',
									'08:30' => '08:30',
									'09:00' => '09:00',
									'09:30' => '09:30',
									'10:00' => '10:00',
									'10:30' => '10:30',
									'11:00' => '11:00',
									'11:30' => '11:30',
									'12:00' => '12:00',
									'12:30' => '12:30',
									'13:00' => '13:00',
									'13:30' => '13:30',
									'14:00' => '14:00',
									'14:30' => '14:30',
									'15:00' => '15:00',
									'15:30' => '15:30',
									'16:00' => '16:00',
									'16:30' => '16:30',
									'17:00' => '17:00',
									'17:30' => '17:30',
									'18:00' => '18:00',
									'18:30' => '18:30',
									'19:00' => '19:00',
									'19:30' => '19:30',
									'20:00' => '20:00',
									'20:30' => '20:30',
									'21:00' => '21:00',
									'21:30' => '21:30',
									'22:00' => '22:00',
									'22:30' => '22:30',
									'23:00' => '23:00',
									'23:30' => '23:30',
									'24:00' => '24:00',
								),
								'default_value' => array(
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
							array(
								'key' => 'field_5c2ccbb11edb7',
								'label' => 'Closing Time',
								'name' => 'closing_time',
								'type' => 'select',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'00:30' => '00:30',
									'01:00' => '01:00',
									'01:30' => '01:30',
									'02:00' => '02:00',
									'02:30' => '02:30',
									'03:00' => '03:00',
									'03:30' => '03:30',
									'04:00' => '04:00',
									'04:30' => '04:30',
									'05:00' => '05:00',
									'05:30' => '05:30',
									'06:00' => '06:00',
									'06:30' => '06:30',
									'07:00' => '07:00',
									'07:30' => '07:30',
									'08:00' => '08:00',
									'08:30' => '08:30',
									'09:00' => '09:00',
									'09:30' => '09:30',
									'10:00' => '10:00',
									'10:30' => '10:30',
									'11:00' => '11:00',
									'11:30' => '11:30',
									'12:00' => '12:00',
									'12:30' => '12:30',
									'13:00' => '13:00',
									'13:30' => '13:30',
									'14:00' => '14:00',
									'14:30' => '14:30',
									'15:00' => '15:00',
									'15:30' => '15:30',
									'16:00' => '16:00',
									'16:30' => '16:30',
									'17:00' => '17:00',
									'17:30' => '17:30',
									'18:00' => '18:00',
									'18:30' => '18:30',
									'19:00' => '19:00',
									'19:30' => '19:30',
									'20:00' => '20:00',
									'20:30' => '20:30',
									'21:00' => '21:00',
									'21:30' => '21:30',
									'22:00' => '22:00',
									'22:30' => '22:30',
									'23:00' => '23:00',
									'23:30' => '23:30',
									'24:00' => '24:00',
								),
								'default_value' => array(
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
					),
					array(
						'key' => 'field_5c2cc8861edaf',
						'label' => 'Upload Logo',
						'name' => 'arrivala_upload_logo',
						'type' => 'image',
						'instructions' => 'optional',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'medium',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
					array(
						'key' => 'field_5c2cc89a1edb0',
						'label' => 'Upload Image of Business',
						'name' => 'arrivala_upload_image_of_business',
						'type' => 'image',
						'instructions' => 'optional',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'medium',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
					array(
						'key' => 'field_5c373befcaad9',
						'label' => 'Add Schema Data To Website',
						'name' => 'arrivala_add_schema_data',
						'type' => 'radio',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'all' => 'Add to all pages (except homepage)',
							'post-types' => 'Select post types manually',
							'select-pages' => 'Select pages manually',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'save_other_choice' => 0,
						'default_value' => '',
						'layout' => 'vertical',
						'return_format' => 'value',
					),
					array(
						'key' => 'field_5c373a55caad8',
						'label' => 'Select Post Types For Schema Data',
						'name' => 'arrivala_select_post_types',
						'type' => 'select',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5c373befcaad9',
									'operator' => '==',
									'value' => 'post-types',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
						),
						'default_value' => array(
						),
						'allow_null' => 0,
						'multiple' => 1,
						'ui' => 1,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
					array(
						'key' => 'field_5c36b8d6d2a97',
						'label' => 'Select Pages For Schema Data',
						'name' => 'arrivala_pages_schema_data',
						'type' => 'post_object',
						'instructions' => 'Select pages to add schema data to.',
						'required' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5c373befcaad9',
									'operator' => '==',
									'value' => 'select-pages',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
						),
						'taxonomy' => array(
						),
						'allow_null' => 0,
						'multiple' => 1,
						'return_format' => 'id',
						'ui' => 1,
					),
					array(
						'key' => 'field_5c36b66a177be',
						'label' => 'Enable Review Widget',
						'name' => 'arrivala_enable_widget',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array(
						'key' => 'field_5c36aa29a4ff4',
						'label' => 'Widget Color',
						'name' => 'arrivala_widget_color',
						'type' => 'color_picker',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5c36b66a177be',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '#4a92af',
					),
					array(
						'key' => 'field_5c2cjuy65gb56',
						'label' => 'Minimum Star Rating To Show',
						'name' => 'arrivala_minimum_star',
						'type' => 'select',
						'instructions' => 'Only reviews equal or greater to this star rating will appear in the widget.',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'1' => '1 Star',
							'2' => '2 Stars',
							'3' => '3 Stars',
							'4' => '4 Stars',
							'5' => '5 Stars',
						),
						'default_value' => array(
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
					array(
						'key' => 'field_5c36b66a188yu',
						'label' => 'Hide Widget On Mobile',
						'name' => 'arrivala_hide_mobile',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5c36b66a177be',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array(
						'key' => 'field_5c36b66a163ew',
						'label' => 'Show "Reviews Powered By Arrivala" Text',
						'name' => 'arrivala_show_powered',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5c36b66a177be',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
    			),
    			'location' => array (
    				array (
    					array (
    						'param' => 'options_page',
    						'operator' => '==',
    						'value' => 'arrivala_fields',
    					),
    				),
    			),
    			'menu_order' => 0,
    			'position' => 'normal',
    			'style' => 'default',
    			'label_placement' => 'top',
    			'instruction_placement' => 'label',
    			'hide_on_screen' => '',
    			'active' => 1,
    			'description' => '',
    		));
    	}
    }

}
new Arrivala_Fields_Plugin();

function embed_arrivala_creation(){
	if(get_field('arrivala_account', 'options')) {
		$error = '';
		$arrivalaFile = "https://arrivala.com/wp-content/themes/twr/review-json/" . get_field('arrivala_account', 'options') . ".json";
		$file_headers = get_headers($arrivalaFile);
		if(strpos($file_headers[0], '404') !== false){
			echo '<script>console.error("Arrivala account number is invalid.");</script>';
		} else {
			$string = file_get_contents($arrivalaFile);
			$json_a = json_decode($string, true);
			$overarray = array_pop($json_a);
			$count = count($json_a);
			$showOn = get_field('arrivala_add_schema_data', 'options');
			if($showOn == 'post-types') {
				$showonPTs = get_field('arrivala_select_post_types', 'options');
			} elseif($showOn == 'select-pages') {
				$showonIDs = get_field('arrivala_pages_schema_data', 'options');
			}
			if(!empty($overarray) && $count > 0) {
				$location = get_field('arrivala_business_address', 'options');
				$locationAddress = explode(', ', $location['address']);
			?>
				<script type="application/ld+json">
				{
				  "@context": "http://schema.org",
				  "@type": "ProfessionalService",    
				  "name": "<?php the_field('arrivala_business_name', 'options'); ?>",
				  "address": {
				    "@type": "PostalAddress",
						"streetAddress": "<?php echo $locationAddress[0]; ?>",
						"addressLocality": "<?php echo $locationAddress[1]; ?>",
						"addressRegion": "<?php echo $locationAddress[2]; ?>"
					},
					"telePhone": "<?php the_field('arrivala_phone_number', 'options'); ?>",
					<?php if( have_rows('arrivala_hours_of_operation', 'options') ) { ?>
					"openingHours": "<?php while ( have_rows('arrivala_hours_of_operation', 'options') ) : the_row(); echo get_sub_field('day') . ' ' . get_sub_field('opening_time') . '-' . get_sub_field('closing_time') . ' '; endwhile; ?>",
					<?php } ?>
					"geo": {
						"@type": "GeoCoordinates",
						"latitude": "<?php echo $location['lat']; ?>",
						"longitude": "<?php echo $location['lng']; ?>"
					},
				  "url": "<?php echo site_url(); ?>",
				  <?php if(get_field('arrivala_upload_logo', 'options')) { ?>
				  "logo": "<?php $logoimg = get_field('arrivala_upload_logo', 'options'); echo $logoimg['url']; ?>",
				  <?php } ?>
				  <?php if(get_field('arrivala_upload_image_of_business', 'options')) { ?>
				  "image": "<?php $logoimg = get_field('arrivala_upload_image_of_business', 'options'); echo $logoimg['url']; ?>",
				  <?php } ?>
				  "priceRange":"<?php the_field('arrivala_price_range', 'options'); ?>"<?php if(($showOn == 'all' && !is_front_page()) || ($showOn == 'select-pages' && in_array(get_the_ID(), $showonIDs)) || ($showOn == 'post-types' && is_singular($showonPTs))) { ?>,
					  "aggregateRating": {
					    "@type": "AggregateRating",
					    "ratingValue": "<?php echo round($overarray['rating'], 2); ?>",
					    "ratingCount": "<?php echo $overarray['total']; ?>"
					 	 },
					  "review": [ 
					  <?php $a=0; $i=0; foreach ($json_a as $person_name => $person_a) { $i++; ?>
					    {
					    "@type": "Review",
					    "author": {
					        "@type": "Person",
					        "name": "<?php echo $person_a['title']; ?>"
					    },
					    "datePublished": "<?php echo $person_a['review-date']; ?>",
					    "reviewBody": "<?php echo $person_a['review']; ?>",
						    "reviewRating": {
						      "@type": "Rating",
						      "bestRating": "5",
						      "ratingValue": "<?php echo $person_a['star']; ?>",
						      "worstRating": "1"
						    }
					    }<?php if($i !== $count) { echo','; } ?>
					  <?php } ?>
					  ]
				  <?php } ?>
				}
				</script>
				<?php if(!empty($overarray) && $count > 0 && get_field('arrivala_enable_widget', 'options')) {?>
				<?php if(get_field('arrivala_minimum_star', 'options')) { 
					$minStar = get_field('arrivala_minimum_star', 'options');
				} else {
					$minStar = 1;
				}
				?>
				<style>
					.arrivala-rating{position:fixed;right:0;bottom:30%;z-index:9999}.arrivala-popup{background:#fff;position:fixed!important;bottom:0!important;right:0!important;height:0%;width:388px!important;z-index:999999!important;box-shadow:0 1px 2px 2px rgba(0,0,0,.12)!important}.arrivala-toggle-button{height:50px;width:220px;padding:14px 8px 0;border-radius:3px 3px 0 0;background:<?php the_field('arrivala_widget_color', 'options'); ?>;color:#fff;line-height:22px;text-align:center;font-size:20px;letter-spacing:.5px;margin-right:-170px;display:inline-block;transform:rotate(-90deg);transform-origin:left top 0;-webkit-box-shadow:-1px -4px 15px -1px rgba(0,0,0,.15);-moz-box-shadow:-1px -4px 15px -1px rgba(0,0,0,.15);box-shadow:-1px -4px 15px -1px rgba(0,0,0,.15)}.arrivala-toggle-button:hover{-webkit-box-shadow:-1px -4px 15px -1px rgba(0,0,0,.35);-moz-box-shadow:-1px -4px 15px -1px rgba(0,0,0,.35);box-shadow:-1px -4px 15px -1px rgba(0,0,0,.35);color:#fff;text-decoration:none}.arrivala-popup.active-reviews{height:100%;visibility:visible}.arrivala-popup .dashicons{position:relative;box-sizing:content-box;padding:5px 0;width:25px;height:25px;overflow:hidden;white-space:nowrap;font-size:25px;line-height:1;cursor:pointer}.arrivala-popup-header{position:absolute;height:100px;width:100%;padding:5px 20px;background:#fff;border-bottom:1px solid #eee;z-index:9999999}.arrivala-popup-header p{line-height:1.2;font-size:14px}.arrivala-stars .dashicons{color:#DAA520;font-size:40px;width:40px;height:40px}.arrivala-header-interior{position:relative}.arrivala-header-interior .arrivala-close-box{position:absolute;right:5px;top:-5px;color:#696868}.arrivala-header-interior .arrivala-close-box:hover{color:#616161}.arrivala-popup-body{overflow-y:scroll;height:100%;background:rgba(250,250,251,.98);padding:100px 15px 70px}.arrivala-popup-body .arrivala-review{padding:15px 20px;margin:10px 5px 20px;background:#FFF;-webkit-box-shadow:0 1px 5px rgba(0,0,0,.2);-moz-box-shadow:0 1px 5px rgba(0,0,0,.2);-o-box-shadow:0 1px 5px rgba(0,0,0,.2);box-shadow:0 1px 5px rgba(0,0,0,.2);clear:both}.arrivala-popup-body .arrivala-review h4{font-size:14px;text-transform:uppercase;font-style:italic}.arrivala-popup-body .arrivala-review p{font-weight:500;line-height:1.4}.arrivala-popup-body .arrivala-review .dashicons{color:#DAA520;font-size:15px;width:15px;height:15px}.arrivala-popup-footer{position:absolute;height:<?php if(get_field('arrivala_show_powered', 'options')) { echo '70'; } else { echo '50'; } ?>px;bottom:0;width:100%;padding:5px;background:#fff;display:none;text-align:center}.arrivala-popup-footer .write-review{background:<?php the_field('arrivala_widget_color', 'options'); ?>;color:#fff;padding:5px 15px;font-size:14px}.arrivala-popup-footer p { margin-top: 5px; }.arrivala-popup-footer p a{font-size:12px;margin-top:5px;color:#333!important}.arrivala-popup-footer.show-arrivala-footer{display:block;padding-top:15px}@media screen and (max-width:767px){<?php if(get_field('arrivala_hide_mobile', 'options')) { ?>.arrivala-toggle-button {display: none !important;}<?php } ?>.arrivala-toggle-button{height:40px;margin-right:-180px;padding-top:8px;font-size:18px}}
				</style>
					<div id="arrivala-sidebar">
						<div class="arrivala-rating">
							<a href="#" class="arrivala-toggle-button">Read Our Reviews</a>
						</div>
						<div class="arrivala-popup">
							<div class="arrivala-popup-header">
								<div class="arrivala-header-interior">
									<div class="arrivala-stars">
										<?php
										echo str_repeat('<span class="dashicons dashicons-star-filled"></span> ', $overarray['rating']); 
										echo is_float($overarray['rating']) ? '<span class="dashicons dashicons-star-half"></span>' : ''; 
										?>
									</div>
									<p><?php the_field('arrivala_business_name', 'options'); ?> is rated <?php echo round($overarray['rating'], 2); ?> out of 5.0 stars based on <?php echo $overarray['total']; ?> review(s).
									<a href="#" class="arrivala-close-box"><span class="dashicons dashicons-no"></span></a>
								</div>
							</div>
							<div class="arrivala-popup-body">
								<?php $i=0; foreach ($json_a as $person_name => $person_a) { $i++; ?>
									<?php if($person_a['star'] >= $minStar) { ?>
										<div class="arrivala-review">
											<div class="review-arrivala-stars">
												<?php
												echo str_repeat('<span class="dashicons dashicons-star-filled"></span> ', $person_a['star']); 
												echo is_float($person_a['star']) ? '<span class="dashicons dashicons-star-half"></span>' : ''; 
												?>
											</div>
											---
											<p><?php echo $person_a['review']; ?></p>
											<h4>- <?php echo $person_a['title']; ?></h4>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<div class="arrivala-popup-footer">
								<a href="<?php echo $overarray['arrivala-account']; ?>" target="_blank" class="write-review">Write Review</a>
								<?php if(get_field('arrivala_show_powered', 'options')) { ?>
									<p><a href="https://arrivala.com" target="_blank">Reviews Powered By Arrivala</a></p>
								<?php } ?>
							</div>
						</div>
					</div>
					<script>
						jQuery('.arrivala-toggle-button').click(function(e){
							e.preventDefault();
							jQuery(".arrivala-popup").toggleClass("active-reviews");
							jQuery(".arrivala-popup-footer").toggleClass("show-arrivala-footer");
						});
						jQuery('.arrivala-close-box').click(function(e){
							e.preventDefault();
							jQuery(".arrivala-popup").toggleClass("active-reviews");
							jQuery(".arrivala-popup-footer").toggleClass("show-arrivala-footer");
						});
					</script>
				<?php } ?>
		<?php
			}
		}
	}
}
add_action('wp_footer', 'embed_arrivala_creation');
?>