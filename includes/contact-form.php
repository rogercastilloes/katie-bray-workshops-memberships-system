<?php
/**
 * Contact Form Handling
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handle contact form submission
 */
function katie_bray_handle_contact_form() {
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact_form_submit')) {
        wp_redirect(add_query_arg('status', 'error', wp_get_referer()));
        exit;
    }

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_redirect(add_query_arg('status', 'error', wp_get_referer()));
        exit;
    }

    $to = get_option('admin_email');
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email
    );

    $email_content = sprintf(
        'Name: %s<br>Email: %s<br>Subject: %s<br><br>Message:<br>%s',
        esc_html($name),
        esc_html($email),
        esc_html($subject),
        nl2br(esc_html($message))
    );

    $mail_sent = wp_mail($to, $subject, $email_content, $headers);

    if ($mail_sent) {
        wp_redirect(add_query_arg('status', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('status', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_contact_form_submit', 'katie_bray_handle_contact_form');
add_action('admin_post_nopriv_contact_form_submit', 'katie_bray_handle_contact_form');

/**
 * Add AJAX support for contact form (future enhancement)
 */
function katie_bray_contact_form_ajax() {
    wp_localize_script('katie-bray-contact', 'contactFormAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('contact_form_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'katie_bray_contact_form_ajax');
