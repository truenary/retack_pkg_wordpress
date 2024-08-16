=== Retack AI ===
Contributors: asheshbh11
Tags: retack, error, logging, php, retack ai
Requires at least: 4.7
Tested up to: 6.6
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin logs errors including PHP, database, and JavaScript, and sends them to an external API.

== Description ==

Retack Logging Plugin helps you log and track errors in your WordPress site by sending them to Retack. It captures PHP errors, database errors, and JavaScript errors and logs them into the Retack system for further analysis.

### Features
– Logs PHP, database, and JavaScript errors.
– Sends error details to an external API.
– Easy configuration with API key settings.

### Configuration
1. After activation, go to "Settings > Retack" in the WordPress admin.
2. Enter your API key and save the settings.

== Frequently Asked Questions ==

= How do I get an API key? =

You can obtain an API key by registering on the Retack platform.

= Can I disable specific types of error logging? =

Currently, all error types are logged by default. Future versions may include selective logging options.

= Why does the plugin load its script in all contexts? =

The `Retack AI` includes a JavaScript error-handling script that needs to be loaded in all contexts (front-end, admin area, and login pages). This is necessary because errors can occur across different parts of the website, and comprehensive error tracking is essential for capturing and analyzing these errors consistently.

The script is specifically designed to monitor and log errors that may impact the site's functionality, ensuring that issues are detected and reported regardless of where they occur. This approach is crucial for maintaining the integrity of the site and providing accurate diagnostics to site administrators and developers.

== Screenshots ==

1. Retack API key.
2. Settings page where you can enter your API key.
3. Error logs sent to the Retack platform.

== Changelog ==

= 1.0 =
* Initial release with PHP, JavaScript, database and AJAX error logging.

== Upgrade Notice ==

= 1.0 =
Initial release.

== A brief Markdown Example ==

Here's a link to [WordPress](https://wordpress.org/) and one to [Markdown's Syntax Documentation](https://daringfireball.net/projects/markdown/syntax).

Ordered list:

1. Logs PHP errors.
2. Captures database errors.
3. Tracks JavaScript errors.

Unordered list:

– Sends error details to Retack API.
– Easy integration with WordPress settings.
– Supports error logging for enhanced debugging.
