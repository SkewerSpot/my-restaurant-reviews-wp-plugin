<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://skewerspot.com/
 * @since      1.0.0
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/admin
 * @author     Anurag Bhandari <anurag.bhd@gmail.com>
 */
class MyRestaurantReviewsAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MyRestaurantReviewsLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyRestaurantReviewsLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mrr-admin.css',
			array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MyRestaurantReviewsLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyRestaurantReviewsLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mrr-admin.js',
			array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	function add_action_links($links) {

		$links = array_merge( $links, array(
			'<a href="' . esc_url( admin_url( '/options-general.php?page=' . $this->plugin_name ) ) . '">' .
			__( 'Settings', 'ssmrr' ) . '</a>'
		) );
		return $links;

	}

	/**
	 * Add options for MRR page in Settings admin menu.
	 * 
	 * @since			1.0.0
	 */
	public function add_settings_page() {

		// A shorthand for add_submenu_page( 'options-general.php' ... )
		add_options_page(
			__( 'My Restaurant Reviews &mdash; Settings', 'ssmrr' ),
			__( 'My Restaurant Reviews', 'ssmrr' ),
			'manage_options',
			'my_restaurant_reviews',
			array( $this, 'mrr_options_page_html' )
		);

	}

	/**
	 * Register settings, and add sections & fields for MRR page in WP Admin.
	 * 
	 * @since			1.0.0
	 */
	public function initialize_settings() {

		$this->init_zomato_settings();
		$this->init_google_settings();
		$this->init_display_settings();
		$this->init_general_settings();

	}

	/**
	 * Add Zomato section and related fields & settings on plugin options page.
	 * 
	 * @since			1.0.0
	 */
	public function init_zomato_settings() {

		// Register a setting for each field
		register_setting( 'mrr_settings', 'mrr_setting_zomato_apikey' );
		register_setting( 'mrr_settings', 'mrr_setting_zomato_restid' );

		// Add section
		add_settings_section(
			'mrr_section_zomato',
			__( 'Zomato Settings', 'ssmrr' ),
			array( $this, 'mrr_section_zomato_html' ),
			'my_restaurant_reviews'
		);

		// Add fields
		add_settings_field(
			'mrr_field_zomato_apikey',
			__( 'Developer API Key', 'ssmrr' ),
			array( $this, 'mrr_field_zomato_apikey_html' ),
			'my_restaurant_reviews',
			'mrr_section_zomato',
			[ 'label_for' => 'mrr_field_zomato_apikey' ]
		);
		add_settings_field(
			'mrr_field_zomato_restid',
			__( 'Restaurant ID', 'ssmrr' ),
			array( $this, 'mrr_field_zomato_restid_html' ),
			'my_restaurant_reviews',
			'mrr_section_zomato',
			[ 'label_for' => 'mrr_field_zomato_restid' ]
		);

	}

	/**
	 * Add Google section and related fields & settings on plugin options page.
	 * 
	 * @since			1.0.0
	 */
	public function init_google_settings() {

		// Register a setting for each field
		register_setting( 'mrr_settings', 'mrr_setting_google_apikey' );
		register_setting( 'mrr_settings', 'mrr_setting_google_placeid' );

		// Add section
		add_settings_section(
			'mrr_section_google',
			__( 'Google Settings', 'ssmrr' ),
			array( $this, 'mrr_section_google_html' ),
			'my_restaurant_reviews'
		);

		// Add fields
		add_settings_field(
			'mrr_field_google_apikey',
			__( 'API Key', 'ssmrr' ),
			array( $this, 'mrr_field_google_apikey_html' ),
			'my_restaurant_reviews',
			'mrr_section_google',
			[ 'label_for' => 'mrr_field_google_apikey' ]
		);
		add_settings_field(
			'mrr_field_google_placeid',
			__( 'Place ID', 'ssmrr' ),
			array( $this, 'mrr_field_google_placeid_html' ),
			'my_restaurant_reviews',
			'mrr_section_google',
			[ 'label_for' => 'mrr_field_google_placeid' ]
		);

	}

	/**
	 * Add Display section and related fields & settings on plugin options page.
	 * 
	 * @since			1.0.0
	 */
	public function init_display_settings() {

		// Register a setting for each field
		register_setting( 'mrr_settings', 'mrr_setting_display_sortorder' );
		register_setting( 'mrr_settings', 'mrr_setting_display_maxdisplayreviews' );
		register_setting( 'mrr_settings', 'mrr_setting_display_minrating' );
		register_setting( 'mrr_settings', 'mrr_setting_display_maxwords' );

		// Add section
		add_settings_section(
			'mrr_section_display',
			__( 'Display (Shortcode) Settings', 'ssmrr' ),
			array( $this, 'mrr_section_display_html' ),
			'my_restaurant_reviews'
		);

		// Add fields
		add_settings_field(
			'mrr_field_display_sortorder',
			__( 'Display reviews in this order', 'ssmrr' ),
			array( $this, 'mrr_field_display_sortorder_html' ),
			'my_restaurant_reviews',
			'mrr_section_display',
			[ 'label_for' => 'mrr_field_display_sortorder' ]
		);
		add_settings_field(
			'mrr_field_display_maxdisplayreviews',
			__( 'How many reviews to display at max?', 'ssmrr' ),
			array( $this, 'mrr_field_display_maxdisplayreviews_html' ),
			'my_restaurant_reviews',
			'mrr_section_display',
			[ 'label_for' => 'mrr_field_display_maxdisplayreviews' ]
		);
		add_settings_field(
			'mrr_field_display_minrating',
			__( 'Do not show reviews with rating below (1-5)', 'ssmrr' ),
			array( $this, 'mrr_field_display_minrating_html' ),
			'my_restaurant_reviews',
			'mrr_section_display',
			[ 'label_for' => 'mrr_field_display_minrating' ]
		);
		add_settings_field(
			'mrr_field_display_maxwords',
			__( 'How many words after which review will be clipped?', 'ssmrr' ),
			array( $this, 'mrr_field_display_maxwords_html' ),
			'my_restaurant_reviews',
			'mrr_section_display',
			[ 'label_for' => 'mrr_field_display_maxwords' ]
		);
	}

	/**
	 * Add General section and related fields & settings on plugin options page.
	 * 
	 * @since			1.0.0
	 */
	public function init_general_settings() {

		// Register a setting for each field
		register_setting( 'mrr_settings', 'mrr_setting_general_polltime' );
		register_setting( 'mrr_settings', 'mrr_setting_general_category' );
		register_setting( 'mrr_settings', 'mrr_setting_general_maxfetchreviews' );

		// Add section
		add_settings_section(
			'mrr_section_general',
			__( 'General Settings', 'ssmrr' ),
			array( $this, 'mrr_section_general_html' ),
			'my_restaurant_reviews'
		);

		// Add fields
		add_settings_field(
			'mrr_field_general_polltime',
			__( 'How frequently should we check for new reviews?', 'ssmrr' ),
			array( $this, 'mrr_field_general_polltime_html' ),
			'my_restaurant_reviews',
			'mrr_section_general',
			[ 'label_for' => 'mrr_field_general_polltime' ]
		);
		add_settings_field(
			'mrr_field_general_category',
			__( 'Create posts for new reviews under category', 'ssmrr' ),
			array( $this, 'mrr_field_general_category_html' ),
			'my_restaurant_reviews',
			'mrr_section_general',
			[ 'label_for' => 'mrr_field_general_category' ]
		);
		add_settings_field(
			'mrr_field_general_maxfetchreviews',
			__( 'How many new reviews to fetch from each source at max?', 'ssmrr' ),
			array( $this, 'mrr_field_general_maxfetchreviews_html' ),
			'my_restaurant_reviews',
			'mrr_section_general',
			[ 'label_for' => 'mrr_field_general_maxfetchreviews' ]
		);

	}

	/**
	 * Outputs the HTML for plugin settings page.
	 * Callable for our plugin's add_options_page.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_options_page_html() {
		// Check user capabilities
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}
	
		// Add error/update messages
		// Check if the user has submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET[ 'settings-updated' ] ) ) {
			add_settings_error( 'mrr_messages', 'mrr_message_settings', __( 'Settings Saved', 'ssmrr' ), 'updated' );
		}
	
		// Show error/update messages
		settings_errors( 'mrr_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<div class="mrr-settings-section-notice">
				<p class="title"><?php echo __( 'Thank you for using My Restaurant Reviews.' ); ?></p>
				<p><?php echo __( 'You can show your restaurant\'s reviews in either or both of the following two ways:' ); ?></p>
				<ul>
					<li><?php echo __( 'use the shortcode <code>[my-restaurant-reviews]</code> in a post or page of your choice' ); ?></li>
					<li><?php echo __( 'enable the option to create posts for new reviews in General Settings below' ); ?></li>
				</ul>
				<p><?php echo __( 'The second option is especially helpful with certain themes, ' .
							'such as <a href="https://wordpress.org/themes/food-restro/">Food Restro</a>, ' .
							'that tie up a slide/carousel/list section with a post category.' ); ?></p>
			</div>
			<form id="mrrOptionsForm" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<?php
				// Under normal conditions, using the following WP function call
				// would automatically add "action" and "nonce" hidden inputs,
				// where value for action set to 'update'.
				// But since we are explicitly handling this form's submission event,
				// we'll need to add these fields manually as we want to set a custom value for action.
				// Refer: https://premium.wpmudev.org/blog/handling-form-submissions/
				//
				// settings_fields( 'mrr_settings' );
				?>
				<input type="hidden" name="action" value="mrr_options_form">
				<?php wp_nonce_field( 'mrr_options_page_submit', 'mrr_options_page_nonce' ); ?>
				<?php
				// Output setting sections and their fields
				// (sections are registered for "my_restaurant_reviews",
				// each field is registered to a specific section)
				do_settings_sections( 'my_restaurant_reviews' );
				// Output save settings button
				submit_button( __( 'Save Settings', 'ssmrr' ) );
				?>
			</form>
		</div>
	<?php

	}

	/**
	 * Outputs the HTML for Zomato section on plugin's options page.
	 * Callable for add_settings_section.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_section_zomato_html() {

		?>
		<div class="mrr-settings-section-notice">
			<?php echo esc_html_e( 'This section contains settings related to Zomato.', 'ssmrr' ); ?>
		</div>
	<?php

	}

	/**
	 * Outputs the HTML for Zomato API Key field.
	 * Callable for add_settings_field.
	 */
	public function mrr_field_zomato_apikey_html( $args ) {

		$api_key = get_option( 'mrr_setting_zomato_apikey' );
		?>
		<input type="text" name="mrr_setting_zomato_apikey" class="regular-text"
			value="<?php echo isset( $api_key ) ? esc_attr( $api_key ) : ''; ?>" />
		<p class="description">
			<?php echo __( 'To get an API key, head over to '.
				'<a href="https://developers.zomato.com/api">'.
				'Zomato Developer API page</a>.', 'ssmrr' ); ?>
		</p>
	<?php

	}

	/**
	 * Outputs the HTML for Zomato Restaurant ID field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_zomato_restid_html( $args ) {

		$restaurant_id = get_option( 'mrr_setting_zomato_restid' );
		?>
		<input type="text" name="mrr_setting_zomato_restid" class="regular-text"
			value="<?php echo isset( $restaurant_id ) ? esc_attr( $restaurant_id ) : ''; ?>" />
		<p class="description">
			<?php echo __( 'Ask your Zomato Account Manager for restaurant ID.<br>'.
				'Alternatively, visit Zomato for Business dashboard and '.
				'notice the value for entity_id in URL.', 'ssmrr' ); ?>
		</p>
	<?php

	}

	/**
	 * Outputs the HTML for Google section on plugin's options page.
	 * Callable for add_settings_section.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_section_google_html() {

		?>
		<div class="mrr-settings-section-notice">
			<?php echo __( 'This section contains settings related to Google (Places/Maps).<br>' .
															'<b>Important:</b> Google\'s Places API is '.
															'<a href="https://developers.google.com/places/web-service/usage-and-billing#atmosphere-data">chargeable</a>.', 'ssmrr' ); ?>
		</div>
	<?php

	}

	/**
	 * Outputs the HTML for Google API Key field.
	 * Callable for add_settings_field.
	 */
	public function mrr_field_google_apikey_html( $args ) {

		$api_key = get_option( 'mrr_setting_google_apikey' );
		?>
		<input type="text" name="mrr_setting_google_apikey" class="regular-text"
			value="<?php echo isset( $api_key ) ? esc_attr( $api_key ) : ''; ?>" />
		<p class="description">
			<?php echo __( 'To get an API key, head over to '.
				'<a href="https://developers.google.com/places/web-service/get-api-key">'.
				'Google Maps API docs</a>.', 'ssmrr' ); ?>
		</p>
	<?php

	}

	/**
	 * Outputs the HTML for Google Maps Place ID field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_google_placeid_html( $args ) {

		$place_id = get_option( 'mrr_setting_google_placeid' );
		?>
		<input type="text" name="mrr_setting_google_placeid" class="regular-text"
			value="<?php echo isset( $place_id ) ? esc_attr( $place_id ) : ''; ?>" />
			<p class="description">
			<?php echo __( 'To get your restaurant\'s place ID, head over to '.
				'<a href="https://developers.google.com/places/place-id">'.
				'this page</a>.', 'ssmrr' ); ?>
		</p>
	<?php

	}

	/**
	 * Outputs the HTML for Display section on plugin's options page.
	 * Callable for add_settings_section.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_section_display_html() {

		?>
		<div class="mrr-settings-section-notice">
			<?php echo esc_html_e( 'This section contains settings related to how reviews are displayed '.
														 'via the [my-restaurant-review] shortcode.', 'ssmrr' ); ?>
		</div>
	<?php

	}

	/**
	 * Outputs the HTML for Sort Order field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_display_sortorder_html( $args ) {

		$selected_sortorder = get_option( 'mrr_setting_display_sortorder', 'timedesc' );
		?>
		<select name="mrr_setting_display_sortorder">
			<?php
				$orders = array(
					'timedesc' => __( 'Latest First' ),
					'timeasc' => __( 'Oldest First' ),
					'random' => __( 'Random' )
				);
				foreach ( $orders as $value => $display_name ) {
					?>
					<option value="<?php echo $value ?>"
						<?php echo $value === $selected_sortorder ? 'selected' : ''; ?>>
						<?php echo $display_name; ?>
					</option>
				<?php
				}
			?>
		</select>
	<?php

	}

	/**
	 * Outputs the HTML for Max Display Reviews field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_display_maxdisplayreviews_html( $args ) {

		$max_num = get_option( 'mrr_setting_display_maxdisplayreviews', 10 );
		?>
		<input type="number" name="mrr_setting_display_maxdisplayreviews" class="small-text"
			value="<?php echo esc_attr( $max_num ); ?>" />
	<?php

	}

	/**
	 * Outputs the HTML for Min Rating field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_display_minrating_html( $args ) {

		$max_num = get_option( 'mrr_setting_display_minrating', 4 );
		?>
		<input type="number" name="mrr_setting_display_minrating" class="small-text"
			min="1" max="5" value="<?php echo esc_attr( $max_num ); ?>" />	
	<?php

	}

	/**
	 * Outputs the HTML for Max Words field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_display_maxwords_html( $args ) {

		$max_words = get_option( 'mrr_setting_display_maxwords', 55 );
		?>
		<input type="number" name="mrr_setting_display_maxwords" class="small-text"
			min="15" max="150" value="<?php echo esc_attr( $max_words ); ?>" />	
	<?php

	}

	/**
	 * Outputs the HTML for General section on plugin's options page.
	 * Callable for add_settings_section.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_section_general_html() {

		?>
		<div class="mrr-settings-section-notice">
			<?php echo __( 'This section contains common settings and customizations.', 'ssmrr' ); ?>
		</div>
	<?php

	}

	/**
	 * Outputs the HTML for Poll Time field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_general_polltime_html( $args ) {

		$poll_time = get_option( 'mrr_setting_general_polltime', 'daily' );
		?>
		<select name="mrr_setting_general_polltime">
			<?php
				$default_cron_schedules = wp_get_schedules();
				foreach ( $default_cron_schedules as $sched_name => $sched_details ) {
					?>
					<option value="<?php echo $sched_name ?>"
						<?php echo $sched_name === $poll_time ? 'selected' : ''; ?>>
						<?php echo $default_cron_schedules[ $sched_name ][ 'display' ]; ?>
					</option>
				<?php
				}
			?>
		</select>
		<input id="btnCheckNow" type="submit" class="button-secondary"
			value="<?php esc_attr_e( 'Check Now', 'ssmrr' ) ?>" />
	<?php

	}

	/**
	 * Outputs the HTML for Category field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_general_category_html( $args ) {

		$selected_category = get_option( 'mrr_setting_general_category', 0 );
		?>
		<select name="mrr_setting_general_category">
			<option value=""><?php esc_html_e( 'DON\'T CREATE NEW POSTS' ) ?></option>
			<?php
				$categories = get_categories( array( 'hide_empty' => false ) );
				foreach ( $categories as $category ) {
					?>
					<option value="<?php echo $category->cat_ID ?>"
						<?php echo $category->cat_ID == $selected_category ? 'selected' : ''; ?>>
						<?php echo $category->name; ?>
					</option>
				<?php
				}
			?>
		</select>
		<p class="description">
			<?php echo __( 'A change in this setting will only apply to future reviews.', 'ssmrr' ); ?>
		</p>
	<?php

	}

	/**
	 * Outputs the HTML for Max Fetch Reviews field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_general_maxfetchreviews_html( $args ) {

		$max_num = get_option( 'mrr_setting_general_maxfetchreviews', 5 );
		?>
		<input type="number" name="mrr_setting_general_maxfetchreviews" class="small-text"
			value="<?php echo esc_attr( $max_num ); ?>" />
		<p class="description">
			<?php echo __( 'Zomato and Google both cap reviews returned at 5.', 'ssmrr' ); ?>
		</p>		
	<?php

	}

	/**
	 * Handler for plugin's options page.
	 * 
	 * @since			1.0.0
	 */
	public function handle_options_form_submission() {

		if ( $_POST[ 'mrr_options_page_nonce' ] &&
				 wp_verify_nonce( $_POST[ 'mrr_options_page_nonce' ], 'mrr_options_page_submit' ) ) {
			$this->save_options();
			$this->update_cron();
			wp_redirect( admin_url( 'options-general.php?page=' . $this->plugin_name . '&settings-updated=true' ) );
			exit;
		} else {
			wp_die( __( 'Invalid nonce specified', 'ssmrr' ),
							__( 'Error', 'ssmrr' ),
							array(
								'response' 	=> 403,
								'back_link' => admin_url( 'options-general.php?page=' . $this->plugin_name ),
							)
			);
		}

	}

	/**
	 * Saves/updates settings on Options page in database.
	 * 
	 * @since			1.0.0
	 */
	public function save_options() {

		$mrr_settings = array(
			'mrr_setting_zomato_apikey' => sanitize_key( $_POST[ 'mrr_setting_zomato_apikey' ] ),
			'mrr_setting_zomato_restid' => sanitize_text_field( $_POST[ 'mrr_setting_zomato_restid' ] ),
			'mrr_setting_google_apikey' => sanitize_text_field( $_POST[ 'mrr_setting_google_apikey' ] ),
			'mrr_setting_google_placeid' => sanitize_text_field( $_POST[ 'mrr_setting_google_placeid' ] ),
			'mrr_setting_display_sortorder' => sanitize_text_field( $_POST[ 'mrr_setting_display_sortorder' ] ),
			'mrr_setting_display_maxdisplayreviews' => absint( $_POST[ 'mrr_setting_display_maxdisplayreviews' ] ),
			'mrr_setting_display_minrating' => absint( $_POST[ 'mrr_setting_display_minrating' ] ),
			'mrr_setting_display_maxwords' => absint( $_POST[ 'mrr_setting_display_maxwords' ] ),
			'mrr_setting_general_polltime' => sanitize_text_field( $_POST[ 'mrr_setting_general_polltime' ] ),
			'mrr_setting_general_category' => absint( $_POST[ 'mrr_setting_general_category' ] ),
			'mrr_setting_general_maxfetchreviews' => absint( $_POST[ 'mrr_setting_general_maxfetchreviews' ] )
		);
		foreach ( $mrr_settings as $key => $value ) {
			$this->set_option( $key, $value );
		}

	}

	/**
	 * Re-schedules the Cron job as per potentially new poll time setting.
	 * 
	 * @since			1.0.0
	 */
	public function update_cron() {

		$poll_time = get_option( 'mrr_setting_general_polltime' );
		if ( wp_next_scheduled( 'mrr_cron_hook' ) ) {
			$timestamp = wp_next_scheduled( 'mrr_cron_hook' );
			wp_unschedule_event( $timestamp, 'mrr_cron_hook' );
		}
		wp_schedule_event( time(), $poll_time, 'mrr_cron_hook' );

	}

	/**
	 * Gets latest restaurant reviews from various online sources,
	 * such as Zomato, Google Maps, etc., stores them in cache,
	 * and optionally adds them as posts under user-specified category.
	 * 
	 * @since			1.0.0
	 */
	public function update_reviews() {

		// Fetch latest online reviews
		$max_num_reviews = get_option( 'mrr_setting_general_maxfetchreviews' );
		$latest_reviews = array_merge(
			array(),
			$this->get_zomato_reviews( $max_num_reviews ),
			$this->get_google_reviews( $max_num_reviews )
		);
		$new_reviews = $this->find_new_reviews( $latest_reviews );

		// (optionally) Create posts for new reviews
		$review_category_id = get_option( 'mrr_setting_general_category' );
		if ( $review_category_id != 0 ) {
			foreach ( $new_reviews as $review ) {
				$this->add_review_to_category( $review, $review_category_id );
			}
		}

		// Update cache
		$updated_reviews = array();
		$cached_reviews = get_option( 'mrr_setting_reviews' );
		if ( $cached_reviews ) {
			$updated_reviews = array_merge( $cached_reviews, $new_reviews );
		} else {
			$updated_reviews = $new_reviews;
		}
		$this->set_option( 'mrr_setting_reviews', $updated_reviews );
		$this->set_option( 'mrr_setting_last_updated', time() );

	}

	/**
	 * Fetches latest $max_num reviews from Zomato Developer API,
	 * and returns them is a normalized format.
	 * 
	 * @since			1.0.0
	 */
	private function get_zomato_reviews($max_num) {

		$normalized_reviews = array();

		$google_apikey = get_option( 'mrr_setting_zomato_apikey' );
		$google_placeid = get_option( 'mrr_setting_zomato_restid' );

		if ( ! $google_apikey || ! $google_placeid ) return array();

		$zomato_api_args = array(
			'headers' => array(
				'user-key' => $google_apikey
			)
		);
		$google_api_url = 'https://developers.zomato.com/api/v2.1/reviews?res_id=' . $google_placeid . '&count=' . $max_num;
		$google_api_response = wp_remote_retrieve_body( wp_remote_get( $google_api_url, $zomato_api_args ) );
		
		if ( $google_api_response ) {
			$google_api_response_json = json_decode( $google_api_response, true );
			$reviews = $google_api_response_json[ 'user_reviews' ];
			if ( is_array( $reviews ) ) {
				foreach ( $reviews as $review ) {
					$r = array(
						'rating' => $review[ 'review' ][ 'rating' ],
						'review_text' => $review[ 'review' ][ 'review_text' ],
						'timestamp' => $review[ 'review' ][ 'timestamp' ],
						'reviewer_name' => $review[ 'review' ][ 'user' ][ 'name' ],
						'reviewer_image' => $review[ 'review' ][ 'user' ][ 'profile_image' ],
						'source' => 'Zomato',
						'orig_id' => $review[ 'review' ][ 'id' ]
					);
					array_push( $normalized_reviews, $r );
				}
			}
		}

		return $normalized_reviews;
		
	}

	/**
	 * Fetches latest $max_num reviews from Google Places API,
	 * and returns them is a normalized format.
	 * 
	 * @since			1.0.0
	 */
	private function get_google_reviews($max_num) {

		$normalized_reviews = array();

		$google_apikey = get_option( 'mrr_setting_google_apikey' );
		$google_placeid = get_option( 'mrr_setting_google_placeid' );

		if ( ! $google_apikey || ! $google_placeid ) return array();

		$google_api_url = 'https://maps.googleapis.com/maps/api/place/details/json?fields=reviews&placeid=' .
												$google_placeid . '&key=' . $google_apikey;
		$google_api_response = wp_remote_retrieve_body( wp_remote_get( $google_api_url ) );
		
		if ( $google_api_response ) {
			$google_api_response_json = json_decode( $google_api_response, true );
			$reviews = $google_api_response_json[ 'result' ][ 'reviews' ];
			if ( is_array( $reviews ) ) {
				foreach ( $reviews as $review ) {
					$r = array(
						'rating' => $review[ 'rating' ],
						'review_text' => $review[ 'text' ],
						'timestamp' => $review[ 'time' ],
						'reviewer_name' => $review[ 'author_name' ],
						'reviewer_image' => $review[ 'profile_photo_url' ],
						'source' => 'Google',
						'orig_id' => ''
					);
					array_push( $normalized_reviews, $r );
				}
			}
		}

		return $normalized_reviews;
		
	}

	/**
	 * Compares given with cached reviews
	 * and returns only the new ones with a minimum rating.
	 * 
	 * @since			1.0.0
	 */
	private function find_new_reviews($reviews) {

		$new_reviews = array();		

		foreach ( $reviews as $review ) {
			if ( $this->is_new_review( $review ) ) {
				array_push( $new_reviews, $review );
			}
		}

		return $new_reviews;

	}

	/**
	 * Checks whether given review already exists in cache.
	 * 
	 * @since			1.0.0
	 */
	private function is_new_review( $review ) {

		$stored_reviews = get_option( 'mrr_setting_reviews' );

		if ( $stored_reviews && is_array( $review ) ) {
			foreach ( $stored_reviews as $r ) {
				if ( $r[ 'reviewer_name' ] === $review[ 'reviewer_name' ]
						 && $r[ 'timestamp' ] === $review[ 'timestamp' ]
						 && $r[ 'source' ] === $review[ 'source' ] ) {
					return false;
			 }
			}
		}

		return true;

	}

	/**
	 * Adds the given review as a new post to the specified category.
	 * 
	 * @since			1.0.0
	 */
	private function add_review_to_category($review, $category_id) {

		$post = array(
			'post_title' => $review[ 'reviewer_name' ] . ' (' . $review[ 'source' ] . ')',
			'post_content' => $review[ 'review_text' ],
			'post_category' => array( $category_id ),
			'post_status' => 'publish'
		);

		return wp_insert_post( $post );

	}

	/**
	 * Sets an option in database:
	 * creates new option if it doesn't exist,
	 * updates existing option otherwise.
	 * 
	 * @since			1.0.0
	 */
	private function set_option($key, $value = '') {

		if ( get_option( $key ) === false ) {
			add_option( $key, $value );
		} else {
			update_option( $key, $value );
		}

	}

}
