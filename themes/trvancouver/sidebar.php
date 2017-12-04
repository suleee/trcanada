<?php
/**
 * The sidebar containing the main widget area.s
 *
 * @package Trvancouver_Theme
 */
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
<div>
	<?php
	// define('TD_THEME_OPTIONS_NAME', 'some');
	// include 'td_util.php';
	// include 'td_remote_cache.php';
	// include 'td_log.php';
	// include 'td_options.php';
	// include 'td_remote_http.php';
	include 'weather.php';
	$w = new td_weather;
	$attr = array('w_location'=> 'Vancouver,ca');
	$ret = $w->render_generic($attr, "abc");
	$weather_data['today_icon_text'] = 'broken clouds';
	// var_dump($ret)

	echo "<div class='we'>".$ret."</div>";
	// $rr = $w->render_block_template($attr, );
	// var_dump($ret);
	// $r = httpGet("http://api.openweathermap.org/data/2.5/weather?q=vancouver,ca&appid=b8d0c5f0b3d878c123756fc0a4284ea9");
	// var_dump($r);
	?>
</div>