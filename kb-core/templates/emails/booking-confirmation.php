<?php
/**
 * Booking confirmation email template
 *
 * @package KB\Email
 * @var object $booking Booking object
 * @var \WP_Post $workshop Workshop post
 * @var \WP_User $user User object
 * @var string $date Workshop date
 * @var string $location Workshop location
 */

if (!defined('ABSPATH')) {
    exit;
}

$ticket_ids = get_post_meta($booking->id, 'kb_ticket_ids', true);
$content = sprintf(
    '<h2 style="
        margin: 0 0 20px;
        color: #212529;
        font-size: 24px;
        font-weight: bold;
    ">%s</h2>',
    __('Booking Confirmation', 'kb-core')
);

$content .= sprintf(
    '<p style="margin: 0 0 20px;">%s %s,</p>',
    __('Dear', 'kb-core'),
    esc_html($user->display_name)
);

$content .= sprintf(
    '<p style="margin: 0 0 20px;">%s</p>',
    __('Thank you for booking! Your workshop registration has been confirmed.', 'kb-core')
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
    esc_html($workshop->post_title)
);

$content .= '<table style="width: 100%; border-collapse: collapse;">';

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Date & Time:', 'kb-core'),
    date_i18n(
        get_option('date_format') . ' ' . get_option('time_format'),
        strtotime($date)
    )
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Location:', 'kb-core'),
    esc_html($location)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%d</td>
    </tr>',
    __('Number of Tickets:', 'kb-core'),
    $booking->quantity
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">€%s</td>
    </tr>',
    __('Total Paid:', 'kb-core'),
    number_format($booking->total, 2)
);

$content .= '</table>';
$content .= '</div>';

if ($ticket_ids) {
    $content .= sprintf(
        '<h3 style="
            margin: 0 0 15px;
            color: #212529;
            font-size: 18px;
            font-weight: bold;
        ">%s</h3>',
        __('Your Ticket IDs', 'kb-core')
    );

    $content .= '<ul style="
        margin: 0 0 20px;
        padding: 0;
        list-style: none;
    ">';

    foreach ($ticket_ids as $id) {
        $content .= sprintf(
            '<li style="
                background-color: #e9ecef;
                border-radius: 4px;
                padding: 8px 12px;
                margin-bottom: 8px;
                font-family: monospace;
            ">%s</li>',
            esc_html($id)
        );
    }

    $content .= '</ul>';
}

$content .= '<div style="margin-top: 30px;">';
$content .= sprintf(
    '<p style="margin: 0 0 20px;">%s</p>',
    __('Please keep this email for your records. You\'ll need to show your ticket IDs when attending the workshop.', 'kb-core')
);

$content .= sprintf(
    '<p style="margin: 0;">%s</p>',
    __('We look forward to seeing you!', 'kb-core')
);
$content .= '</div>';

include __DIR__ . '/base.php';
