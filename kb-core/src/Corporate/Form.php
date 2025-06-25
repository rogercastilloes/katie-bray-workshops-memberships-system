<?php
/**
 * Corporate Form Handler
 *
 * @package KB\Corporate
 */

namespace KB\Corporate;

class Form {
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
        add_action('init', [$this, 'register_post_type']);
        add_action('wp_ajax_kb_submit_corporate_form', [$this, 'handle_submission']);
        add_action('wp_ajax_nopriv_kb_submit_corporate_form', [$this, 'handle_submission']);
        add_shortcode('kb_corporate_form', [$this, 'render_form']);
    }

    /**
     * Register corporate lead post type
     */
    public function register_post_type() {
        register_post_type('kb_corporate_lead', [
            'labels' => [
                'name' => __('Corporate Leads', 'kb-core'),
                'singular_name' => __('Corporate Lead', 'kb-core'),
                'menu_name' => __('Corporate Leads', 'kb-core'),
                'all_items' => __('All Leads', 'kb-core'),
                'view_item' => __('View Lead', 'kb-core'),
                'edit_item' => __('Edit Lead', 'kb-core'),
                'update_item' => __('Update Lead', 'kb-core'),
                'add_new_item' => __('Add New Lead', 'kb-core'),
                'new_item_name' => __('New Lead Name', 'kb-core'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'kb-core',
            'supports' => ['title'],
            'menu_position' => 32,
        ]);
    }

    /**
     * Handle form submission
     */
    public function handle_submission() {
        check_ajax_referer('kb-frontend');

        $required_fields = [
            'company_name' => __('Company Name', 'kb-core'),
            'contact_name' => __('Contact Name', 'kb-core'),
            'email' => __('Email', 'kb-core'),
            'phone' => __('Phone', 'kb-core'),
            'participants' => __('Number of Participants', 'kb-core'),
        ];

        foreach ($required_fields as $field => $label) {
            if (empty($_POST[$field])) {
                wp_send_json_error(sprintf(
                    __('%s is required', 'kb-core'),
                    $label
                ));
            }
        }

        if (!is_email($_POST['email'])) {
            wp_send_json_error(__('Please enter a valid email address', 'kb-core'));
        }

        $participants = absint($_POST['participants']);
        if ($participants < 1) {
            wp_send_json_error(__('Please enter a valid number of participants', 'kb-core'));
        }

        // Create lead post
        $lead_id = wp_insert_post([
            'post_title' => sanitize_text_field($_POST['company_name']),
            'post_type' => 'kb_corporate_lead',
            'post_status' => 'publish',
        ]);

        if (!$lead_id) {
            wp_send_json_error(__('Failed to submit form. Please try again.', 'kb-core'));
        }

        // Save meta data
        $meta_fields = [
            '_company_name' => 'sanitize_text_field',
            '_contact_name' => 'sanitize_text_field',
            '_email' => 'sanitize_email',
            '_phone' => 'sanitize_text_field',
            '_participants' => 'absint',
            '_preferred_date' => 'sanitize_text_field',
            '_message' => 'sanitize_textarea_field',
        ];

        foreach ($meta_fields as $key => $sanitize) {
            $field = ltrim($key, '_');
            if (isset($_POST[$field])) {
                update_post_meta($lead_id, $key, $sanitize($_POST[$field]));
            }
        }

        // Send notifications
        \KB\Email\Mailer::get_instance()->send_corporate_lead_notification($lead_id);

        wp_send_json_success(__('Thank you for your interest! We will contact you shortly.', 'kb-core'));
    }

    /**
     * Render corporate form
     *
     * @return string
     */
    public function render_form() {
        ob_start();
        ?>
        <div class="kb-corporate-form">
            <form id="kb-corporate-form">
                <?php wp_nonce_field('kb-frontend'); ?>

                <div class="form-row">
                    <label for="company_name">
                        <?php _e('Company Name', 'kb-core'); ?> *
                    </label>
                    <input type="text" 
                           id="company_name" 
                           name="company_name" 
                           required>
                </div>

                <div class="form-row">
                    <label for="contact_name">
                        <?php _e('Contact Person', 'kb-core'); ?> *
                    </label>
                    <input type="text" 
                           id="contact_name" 
                           name="contact_name" 
                           required>
                </div>

                <div class="form-row">
                    <label for="email">
                        <?php _e('Email', 'kb-core'); ?> *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required>
                </div>

                <div class="form-row">
                    <label for="phone">
                        <?php _e('Phone', 'kb-core'); ?> *
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           required>
                </div>

                <div class="form-row">
                    <label for="participants">
                        <?php _e('Number of Participants', 'kb-core'); ?> *
                    </label>
                    <input type="number" 
                           id="participants" 
                           name="participants" 
                           min="1" 
                           required>
                </div>

                <div class="form-row">
                    <label for="preferred_date">
                        <?php _e('Preferred Date', 'kb-core'); ?>
                    </label>
                    <input type="date" 
                           id="preferred_date" 
                           name="preferred_date" 
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-row">
                    <label for="message">
                        <?php _e('Additional Information', 'kb-core'); ?>
                    </label>
                    <textarea id="message" 
                              name="message" 
                              rows="5"></textarea>
                </div>

                <div class="form-row">
                    <button type="submit" class="button button-primary">
                        <?php _e('Submit Inquiry', 'kb-core'); ?>
                    </button>
                    <span class="spinner"></span>
                </div>

                <div class="form-message"></div>
            </form>
        </div>

        <style>
        .kb-corporate-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .kb-corporate-form .form-row {
            margin-bottom: 1.5rem;
        }
        .kb-corporate-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .kb-corporate-form input,
        .kb-corporate-form textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .kb-corporate-form textarea {
            resize: vertical;
        }
        .kb-corporate-form .spinner {
            float: none;
            margin-left: 1rem;
        }
        .kb-corporate-form .form-message {
            margin-top: 1rem;
        }
        .kb-corporate-form .success {
            color: #2e7d32;
            padding: 1rem;
            background: #e8f5e9;
            border-radius: 4px;
        }
        .kb-corporate-form .error {
            color: #c62828;
            padding: 1rem;
            background: #ffebee;
            border-radius: 4px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#kb-corporate-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $spinner = $form.find('.spinner');
                var $message = $form.find('.form-message');

                $button.prop('disabled', true);
                $spinner.css('visibility', 'visible');
                $message.html('');

                $.post(kbFront.ajaxUrl, {
                    action: 'kb_submit_corporate_form',
                    nonce: kbFront.nonce,
                    company_name: $('#company_name').val(),
                    contact_name: $('#contact_name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    participants: $('#participants').val(),
                    preferred_date: $('#preferred_date').val(),
                    message: $('#message').val()
                }, function(response) {
                    if (response.success) {
                        $form[0].reset();
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
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
