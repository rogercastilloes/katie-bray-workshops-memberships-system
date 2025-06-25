<?php
/**
 * Core initialization and admin settings
 *
 * @package KB\Core
 */

namespace KB\Core;

class Init {
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
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_scripts']);
        add_action('init', [$this, 'load_textdomain']);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Katie Bray', 'kb-core'),
            __('Katie Bray', 'kb-core'),
            'manage_options',
            'kb-core',
            [$this, 'render_settings_page'],
            'dashicons-calendar-alt',
            30
        );

        add_submenu_page(
            'kb-core',
            __('Settings', 'kb-core'),
            __('Settings', 'kb-core'),
            'manage_options',
            'kb-core',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // General Settings
        register_setting('kb_general', 'kb_premium_price');
        register_setting('kb_general', 'kb_premium_discount');
        register_setting('kb_general', 'kb_company_address');

        // Stripe Settings
        register_setting('kb_stripe', 'kb_stripe_test_mode');
        register_setting('kb_stripe', 'kb_stripe_test_publishable_key');
        register_setting('kb_stripe', 'kb_stripe_test_secret_key');
        register_setting('kb_stripe', 'kb_stripe_live_publishable_key');
        register_setting('kb_stripe', 'kb_stripe_live_secret_key');
        register_setting('kb_stripe', 'kb_stripe_webhook_secret');
        register_setting('kb_stripe', 'kb_stripe_premium_price_id');

        // Email Settings
        register_setting('kb_email', 'kb_smtp_enabled');
        register_setting('kb_email', 'kb_smtp_host');
        register_setting('kb_email', 'kb_smtp_port');
        register_setting('kb_email', 'kb_smtp_secure');
        register_setting('kb_email', 'kb_smtp_auth');
        register_setting('kb_email', 'kb_smtp_user');
        register_setting('kb_email', 'kb_smtp_pass');
        register_setting('kb_email', 'kb_email_from_name');
        register_setting('kb_email', 'kb_email_from_address');
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        ?>
        <div class="wrap">
            <h1><?php _e('Katie Bray Settings', 'kb-core'); ?></h1>

            <h2 class="nav-tab-wrapper">
                <a href="?page=kb-core&tab=general" 
                   class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('General', 'kb-core'); ?>
                </a>
                <a href="?page=kb-core&tab=stripe" 
                   class="nav-tab <?php echo $active_tab === 'stripe' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Stripe', 'kb-core'); ?>
                </a>
                <a href="?page=kb-core&tab=email" 
                   class="nav-tab <?php echo $active_tab === 'email' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Email', 'kb-core'); ?>
                </a>
            </h2>

            <form method="post" action="options.php">
                <?php
                switch ($active_tab) {
                    case 'stripe':
                        settings_fields('kb_stripe');
                        $this->render_stripe_settings();
                        break;
                    case 'email':
                        settings_fields('kb_email');
                        $this->render_email_settings();
                        break;
                    default:
                        settings_fields('kb_general');
                        $this->render_general_settings();
                }
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general settings
     */
    private function render_general_settings() {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="kb_premium_price">
                        <?php _e('Premium Membership Price', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="number" 
                           id="kb_premium_price" 
                           name="kb_premium_price" 
                           value="<?php echo esc_attr(get_option('kb_premium_price', 35)); ?>" 
                           min="0" 
                           step="0.01" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('Monthly subscription price in EUR', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_premium_discount">
                        <?php _e('Premium Member Discount', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="number" 
                           id="kb_premium_discount" 
                           name="kb_premium_discount" 
                           value="<?php echo esc_attr(get_option('kb_premium_discount', 25)); ?>" 
                           min="0" 
                           max="100" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('Discount percentage for premium members', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_company_address">
                        <?php _e('Company Address', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <textarea id="kb_company_address" 
                              name="kb_company_address" 
                              rows="3" 
                              class="large-text"><?php 
                        echo esc_textarea(get_option('kb_company_address')); 
                    ?></textarea>
                    <p class="description">
                        <?php _e('Company address for email footers', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render Stripe settings
     */
    private function render_stripe_settings() {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="kb_stripe_test_mode">
                        <?php _e('Test Mode', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" 
                           id="kb_stripe_test_mode" 
                           name="kb_stripe_test_mode" 
                           value="1" 
                           <?php checked(1, get_option('kb_stripe_test_mode')); ?>>
                    <p class="description">
                        <?php _e('Enable Stripe test mode', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_test_publishable_key">
                        <?php _e('Test Publishable Key', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_stripe_test_publishable_key" 
                           name="kb_stripe_test_publishable_key" 
                           value="<?php echo esc_attr(get_option('kb_stripe_test_publishable_key')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_test_secret_key">
                        <?php _e('Test Secret Key', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="password" 
                           id="kb_stripe_test_secret_key" 
                           name="kb_stripe_test_secret_key" 
                           value="<?php echo esc_attr(get_option('kb_stripe_test_secret_key')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_live_publishable_key">
                        <?php _e('Live Publishable Key', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_stripe_live_publishable_key" 
                           name="kb_stripe_live_publishable_key" 
                           value="<?php echo esc_attr(get_option('kb_stripe_live_publishable_key')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_live_secret_key">
                        <?php _e('Live Secret Key', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="password" 
                           id="kb_stripe_live_secret_key" 
                           name="kb_stripe_live_secret_key" 
                           value="<?php echo esc_attr(get_option('kb_stripe_live_secret_key')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_webhook_secret">
                        <?php _e('Webhook Secret', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="password" 
                           id="kb_stripe_webhook_secret" 
                           name="kb_stripe_webhook_secret" 
                           value="<?php echo esc_attr(get_option('kb_stripe_webhook_secret')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_stripe_premium_price_id">
                        <?php _e('Premium Price ID', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_stripe_premium_price_id" 
                           name="kb_stripe_premium_price_id" 
                           value="<?php echo esc_attr(get_option('kb_stripe_premium_price_id')); ?>" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('Stripe Price ID for premium membership subscription', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render email settings
     */
    private function render_email_settings() {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="kb_smtp_enabled">
                        <?php _e('Enable SMTP', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" 
                           id="kb_smtp_enabled" 
                           name="kb_smtp_enabled" 
                           value="1" 
                           <?php checked(1, get_option('kb_smtp_enabled')); ?>>
                    <p class="description">
                        <?php _e('Use SMTP for sending emails', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_host">
                        <?php _e('SMTP Host', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_smtp_host" 
                           name="kb_smtp_host" 
                           value="<?php echo esc_attr(get_option('kb_smtp_host')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_port">
                        <?php _e('SMTP Port', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="number" 
                           id="kb_smtp_port" 
                           name="kb_smtp_port" 
                           value="<?php echo esc_attr(get_option('kb_smtp_port', 587)); ?>" 
                           class="small-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_secure">
                        <?php _e('Encryption', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <select id="kb_smtp_secure" name="kb_smtp_secure">
                        <option value=""><?php _e('None', 'kb-core'); ?></option>
                        <option value="ssl" <?php selected('ssl', get_option('kb_smtp_secure')); ?>>
                            SSL
                        </option>
                        <option value="tls" <?php selected('tls', get_option('kb_smtp_secure')); ?>>
                            TLS
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_auth">
                        <?php _e('Authentication', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" 
                           id="kb_smtp_auth" 
                           name="kb_smtp_auth" 
                           value="1" 
                           <?php checked(1, get_option('kb_smtp_auth')); ?>>
                    <p class="description">
                        <?php _e('Use SMTP authentication', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_user">
                        <?php _e('Username', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_smtp_user" 
                           name="kb_smtp_user" 
                           value="<?php echo esc_attr(get_option('kb_smtp_user')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_smtp_pass">
                        <?php _e('Password', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="password" 
                           id="kb_smtp_pass" 
                           name="kb_smtp_pass" 
                           value="<?php echo esc_attr(get_option('kb_smtp_pass')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_email_from_name">
                        <?php _e('From Name', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="kb_email_from_name" 
                           name="kb_email_from_name" 
                           value="<?php echo esc_attr(get_option('kb_email_from_name')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kb_email_from_address">
                        <?php _e('From Email', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="email" 
                           id="kb_email_from_address" 
                           name="kb_email_from_address" 
                           value="<?php echo esc_attr(get_option('kb_email_from_address')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Test Email', 'kb-core'); ?></th>
                <td>
                    <button type="button" 
                            class="button" 
                            id="kb-test-email">
                        <?php _e('Send Test Email', 'kb-core'); ?>
                    </button>
                    <span class="spinner"></span>
                    <p class="description">
                        <?php _e('Send a test email to check your settings', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <script>
        jQuery(document).ready(function($) {
            $('#kb-test-email').on('click', function() {
                var $button = $(this);
                var $spinner = $button.next('.spinner');

                $button.prop('disabled', true);
                $spinner.css('visibility', 'visible');

                $.post(ajaxurl, {
                    action: 'kb_test_email',
                    _ajax_nonce: '<?php echo wp_create_nonce('kb-admin'); ?>'
                }, function(response) {
                    alert(response.data);
                    $button.prop('disabled', false);
                    $spinner.css('visibility', 'hidden');
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts() {
        wp_enqueue_style(
            'kb-admin',
            KB_CORE_ASSETS . 'css/admin.css',
            [],
            KB_CORE_VERSION
        );

        wp_enqueue_script(
            'kb-admin',
            KB_CORE_ASSETS . 'js/admin.js',
            ['jquery'],
            KB_CORE_VERSION,
            true
        );

        wp_localize_script('kb-admin', 'kbAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kb-admin'),
        ]);
    }

    /**
     * Enqueue frontend scripts
     */
    public function frontend_scripts() {
        wp_enqueue_style(
            'kb-frontend',
            KB_CORE_ASSETS . 'css/frontend.css',
            [],
            KB_CORE_VERSION
        );

        wp_enqueue_script(
            'stripe',
            'https://js.stripe.com/v3/',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'kb-frontend',
            KB_CORE_ASSETS . 'js/frontend.js',
            ['jquery', 'stripe'],
            KB_CORE_VERSION,
            true
        );

        wp_localize_script('kb-frontend', 'kbFront', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kb-frontend'),
            'stripeKey' => get_option('kb_stripe_test_mode') ? 
                get_option('kb_stripe_test_publishable_key') : 
                get_option('kb_stripe_live_publishable_key'),
        ]);
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'kb-core',
            false,
            dirname(plugin_basename(KB_CORE_FILE)) . '/languages'
        );
    }
}
