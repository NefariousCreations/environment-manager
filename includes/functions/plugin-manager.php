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
function run_environment_plugin_update() {

  if (! current_user_can('activate_plugins')):
    wp_die(__('You do not have sufficient permissions to activate plugins for this site.'));
  endif;

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

          // Remove the blacklisted plugins from global plugin list
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
add_action('admin_init', 'run_environment_plugin_update');

// Trigger plugin update after a WP MigrateDB Push or Pull
add_action('wpmdb_migration_complete', 'run_environment_plugin_update');

// -----------------------------------------------------------------------------

/**
 * Remove plugin from the current environment blacklist if the plugin is activated
 */
add_action('activate_plugin', function ($plugin) {

  // Check if any environments have been set
  if(have_rows('environment', 'option')):

    while (have_rows('environment', 'option')) : the_row();

      // Get the type of environment select
      if(get_row_layout() == 'environment_by_url'):

        // Check for the current environment
        if (get_site_url() === get_sub_field('environment_site_url', 'option')):

          $plugins_row_to_remove_from_blacklist = array();

          // Get the index (ID) of the rows for the plugin that is to be activated
          while(have_rows('environment_blacklisted_plugins')): the_row();
            if ($plugin == get_sub_field('environment_blacklisted_plugin_name')):
              $plugins_row_to_remove_from_blacklist[] = get_row_index();
            endif;

          endwhile;

          // If the plugin to be activated is blacklisted, remove it from the blacklist,
          // check for multiple instances and remove all, the array is reversed to delete
          // the highest row numbers first to prevent the row numbers changing after deleting a row
          asort($plugins_row_to_remove_from_blacklist);
          foreach (array_reverse($plugins_row_to_remove_from_blacklist) as $plugin_row_to_remove_from_blacklist):
            delete_sub_row('environment_blacklisted_plugins', $plugin_row_to_remove_from_blacklist);
          endforeach;


        endif;

      endif;

    endwhile;

  endif;

}, 10, 2 );


// -----------------------------------------------------------------------------

/**
 * Add plugin to the current environment blacklist if the plugin is deactivated
 */
add_action('deactivated_plugin', function ($plugin) {

  // Check if any environments have been set
  if(have_rows('environment', 'option')):

    while (have_rows('environment', 'option')) : the_row();

      // Get the type of environment select
      if(get_row_layout() == 'environment_by_url'):

        // Check for the current environment
        if (get_site_url() === get_sub_field('environment_site_url', 'option')):

          // Check if the plugin is already on the blacklist (This is for the sake of preventing duplication)
          while(have_rows('environment_blacklisted_plugins')): the_row();
            if ($plugin === get_sub_field('environment_blacklisted_plugin_name')):
              $environment_blacklisted_plugin_name_duplicate = true;
            endif;
          endwhile;

          // As long as the plugin isn't already listed on the blacklist add it to the blacklist
          if ($environment_blacklisted_plugin_name_duplicate !== true):
            add_sub_row('environment_blacklisted_plugins', ['environment_blacklisted_plugin_name' => $plugin]);
          endif;

        endif;

      endif;

    endwhile;

  endif;

}, 10, 2 );

