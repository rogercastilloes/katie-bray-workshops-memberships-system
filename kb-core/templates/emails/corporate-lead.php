<?php
/**
 * Corporate lead notification email template
 *
 * @package KB\Email
 * @var \WP_Post $lead Corporate lead post
 */

if (!defined('ABSPATH')) {
    exit;
}

$company_name = get_post_meta($lead->ID, '_company_name', true);
$contact_name = get_post_meta($lead->ID, '_contact_name', true);
$email = get_post_meta($lead->ID, '_email', true);
$phone = get_post_meta($lead->ID, '_phone', true);
$participants = get_post_meta($lead->ID, '_participants', true);
$preferred_date = get_post_meta($lead->ID, '_preferred_date', true);
$message = get_post_meta($lead->ID, '_message', true);

$content = sprintf(
    '<h2 style="
        margin: 0 0 20px;
        color: #212529;
        font-size: 24px;
        font-weight: bold;
    ">%s</h2>',
    __('New Corporate Workshop Inquiry', 'kb-core')
);

$content .= '<div style="
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
">';

$content .= sprintf(
    '<h3 style="
        margin: 0 0 15px;
        color: #212529;
        font-size: 18px;
        font-weight: bold;
    ">%s</h3>',
    __('Company Details', 'kb-core')
);

$content .= '<table style="width: 100%; border-collapse: collapse;">';

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Company Name:', 'kb-core'),
    esc_html($company_name)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Contact Person:', 'kb-core'),
    esc_html($contact_name)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">
            <a href="mailto:%1$s" style="color: #007bff; text-decoration: none;">%1$s</a>
        </td>
    </tr>',
    __('Email:', 'kb-core'),
    esc_html($email)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">
            <a href="tel:%1$s" style="color: #007bff; text-decoration: none;">%1$s</a>
        </td>
    </tr>',
    __('Phone:', 'kb-core'),
    esc_html($phone)
);

$content .= '</table>';
$content .= '</div>';

$content .= '<div style="
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
">';

$content .= sprintf(
    '<h3 style="
        margin: 0 0 15px;
        color: #212529;
        font-size: 18px;
        font-weight: bold;
    ">%s</h3>',
    __('Workshop Details', 'kb-core')
);

$content .= '<table style="width: 100%; border-collapse: collapse;">';

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%d</td>
    </tr>',
    __('Number of Participants:', 'kb-core'),
    absint($participants)
);

if ($preferred_date) {
    $content .= sprintf(
        '<tr>
            <td style="padding: 8px 0; color: #6c757d;">%s</td>
            <td style="padding: 8px 0;">%s</td>
        </tr>',
        __('Preferred Date:', 'kb-core'),
        date_i18n(get_option('date_format'), strtotime($preferred_date))
    );
}

$content .= '</table>';

if ($message) {
    $content .= sprintf(
        '<div style="margin-top: 15px;">
            <h4 style="
                margin: 0 0 10px;
                color: #6c757d;
                font-size: 14px;
                font-weight: bold;
            ">%s</h4>
            <p style="margin: 0;">%s</p>
        </div>',
        __('Additional Information:', 'kb-core'),
        nl2br(esc_html($message))
    );
}

$content .= '</div>';

$content .= sprintf(
    '<p style="margin: 20px 0 0;">
        <a href="%s" 
           style="
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        ">%s</a>
    </p>',
    admin_url('post.php?post=' . $lead->ID . '&action=edit'),
    __('View Lead in Admin', 'kb-core')
);

include __DIR__ . '/base.php';
