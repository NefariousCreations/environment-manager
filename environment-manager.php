<?php
/*
Plugin Name: Environment Manager by Nefarious Creations
Plugin URI: https://nefariouscreations.com.au
Description: Dynamically control WordPress settings and options across environments at the content level using unique urls.
Version: 0.1.0
Author: Nefarious Creations
Author URI: https://nefariouscreations.com.au
*/

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Load Plugin Update Checker
 */
require 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/NefariousCreations/environment-manager/',
  __FILE__, //Full path to the main plugin file or functions.php.
  'environment-manager'
);

$myUpdateChecker->setBranch('master');

/**
 * Include the plugin files
 *
 * @since 0.1.0
 */
add_action( 'plugins_loaded', function () {

  // Get the plugin path
  $plugin_path = plugin_dir_path( __FILE__ );

  // Functions
  require_once $plugin_path . '/includes/functions/plugin-manager.php';

  // Options Page
  require_once $plugin_path . '/includes/options-pages/environment-manager.php';

  // Scripts & Styles
  add_action('admin_enqueue_scripts', function () {
//    wp_enqueue_style('main.css', plugins_url('resources/assets/styles/main.css', __FILE__));
    wp_enqueue_script('main.js', plugins_url('resources/assets/scripts/main.js', __FILE__), array('jquery'), true);
  });

});

