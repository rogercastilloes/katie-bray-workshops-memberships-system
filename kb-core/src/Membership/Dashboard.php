<?php
/**
 * Premium Membership Dashboard Handler
 *
 * @package KB\Membership
 */

namespace KB\Membership;

class Dashboard {
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
        add_shortcode('kb_member_dashboard', [$this, 'render_dashboard']);
        add_action('wp_ajax_kb_update_profile', [$this, 'update_profile']);
    }

    /**
     * Render member dashboard
     *
     * @return string
     */
    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return sprintf(
                '<p>%s <a href="%s">%s</a></p>',
                __('Please', 'kb-core'),
                wp_login_url(get_permalink()),
                __('log in', 'kb-core')
            );
        }

        $user = wp_get_current_user();
        $subscription_status = Subscription::get_status($user->ID);
        $upcoming_workshops = $this->get_upcoming_workshops($user->ID);
        $past_workshops = $this->get_past_workshops($user->ID);

        ob_start();
        ?>
        <div class="kb-member-dashboard">
            <!-- Profile Section -->
            <section class="dashboard-section">
                <h2><?php _e('Profile', 'kb-core'); ?></h2>
                <form id="kb-profile-form" class="dashboard-form">
                    <?php wp_nonce_field('kb-frontend'); ?>
                    <div class="form-row">
                        <label for="display_name">
                            <?php _e('Display Name', 'kb-core'); ?>
                        </label>
                        <input type="text" 
                               id="display_name" 
                               name="display_name" 
                               value="<?php echo esc_attr($user->display_name); ?>" 
                               required>
                    </div>
                    <div class="form-row">
                        <label for="user_email">
                            <?php _e('Email', 'kb-core'); ?>
                        </label>
                        <input type="email" 
                               id="user_email" 
                               name="user_email" 
                               value="<?php echo esc_attr($user->user_email); ?>" 
                               required>
                    </div>
                    <div class="form-row">
                        <label for="phone">
                            <?php _e('Phone', 'kb-core'); ?>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>">
                    </div>
                    <div class="form-row">
                        <button type="submit" class="button button-primary">
                            <?php _e('Update Profile', 'kb-core'); ?>
                        </button>
                        <span class="spinner"></span>
                    </div>
                    <div class="form-message"></div>
                </form>
            </section>

            <!-- Membership Section -->
            <section class="dashboard-section">
                <h2><?php _e('Membership', 'kb-core'); ?></h2>
                <?php if ($subscription_status === 'active') : ?>
                    <div class="membership-status active">
                        <p>
                            <?php _e('You are a Premium Member!', 'kb-core'); ?>
                            <br>
                            <?php _e('Enjoy exclusive discounts on all workshops.', 'kb-core'); ?>
                        </p>
                        <button type="button" 
                                class="button" 
                                id="kb-cancel-subscription">
                            <?php _e('Cancel Membership', 'kb-core'); ?>
                        </button>
                    </div>
                <?php elseif ($subscription_status === 'canceling') : ?>
                    <div class="membership-status canceling">
                        <p>
                            <?php _e('Your membership will end at the end of the current billing period.', 'kb-core'); ?>
                        </p>
                        <button type="button" 
                                class="button button-primary" 
                                id="kb-resume-subscription">
                            <?php _e('Resume Membership', 'kb-core'); ?>
                        </button>
                    </div>
                <?php elseif ($subscription_status === 'past_due') : ?>
                    <div class="membership-status past-due">
                        <p>
                            <?php _e('Your last payment failed. Please update your payment method.', 'kb-core'); ?>
                        </p>
                        <button type="button" 
                                class="button button-primary" 
                                id="kb-update-payment">
                            <?php _e('Update Payment', 'kb-core'); ?>
                        </button>
                    </div>
                <?php else : ?>
                    <div class="membership-status none">
                        <p>
                            <?php _e('Become a Premium Member and get exclusive discounts!', 'kb-core'); ?>
                        </p>
                        <button type="button" 
                                class="button button-primary" 
                                id="kb-subscribe">
                            <?php _e('Subscribe Now', 'kb-core'); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Upcoming Workshops -->
            <section class="dashboard-section">
                <h2><?php _e('Upcoming Workshops', 'kb-core'); ?></h2>
                <?php if ($upcoming_workshops) : ?>
                    <div class="workshop-list">
                        <?php foreach ($upcoming_workshops as $booking) :
                            $workshop = get_post($booking->workshop_id);
                            $date = get_post_meta($workshop->ID, '_workshop_date', true);
                            $location = get_post_meta($workshop->ID, '_workshop_location', true);
                            ?>
                            <div class="workshop-item">
                                <h3><?php echo esc_html($workshop->post_title); ?></h3>
                                <p>
                                    <?php echo date_i18n(
                                        get_option('date_format') . ' ' . get_option('time_format'),
                                        strtotime($date)
                                    ); ?>
                                    <br>
                                    <?php echo esc_html($location); ?>
                                </p>
                                <p>
                                    <?php printf(
                                        __('Tickets: %d', 'kb-core'),
                                        $booking->quantity
                                    ); ?>
                                </p>
                                <?php
                                $ticket_ids = get_post_meta($booking->id, 'kb_ticket_ids', true);
                                if ($ticket_ids) : ?>
                                    <div class="ticket-ids">
                                        <strong><?php _e('Ticket IDs:', 'kb-core'); ?></strong>
                                        <ul>
                                            <?php foreach ($ticket_ids as $id) : ?>
                                                <li><?php echo esc_html($id); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><?php _e('No upcoming workshops.', 'kb-core'); ?></p>
                <?php endif; ?>
            </section>

            <!-- Past Workshops -->
            <section class="dashboard-section">
                <h2><?php _e('Past Workshops', 'kb-core'); ?></h2>
                <?php if ($past_workshops) : ?>
                    <div class="workshop-list">
                        <?php foreach ($past_workshops as $booking) :
                            $workshop = get_post($booking->workshop_id);
                            $date = get_post_meta($workshop->ID, '_workshop_date', true);
                            $location = get_post_meta($workshop->ID, '_workshop_location', true);
                            ?>
                            <div class="workshop-item past">
                                <h3><?php echo esc_html($workshop->post_title); ?></h3>
                                <p>
                                    <?php echo date_i18n(
                                        get_option('date_format') . ' ' . get_option('time_format'),
                                        strtotime($date)
                                    ); ?>
                                    <br>
                                    <?php echo esc_html($location); ?>
                                </p>
                                <p>
                                    <?php printf(
                                        __('Tickets: %d', 'kb-core'),
                                        $booking->quantity
                                    ); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><?php _e('No past workshops.', 'kb-core'); ?></p>
                <?php endif; ?>
            </section>
        </div>

        <style>
        .kb-member-dashboard {
            max-width: 800px;
            margin: 0 auto;
        }
        .dashboard-section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard-section h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .dashboard-form .form-row {
            margin-bottom: 1rem;
        }
        .dashboard-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .dashboard-form input {
            width: 100%;
            padding: 0.5rem;
        }
        .membership-status {
            padding: 1.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .membership-status.active {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
        }
        .membership-status.canceling {
            background: #fff3e0;
            border: 1px solid #ffcc80;
        }
        .membership-status.past-due {
            background: #ffebee;
            border: 1px solid #ef9a9a;
        }
        .membership-status.none {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
        }
        .workshop-list {
            display: grid;
            gap: 1.5rem;
        }
        .workshop-item {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .workshop-item h3 {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .workshop-item.past {
            opacity: 0.7;
        }
        .ticket-ids {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .ticket-ids ul {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0;
        }
        .spinner {
            float: none;
            margin-left: 1rem;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Profile form submission
            $('#kb-profile-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $spinner = $form.find('.spinner');
                var $message = $form.find('.form-message');

                $button.prop('disabled', true);
                $spinner.css('visibility', 'visible');
                $message.html('');

                $.post(kbFront.ajaxUrl, {
                    action: 'kb_update_profile',
                    nonce: kbFront.nonce,
                    display_name: $('#display_name').val(),
                    user_email: $('#user_email').val(),
                    phone: $('#phone').val()
                }, function(response) {
                    if (response.success) {
                        $message.html(
                            '<div class="success">' + response.data + '</div>'
                        );
                    } else {
                        $message.html(
                            '<div class="error">' + response.data + '</div>'
                        );
                    }

                    $button.prop('disabled', false);
                    $spinner.css('visibility', 'hidden');
                });
            });

            // Subscription management
            $('#kb-cancel-subscription').on('click', function() {
                if (!confirm('<?php _e('Are you sure you want to cancel your membership?', 'kb-core'); ?>')) {
                    return;
                }

                var $button = $(this);
                $button.prop('disabled', true);

                $.post(kbFront.ajaxUrl, {
                    action: 'kb_cancel_subscription',
                    nonce: kbFront.nonce
                }, function(response) {
                    alert(response.data);
                    if (response.success) {
                        location.reload();
                    }
                    $button.prop('disabled', false);
                });
            });

            $('#kb-resume-subscription').on('click', function() {
                var $button = $(this);
                $button.prop('disabled', true);

                $.post(kbFront.ajaxUrl, {
                    action: 'kb_resume_subscription',
                    nonce: kbFront.nonce
                }, function(response) {
                    alert(response.data);
                    if (response.success) {
                        location.reload();
                    }
                    $button.prop('disabled', false);
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Update user profile
     */
    public function update_profile() {
        check_ajax_referer('kb-frontend');

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('Please log in to update your profile', 'kb-core'));
        }

        $display_name = sanitize_text_field($_POST['display_name']);
        $user_email = sanitize_email($_POST['user_email']);
        $phone = sanitize_text_field($_POST['phone']);

        if (!$display_name || !$user_email) {
            wp_send_json_error(__('Please fill in all required fields', 'kb-core'));
        }

        if (!is_email($user_email)) {
            wp_send_json_error(__('Please enter a valid email address', 'kb-core'));
        }

        $user = wp_get_current_user();
        if ($user_email !== $user->user_email && email_exists($user_email)) {
            wp_send_json_error(__('This email address is already in use', 'kb-core'));
        }

        wp_update_user([
            'ID' => $user_id,
            'display_name' => $display_name,
            'user_email' => $user_email,
        ]);

        update_user_meta($user_id, 'phone', $phone);

        wp_send_json_success(__('Profile updated successfully', 'kb-core'));
    }

    /**
     * Get upcoming workshops for user
     *
     * @param int $user_id User ID
     * @return array
     */
    private function get_upcoming_workshops($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT b.* 
            FROM {$wpdb->prefix}kb_bookings b 
            JOIN {$wpdb->postmeta} m ON b.workshop_id = m.post_id 
            WHERE b.user_id = %d 
            AND b.status = 'confirmed' 
            AND m.meta_key = '_workshop_date' 
            AND m.meta_value >= %s 
            ORDER BY m.meta_value ASC",
            $user_id,
            current_time('mysql')
        ));
    }

    /**
     * Get past workshops for user
     *
     * @param int $user_id User ID
     * @return array
     */
    private function get_past_workshops($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT b.* 
            FROM {$wpdb->prefix}kb_bookings b 
            JOIN {$wpdb->postmeta} m ON b.workshop_id = m.post_id 
            WHERE b.user_id = %d 
            AND b.status = 'confirmed' 
            AND m.meta_key = '_workshop_date' 
            AND m.meta_value < %s 
            ORDER BY m.meta_value DESC",
            $user_id,
            current_time('mysql')
        ));
    }
}
