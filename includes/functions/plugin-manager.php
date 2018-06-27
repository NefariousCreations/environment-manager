<?php
/**
 * Plugin Manager
 *
 * Blacklists and disables plugins depending on the defined site_url which is
 * usually different between environments. This plugin also assumes any non
 * blacklist plugin should be current, and will force some plugins to be current.
 *
 * @since 0.1.0
 */
function runBlacklistPluginActivation() {

  /**
   * Import required functions from WordPress Admin
   */
  if (! function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  /**
   * Get the plugins from the options page
   */

  // Check if any environments have been set
  if(have_rows('environment', 'option')):

    while (have_rows('environment', 'option')) : the_row();

      // Get the type of environment select
      if(get_row_layout() == 'environment_by_url'):

        // Check for the current environment
        if (get_site_url() === get_sub_field('environment_site_url', 'option')):

          // Get the plugins listed on the current environments blacklist
          if(have_rows('environment_blacklisted_plugins')):

            // Define the current environments blacklisted plugins array
            $current_environment_blacklisted_plugins = array();

            while (have_rows('environment_blacklisted_plugins')) : the_row();

              // Add the blacklisted plugins to the array
              $current_environment_blacklisted_plugins[] = get_sub_field('environment_blacklisted_plugin_name');

            endwhile;

          endif;

          /**
           * Disable Blacklisted Plugins And force Enable others Only if a current Environment is Active
           */

          // Remove the black listed plugins from global plugin list
          $environment_enabled_plugins = array_diff(array_keys(get_plugins()), $current_environment_blacklisted_plugins);

          // Remove the already active plugins from the global plugin list
          $environment_enabled_plugins = array_diff($environment_enabled_plugins, get_option('active_plugins'));

          // Deactivate the blacklisted plugins
          deactivate_plugins($current_environment_blacklisted_plugins);

          // Enable all plugins except those on the blacklist
          activate_plugins($environment_enabled_plugins);

        endif;

      endif;

    endwhile;

  endif;

}

// Trigger plugin update on admin page loads
add_action('admin_init', 'runBlacklistPluginActivation');

// Trigger plugin update after a WP MigrateDB Push or Pull
add_action('wpmdb_migration_complete', 'runBlacklistPluginActivation');

// -----------------------------------------------------------------------------

/**
 * Remove plugin from the current environment blacklist if the plugin is activated
 */
add_action( 'activated_plugin', function ($plugin) {
  /**
   * Import required functions from WordPress Admin
   */
  if (! function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  /**
   * Get the plugins from the options page
   */

  // Check if any environments have been set
  if(have_rows('environment', 'option')):

    while (have_rows('environment', 'option')) : the_row();

      // Get the type of environment select
      if(get_row_layout() == 'environment_by_url'):

        // Check for the current environment
        if (get_site_url() === get_sub_field('environment_site_url', 'option')):

          /**
           * Check for plugin activation POST and remove activated plugin from blacklist
           */

          // Get the index (ID) of the rows for the plugin that is to be activated
          while(have_rows('environment_blacklisted_plugins')): the_row();
            if ($plugin === get_sub_field('environment_blacklisted_plugin_name')):
              $plugin_row_to_remove_from_blacklist = get_row_index();
            endif;
          endwhile;

          // If the plugin to be activated is blacklisted, remove it from the blacklist
          if ($plugin_row_to_remove_from_blacklist):
            delete_sub_row('environment_blacklisted_plugins', $plugin_row_to_remove_from_blacklist);
          endif;

        endif;

      endif;

    endwhile;

  endif;

}, 10, 2 );


// -----------------------------------------------------------------------------

/**
 * Add plugin to the current environment blacklist if the plugin is deactivated
 */
add_action( 'deactivated_plugin', function ($plugin) {
  /**
   * Import required functions from WordPress Admin
   */
  if (! function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  /**
   * Get the plugins from the options page
   */

  // Check if any environments have been set
  if(have_rows('environment', 'option')):

    while (have_rows('environment', 'option')) : the_row();

      // Get the type of environment select
      if(get_row_layout() == 'environment_by_url'):

        // Check for the current environment
        if (get_site_url() === get_sub_field('environment_site_url', 'option')):

          /**
           * Check for plugin deactivation POST and add deactivated plugin to blacklist
           */
          if ($plugin):
            add_sub_row('environment_blacklisted_plugins', ['environment_blacklisted_plugin_name' => $plugin]);
          endif;

        endif;

      endif;

    endwhile;

  endif;

}, 10, 2 );

