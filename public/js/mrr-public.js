(function( $ ) {
	'use strict';

	let currentReviewIdx = 0;
	let numReviews = 0;
	let interval;

	$(function() {
		initReviewsSlider();
		setPrevBtnHandler();
		setNextBtnHandler();
	});
	
	function initReviewsSlider() {
		numReviews = $('.mrr-review').length;
		highlightReview(currentReviewIdx);
		interval = setInterval(showNextReview, 5000);
	}

	function setPrevBtnHandler() {
		$('.mrr-previous-button').click(e => {
			if (interval) {
				clearInterval(interval);
				showPreviousReview();
			}
		});
	}

	function setNextBtnHandler() {
		$('.mrr-next-button').click(e => {
			if (interval) {
				clearInterval(interval);
				showNextReview();
			}
		});
	}

	function showPreviousReview() {
		currentReviewIdx--;
		if (currentReviewIdx < 0) {
			currentReviewIdx = numReviews - (Math.abs(currentReviewIdx) % numReviews);
		}
		highlightReview(currentReviewIdx);
	}

	function showNextReview() {
		currentReviewIdx++;
		if (currentReviewIdx >= numReviews) {
			currentReviewIdx = currentReviewIdx % numReviews;
		}
		highlightReview(currentReviewIdx);
	}

	function highlightReview(idx) {
		$('.mrr-review').hide();
		$('.mrr-review').eq(idx).fadeIn('slow');
	}

})( jQuery );
