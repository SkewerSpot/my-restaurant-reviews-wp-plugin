<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://skewerspot.com/
 * @since      1.0.0
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MyRestaurantReviews
 * @subpackage MyRestaurantReviews/public
 * @author     Anurag Bhandari <anurag.bhd@gmail.com>
 */
class MyRestaurantReviewsPublic
{

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mrr-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mrr-public.js', array('jquery'), $this->version, false);
	}

	/**
	 * Register the [my-restaurant-reviews] shortcode.
	 *
	 * @since    1.0.0
	 */
	public function add_mrr_shortcode()
	{

		add_shortcode('my-restaurant-reviews', array($this, 'mrr_shortcode_html'));
	}

	/**
	 * Outputs HTML for [my-restaurant-reviews] shortcode.
	 *
	 * @since    1.0.0
	 */
	public function mrr_shortcode_html($atts = [], $content = null)
	{

		// Get cached reviews
		$reviews = get_option( 'mrr_setting_reviews' );
		if ( ! $reviews ) {
			return $this->no_reviews_message_html();
		}

		// Get display settings
		$sort_order = get_option( 'mrr_setting_display_sortorder' );
		$max_display_reviews = absint( get_option( 'mrr_setting_display_maxdisplayreviews' ) );
		$min_rating = absint( get_option( 'mrr_setting_display_minrating' ) );
		$max_words = absint( get_option( 'mrr_setting_display_maxwords' ) );

		// Filter out reviews with rating less than min rating setting
		foreach ( $reviews as $idx => $review ) {
			if ( $review[ 'rating' ] < $min_rating ) unset( $reviews[ $idx ] );
		}

		// Sort the reviews array as per setting
		if ( $sort_order ) {
			switch( $sort_order ) {
				case 'timedesc':
					$timestamps = array_column( $reviews, 'timestamp' );
					array_multisort( $timestamps, SORT_DESC, $reviews );
					break;
				case 'timeasc':
					$timestamps = array_column( $reviews, 'timestamp' );
					array_multisort( $timestamps, SORT_ASC, $reviews );
					break;
				case 'random':
					$reviews = $this->shuffle_assoc( $reviews );
					break;
			}
		}

		// Build the output HTML string
		$html = '';
		$html = '<div class="mrr-box">';
		$html .= '<div class="mrr-previous-button">';
		$html .= '<button><span class="dashicons dashicons-arrow-left-alt2"></span></button>';
		$html .= '</div>'; // end .mrr-previous-button
		$html .= '<div class="mrr-reviews">';
		$html .= '<div class="mrr-quote"><span class="dashicons dashicons-format-quote"></span></div>';
		$review_idx = 0;
		foreach ($reviews as $review) {
			if ( $review_idx === $max_display_reviews ) break;
			$html .= '<div class="mrr-review">';
			$html .= '<div class="mrr-review-rating">';
			$html .= '<span>Rating:&nbsp;</span>';
			$rating = (int) $review[ 'rating' ];
			for ( $i = 0; $i < $rating; $i++ ) {
				$html .= '<span class="dashicons dashicons-star-filled"></span>';
			}
			for ( $i = 0; $i < ( 5 - $rating ); $i++ ) {
				$html .= '<span class="dashicons dashicons-star-empty"></span>';
			}
			$html .= '</div>'; // end .mrr-review-rating
			$html .= '<div class="mrr-review-text">';
			$review_text = $review['review_text'];
			if ( ! $max_words ) $max_words = 55;
			$review_text = wp_trim_words( $review_text, $max_words );
			$html .= esc_html( $review_text );
			$html .= '</div>'; // end .mrr-review-text
			$html .= '<div class="mrr-reviewer">';
			$html .= '<div class="mrr-reviewer-image">';
			$html .= '<img src="' . esc_attr($review['reviewer_image']) . '"' .
				'alt="Picture of ' . esc_attr($review['reviewer_name']) . '">';
			$html .= '</div>'; // end .mrr-reviewer-image
			$html .= '<div class="mrr-reviewer-detail">';
			$html .= '<span class="mrr-reviewer-name">';
			$html .= esc_html($review['reviewer_name']);
			$html .= '</span>';
			$html .= ' on ';
			$html .= ' <a class="mrr-review-source" href="' . $this->get_source_url( $review[ 'source' ] ) . '">';
			$html .= esc_html($review['source']);
			$html .= '</a>';
			$html .= '</div>'; // end .mrr-reviewer-detail
			$html .= '</div>'; // end .mrr-reviewer
			$html .= '</div>'; // end .mrr-review
			$review_idx++;
		}
		$html .= '</div>'; // end .mrr-reviews
		$html .= '<div class="mrr-next-button">';
		$html .= '<button><span class="dashicons dashicons-arrow-right-alt2"></span></button>';
		$html .= '</div>'; // end .mrr-next-button
		$html .= '</div>'; // end .mrr-box

		return $html;

	}

	/**
	 * Builds and returns the URL of restaurant as listed on the given source.
	 * 
	 * @since    1.0.0
	 */
	private function get_source_url($source = null) {

		$url = '';

		switch ( strtolower( $source ) ) {
			case 'zomato':
				$url = 'https://zoma.to/r/' . get_option( 'mrr_setting_zomato_restid' );
				break;
		}

		return $url;

	}

	/**
	 * Shuffle associative and non-associative array while preserving key, value pairs.
	 * Also returns the shuffled array instead of shuffling it in place.
	 * https://www.php.net/manual/en/function.shuffle.php#99624
	 * 
	 * @since    1.0.0
	 */
	private function shuffle_assoc($list) { 

		if (!is_array($list)) return $list; 
	
		$keys = array_keys($list); 
		shuffle($keys); 
		$random = array(); 
		foreach ($keys as $key) 
			$random[$key] = $list[$key]; 
	
		return $random; 

	}

	/**
	 * Outputs HTML for no reviews error message.
	 * 
	 * @since    1.0.0
	 */
	private function no_reviews_message_html() {
		
		$html = '';
		$html .= '<div class="mrr-message-box">';
		$html .= __( 'Oh no! Did you forget to configure My Restaurant Reviews plugin?', 'ssmrr' );
		$html .= '<br>';
		$html .= __( 'You did configure? Then perhaps you\'ve got no reviews yet :(', 'ssmrr' );
		$html .= '</div>';
		return $html;

	}

}
