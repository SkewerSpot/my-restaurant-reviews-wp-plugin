<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://skewerspot.com/
 * @since      1.0.0
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/includes
 * @author     Anurag Bhandari <anurag.bhd@gmail.com>
 */
class MyRestaurantReviewsDeactivator {

	/**
	 * Does clean up on plugin deactivation:
	 * removes active Cron jobs.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Remove the cron job that periodically refreshes online reviews in database
		if ( wp_next_scheduled( 'mrr_cron_hook' ) ) {
			$timestamp = wp_next_scheduled( 'mrr_cron_hook' );
			wp_unschedule_event( $timestamp, 'mrr_cron_hook' );
		}

	}

}
