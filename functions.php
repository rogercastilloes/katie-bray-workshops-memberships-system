<?php
/**
 * Katie Bray Theme Functions
 * 
 * @package KatieBray
 * @version 1.0.0
 * @author Katie Bray
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('KATIE_BRAY_VERSION', '1.0.0');
define('KATIE_BRAY_PATH', get_template_directory());
define('KATIE_BRAY_URL', get_template_directory_uri());
define('KATIE_BRAY_ASSETS', KATIE_BRAY_URL . '/assets');

/**
 * Theme Setup
 */
function katie_bray_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'katie-bray'),
        'footer' => __('Footer Menu', 'katie-bray'),
    ));
    
    // Add image sizes
    add_image_size('workshop-thumbnail', 400, 300, true);
    add_image_size('workshop-hero', 1200, 600, true);
    add_image_size('logo-wall', 200, 100, true);
}
add_action('after_setup_theme', 'katie_bray_setup');

/**
 * Enqueue scripts and styles
 */
function katie_bray_scripts() {
    // Main stylesheet
    wp_enqueue_style('katie-bray-style', get_stylesheet_uri(), array(), KATIE_BRAY_VERSION);
    
    // Frontend styles
    wp_enqueue_style('katie-bray-frontend', KATIE_BRAY_ASSETS . '/css/frontend.css', array(), KATIE_BRAY_VERSION);
    
    // Checkout styles
    wp_enqueue_style('katie-bray-checkout', KATIE_BRAY_ASSETS . '/css/checkout-upsell.css', array(), KATIE_BRAY_VERSION);
    
    // JavaScript
    wp_enqueue_script('katie-bray-main', KATIE_BRAY_ASSETS . '/js/premium-dashboard.js', array('jquery'), KATIE_BRAY_VERSION, true);
    wp_enqueue_script('katie-bray-workshop-booking', KATIE_BRAY_ASSETS . '/js/workshop-booking.js', array('jquery'), KATIE_BRAY_VERSION, true);
    wp_enqueue_script('katie-bray-checkout', KATIE_BRAY_ASSETS . '/js/checkout-upsell.js', array('jquery'), KATIE_BRAY_VERSION, true);
    
    // Localize scripts
    wp_localize_script('katie-bray-main', 'katieBrayAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('katie_bray_nonce'),
        'stripe_key' => get_option('katie_bray_stripe_publishable_key'),
    ));
    
    // Stripe.js
    if (get_option('katie_bray_stripe_publishable_key')) {
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'katie_bray_scripts');

/**
 * Admin scripts and styles
 */
