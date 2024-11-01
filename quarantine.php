<?php
/**
 * @package Coronafeed
 */
/*
Plugin Name: Coronavirus Update
Plugin URI: https://quarantine.country/coronavirus/plugins/wordpress/coronavirus-update
Description: Coronavirus Update Widget with coronavirus tracker. Cases by country and by state. Free COVID-19 live update for WordPress, based on multiple sources via coronavirus API. The plugin is relying on the robust quarantine.country Coronavirus API, with full access and live data updates. No data is collected, please read https://quarantine.country/coronavirus/api/privacy.html
Version: 1.1.2
Author: H7.org
Author URI: https://h7.org
License: GPLv2
Text Domain: Coronavirus
*/

require_once plugin_dir_path( __FILE__ ) . '/class.widget.php';

add_action( 'admin_init', array(CoronaFeedWidget::class, 'privacyPolicy') );
add_action( 'widgets_init', array(CoronaFeedWidget::class, 'register') );
add_action( 'wp_ajax_feed_update', array(CoronaFeedWidget::class, 'ajaxHandler') );
add_action( 'wp_ajax_nopriv_feed_update', array(CoronaFeedWidget::class, 'ajaxHandler') );