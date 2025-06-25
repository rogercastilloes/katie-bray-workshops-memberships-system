<?php
/**
 * Email Mailer Handler
 *
 * @package KB\Email
 */

namespace KB\Email;

class Mailer {
    /**
     * Instance of this class
     *
     * @var self
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return self
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
        add_action('wp_ajax_kb_test_email', [$this, 'send_test_email']);
        add_filter('wp_mail_from', [$this, 'get_from_email']);
        add_filter('wp_mail_from_name', [$this, 'get_from_name']);
    }

    /**
     * Get from email address
     *
     * @return string
     */
    public function get_from_email() {
        return get_option('kb_email_from_address', get_bloginfo('admin_email'));
    }

    /**
     * Get from name
     *
     * @return string
     */
    public function get_from_name() {
        return get_option('kb_email_from_name', get_bloginfo('name'));
    }

    /**
     * Send test email
     */
    public function send_test_email() {
        check_ajax_referer('kb-admin');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'kb-core'));
        }

        $user = wp_get_current_user();
        $subject = __('Test Email from Katie Bray', 'kb-core');
        $content = sprintf(
            '<p>%s</p>',
            sprintf(__('This is a test email sent from %s.', 'kb-core'), get_bloginfo('name'))
        );

        $sent = $this->send($user->user_email, $subject, $content);

        if ($sent) {
            wp_send_json_success(__('Test email sent successfully!', 'kb-core'));
        } else {
            wp_send_json_error(__('Failed to send test email.', 'kb-core'));
        }
    }

    /**
     * Send booking confirmation email
     *
     * @param int $booking_id Booking ID
     * @return bool
     */
    public function send_booking_confirmation($booking_id) {
        $booking = \KB\Workshop\Checkout::get_booking($booking_id);
        if (!$booking) {
            return false;
        }

        $workshop = get_post($booking->workshop_id);
        $user = get_userdata($booking->user_id);
        $date = get_post_meta($workshop->ID, '_workshop_date', true);
        $location = get_post_meta($workshop->ID, '_workshop_location', true);

        ob_start();
        include KB_CORE_TEMPLATES . 'emails/booking-confirmation.php';
        $message = ob_get_clean();

        return $this->send(
            $user->user_email,
            sprintf(__('Booking Confirmation - %s', 'kb-core'), $workshop->post_title),
            $message
        );
    }

    /**
     * Send admin booking notification
     *
     * @param int $booking_id Booking ID
     * @return bool
     */
    public function send_admin_booking_notification($booking_id) {
        $booking = \KB\Workshop\Checkout::get_booking($booking_id);
        if (!$booking) {
            return false;
        }

        $workshop = get_post($booking->workshop_id);
        $user = get_userdata($booking->user_id);
        $date = get_post_meta($workshop->ID, '_workshop_date', true);
        $location = get_post_meta($workshop->ID, '_workshop_location', true);

        ob_start();
        include KB_CORE_TEMPLATES . 'emails/admin-notification.php';
        $message = ob_get_clean();

        return $this->send(
            get_option('admin_email'),
            sprintf(__('New Workshop Booking - %s', 'kb-core'), $workshop->post_title),
            $message
        );
    }

    /**
     * Send corporate lead notification
     *
     * @param int $lead_id Lead ID
     * @return bool
     */
    public function send_corporate_lead_notification($lead_id) {
        $lead = get_post($lead_id);
        if (!$lead) {
            return false;
        }

        ob_start();
        include KB_CORE_TEMPLATES . 'emails/corporate-lead.php';
        $message = ob_get_clean();

        return $this->send(
            get_option('admin_email'),
            __('New Corporate Workshop Inquiry', 'kb-core'),
            $message
        );
    }

    /**
     * Send subscription confirmation
     *
     * @param int $user_id User ID
     * @return bool
     */
    public function send_subscription_confirmation($user_id) {
        $user = get_userdata($user_id);
        if (!$user) {
            return false;
        }

        $message = sprintf(
            __('Dear %s,

Thank you for becoming a Premium Member! Your subscription has been activated successfully.

As a Premium Member, you now enjoy:
- %d%% discount on all workshops
- Priority booking for new workshops
- Exclusive content and resources

Your next payment will be processed on %s.

If you have any questions about your membership, please don\'t hesitate to contact us.

Best regards,
%s', 'kb-core'),
            $user->display_name,
            get_option('kb_premium_discount', 25),
            date_i18n(
                get_option('date_format'),
                strtotime('+1 month')
            ),
            get_bloginfo('name')
        );

        return $this->send(
            $user->user_email,
            __('Welcome to Premium Membership!', 'kb-core'),
            $message
        );
    }

    /**
     * Send subscription cancellation
     *
     * @param int $user_id User ID
     * @return bool
     */
    public function send_subscription_cancellation($user_id) {
        $user = get_userdata($user_id);
        if (!$user) {
            return false;
        }

        $message = sprintf(
            __('Dear %s,

Your Premium Membership has been cancelled. Your benefits will continue until the end of your current billing period.

We\'re sorry to see you go! If you change your mind, you can reactivate your membership at any time.

Best regards,
%s', 'kb-core'),
            $user->display_name,
            get_bloginfo('name')
        );

        return $this->send(
            $user->user_email,
            __('Premium Membership Cancelled', 'kb-core'),
            $message
        );
    }

    /**
     * Send payment failed notification
     *
     * @param int $user_id User ID
     * @return bool
     */
    public function send_payment_failed($user_id) {
        $user = get_userdata($user_id);
        if (!$user) {
            return false;
        }

        $message = sprintf(
            __('Dear %s,

We were unable to process your latest membership payment. Please update your payment method to continue enjoying your Premium Member benefits.

You can update your payment details here: %s

If you need any assistance, please don\'t hesitate to contact us.

Best regards,
%s', 'kb-core'),
            $user->display_name,
            home_url('/account/'),
            get_bloginfo('name')
        );

        return $this->send(
            $user->user_email,
            __('Payment Failed - Action Required', 'kb-core'),
            $message
        );
    }

    /**
     * Send email
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @return bool
     */
    private function send($to, $subject, $message) {
        add_filter('wp_mail_content_type', function() {
            return 'text/html';
        });

        $sent = wp_mail($to, $subject, $message);

        remove_filter('wp_mail_content_type', null);

        return $sent;
    }
}
