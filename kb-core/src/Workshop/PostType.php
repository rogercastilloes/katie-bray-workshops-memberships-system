<?php
/**
 * Workshop Post Type Handler
 *
 * @package KB\Workshop
 */

namespace KB\Workshop;

class PostType {
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
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_workshop', [$this, 'save_meta']);
        add_filter('manage_workshop_posts_columns', [$this, 'add_columns']);
        add_action('manage_workshop_posts_custom_column', [$this, 'render_column'], 10, 2);
        add_filter('manage_edit-workshop_sortable_columns', [$this, 'sortable_columns']);
    }

    /**
     * Register workshop post type
     */
    public function register_post_type() {
        register_post_type('workshop', [
            'labels' => [
                'name' => __('Workshops', 'kb-core'),
                'singular_name' => __('Workshop', 'kb-core'),
                'add_new' => __('Add New', 'kb-core'),
                'add_new_item' => __('Add New Workshop', 'kb-core'),
                'edit_item' => __('Edit Workshop', 'kb-core'),
                'new_item' => __('New Workshop', 'kb-core'),
                'view_item' => __('View Workshop', 'kb-core'),
                'search_items' => __('Search Workshops', 'kb-core'),
                'not_found' => __('No workshops found', 'kb-core'),
                'not_found_in_trash' => __('No workshops found in trash', 'kb-core'),
                'menu_name' => __('Workshops', 'kb-core'),
            ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'kb-core',
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_position' => 31,
            'menu_icon' => 'dashicons-calendar-alt',
            'has_archive' => true,
            'rewrite' => ['slug' => 'workshops'],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'workshop_details',
            __('Workshop Details', 'kb-core'),
            [$this, 'render_details_meta_box'],
            'workshop',
            'normal',
            'high'
        );

        add_meta_box(
            'workshop_bookings',
            __('Bookings', 'kb-core'),
            [$this, 'render_bookings_meta_box'],
            'workshop',
            'normal',
            'high'
        );
    }

    /**
     * Render details meta box
     *
     * @param \WP_Post $post Post object
     */
    public function render_details_meta_box($post) {
        wp_nonce_field('workshop_details', 'workshop_details_nonce');

        $date = get_post_meta($post->ID, '_workshop_date', true);
        $location = get_post_meta($post->ID, '_workshop_location', true);
        $capacity = get_post_meta($post->ID, '_workshop_capacity', true);
        $price = get_post_meta($post->ID, '_workshop_price', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="workshop_date">
                        <?php _e('Date & Time', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="datetime-local" 
                           id="workshop_date" 
                           name="workshop_date" 
                           value="<?php echo esc_attr($date); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="workshop_location">
                        <?php _e('Location', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" 
                           id="workshop_location" 
                           name="workshop_location" 
                           value="<?php echo esc_attr($location); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="workshop_capacity">
                        <?php _e('Capacity', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="number" 
                           id="workshop_capacity" 
                           name="workshop_capacity" 
                           value="<?php echo esc_attr($capacity); ?>" 
                           min="1" 
                           class="small-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="workshop_price">
                        <?php _e('Price', 'kb-core'); ?>
                    </label>
                </th>
                <td>
                    <input type="number" 
                           id="workshop_price" 
                           name="workshop_price" 
                           value="<?php echo esc_attr($price); ?>" 
                           min="0" 
                           step="0.01" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('Price in EUR', 'kb-core'); ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render bookings meta box
     *
     * @param \WP_Post $post Post object
     */
    public function render_bookings_meta_box($post) {
        global $wpdb;
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT b.*, u.display_name 
            FROM {$wpdb->prefix}kb_bookings b 
            JOIN {$wpdb->users} u ON b.user_id = u.ID 
            WHERE b.workshop_id = %d 
            ORDER BY b.created_at DESC",
            $post->ID
        ));

        if (!$bookings) {
            echo '<p>' . __('No bookings yet.', 'kb-core') . '</p>';
            return;
        }
        ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('Customer', 'kb-core'); ?></th>
                    <th><?php _e('Tickets', 'kb-core'); ?></th>
                    <th><?php _e('Total', 'kb-core'); ?></th>
                    <th><?php _e('Status', 'kb-core'); ?></th>
                    <th><?php _e('Date', 'kb-core'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_edit_user_link($booking->user_id); ?>">
                                <?php echo esc_html($booking->display_name); ?>
                            </a>
                        </td>
                        <td><?php echo absint($booking->quantity); ?></td>
                        <td>€<?php echo number_format($booking->total, 2); ?></td>
                        <td><?php echo esc_html(ucfirst($booking->status)); ?></td>
                        <td>
                            <?php echo date_i18n(
                                get_option('date_format') . ' ' . get_option('time_format'),
                                strtotime($booking->created_at)
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta($post_id) {
        if (!isset($_POST['workshop_details_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['workshop_details_nonce'], 'workshop_details')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            'workshop_date' => 'sanitize_text_field',
            'workshop_location' => 'sanitize_text_field',
            'workshop_capacity' => 'absint',
            'workshop_price' => 'floatval',
        ];

        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta(
                    $post_id,
                    '_' . $field,
                    $sanitize($_POST[$field])
                );
            }
        }
    }

    /**
     * Add custom columns
     *
     * @param array $columns Columns array
     * @return array
     */
    public function add_columns($columns) {
        $date = $columns['date'];
        unset($columns['date']);

        $columns['workshop_date'] = __('Date', 'kb-core');
        $columns['workshop_location'] = __('Location', 'kb-core');
        $columns['workshop_capacity'] = __('Capacity', 'kb-core');
        $columns['workshop_bookings'] = __('Bookings', 'kb-core');
        $columns['workshop_price'] = __('Price', 'kb-core');
        $columns['date'] = $date;

        return $columns;
    }

    /**
     * Render column content
     *
     * @param string $column Column name
     * @param int $post_id Post ID
     */
    public function render_column($column, $post_id) {
        switch ($column) {
            case 'workshop_date':
                $date = get_post_meta($post_id, '_workshop_date', true);
                if ($date) {
                    echo date_i18n(
                        get_option('date_format') . ' ' . get_option('time_format'),
                        strtotime($date)
                    );
                }
                break;

            case 'workshop_location':
                echo esc_html(get_post_meta($post_id, '_workshop_location', true));
                break;

            case 'workshop_capacity':
                $capacity = get_post_meta($post_id, '_workshop_capacity', true);
                $booked = self::get_booked_spots($post_id);
                echo sprintf(
                    __('%1$d/%2$d', 'kb-core'),
                    $booked,
                    $capacity
                );
                break;

            case 'workshop_bookings':
                global $wpdb;
                $count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}kb_bookings WHERE workshop_id = %d",
                    $post_id
                ));
                echo absint($count);
                break;

            case 'workshop_price':
                $price = get_post_meta($post_id, '_workshop_price', true);
                echo '€' . number_format($price, 2);
                break;
        }
    }

    /**
     * Make columns sortable
     *
     * @param array $columns Columns array
     * @return array
     */
    public function sortable_columns($columns) {
        $columns['workshop_date'] = 'workshop_date';
        $columns['workshop_price'] = 'workshop_price';
        return $columns;
    }

    /**
     * Get booked spots for a workshop
     *
     * @param int $workshop_id Workshop ID
     * @return int
     */
    public static function get_booked_spots($workshop_id) {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(quantity) FROM {$wpdb->prefix}kb_bookings WHERE workshop_id = %d",
            $workshop_id
        ));
    }

    /**
     * Get available spots for a workshop
     *
     * @param int $workshop_id Workshop ID
     * @return int
     */
    public static function get_available_spots($workshop_id) {
        $capacity = get_post_meta($workshop_id, '_workshop_capacity', true);
        $booked = self::get_booked_spots($workshop_id);
        return max(0, $capacity - $booked);
    }

    /**
     * Check if workshop is full
     *
     * @param int $workshop_id Workshop ID
     * @return bool
     */
    public static function is_full($workshop_id) {
        return self::get_available_spots($workshop_id) === 0;
    }

    /**
     * Get workshop price for user
     *
     * @param int $workshop_id Workshop ID
     * @param int $user_id User ID
     * @return float
     */
    public static function get_price_for_user($workshop_id, $user_id) {
        $price = get_post_meta($workshop_id, '_workshop_price', true);

        if (user_can($user_id, 'kb_member')) {
            $discount = get_option('kb_premium_discount', 25);
            $price = $price * (1 - $discount / 100);
        }

        return round($price, 2);
    }
}
