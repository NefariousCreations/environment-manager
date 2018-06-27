/**
 * Dynamically Add PHP post request links on the WP Plugins page using the data-plugin attribute
 */

jQuery(function ($) {
  $(document).ready(function () {

    $('[data-plugin]').each(function () {

      var PluginName = $(this).attr("data-plugin");
      if (PluginName) {

        // Function to add POST link to blacklist plugin
        // This could be used if we can get each environment, to create a dropdown with links to enable disable in each alternative env
        // $(this).find('.row-actions').append(' | <a href="?action=deactivate?Plugin=' + PluginName + '">Blacklist Plugin</a>');

        // Add link to manage environment blacklist
        $(this).find('.row-actions').append(' | <a href="/wp-admin/admin.php?page=environment-manager">Manage Blacklist</a>');

      }

    })

  });
});