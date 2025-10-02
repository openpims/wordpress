=== OpenPIMS ===
Contributors: portalix
Plugin URI: https://openpims.de/
Tags: privacy, cookies, gdpr, consent, tracking
Requires at least: 4.0
Tested up to: 6.8
Stable tag: 0.1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

OpenPIMS plugin for WordPress - External privacy and cookie management service integration.

== Description ==

OpenPIMS is a WordPress plugin that integrates with the OpenPIMS external service to provide comprehensive privacy and cookie management for your website visitors. It offers a centralized approach to GDPR compliance and cookie consent management across multiple websites.

**Key Features:**

* External privacy management service integration via OpenPIMS.de
* Cookie consent modal display for first-time visitors
* Header-based detection using x-openpims HTTP headers
* Configurable cookie categories through openpims.json
* Automatic API communication with OpenPIMS service
* Lightweight implementation with no database requirements
* GDPR and privacy regulation compliance support
* German and international language support (i18n ready)

**How it works:**

1. When a user visits your site, the plugin checks for the x-openpims HTTP header
2. The plugin sends a request to the OpenPIMS service with your site's configuration
3. If the user hasn't registered their preferences, a modal appears directing them to OpenPIMS
4. Users configure their privacy preferences once on the OpenPIMS platform
5. Preferences are applied across all websites using OpenPIMS
6. The modal no longer appears for users who have configured their settings

**Cookie Categories Supported:**

* Necessary cookies (required for site functionality)
* Marketing cookies (advertising and remarketing)
* Functional cookies (enhanced features)
* Analytics cookies (usage tracking and statistics)
* Custom categories as defined in openpims.json

The plugin uses a singleton pattern for optimal performance and follows WordPress best practices for security and internationalization.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/openpims` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Ensure the openpims.json file is present in your plugin directory (included by default)
4. The plugin works automatically - no additional configuration needed
5. Optional: Customize the openpims.json file to define your specific cookie categories and vendors

== Frequently Asked Questions ==

= What is OpenPIMS? =

OpenPIMS is an external service (openpims.de) that allows users to manage cookie and tracking preferences across multiple websites from a central location. It provides a unified privacy management system for GDPR compliance.

= Do I need to configure anything? =

No, the plugin works automatically once activated. It will detect your site's URL and integrate with the OpenPIMS service. However, you can customize the openpims.json file to define your specific cookie categories and third-party vendors.

= How do I customize cookie categories? =

Edit the openpims.json file in the plugin directory to define your cookie categories (necessary, marketing, functional, analytics) and specify third-party vendors like Facebook, Google Analytics, etc.

= Will this affect my site's performance? =

The plugin has minimal impact on performance. It only loads a small modal (CSS and JavaScript) and makes a single API request to check user registration status. No database queries are required.

= What happens if the OpenPIMS service is unavailable? =

If the OpenPIMS service is unreachable, the plugin will not display the modal and your site will continue to function normally. The plugin includes proper error handling to prevent site disruption.

= Is the plugin GDPR compliant? =

The plugin helps facilitate GDPR compliance by providing a mechanism for users to manage cookie consent. However, full GDPR compliance depends on your overall website implementation and privacy practices.

= Can I translate the plugin to other languages? =

Yes, the plugin is internationalization-ready with text domain 'openpims'. Currently, the modal displays German text by default, but you can create translation files for other languages.

== Screenshots ==

1. Privacy consent modal displayed to users
2. OpenPIMS registration page

== Changelog ==

= 0.1.0 =
* Enhanced modal functionality with improved styling
* Added x-openpims HTTP header detection
* Implemented singleton pattern for better performance
* Added comprehensive error handling
* Improved WordPress coding standards compliance
* Added proper asset enqueueing with localized settings
* Implemented security best practices (nonce, escaping, sanitization)
* Added support for configurable cookie categories via openpims.json
* Internationalization support with text domain 'openpims'
* Admin settings page structure (for future enhancements)

= 0.0.1 =
* Initial release
* Basic modal functionality
* OpenPIMS service integration

== Upgrade Notice ==

= 0.1.0 =
Improved stability, security, and performance. Enhanced modal functionality and better WordPress integration.

= 0.0.1 =
Initial release of the OpenPIMS WordPress plugin.