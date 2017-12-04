<?php
/*
Plugin Name: Weather TR
Plugin URI: localhost
Description: Shows Vancouver weather
Version: 0.1
Author: lso
Author URI: lso.space
License: CC
*/

class weather_tr extends WP_Widget {

    public function __construct() {
        parent::__construct(false, $name = __('Weather TR', 'weather_tr_plugin') );
    }

    public function form($instance) {
    }

    public function update($new_instance, $old_instance) {
    }

    public function widget($args, $instance) {
        include 'weather.php';
        $w = new td_weather;
        $attr = array('w_location'=> 'Vancouver, ca');
        $ret = $w->render_generic($attr, "abc");
        echo "<div class='we'>".$ret."</div>";
    }
}

function register_foo_widget() {
    register_widget( 'weather_tr' );
}
add_action( 'widgets_init', 'register_foo_widget' );