=== OpenPIMS ===
Contributors: portalix
Plugin URI: https://openpims.de/
Tags: privacy, cookies, gdpr, consent, tracking
Requires at least: 4.0
Tested up to: 6.8
Stable tag: 0.0.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

OpenPIMS plugin for WordPress - External privacy and cookie management service integration.

== Description ==

OpenPIMS is a WordPress plugin that integrates with the OpenPIMS external service to provide comprehensive privacy and cookie management for your website visitors.

**Key Features:**

* External privacy management service integration
* Cookie consent modal display
* Automatic detection of OpenPIMS service configuration
* Seamless user experience for privacy settings
* GDPR compliance support

The plugin displays a modal to users who haven't configured their privacy settings yet, directing them to the OpenPIMS service where they can manage all cookie and tracking preferences in one place.

**How it works:**

1. When a user visits your site, the plugin checks if they have configured their privacy settings
2. If not configured, a modal appears directing them to OpenPIMS
3. Users configure their preferences once on OpenPIMS
4. The modal no longer appears for configured users

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/openpims` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The plugin works automatically - no additional configuration needed

== Frequently Asked Questions ==

= What is OpenPIMS? =

OpenPIMS is an external service that allows users to manage cookie and tracking preferences across multiple websites from a central location.

= Do I need to configure anything? =

No, the plugin works automatically once activated. It will detect your site's URL and integrate with the OpenPIMS service.

= Will this affect my site's performance? =

The plugin has minimal impact on performance. It only loads a small modal and makes external requests when necessary.

== Screenshots ==

1. Privacy consent modal displayed to users
2. OpenPIMS registration page

== Changelog ==

= 0.0.1 =
* Initial release
* Basic modal functionality
* OpenPIMS service integration

== Upgrade Notice ==

= 0.0.1 =
Initial release of the OpenPIMS WordPress plugin.