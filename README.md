# OpenPIMS WordPress Plugin

[![WordPress Version](https://img.shields.io/badge/WordPress-4.0%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.0%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPLv2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-0.1.0-orange.svg)](https://github.com/openpims/wordpress)

A WordPress plugin that integrates with the OpenPIMS external privacy management service to provide centralized cookie consent and GDPR compliance across multiple websites.

## 🎯 Overview

OpenPIMS WordPress Plugin enables websites to integrate with the [OpenPIMS service](https://openpims.de/) for managing user privacy preferences and cookie consent. Users configure their privacy settings once on the OpenPIMS platform, and these preferences apply across all participating websites.

## ✨ Features

- 🔒 **External Privacy Management** - Integration with OpenPIMS.de service
- 🍪 **Cookie Consent Management** - Configurable cookie categories (necessary, marketing, functional, analytics)
- 🚀 **Lightweight Implementation** - No database dependencies, minimal performance impact
- 🔍 **Multiple Detection Methods** - Checks X-OpenPIMS header, User-Agent signal (Safari), and cookie for registration status
- 🌍 **Internationalization Ready** - i18n support with text domain `openpims`
- 🛡️ **Security First** - Follows WordPress security best practices
- ⚙️ **Zero Configuration** - Works out of the box with sensible defaults
- 📱 **Responsive Modal** - Mobile-friendly privacy consent interface

## 📋 Requirements

- WordPress 4.0 or higher
- PHP 7.0 or higher
- SSL certificate (HTTPS) recommended for API communication

## 🚀 Installation

### From GitHub

1. Clone or download this repository:
```bash
git clone https://github.com/openpims/wordpress.git openpims
```

2. Upload to your WordPress plugins directory:
```bash
mv openpims /path/to/wordpress/wp-content/plugins/
```

3. Activate the plugin through the WordPress admin panel:
   - Navigate to **Plugins** → **Installed Plugins**
   - Find **OpenPIMS** and click **Activate**

### From WordPress Admin

1. Download the latest release as a ZIP file
2. Go to **Plugins** → **Add New** → **Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Activate the plugin after installation

## ⚙️ Configuration

### Default Configuration

The plugin works automatically after activation with no required configuration. It will:
- Detect your site's URL
- Check for user registration with OpenPIMS
- Display modal to unregistered users

### Custom Cookie Categories

Edit the `openpims.json` file to define your cookie categories and vendors:

```json
{
  "categories": {
    "necessary": {
      "id": "necessary",
      "label": "Necessary",
      "description": "Required cookies for basic site functionality"
    },
    "marketing": {
      "id": "marketing",
      "label": "Marketing",
      "description": "Marketing and advertising cookies",
      "vendors": [
        {
          "name": "Facebook",
          "privacy_policy": "https://www.facebook.com/privacy/explanation"
        }
      ]
    }
  }
}
```

## 🔧 How It Works

### Technical Flow

```mermaid
graph TD
    A[User visits site] --> B{Check for OpenPIMS signal}
    B --> B1[Check X-OpenPIMS header<br/>Chrome/Firefox/Chromium]
    B --> B2[Check User-Agent signal<br/>Safari]
    B --> B3[Check x-openpims cookie<br/>Legacy fallback]
    B1 -->|Found| C[Extract OpenPIMS URL]
    B2 -->|Pattern: OpenPIMS/1.0 +url| C
    B3 -->|Found| C
    B1 -->|Not found| F[Display registration modal]
    B2 -->|Not found| F
    B3 -->|Not found| F
    C --> D[Send API request to OpenPIMS URL]
    D --> E{User registered?}
    E -->|Yes - 200 OK| G[No modal shown]
    E -->|No| F
    F --> H[User clicks to OpenPIMS]
    H --> I[Configure preferences]
    I --> J[Return to site]
    J --> G
```

### Plugin Architecture

- **Singleton Pattern** - Ensures single instance via `OpenPIMS::get_instance()`
- **WordPress Hooks** - Integrates with core WordPress lifecycle
- **API Communication** - Uses `wp_remote_get()` for HTTP requests
- **Asset Management** - Proper enqueueing with `wp_localize_script()`
- **Security** - Input sanitization and output escaping

### OpenPIMS Detection Priority

The plugin checks for OpenPIMS signals in this order:

1. **X-OpenPIMS Header** (Chrome, Firefox, Chromium)
   - Header: `X-OpenPIMS: https://token.openpims.de`
   - Standard method for browsers supporting custom headers

2. **User-Agent Signal** (Safari)
   - Pattern: `Mozilla/5.0 (...) OpenPIMS/1.0 (+https://token.openpims.de)`
   - Regex: `/OpenPIMS\/[\d.]+\s+\(\+([^)]+)\)/`
   - Used because Safari doesn't support custom header modification
   - Extracted in `check_openpims_header()` method (line 112-120)

3. **x-openpims Cookie** (Legacy fallback)
   - Cookie: `x-openpims=https://token.openpims.de`
   - Maintained for backward compatibility

The plugin extracts the OpenPIMS URL from any of these sources and queries it with `?url=https://{site}/openpims.json` to verify user registration.

### Key Files

| File | Purpose |
|------|---------|
| `openpims.php` | Main plugin file with core logic |
| `openpims.json` | Cookie category configuration |
| `assets/js/openpims-modal.js` | Modal interaction JavaScript |
| `assets/css/openpims-modal.css` | Modal styling |

## 🔌 WordPress Hooks

The plugin uses these WordPress hooks:

- `plugins_loaded` - Initialize plugin
- `wp_enqueue_scripts` - Load frontend assets
- `wp_footer` - Insert modal HTML
- `admin_menu` - Register settings page
- `register_activation_hook` - Setup on activation
- `register_deactivation_hook` - Cleanup on deactivation

## 🧪 Development

### Local Setup

1. Set up a local WordPress development environment
2. Clone this repository into `wp-content/plugins/`
3. Activate the plugin in WordPress admin

### Code Standards

This plugin follows:
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- PSR-12 for PHP code style
- Proper escaping for all output
- Nonce verification for forms

### Testing Modal Display

To test the modal display:
1. Clear browser cookies
2. Remove `x-openpims` header if present
3. Visit the site - modal should appear
4. Register on OpenPIMS
5. Return to site - modal should not appear

## 📝 API Endpoints

The plugin communicates with:
- **OpenPIMS Service**: `https://openpims.de/`
- **Configuration Check**: Sends site's `openpims.json` URL

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/YourFeature`)
3. Commit your changes (`git commit -m 'Add YourFeature'`)
4. Push to the branch (`git push origin feature/YourFeature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow WordPress coding standards
- Add proper documentation for new features
- Test changes in multiple WordPress versions
- Update the changelog in readme.txt

## 📄 License

This project is licensed under the GPLv2 or later - see the [LICENSE](LICENSE) file for details.

**Note**: There is currently a license inconsistency - the LICENSE file shows Apache 2.0 while the plugin header indicates GPLv2. This should be resolved in a future update.

## 🐛 Known Issues

- Version mismatch between plugin file (0.1.0) and some documentation
- License inconsistency (GPLv2 vs Apache 2.0)
- Admin settings page exists but has no functional settings yet
- Modal text currently in German only (i18n ready for translations)

## 📞 Support

- **Plugin Homepage**: [https://openpims.de/](https://openpims.de/)
- **Issues**: [GitHub Issues](https://github.com/openpims/wordpress/issues)
- **WordPress Plugin Directory**: (Coming soon)

## 👤 Author

**Stefan Böck**
- GitHub: [@stefanboeck](https://github.com/stefanboeck)

## 🙏 Acknowledgments

- OpenPIMS service for privacy management infrastructure
- WordPress community for development resources
- Contributors and testers

## 📈 Roadmap

- [ ] Add multilingual support for modal text
- [ ] Implement functional admin settings page
- [ ] Add customizable modal styling options
- [ ] Create shortcode for manual modal trigger
- [ ] Add support for custom cookie categories
- [ ] Implement caching for API responses
- [ ] Add debug mode for troubleshooting
- [ ] WordPress.org plugin directory submission

---

Made with ❤️ for privacy-conscious WordPress sites