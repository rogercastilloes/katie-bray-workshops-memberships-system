<?php
/**
 * Admin notification email template
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

$content = sprintf(
    '<h2 style="
        margin: 0 0 20px;
        color: #212529;
        font-size: 24px;
        font-weight: bold;
    ">%s</h2>',
    __('New Workshop Booking', 'kb-core')
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

// Workshop Details
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

$content .= '</table>';
$content .= '</div>';

// Customer Details
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
    __('Customer Details', 'kb-core')
);

$content .= '<table style="width: 100%; border-collapse: collapse;">';

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Name:', 'kb-core'),
    esc_html($user->display_name)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Email:', 'kb-core'),
    esc_html($user->user_email)
);

if ($phone = get_user_meta($user->ID, 'phone', true)) {
    $content .= sprintf(
        '<tr>
            <td style="padding: 8px 0; color: #6c757d;">%s</td>
            <td style="padding: 8px 0;">%s</td>
        </tr>',
        __('Phone:', 'kb-core'),
        esc_html($phone)
    );
}

$content .= '</table>';
$content .= '</div>';

// Booking Details
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
    __('Booking Details', 'kb-core')
);

$content .= '<table style="width: 100%; border-collapse: collapse;">';

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

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Payment ID:', 'kb-core'),
    esc_html($booking->payment_id)
);

$content .= sprintf(
    '<tr>
        <td style="padding: 8px 0; color: #6c757d;">%s</td>
        <td style="padding: 8px 0;">%s</td>
    </tr>',
    __('Booking Date:', 'kb-core'),
    date_i18n(
        get_option('date_format') . ' ' . get_option('time_format'),
        strtotime($booking->created_at)
    )
);

$content .= '</table>';
$content .= '</div>';

// Workshop Status
$available = \KB\Workshop\PostType::get_available_spots($workshop->ID);
$total = get_post_meta($workshop->ID, '_workshop_capacity', true);
$booked = $total - $available;

$content .= '<div style="
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
">';

$content .= sprintf(
    '<h3 style="
        margin: 0 0 15px;
        color: #212529;
        font-size: 18px;
        font-weight: bold;
    ">%s</h3>',
    __('Workshop Status', 'kb-core')
);

$content .= sprintf(
    '<p style="margin: 0;">%s</p>',
    sprintf(
        __('%d of %d spots booked (%d%% full)', 'kb-core'),
        $booked,
        $total,
        round(($booked / $total) * 100)
    )
);

$content .= '</div>';

// View in Admin
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
    admin_url('post.php?post=' . $workshop->ID . '&action=edit'),
    __('View Workshop in Admin', 'kb-core')
);

include __DIR__ . '/base.php';
