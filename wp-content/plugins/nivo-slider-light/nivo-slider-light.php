<?php
/*
Plugin Name: NIVO slider light
Plugin URI: https://www.netaction.de/wordpress-plugin-nivo-slider-light/
Description: This is a wrapper for the jQuery plugin NIVO Image Slider from dev7studios.
Version: 1.11
Author: Thomas Schmidt
Author URI: http://netaction.de
*/


function NivoInit() {
	wp_enqueue_script('nivoSliderScript', WP_PLUGIN_URL.'/nivo-slider-light/jquery.nivo.slider.pack.js', array('jquery'));
	wp_enqueue_style('nivoStyleSheet', WP_PLUGIN_URL . '/nivo-slider-light/nivo-slider.css');
	wp_enqueue_style('nivoCustomStyleSheet', WP_PLUGIN_URL . '/nivo-slider-light/custom-nivo-slider.css');
}


function NivoHeader() {
?>
<script type="text/javascript">
/* <![CDATA[ */
	jQuery(document).ready(function($){
		$(".nivoSlider br").each(function(){ // strip BR elements created by Wordpress
			$(this).remove();
		});
		$('.nivoSlider').nivoSlider({
			effect: 'fade', // Specify sets like: 'fold,random,sliceDown'
			slices: 15, // For slice animations
			boxCols: 8, // For box animations
			boxRows: 4, // For box animations
			animSpeed: 500, // Slide transition speed
			pauseTime: 3000, // How long each slide will show
			startSlide: 0, // Set starting Slide (0 index)
			directionNav: true, // Next & Prev navigation
			controlNav: true, // 1,2,3... navigation
			controlNavThumbs: false, // Use thumbnails for Control Nav
			pauseOnHover: true, // Stop animation while hovering
			manualAdvance: false, // Force manual transitions
			prevText: 'Prev', // Prev directionNav text
			nextText: 'Next', // Next directionNav text
			randomStart: false, // Start on a random slide
			beforeChange: function(){}, // Triggers before a slide transition
			afterChange: function(){}, // Triggers after a slide transition
			slideshowEnd: function(){}, // Triggers after all slides have been shown
			lastSlide: function(){}, // Triggers when last slide is shown
			afterLoad: function(){} // Triggers when slider has loaded
		});
	});
/* ]]> */
</script>
<?php
}

if (!is_admin()  ) {
	add_action('init', 'NivoInit');
	add_action('wp_head', 'NivoHeader');
}
?>
