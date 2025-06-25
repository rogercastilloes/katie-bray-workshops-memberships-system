<?php
/**
 * Plugin Name: Katie Bray Core
 * Plugin URI: https://katiebray.com
 * Description: Core functionality for Katie Bray's workshop booking system
 * Version: 1.0.0
 * Author: Katie Bray
 * Author URI: https://katiebray.com
 * Text Domain: kb-core
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('KB_CORE_VERSION', '1.0.0');
define('KB_CORE_FILE', __FILE__);
define('KB_CORE_PATH', plugin_dir_path(__FILE__));
define('KB_CORE_URL', plugin_dir_url(__FILE__));
define('KB_CORE_TEMPLATES', KB_CORE_PATH . 'templates/');
define('KB_CORE_ASSETS', KB_CORE_URL . 'assets/');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'KB\\';
    $base_dir = KB_CORE_PATH . 'src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize plugin
add_action('plugins_loaded', function () {
    // Initialize core components
    \KB\Core\Init::get_instance();

    // Initialize workshop components
    \KB\Workshop\PostType::get_instance();
    \KB\Workshop\Checkout::get_instance();

    // Initialize membership components
    \KB\Membership\Subscription::get_instance();
    \KB\Membership\Dashboard::get_instance();

    // Initialize corporate components
    \KB\Corporate\Form::get_instance();
    \KB\Corporate\Logos::get_instance();

    // Load theme's Stripe SDK
    if (file_exists(get_template_directory() . '/vendor/autoload.php')) {
        require_once get_template_directory() . '/vendor/autoload.php';
    }

    // Initialize email system (using wp_mail)
    \KB\Email\Mailer::get_instance();
});

// Activation hook
register_activation_hook(__FILE__, function () {
    // Create required database tables
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Bookings table
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}kb_bookings (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        workshop_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        quantity int(11) NOT NULL,
        total decimal(10,2) NOT NULL,
        status varchar(20) NOT NULL,
        payment_id varchar(100) NOT NULL,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY workshop_id (workshop_id),
        KEY user_id (user_id),
        KEY status (status)
    ) $charset_collate;";

    // Chat messages table
    $sql .= "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}kb_chat_messages (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        message text NOT NULL,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Create premium content directory
    $upload_dir = wp_upload_dir();
    $premium_dir = $upload_dir['basedir'] . '/kb-premium';
    if (!file_exists($premium_dir)) {
        wp_mkdir_p($premium_dir);
    }

    // Add member role
    add_role('kb_member', __('Premium Member', 'kb-core'), [
        'read' => true,
        'kb_member' => true,
    ]);

    // Set default options
    $default_options = [
        'kb_premium_price' => 35,
        'kb_premium_discount' => 25,
        'kb_smtp_enabled' => false,
        'kb_smtp_host' => 'smtp.gmail.com',
        'kb_smtp_port' => 587,
        'kb_smtp_secure' => 'tls',
        'kb_smtp_auth' => true,
        'kb_email_from_name' => get_bloginfo('name'),
        'kb_email_from_address' => get_bloginfo('admin_email'),
    ];

    foreach ($default_options as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }

    // Flush rewrite rules
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function () {
    // Remove member role
    remove_role('kb_member');

    // Flush rewrite rules
    flush_rewrite_rules();
});

// Uninstall hook
register_uninstall_hook(__FILE__, function () {
    // Drop custom tables
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}kb_bookings");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}kb_chat_messages");

    // Delete premium content directory
    $upload_dir = wp_upload_dir();
    $premium_dir = $upload_dir['basedir'] . '/kb-premium';
    if (file_exists($premium_dir)) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
        require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
        $filesystem = new WP_Filesystem_Direct(null);
        $filesystem->rmdir($premium_dir, true);
    }

    // Delete options
    $options = [
        'kb_premium_price',
        'kb_premium_discount',
        'kb_smtp_enabled',
        'kb_smtp_host',
        'kb_smtp_port',
        'kb_smtp_secure',
        'kb_smtp_auth',
        'kb_smtp_user',
        'kb_smtp_pass',
        'kb_email_from_name',
        'kb_email_from_address',
        'kb_stripe_test_mode',
        'kb_stripe_test_publishable_key',
        'kb_stripe_test_secret_key',
        'kb_stripe_live_publishable_key',
        'kb_stripe_live_secret_key',
        'kb_stripe_webhook_secret',
        'kb_stripe_premium_price_id',
    ];

    foreach ($options as $option) {
        delete_option($option);
    }
});
