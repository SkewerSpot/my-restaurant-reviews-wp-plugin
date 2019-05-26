<?php

/**
 * Fired during plugin activation
 *
 * @link       https://skewerspot.com/
 * @since      1.0.0
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/includes
 * @author     Anurag Bhandari <anurag.bhd@gmail.com>
 */
class MyRestaurantReviewsActivator {

	/**
	 * Initializes certain stuff on plugin activation:
	 * schedules Cron job for refreshing stored reviews.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		wp_schedule_event( time(), 'mrr_poll_interval', 'mrr_cron_hook' );

	}

}
