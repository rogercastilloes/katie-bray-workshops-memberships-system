<?php
/**
 * Workshop Checkout Handler
 *
 * @package KB\Workshop
 */

namespace KB\Workshop;

class Checkout {
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
        add_action('wp_ajax_kb_create_booking', [$this, 'create_booking']);
        add_action('wp_ajax_nopriv_kb_create_booking', [$this, 'create_booking']);
        add_action('wp_ajax_kb_confirm_booking', [$this, 'confirm_booking']);
        add_action('wp_ajax_nopriv_kb_confirm_booking', [$this, 'confirm_booking']);
    }

    /**
     * Create booking and payment intent
     */
    public function create_booking() {
        check_ajax_referer('kb-frontend');

        if (!isset($_POST['workshop_id'], $_POST['quantity'])) {
            wp_send_json_error(__('Invalid request', 'kb-core'));
        }

        $workshop_id = absint($_POST['workshop_id']);
        $quantity = absint($_POST['quantity']);

        if (!$workshop_id || !$quantity) {
            wp_send_json_error(__('Invalid request', 'kb-core'));
        }

        $workshop = get_post($workshop_id);
        if (!$workshop || $workshop->post_type !== 'workshop') {
            wp_send_json_error(__('Workshop not found', 'kb-core'));
        }

        $available = PostType::get_available_spots($workshop_id);
        if ($quantity > $available) {
            wp_send_json_error(__('Not enough spots available', 'kb-core'));
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('Please log in to book a workshop', 'kb-core'));
        }

        $price = PostType::get_price_for_user($workshop_id, $user_id);
        $total = $price * $quantity;

        try {
            // Create Stripe payment intent
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total * 100, // Convert to cents
                'currency' => 'eur',
                'metadata' => [
                    'workshop_id' => $workshop_id,
                    'user_id' => $user_id,
                    'quantity' => $quantity,
                ],
            ]);

            // Create pending booking
            global $wpdb;
            $wpdb->insert(
                $wpdb->prefix . 'kb_bookings',
                [
                    'workshop_id' => $workshop_id,
                    'user_id' => $user_id,
                    'quantity' => $quantity,
                    'total' => $total,
                    'status' => 'pending',
                    'payment_id' => $intent->id,
                ],
                ['%d', '%d', '%d', '%f', '%s', '%s']
            );

            if (!$wpdb->insert_id) {
                throw new \Exception(__('Failed to create booking', 'kb-core'));
            }

            wp_send_json_success([
                'clientSecret' => $intent->client_secret,
                'total' => $total,
            ]);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Confirm booking after successful payment
     */
    public function confirm_booking() {
        check_ajax_referer('kb-frontend');

        if (!isset($_POST['payment_intent'])) {
            wp_send_json_error(__('Invalid request', 'kb-core'));
        }

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            $intent = \Stripe\PaymentIntent::retrieve($_POST['payment_intent']);
            if (!$intent || $intent->status !== 'succeeded') {
                throw new \Exception(__('Payment not confirmed', 'kb-core'));
            }

            global $wpdb;
            $booking = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}kb_bookings WHERE payment_id = %s",
                $intent->id
            ));

            if (!$booking) {
                throw new \Exception(__('Booking not found', 'kb-core'));
            }

            // Update booking status
            $wpdb->update(
                $wpdb->prefix . 'kb_bookings',
                ['status' => 'confirmed'],
                ['id' => $booking->id],
                ['%s'],
                ['%d']
            );

            // Generate ticket IDs
            $ticket_ids = [];
            for ($i = 0; $i < $booking->quantity; $i++) {
                $ticket_ids[] = strtoupper(uniqid('KB'));
            }
            update_post_meta($booking->id, 'kb_ticket_ids', $ticket_ids);

            // Send confirmation email
            \KB\Email\Mailer::get_instance()->send_booking_confirmation($booking->id);

            wp_send_json_success([
                'message' => __('Booking confirmed! Check your email for details.', 'kb-core'),
                'redirect' => get_permalink($booking->workshop_id),
            ]);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Get booking by ID
     *
     * @param int $booking_id Booking ID
     * @return object|null
     */
    public static function get_booking($booking_id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}kb_bookings WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Get user bookings
     *
     * @param int $user_id User ID
     * @return array
     */
    public static function get_user_bookings($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}kb_bookings WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ));
    }

    /**
     * Get workshop bookings
     *
     * @param int $workshop_id Workshop ID
     * @return array
     */
    public static function get_workshop_bookings($workshop_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}kb_bookings WHERE workshop_id = %d ORDER BY created_at DESC",
            $workshop_id
        ));
    }

    /**
     * Cancel booking
     *
     * @param int $booking_id Booking ID
     * @return bool
     */
    public static function cancel_booking($booking_id) {
        global $wpdb;
        $booking = self::get_booking($booking_id);
        if (!$booking || $booking->status !== 'confirmed') {
            return false;
        }

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            // Refund payment
            \Stripe\Refund::create([
                'payment_intent' => $booking->payment_id,
            ]);

            // Update booking status
            return (bool) $wpdb->update(
                $wpdb->prefix . 'kb_bookings',
                ['status' => 'cancelled'],
                ['id' => $booking_id],
                ['%s'],
                ['%d']
            );

        } catch (\Exception $e) {
            return false;
        }
    }
}
