<?php
/**
 * Premium Membership Subscription Handler
 *
 * @package KB\Membership
 */

namespace KB\Membership;

class Subscription {
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
        add_action('wp_ajax_kb_create_subscription', [$this, 'create_subscription']);
        add_action('wp_ajax_kb_cancel_subscription', [$this, 'cancel_subscription']);
        add_action('wp_ajax_kb_resume_subscription', [$this, 'resume_subscription']);
        add_action('init', [$this, 'handle_webhook']);
    }

    /**
     * Create subscription
     */
    public function create_subscription() {
        check_ajax_referer('kb-frontend');

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('Please log in to subscribe', 'kb-core'));
        }

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            // Get or create customer
            $customer_id = get_user_meta($user_id, '_stripe_customer_id', true);
            if (!$customer_id) {
                $user = get_userdata($user_id);
                $customer = \Stripe\Customer::create([
                    'email' => $user->user_email,
                    'metadata' => [
                        'user_id' => $user_id,
                    ],
                ]);
                $customer_id = $customer->id;
                update_user_meta($user_id, '_stripe_customer_id', $customer_id);
            }

            // Create subscription
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer_id,
                'items' => [[
                    'price' => get_option('kb_stripe_premium_price_id'),
                ]],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'user_id' => $user_id,
                ],
            ]);

            wp_send_json_success([
                'subscriptionId' => $subscription->id,
                'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
            ]);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel_subscription() {
        check_ajax_referer('kb-frontend');

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('Please log in to manage your subscription', 'kb-core'));
        }

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            $subscription_id = get_user_meta($user_id, '_stripe_subscription_id', true);
            if (!$subscription_id) {
                throw new \Exception(__('No active subscription found', 'kb-core'));
            }

            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            $subscription->cancel_at_period_end = true;
            $subscription->save();

            update_user_meta($user_id, '_subscription_status', 'canceling');

            wp_send_json_success(__('Subscription will be canceled at the end of the billing period', 'kb-core'));

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Resume subscription
     */
    public function resume_subscription() {
        check_ajax_referer('kb-frontend');

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('Please log in to manage your subscription', 'kb-core'));
        }

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            $subscription_id = get_user_meta($user_id, '_stripe_subscription_id', true);
            if (!$subscription_id) {
                throw new \Exception(__('No subscription found', 'kb-core'));
            }

            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            $subscription->cancel_at_period_end = false;
            $subscription->save();

            update_user_meta($user_id, '_subscription_status', 'active');

            wp_send_json_success(__('Subscription resumed successfully', 'kb-core'));

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handle_webhook() {
        if (!isset($_GET['kb-webhook']) || $_GET['kb-webhook'] !== 'stripe') {
            return;
        }

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            require_once KB_CORE_PATH . 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey(
                get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_secret_key') : 
                get_option('kb_stripe_live_secret_key')
            );

            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                get_option('kb_stripe_webhook_secret')
            );

            switch ($event->type) {
                case 'customer.subscription.created':
                    $this->handle_subscription_created($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handle_subscription_updated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handle_subscription_deleted($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handle_payment_failed($event->data->object);
                    break;
            }

            http_response_code(200);
            exit;

        } catch (\Exception $e) {
            http_response_code(400);
            exit;
        }
    }

    /**
     * Handle subscription created event
     *
     * @param object $subscription Stripe subscription object
     */
    private function handle_subscription_created($subscription) {
        $user_id = $subscription->metadata->user_id;
        if (!$user_id) {
            return;
        }

        update_user_meta($user_id, '_stripe_subscription_id', $subscription->id);
        update_user_meta($user_id, '_subscription_status', 'active');

        $user = get_user_by('id', $user_id);
        $user->add_role('kb_member');

        \KB\Email\Mailer::get_instance()->send_subscription_confirmation($user_id);
    }

    /**
     * Handle subscription updated event
     *
     * @param object $subscription Stripe subscription object
     */
    private function handle_subscription_updated($subscription) {
        $user_id = $subscription->metadata->user_id;
        if (!$user_id) {
            return;
        }

        if ($subscription->status === 'active') {
            update_user_meta($user_id, '_subscription_status', 'active');
            $user = get_user_by('id', $user_id);
            $user->add_role('kb_member');
        }
    }

    /**
     * Handle subscription deleted event
     *
     * @param object $subscription Stripe subscription object
     */
    private function handle_subscription_deleted($subscription) {
        $user_id = $subscription->metadata->user_id;
        if (!$user_id) {
            return;
        }

        delete_user_meta($user_id, '_stripe_subscription_id');
        delete_user_meta($user_id, '_subscription_status');

        $user = get_user_by('id', $user_id);
        $user->remove_role('kb_member');

        \KB\Email\Mailer::get_instance()->send_subscription_cancellation($user_id);
    }

    /**
     * Handle payment failed event
     *
     * @param object $invoice Stripe invoice object
     */
    private function handle_payment_failed($invoice) {
        $subscription = \Stripe\Subscription::retrieve($invoice->subscription);
        $user_id = $subscription->metadata->user_id;
        if (!$user_id) {
            return;
        }

        update_user_meta($user_id, '_subscription_status', 'past_due');
        \KB\Email\Mailer::get_instance()->send_payment_failed($user_id);
    }

    /**
     * Get subscription status for user
     *
     * @param int $user_id User ID
     * @return string
     */
    public static function get_status($user_id) {
        return get_user_meta($user_id, '_subscription_status', true) ?: 'none';
    }

    /**
     * Check if user has active subscription
     *
     * @param int $user_id User ID
     * @return bool
     */
    public static function is_active($user_id) {
        return self::get_status($user_id) === 'active';
    }
}
