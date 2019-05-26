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

		// Add Zomato, Google Maps and TripAdvisor sections
		$this->init_zomato_settings();
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
			null,
			//array( $this, 'mrr_section_zomato_html' ),
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
	 * Add General section and related fields & settings on plugin options page.
	 * 
	 * @since			1.0.0
	 */
	public function init_general_settings() {

		// Register a setting for each field
		register_setting( 'mrr_settings', 'mrr_setting_general_polltime' );

		// Add section
		add_settings_section(
			'mrr_section_general',
			__( 'General Settings', 'ssmrr' ),
			null,
			'my_restaurant_reviews'
		);

		// Add fields
		add_settings_field(
			'mrr_field_general_polltime',
			__( 'Check for new reviews every (mins)', 'ssmrr' ),
			array( $this, 'mrr_field_general_polltime_html' ),
			'my_restaurant_reviews',
			'mrr_section_general',
			[ 'label_for' => 'mrr_field_general_polltime' ]
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
		// Check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// Add settings saved message with the class of "updated"
			add_settings_error( 'mrr_messages', 'mrr_message', __( 'Settings Saved', 'ssmrr' ), 'updated' );
		}
	
		// Show error/update messages
		settings_errors( 'mrr_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
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
		<div style="padding: 10px; font-style: italic; border: 1px solid #aaa;">
			<?php echo esc_html_e( 'To get an API key, head to...', 'ssmrr' ); ?><br />
			<?php echo esc_html_e( 'The restaurant id...', 'ssmrr' ); ?>
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
	 * Outputs the HTML for Poll Time field.
	 * Callable for add_settings_field.
	 * 
	 * @since			1.0.0
	 */
	public function mrr_field_general_polltime_html( $args ) {

		$poll_time = get_option( 'mrr_setting_general_polltime', 15 );
		?>
		<input type="number" name="mrr_setting_general_polltime" class="small-text"
			value="<?php echo $poll_time; ?>" />
		<input type="submit" class="button-secondary"
			value="<?php esc_attr_e( 'Check Now', 'ssmrr' ) ?>" />
	<?php

	}

	/**
	 * Adds a custom cron schedule based on poll time option.
	 *
	 * @param array $schedules An array of non-default cron schedules.
	 * @return array Filtered array of non-default cron schedules.
	 * 
	 * @since			1.0.0
	 */
	public function add_custom_cron_interval( $schedules ) {

		$poll_time = get_option( 'mrr_setting_general_polltime' );
		$poll_time_secs = ( (int) $poll_time ) * 60;

		$schedules[ 'mrr_poll_interval' ] = array(
			'interval' => $poll_time_secs,
			'display' => __( 'Polling interval for My Restaurant Reviews plugin', 'ssmrr' )
		);

		return $schedules;

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
			'mrr_setting_general_polltime' => absint( $_POST[ 'mrr_setting_general_polltime' ] )
		);
		foreach ( $mrr_settings as $key => $value ) {
			if ( get_option( $key ) === false ) {
				add_option( $key, $value );
			} else {
				update_option( $key, $value );
			}
		}

	}

	/**
	 * Updates plugin's custom Cron schedule as per poll time setting,
	 * and re-schedules the Cron job.
	 * 
	 * @since			1.0.0
	 */
	public function update_cron() {

		add_filter( 'cron_schedules', array( $this, 'add_custom_cron_interval' ) );
		if ( wp_next_scheduled( 'mrr_cron_hook' ) ) {
			$timestamp = wp_next_scheduled( 'mrr_cron_hook' );
			wp_unschedule_event( $timestamp, 'mrr_cron_hook' );
		}
		wp_schedule_event( time(), 'hourly', 'mrr_cron_hook' );

	}

	/**
	 * Gets restaurant reviews from various online sources,
	 * such as Zomato, Google Maps, etc.
	 * 
	 * @since			1.0.0
	 */
	public function get_latest_reviews() {

		$normalized_reviews = array();

		// Fetch Zomato reviews
		$zomato_apikey = get_option( 'mrr_setting_zomato_apikey' );
		$zomato_restid = get_option( 'mrr_setting_zomato_restid' );
		$zomato_api_args = array(
			'headers' => array(
				'user-key' => $zomato_apikey
			)
		);
		$zomato_api_url = 'https://developers.zomato.com/api/v2.1/reviews?res_id='.$zomato_restid;
		$zomato_api_response = wp_remote_retrieve_body( wp_remote_get( $zomato_api_url, $zomato_api_args ) );
		if ( $zomato_api_response ) {
			$zomato_api_response_json = json_decode( $zomato_api_response, true );
			$reviews = $zomato_api_response_json[ 'user_reviews' ];
			if ( is_array( $reviews ) ) {
				foreach ( $reviews as $review ) {
					array_push( $normalized_reviews, array(
						'rating' => $review[ 'review' ][ 'rating' ],
						'review_text' => $review[ 'review' ][ 'review_text' ],
						'timestamp' => $review[ 'review' ][ 'timestamp' ],
						'reviewer_name' => $review[ 'review' ][ 'user' ][ 'name' ],
						'reviewer_image' => $review[ 'review' ][ 'user' ][ 'profile_image' ],
						'source' => 'Zomato'
					) );
				}
			}
		}

		if ( get_option( 'mrr_setting_reviews' ) === false ) {
			add_option( 'mrr_setting_reviews', $normalized_reviews );
		} else {
			update_option( 'mrr_setting_reviews', $normalized_reviews );
		}

	}

}
