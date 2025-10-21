<?php

/**
 * @package OpenPIMS
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
Plugin Name: OpenPIMS
Plugin URI: https://openpims.de/
Description: OpenPIMS plugin
Version: 0.1.0
Author: Stefan Böck
Author URI: https://stefan.boeck.name
License: GPLv2 or later
Text Domain: openpims
*/

// Define plugin version constant
if (!defined('OPENPIMS_VERSION')) {
    define('OPENPIMS_VERSION', '0.1.0');
}

// Define plugin directory path
if (!defined('OPENPIMS_PLUGIN_DIR')) {
    define('OPENPIMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Define plugin URL
if (!defined('OPENPIMS_PLUGIN_URL')) {
    define('OPENPIMS_PLUGIN_URL', plugin_dir_url(__FILE__));
}

/**
 * Main OpenPIMS Class
 */
class OpenPIMS {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Plugin settings
     */
    private $show_modal = true;
    private $openpims_host = 'openpims.de';
    private $site_host = '';
    private $data = array();

    /**
     * Get instance of this class (Singleton)
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Initialize the plugin
     */
    private function init() {
        // Set site host
        $url_data = parse_url(home_url());
        $this->site_host = isset($url_data['host']) ? $url_data['host'] : '';

        // Check for OpenPIMS header
        $this->check_openpims_header();

        // Hook into WordPress
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'add_modal_html'));

        // Add activation/deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Check for OpenPIMS User-Agent
     */
    private function check_openpims_header() {
        // Get all headers in a WordPress-compatible way
        $headers = $this->get_all_headers();

        // Check for OpenPIMS URL from User-Agent only
        $url = null;

        // Check for OpenPIMS in User-Agent
        if (isset($headers['user-agent']) && !empty($headers['user-agent'])) {
            $user_agent = $headers['user-agent'];
            // Look for pattern: OpenPIMS/X.X.X (+https://example.com)
            if (preg_match('/OpenPIMS\/[\d.]+\s+\(\+([^)]+)\)/', $user_agent, $matches)) {
                if (isset($matches[1])) {
                    $url = sanitize_url($matches[1]);
                }
            }
        }

        if (!empty($url)) {
            // Extract host from user URL
            $url_user_data = parse_url($url);
            if (isset($url_user_data['host'])) {
                $user_host = $url_user_data['host'];
                $parts = explode('.', $user_host);
                if (count($parts) > 1) {
                    array_shift($parts);
                    $this->openpims_host = implode('.', $parts);
                }

                // Build request URL
                $param = "?url=https://" . $this->site_host . "/openpims.json";
                $request_url = $url . $param;

                // Make the request with proper error handling
                $response = wp_remote_get($request_url, array(
                    'timeout' => 10,
                    'sslverify' => true,
                    'user-agent' => 'OpenPIMS Plugin/' . OPENPIMS_VERSION
                ));

                if (!is_wp_error($response)) {
                    $body = wp_remote_retrieve_body($response);
                    $status_code = wp_remote_retrieve_response_code($response);

                    if ($status_code === 200 && !empty($body)) {
                        $decoded = json_decode($body, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $this->data = $decoded;
                            $this->show_modal = false;
                        }
                    }
                } else {
                    // Log error for debugging (optional)
                    error_log('OpenPIMS Request Error: ' . $response->get_error_message());
                }
            }
        }
    }

    /**
     * Get all headers in a WordPress-compatible way
     */
    private function get_all_headers() {
        $headers = array();

        // Use getallheaders if available
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }
        // Fallback for servers where getallheaders is not available
        else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) === 'HTTP_') {
                    $header_name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$header_name] = $value;
                }
            }
        }

        // Convert to lowercase keys
        return array_change_key_case($headers, CASE_LOWER);
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only load on frontend
        if (is_admin()) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'openpims-modal',
            OPENPIMS_PLUGIN_URL . 'assets/css/openpims-modal.css',
            array(),
            OPENPIMS_VERSION
        );

        // Enqueue JS
        wp_enqueue_script(
            'openpims-modal',
            OPENPIMS_PLUGIN_URL . 'assets/js/openpims-modal.js',
            array(),
            OPENPIMS_VERSION,
            true // Load in footer
        );

        // Pass data to JavaScript
        wp_localize_script('openpims-modal', 'openpimsSettings', array(
            'showModal' => $this->show_modal,
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('openpims_nonce')
        ));
    }

    /**
     * Add modal HTML to footer
     */
    public function add_modal_html() {
        // Only show on frontend
        if (is_admin()) {
            return;
        }

        // Prepare URLs
        $register_url = sprintf(
            'https://%s/register?url=%s',
            esc_attr($this->openpims_host),
            esc_attr($this->site_host)
        );
        ?>
        <!-- OpenPIMS Modal -->
        <div id="openpimsModal" class="openpims-modal" role="dialog" aria-labelledby="openpims-title" aria-modal="true">
            <!-- Modal content -->
            <div class="openpims-modal-content">
                <!--span class="openpims-close" role="button" aria-label="<?php esc_attr_e('Schließen', 'openpims'); ?>">&times;</span-->
                <h2 id="openpims-title" class="screen-reader-text"><?php esc_html_e('Cookie-Einstellungen', 'openpims'); ?></h2>
                <p>
                    <?php esc_html_e('Wir nutzen einen externen Service, der Dir ermöglicht, die Verwaltung von von uns eingesetzten Cookies und anderen Tracking-Tools zu steuern.', 'openpims'); ?>
                </p>
                <p>
                    <?php
                    printf(
                        /* translators: %s: OpenPIMS link */
                        esc_html__('Bei %s kannst Du einmalig alle benötigten Einstellungen vornehmen und dann hierher zurückkehren. Wir freuen uns bereits auf Deinen nächsten Besuch.', 'openpims'),
                        '<a href="' . esc_url($register_url) . '" target="_blank" rel="noopener noreferrer">OpenPIMS</a>'
                    );
                    ?>
                </p>
                <p>
                    <?php esc_html_e('Sobald dies erledigt ist, wird diese Benachrichtigung nicht mehr für Dich sichtbar sein.', 'openpims'); ?>
                </p>
                <div style="text-align: center;">
                    <a href="<?php echo esc_url($register_url); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="openpims-button">
                        🍪 OpenPIMS
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Plugin activation hook
     */
    public function activate() {
        // Create database tables if needed
        // Set default options
        add_option('openpims_version', OPENPIMS_VERSION);
        add_option('openpims_settings', array(
            'enabled' => true,
            'default_host' => 'openpims.de'
        ));

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation hook
     */
    public function deactivate() {
        // Clean up temporary data if needed
        flush_rewrite_rules();
    }
}

// Initialize the plugin
add_action('plugins_loaded', function() {
    OpenPIMS::get_instance();
});

// Optional: Add settings page in admin
add_action('admin_menu', function() {
    add_options_page(
        __('OpenPIMS Einstellungen', 'openpims'),
        __('OpenPIMS', 'openpims'),
        'manage_options',
        'openpims-settings',
        'openpims_settings_page'
    );
});

function openpims_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('openpims_settings');
            do_settings_sections('openpims_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}