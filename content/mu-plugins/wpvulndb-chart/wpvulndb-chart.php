<?php
/**
 * Add the wpvulndb_chart shortcode.
 *
 * @package bjornjohansen\bjornjohansen.com
 */

$wpvulndb_chart_idx = 0;

/**
 * The wpvulndb_chart shortcode output.
 *
 * @param  array  $atts            Attributes.
 * @param  string $content        Content.
 * @param  string $shortcode_name Shortcode name.
 * @return string                 Output.
 */
function wpvulndb_chart( $atts = null, $content = '', $shortcode_name = '' ) {

	global $wpvulndb_chart_idx;

	$wpvulndb_chart_idx++;

	wp_enqueue_script( 'chartjs', 'https://bjornjohansen.com/content/mu-plugins/wpvulndb-chart/Chart.min.js', [], filemtime( dirname( __FILE__ ) . '/Chart.min.js' ), true );
	wp_enqueue_script( 'wpvulndb-chart', 'https://bjornjohansen.com/content/mu-plugins/wpvulndb-chart/wpvulndb-chart.js', [], filemtime( dirname( __FILE__ ) . '/wpvulndb-chart.js' ), true );

	$output  = '';
	$output .= sprintf( '<canvas id="wpvulndb_chart_%d" width="400" height="250" style="width: 100%%; max-width: 100%%"></canvas>', $wpvulndb_chart_idx );

	return $output;
}
add_shortcode( 'wpvulndb_chart', 'wpvulndb_chart' );
