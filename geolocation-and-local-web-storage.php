<?php
/*
 * Plugin Name: GeoLocation and Local Web Storage - JS
 * Description: Plugin to show how use the GeoLocation API and the Local Web Storage in a WordPress Shortcode and plugin.
 * Version: 1.0
 * Author: Manuel Ramirez Coronel
 * Author URI: https://github.com/racmanuel
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function wp_enqueue_scripts_styles(){
    wp_enqueue_script( 'location', plugins_url('/js/custom.js', __FILE__), array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_scripts_styles' );

function get_current_location(){
    ?>
    <button id="demo">Get Current Position</button>
    <?php
    /**
     * If you need the variable in PHP you can use the next:
     * $latitude = $_GET['latitude'];
     * $longitud = $_GET['longitude'];
     */
}
add_shortcode( 'location', 'get_current_location' );
