<?php
/**
 * Plugin Manager
 *
 * Blacklists and disables plugins depending on the defined site_url which is
 * usually different between environments. This plugin also assumes any non
 * blacklist plugin should be active, and will force some plugins to be active.
 *
 * @since 0.1.0
 */
function runBlacklistPluginActivation() {

  /**
   * Import required functions from WordPress Admin
   */
  require_once(ABSPATH . 'wp-admin/includes/plugin.php');

  // Development URL Target
  $developmentURLString = 'development.';

  // Staging URL Target
  $stagingURLString = 'staging.';

  // Plugins to be disabled on Production site
  $productionBlackList = array(
  'fakerpress/fakerpress.php',
  'regenerate-thumbnails/regenerate-thumbnails.php',
  );

  // Plugins to be disabled on Staging site
  $stagingBlackList = array(
  'akismet/akismet.php',
  'wp-google-analytics-events/ga-scroll-event.php',
  'wp-to-buffer/wp-to-buffer.php',
  );

  // Plugins to be disabled on Development site
  $developmentBlackList = array(
  'google-analytics-dashboard-for-wp/gadwp.php',
  'wp-google-analytics-events/ga-scroll-event.php',
  'wp-to-buffer/wp-to-buffer.php',
  );

  if(strpos(get_site_url(), $developmentURLString) !== false) {

  // Merge the plugin blacklists of alternative environments
  $enablePlugins = array_merge($productionBlackList, $stagingBlackList);

  // Determine plugins safe to enable by removing current env blacklist plugins
  $enablePlugins = array_diff($enablePlugins, $developmentBlackList);

  // Enable alternative environment blacklist plugins
  activate_plugins($enablePlugins);

  // Deactivate all Blacklisted Plugins
  deactivate_plugins($developmentBlackList);

  } elseif (strpos(get_site_url(), $stagingURLString) !== false) {

  // Merge the plugin blacklists of alternative environments
  $enablePlugins = array_merge($productionBlackList, $developmentBlackList);

  // Determine plugins safe to enable by removing current env blacklist plugins
  $enablePlugins = array_diff($enablePlugins, $stagingBlackList);

  // Enable alternative environment blacklist plugins
  activate_plugins($enablePlugins);

  // Deactivate all Blacklisted Plugins
  deactivate_plugins($stagingBlackList);

  } else {

  // Merge the plugin blacklists of alternative environments
  $enablePlugins = array_merge($stagingBlackList, $developmentBlackList);

  // Determine plugins safe to enable by removing current env blacklist plugins
  $enablePlugins = array_diff($enablePlugins, $productionBlackList);

  // Enable alternative environment blacklist plugins
  activate_plugins($enablePlugins);

  // Deactivate all Blacklisted Plugins
  deactivate_plugins($productionBlackList);

  }

}

// Trigger blacklist plugin check on admin page loads
add_action('admin_init', 'runBlacklistPluginActivation');

// Trigger blacklist plugin check after a MigrateDB Push or Pull
add_action('wpmdb_migration_complete', 'runBlacklistPluginActivation');
