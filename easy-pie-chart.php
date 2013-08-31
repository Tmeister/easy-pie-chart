<?php
/*
Plugin Name: Easy Pie Chart
Author: Enrique Chavez
Author URI: http://enriquechavez.co
Version: 1.0
Description: Easy pie chart is a section to show simple pie charts for single values. These charts are highly customizable, very easy to implement, scale to the resolution of the display of the client to provide sharp charts even on retina displays
*/

define( 'EC_STORE_URL', 'http://enriquechavez.co' );
add_action( 'admin_init', 'easy_check_for_updates' );

function easy_check_for_updates(){
    $item_name  = "Easy Pie Chart";
    $item_key = strtolower( str_replace(' ', '_', $item_name) );

    if( get_option( $item_key."_activated" )){
        if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
            include( dirname( __FILE__ ) . '/sections/easy-pie-chart/inc/EDD_SL_Plugin_Updater.php' );
        }

        $license_key = trim( get_option( $item_key."_license", $default = false ) );

        $edd_updater = new EDD_SL_Plugin_Updater( EC_STORE_URL, __FILE__, array(
                'version'   => '1.0',
                'license'   => $license_key,
                'item_name' => $item_name,
                'author'    => 'Enrique Chavez'
            )
        );
    }
}