function katie_bray_admin_scripts($hook) {
    if (strpos($hook, 'katie-bray') !== false || $hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_style('katie-bray-admin', KATIE_BRAY_ASSETS . '/css/admin.css', array(), KATIE_BRAY_VERSION);
        wp_enqueue_script('katie-bray-admin', KATIE_BRAY_ASSETS . '/js/admin.js', array('jquery'), KATIE_BRAY_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'katie_bray_admin_scripts');

/**
 * Include core classes
 */
require_once KATIE_BRAY_PATH . '/includes/class-katie-bray-core.php';
require_once KATIE_BRAY_PATH . '/includes/class-stripe-handler.php';
require_once KATIE_BRAY_PATH . '/includes/class-email-handler.php';
require_once KATIE_BRAY_PATH . '/includes/class-corporate-handler.php';
require_once KATIE_BRAY_PATH . '/includes/class-premium-handler.php';
require_once KATIE_BRAY_PATH . '/includes/class-admin-settings.php';
require_once KATIE_BRAY_PATH . '/includes/class-webhook-handler.php';
require_once KATIE_BRAY_PATH . '/includes/class-enhanced-email.php';
require_once KATIE_BRAY_PATH . '/includes/class-premium-dashboard.php';
require_once KATIE_BRAY_PATH . '/includes/checkout-upsell.php';
require_once KATIE_BRAY_PATH . '/includes/payments/registration-form.php';
require_once KATIE_BRAY_PATH . '/includes/payments/legacy-compat.php';

/**
 * Initialize theme components
 */
function katie_bray_init() {
    // Initialize core classes
    new Katie_Bray_Core();
    new Katie_Bray_Stripe_Handler();
    new Katie_Bray_Email_Handler();
    new Katie_Bray_Corporate_Handler();
    new Katie_Bray_Premium_Handler();
    new Katie_Bray_Admin_Settings();
    new Katie_Bray_Webhook_Handler();
    new Katie_Bray_Enhanced_Email();
    new Katie_Bray_Premium_Dashboard();
}
add_action('init', 'katie_bray_init');

/**
 * Register custom post types
 */
function katie_bray_register_post_types() {
    // Workshop post type
    register_post_type('workshop', array(
        'labels' => array(
            'name' => __('Workshops', 'katie-bray'),
            'singular_name' => __('Workshop', 'katie-bray'),
            'add_new' => __('Add New Workshop', 'katie-bray'),
            'add_new_item' => __('Add New Workshop', 'katie-bray'),
            'edit_item' => __('Edit Workshop', 'katie-bray'),
            'new_item' => __('New Workshop', 'katie-bray'),
            'view_item' => __('View Workshop', 'katie-bray'),
            'search_items' => __('Search Workshops', 'katie-bray'),
            'not_found' => __('No workshops found', 'katie-bray'),
            'not_found_in_trash' => __('No workshops found in trash', 'katie-bray'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-calendar-alt',
        'rewrite' => array('slug' => 'workshops'),
        'show_in_rest' => true,
    ));
    
    // Booking post type
    register_post_type('booking', array(
        'labels' => array(
            'name' => __('Bookings', 'katie-bray'),
            'singular_name' => __('Booking', 'katie-bray'),
            'add_new' => __('Add New Booking', 'katie-bray'),
            'add_new_item' => __('Add New Booking', 'katie-bray'),
            'edit_item' => __('Edit Booking', 'katie-bray'),
            'new_item' => __('New Booking', 'katie-bray'),
            'view_item' => __('View Booking', 'katie-bray'),
            'search_items' => __('Search Bookings', 'katie-bray'),
            'not_found' => __('No bookings found', 'katie-bray'),
            'not_found_in_trash' => __('No bookings found in trash', 'katie-bray'),
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-tickets-alt',
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false,
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'katie_bray_register_post_types');

/**
 * Register custom taxonomies
 */
function katie_bray_register_taxonomies() {
    // Workshop categories
    register_taxonomy('workshop_category', 'workshop', array(
        'labels' => array(
            'name' => __('Workshop Categories', 'katie-bray'),
            'singular_name' => __('Workshop Category', 'katie-bray'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'workshop-category'),
        'show_in_rest' => true,
    ));
    
    // Premium resource categories
    register_taxonomy('premium_category', 'attachment', array(
        'labels' => array(
            'name' => __('Premium Categories', 'katie-bray'),
            'singular_name' => __('Premium Category', 'katie-bray'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'premium-category'),
    ));
}
add_action('init', 'katie_bray_register_taxonomies');

/**
 * Add custom meta boxes
 */
function katie_bray_add_meta_boxes() {
    add_meta_box(
        'workshop_details',
        __('Workshop Details', 'katie-bray'),
        'katie_bray_workshop_details_callback',
        'workshop',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'katie_bray_add_meta_boxes');

/**
 * Workshop details meta box callback
 */
function katie_bray_workshop_details_callback($post) {
    wp_nonce_field('katie_bray_workshop_details', 'katie_bray_workshop_details_nonce');
    
    $workshop_date = get_post_meta($post->ID, '_workshop_date', true);
    $workshop_time = get_post_meta($post->ID, '_workshop_time', true);
    $workshop_location = get_post_meta($post->ID, '_workshop_location', true);
    $workshop_price = get_post_meta($post->ID, '_workshop_price', true);
    $workshop_capacity = get_post_meta($post->ID, '_workshop_capacity', true);
    $workshop_stripe_id = get_post_meta($post->ID, '_workshop_stripe_id', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="workshop_date"><?php _e('Date', 'katie-bray'); ?></label></th>
            <td><input type="date" id="workshop_date" name="workshop_date" value="<?php echo esc_attr($workshop_date); ?>" /></td>
        </tr>
        <tr>
            <th><label for="workshop_time"><?php _e('Time', 'katie-bray'); ?></label></th>
            <td><input type="time" id="workshop_time" name="workshop_time" value="<?php echo esc_attr($workshop_time); ?>" /></td>
        </tr>
        <tr>
            <th><label for="workshop_location"><?php _e('Location', 'katie-bray'); ?></label></th>
            <td><input type="text" id="workshop_location" name="workshop_location" value="<?php echo esc_attr($workshop_location); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="workshop_price"><?php _e('Price (€)', 'katie-bray'); ?></label></th>
            <td><input type="number" id="workshop_price" name="workshop_price" value="<?php echo esc_attr($workshop_price); ?>" step="0.01" min="0" /></td>
        </tr>
        <tr>
            <th><label for="workshop_capacity"><?php _e('Capacity', 'katie-bray'); ?></label></th>
            <td><input type="number" id="workshop_capacity" name="workshop_capacity" value="<?php echo esc_attr($workshop_capacity); ?>" min="1" /></td>
        </tr>
        <tr>
            <th><label for="workshop_stripe_id"><?php _e('Stripe Product ID', 'katie-bray'); ?></label></th>
            <td><input type="text" id="workshop_stripe_id" name="workshop_stripe_id" value="<?php echo esc_attr($workshop_stripe_id); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Save workshop meta data
 */
function katie_bray_save_workshop_meta($post_id) {
    if (!isset($_POST['katie_bray_workshop_details_nonce']) || 
        !wp_verify_nonce($_POST['katie_bray_workshop_details_nonce'], 'katie_bray_workshop_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('workshop_date', 'workshop_time', 'workshop_location', 'workshop_price', 'workshop_capacity', 'workshop_stripe_id');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_workshop', 'katie_bray_save_workshop_meta');

/**
 * Register shortcodes
 */
function katie_bray_register_shortcodes() {
    add_shortcode('workshop_booking', 'katie_bray_workshop_booking_shortcode');
    add_shortcode('member_dashboard', 'katie_bray_member_dashboard_shortcode');
    add_shortcode('premium_resources', 'katie_bray_premium_resources_shortcode');
    add_shortcode('corporate_form', 'katie_bray_corporate_form_shortcode');
    add_shortcode('logo_wall', 'katie_bray_logo_wall_shortcode');
}
add_action('init', 'katie_bray_register_shortcodes');

/**
 * Workshop booking shortcode
 */
function katie_bray_workshop_booking_shortcode($atts) {
    $atts = shortcode_atts(array(
        'workshop_id' => get_the_ID(),
    ), $atts);
    
    ob_start();
    include KATIE_BRAY_PATH . '/template-parts/workshops/booking-form.php';
    return ob_get_clean();
}

/**
 * Member dashboard shortcode
 */
function katie_bray_member_dashboard_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<p>' . __('Please log in to access your dashboard.', 'katie-bray') . '</p>';
    }
    
    ob_start();
    include KATIE_BRAY_PATH . '/template-parts/members/dashboard.php';
    return ob_get_clean();
}

/**
 * Premium resources shortcode
 */
function katie_bray_premium_resources_shortcode($atts) {
    if (!is_user_logged_in() || !katie_bray_user_has_premium_access()) {
        return '<p>' . __('Premium access required.', 'katie-bray') . '</p>';
    }
    
    ob_start();
    include KATIE_BRAY_PATH . '/template-parts/members/premium-resources.php';
    return ob_get_clean();
}

/**
 * Corporate form shortcode
 */
function katie_bray_corporate_form_shortcode($atts) {
    ob_start();
    include KATIE_BRAY_PATH . '/template-parts/corporate/lead-form.php';
    return ob_get_clean();
}

/**
 * Logo wall shortcode
 */
function katie_bray_logo_wall_shortcode($atts) {
    ob_start();
    include KATIE_BRAY_PATH . '/template-parts/corporate/logo-wall.php';
    return ob_get_clean();
}

/**
 * Check if user has premium access
 */
function katie_bray_user_has_premium_access() {
    if (!is_user_logged_in()) {
        return false;
    }
    
    $user_id = get_current_user_id();
    $subscription_status = get_user_meta($user_id, '_stripe_subscription_status', true);
    
    return $subscription_status === 'active';
}

/**
 * Get workshop price with discount
 */
function katie_bray_get_workshop_price_with_discount($workshop_id, $user_id = null) {
    $base_price = get_post_meta($workshop_id, '_workshop_price', true);
    
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if ($user_id && katie_bray_user_has_premium_access()) {
        $discount_percentage = get_option('katie_bray_membership_discount', 25);
        $discount = ($base_price * $discount_percentage) / 100;
        return $base_price - $discount;
    }
    
    return $base_price;
}

/**
 * Theme activation hook
 */
function katie_bray_theme_activation() {
    // Create database tables
    katie_bray_create_tables();
    
    // Set default options
    katie_bray_set_default_options();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'katie_bray_theme_activation');

/**
 * Create database tables
 */
function katie_bray_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Bookings table
    $table_bookings = $wpdb->prefix . 'katie_bray_bookings';
    $sql_bookings = "CREATE TABLE $table_bookings (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        workshop_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        quantity int(11) NOT NULL DEFAULT 1,
        total_amount decimal(10,2) NOT NULL,
        stripe_payment_intent_id varchar(255),
        status varchar(50) NOT NULL DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY workshop_id (workshop_id),
        KEY user_id (user_id),
        KEY status (status)
    ) $charset_collate;";
    
    // Messages table
    $table_messages = $wpdb->prefix . 'katie_bray_messages';
    $sql_messages = "CREATE TABLE $table_messages (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        admin_id bigint(20),
        message text NOT NULL,
        is_from_admin tinyint(1) NOT NULL DEFAULT 0,
        is_read tinyint(1) NOT NULL DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY admin_id (admin_id),
        KEY is_read (is_read)
    ) $charset_collate;";
    
    // Corporate leads table
    $table_leads = $wpdb->prefix . 'katie_bray_corporate_leads';
    $sql_leads = "CREATE TABLE $table_leads (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        company_name varchar(255) NOT NULL,
        contact_email varchar(255) NOT NULL,
        contact_phone varchar(50),
        min_participants int(11) NOT NULL DEFAULT 8,
        message text,
        status varchar(50) NOT NULL DEFAULT 'new',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY status (status)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_bookings);
    dbDelta($sql_messages);
    dbDelta($sql_leads);
}

/**
 * Set default options
 */
function katie_bray_set_default_options() {
    $defaults = array(
        'katie_bray_membership_discount' => 25,
        'katie_bray_membership_price' => 35,
        'katie_bray_email_from_name' => get_bloginfo('name'),
        'katie_bray_email_from_address' => get_option('admin_email'),
        'katie_bray_smtp_host' => '',
        'katie_bray_smtp_port' => 587,
        'katie_bray_smtp_username' => '',
        'katie_bray_smtp_password' => '',
        'katie_bray_brevo_api_key' => '',
    );
    
    foreach ($defaults as $option => $value) {
        if (get_option($option) === false) {
            add_option($option, $value);
        }
    }
}

/**
 * Add custom user roles
 */
function katie_bray_add_user_roles() {
    add_role('premium_member', __('Premium Member', 'katie-bray'), array(
        'read' => true,
        'upload_files' => true,
    ));
}
add_action('init', 'katie_bray_add_user_roles');

/**
 * Custom login redirect
 */
function katie_bray_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('premium_member', $user->roles)) {
            return home_url('/mi-cuenta/');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'katie_bray_login_redirect', 10, 3);

/**
 * Add custom endpoints
 */
function katie_bray_add_endpoints() {
    add_rewrite_endpoint('mi-cuenta', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('recursos-premium', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('mensajes', EP_ROOT | EP_PAGES);
}
add_action('init', 'katie_bray_add_endpoints');

/**
 * Flush rewrite rules on theme activation
 */
function katie_bray_flush_rewrite_rules() {
    katie_bray_add_endpoints();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'katie_bray_flush_rewrite_rules');

/**
 * Add custom query vars
 */
function katie_bray_query_vars($vars) {
    $vars[] = 'mi-cuenta';
    $vars[] = 'recursos-premium';
    $vars[] = 'mensajes';
    return $vars;
}
add_filter('query_vars', 'katie_bray_query_vars');

/**
 * Custom template loader
 */
function katie_bray_template_loader($template) {
    if (is_page('mi-cuenta')) {
        $new_template = locate_template(array('page-mi-cuenta.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    if (is_page('recursos-premium')) {
        $new_template = locate_template(array('page-recursos-premium.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    if (is_page('mensajes')) {
        $new_template = locate_template(array('page-mensajes.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'katie_bray_template_loader');

/**
 * Add custom body classes
 */
function katie_bray_body_classes($classes) {
    if (is_user_logged_in() && katie_bray_user_has_premium_access()) {
        $classes[] = 'premium-member';
    }
    
    if (is_singular('workshop')) {
        $classes[] = 'single-workshop';
    }
    
    return $classes;
}
add_filter('body_class', 'katie_bray_body_classes');

/**
 * Custom excerpt length
 */
function katie_bray_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'katie_bray_excerpt_length');

/**
 * Custom excerpt more
 */
function katie_bray_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'katie_bray_excerpt_more');

/**
 * Add custom image sizes
 */
function katie_bray_image_sizes() {
    add_image_size('workshop-thumbnail', 400, 300, true);
    add_image_size('workshop-hero', 1200, 600, true);
    add_image_size('logo-wall', 200, 100, true);
    add_image_size('premium-resource', 800, 600, false);
}
add_action('after_setup_theme', 'katie_bray_image_sizes');

/**
 * Security: Disable file editing in admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Security: Hide WordPress version
 */
remove_action('wp_head', 'wp_generator');

/**
 * Security: Remove unnecessary meta tags
 */
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

/**
 * Performance: Remove emoji scripts
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Include ACF fields
 */
require_once KATIE_BRAY_PATH . '/includes/acf-fields/company-logos.php';

/**
 * Add Theme Options Page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => __('Theme Settings', 'katie-bray'),
        'menu_title' => __('Theme Settings', 'katie-bray'),
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'manage_options',
        'redirect'   => false,
        'position'   => 59
    ));
}

/**
 * Add custom admin menu
 */
function katie_bray_admin_menu() {
    add_menu_page(
        __('Katie Bray', 'katie-bray'),
        __('Katie Bray', 'katie-bray'),
        'manage_options',
        'katie-bray',
        'katie_bray_admin_page',
        'dashicons-admin-generic',
        30
    );
    
    add_submenu_page(
        'katie-bray',
        __('Settings', 'katie-bray'),
        __('Settings', 'katie-bray'),
        'manage_options',
        'katie-bray-settings',
        'katie_bray_settings_page'
    );
    
    add_submenu_page(
        'katie-bray',
        __('Bookings', 'katie-bray'),
        __('Bookings', 'katie-bray'),
        'manage_options',
        'katie-bray-bookings',
        'katie_bray_bookings_page'
    );
    
    add_submenu_page(
        'katie-bray',
        __('Messages', 'katie-bray'),
        __('Messages', 'katie-bray'),
        'manage_options',
        'katie-bray-messages',
        'katie_bray_messages_page'
    );
    
    add_submenu_page(
        'katie-bray',
        __('Corporate Leads', 'katie-bray'),
        __('Corporate Leads', 'katie-bray'),
        'manage_options',
        'katie-bray-leads',
        'katie_bray_leads_page'
    );
}
add_action('admin_menu', 'katie_bray_admin_menu');

/**
 * Admin page callbacks (these will be implemented in the admin settings class)
 */
function katie_bray_admin_page() {
    echo '<div class="wrap"><h1>' . __('Katie Bray Dashboard', 'katie-bray') . '</h1></div>';
}

function katie_bray_settings_page() {
    echo '<div class="wrap"><h1>' . __('Katie Bray Settings', 'katie-bray') . '</h1></div>';
}

function katie_bray_bookings_page() {
    echo '<div class="wrap"><h1>' . __('Bookings Management', 'katie-bray') . '</h1></div>';
}

function katie_bray_messages_page() {
    echo '<div class="wrap"><h1>' . __('Messages Management', 'katie-bray') . '</h1></div>';
}

function katie_bray_leads_page() {
    echo '<div class="wrap"><h1>' . __('Corporate Leads', 'katie-bray') . '</h1></div>';
}

/**
 * Add custom dashboard widgets
 */
function katie_bray_dashboard_widgets() {
    wp_add_dashboard_widget(
        'katie_bray_stats',
        __('Katie Bray Statistics', 'katie-bray'),
        'katie_bray_dashboard_stats_widget'
    );
}
add_action('wp_dashboard_setup', 'katie_bray_dashboard_widgets');

/**
 * Dashboard stats widget
 */
function katie_bray_dashboard_stats_widget() {
    $workshop_count = wp_count_posts('workshop')->publish;
    $booking_count = wp_count_posts('booking')->publish;
    $premium_users = count_users()['avail_roles']['premium_member'] ?? 0;
    
    echo '<ul>';
    echo '<li><strong>' . __('Active Workshops:', 'katie-bray') . '</strong> ' . $workshop_count . '</li>';
    echo '<li><strong>' . __('Total Bookings:', 'katie-bray') . '</strong> ' . $booking_count . '</li>';
    echo '<li><strong>' . __('Premium Members:', 'katie-bray') . '</strong> ' . $premium_users . '</li>';
    echo '</ul>';
}

/**
 * Theme deactivation
 */
function katie_bray_theme_deactivation() {
    // Remove custom roles
    remove_role('premium_member');
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'katie_bray_theme_deactivation');
