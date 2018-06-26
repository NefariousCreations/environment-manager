/**
 * Dynamically Add PHP post request links on the WP Plugins page using the data-plugin attribute
 */

jQuery(function ($) {
  $(document).ready(function () {

    $('[data-plugin]').each(function () {

      var PluginName = $(this).attr("data-plugin");
      if (PluginName) {
        $(this).find('.row-actions').append(' | <a href="?action=deactivate?Plugin=' + PluginName + '">Blacklist Plugin</a>');
      }

    })

  });
});