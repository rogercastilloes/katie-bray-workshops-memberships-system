<?php
/**
 * Uninstall script for Katie Bray Core
 *
 * @package KB\Core
 */

// Exit if not called by WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remove plugin options
$options = [
    'kb_stripe_test_mode',
    'kb_stripe_test_publishable_key',
    'kb_stripe_test_secret_key',
    'kb_stripe_live_publishable_key',
    'kb_stripe_live_secret_key',
    'kb_stripe_webhook_secret',
    'kb_stripe_premium_price_id',
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
];

foreach ($options as $option) {
    delete_option($option);
}

// Remove custom tables
global $wpdb;
$tables = [
    $wpdb->prefix . 'kb_bookings',
    $wpdb->prefix . 'kb_chat_messages',
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
}

// Remove custom post types and their data
$post_types = ['workshop', 'company_logo', 'kb_corporate_lead'];

foreach ($post_types as $post_type) {
    $items = get_posts([
        'post_type' => $post_type,
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);

    foreach ($items as $item) {
        wp_delete_post($item, true);
    }
}

// Remove user meta
$user_meta_keys = [
    '_stripe_customer_id',
    '_stripe_subscription_id',
    '_subscription_status',
    '_subscription_end_date',
];

$users = get_users(['fields' => 'ids']);
foreach ($users as $user_id) {
    foreach ($user_meta_keys as $key) {
        delete_user_meta($user_id, $key);
    }
}

// Remove premium member role
remove_role('kb_member');

// Remove premium content directory
$upload_dir = wp_upload_dir();
$premium_dir = $upload_dir['basedir'] . '/kb-premium';

if (is_dir($premium_dir)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($premium_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($premium_dir);
}

// Clear any cached data
wp_cache_flush();
