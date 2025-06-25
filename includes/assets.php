<?php
/**
 * Theme Assets Management
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles
 */
function katie_bray_enqueue_assets() {
    // Main CSS (includes compiled Tailwind)
    wp_enqueue_style('katie-bray-main', get_template_directory_uri() . '/assets/css/dist/style.css', array(), KATIE_BRAY_VERSION);
    
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null);
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', array(), '6.0.0');
    
    // Theme styles
    wp_enqueue_style('katie-bray-style', get_stylesheet_uri(), array(), KATIE_BRAY_VERSION);
    
    // jQuery (already included by WordPress)
    wp_enqueue_script('jquery');
    
    // Theme scripts
    wp_enqueue_script(
        'katie-bray-navigation', 
        get_template_directory_uri() . '/assets/js/navigation.js', 
        array('jquery'), 
        KATIE_BRAY_VERSION, 
        true
    );

    // Workshop registration scripts
    if (is_singular('workshop') || is_post_type_archive('workshop')) {
        // Stripe JS
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);
        
        wp_enqueue_script(
            'workshop-registration',
            get_template_directory_uri() . '/assets/js/workshop-registration.js',
            array('jquery', 'stripe-js'),
            KATIE_BRAY_VERSION,
            true
        );

        // Localize script for Stripe and AJAX
        wp_localize_script('workshop-registration', 'stripeConfig', array(
            'publishableKey' => STRIPE_PUBLISHABLE_KEY,
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('workshop_registration')
        ));
    }

    // Global theme settings
    wp_localize_script('katie-bray-navigation', 'katieThemeSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'homeurl' => home_url('/'),
    ));

    // Add comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'katie_bray_enqueue_assets');

/**
 * Add preconnect for external resources
 */
function katie_bray_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add preconnect for Google Fonts
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
        // Add preconnect for Font Awesome
        $urls[] = array(
            'href' => 'https://cdnjs.cloudflare.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'katie_bray_resource_hints', 10, 2);

/**
 * Add async/defer attributes to scripts
 */
function katie_bray_script_attributes($tag, $handle) {
    // Add async to non-critical scripts
    $async_scripts = array('google-analytics', 'facebook-pixel');
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }

    // Add defer to other scripts
    $defer_scripts = array('katie-bray-navigation', 'workshop-registration');
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'katie_bray_script_attributes', 10, 2);

/**
 * Add custom image sizes
 */
function katie_bray_add_image_sizes() {
    add_image_size('workshop-thumbnail', 600, 400, true);
    add_image_size('workshop-single', 1200, 800, true);
}
add_action('after_setup_theme', 'katie_bray_add_image_sizes');

/**
 * Add custom image sizes to media library
 */
function katie_bray_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'workshop-thumbnail' => __('Workshop Thumbnail', 'katie-bray'),
        'workshop-single'   => __('Workshop Single', 'katie-bray'),
    ));
}
add_filter('image_size_names_choose', 'katie_bray_custom_image_sizes');
