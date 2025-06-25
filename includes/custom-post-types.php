<?php
/**
 * Custom Post Types Registration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Workshop post type
 */
function katie_bray_register_workshop_post_type() {
    $labels = array(
        'name'               => __('Workshops', 'katie-bray'),
        'singular_name'      => __('Workshop', 'katie-bray'),
        'menu_name'          => __('Workshops', 'katie-bray'),
        'add_new'           => __('Add New', 'katie-bray'),
        'add_new_item'      => __('Add New Workshop', 'katie-bray'),
        'edit_item'         => __('Edit Workshop', 'katie-bray'),
        'new_item'          => __('New Workshop', 'katie-bray'),
        'view_item'         => __('View Workshop', 'katie-bray'),
        'search_items'      => __('Search Workshops', 'katie-bray'),
        'not_found'         => __('No workshops found', 'katie-bray'),
        'not_found_in_trash'=> __('No workshops found in trash', 'katie-bray'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'workshop'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true, // Enable Gutenberg editor
    );

    register_post_type('workshop', $args);
}
add_action('init', 'katie_bray_register_workshop_post_type');

/**
 * Add Workshop meta boxes
 */
function katie_bray_add_workshop_meta_boxes() {
    add_meta_box(
        'workshop_details',
        __('Workshop Details', 'katie-bray'),
        'katie_bray_workshop_details_callback',
        'workshop',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'katie_bray_add_workshop_meta_boxes');

/**
 * Workshop details meta box callback
 */
function katie_bray_workshop_details_callback($post) {
    wp_nonce_field('workshop_details', 'workshop_details_nonce');

    $date = get_post_meta($post->ID, '_workshop_date', true);
    $time = get_post_meta($post->ID, '_workshop_time', true);
    $location = get_post_meta($post->ID, '_workshop_location', true);
    $capacity = get_post_meta($post->ID, '_workshop_capacity', true);
    $price = get_post_meta($post->ID, '_workshop_price', true);
    ?>
    <div class="workshop-meta-fields">
        <p>
            <label for="workshop_date"><?php _e('Date:', 'katie-bray'); ?></label><br>
            <input type="date" id="workshop_date" name="workshop_date" 
                   value="<?php echo esc_attr($date); ?>" class="widefat">
        </p>
        <p>
            <label for="workshop_time"><?php _e('Time:', 'katie-bray'); ?></label><br>
            <input type="time" id="workshop_time" name="workshop_time" 
                   value="<?php echo esc_attr($time); ?>" class="widefat">
        </p>
        <p>
            <label for="workshop_location"><?php _e('Location:', 'katie-bray'); ?></label><br>
            <input type="text" id="workshop_location" name="workshop_location" 
                   value="<?php echo esc_attr($location); ?>" class="widefat">
        </p>
        <p>
            <label for="workshop_capacity"><?php _e('Capacity:', 'katie-bray'); ?></label><br>
            <input type="number" id="workshop_capacity" name="workshop_capacity" 
                   value="<?php echo esc_attr($capacity); ?>" min="1" class="widefat">
        </p>
        <p>
            <label for="workshop_price"><?php _e('Price ($):', 'katie-bray'); ?></label><br>
            <input type="number" id="workshop_price" name="workshop_price" 
                   value="<?php echo esc_attr($price); ?>" min="0" step="0.01" class="widefat">
        </p>
    </div>
    <?php
}

/**
 * Save workshop meta box data
 */
function katie_bray_save_workshop_meta_box($post_id) {
    if (!isset($_POST['workshop_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['workshop_details_nonce'], 'workshop_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = array(
        'workshop_date',
        'workshop_time',
        'workshop_location',
        'workshop_capacity',
        'workshop_price'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                '_' . $field,
                sanitize_text_field($_POST[$field])
            );
        }
    }
}
add_action('save_post', 'katie_bray_save_workshop_meta_box');

/**
 * Register Workshop Categories taxonomy
 */
function katie_bray_register_workshop_taxonomy() {
    $labels = array(
        'name'              => __('Workshop Categories', 'katie-bray'),
        'singular_name'     => __('Workshop Category', 'katie-bray'),
        'search_items'      => __('Search Categories', 'katie-bray'),
        'all_items'         => __('All Categories', 'katie-bray'),
        'parent_item'       => __('Parent Category', 'katie-bray'),
        'parent_item_colon' => __('Parent Category:', 'katie-bray'),
        'edit_item'         => __('Edit Category', 'katie-bray'),
        'update_item'       => __('Update Category', 'katie-bray'),
        'add_new_item'      => __('Add New Category', 'katie-bray'),
        'new_item_name'     => __('New Category Name', 'katie-bray'),
        'menu_name'         => __('Categories', 'katie-bray'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'           => $labels,
        'show_ui'          => true,
        'show_admin_column'=> true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'workshop-category'),
        'show_in_rest'     => true,
    );

    register_taxonomy('workshop_category', array('workshop'), $args);
}
add_action('init', 'katie_bray_register_workshop_taxonomy');

/**
 * Register Workshop Registration post type
 */
function katie_bray_register_workshop_registration_post_type() {
    $labels = array(
        'name'               => __('Registrations', 'katie-bray'),
        'singular_name'      => __('Registration', 'katie-bray'),
        'menu_name'          => __('Workshop Registrations', 'katie-bray'),
        'add_new'           => __('Add New', 'katie-bray'),
        'add_new_item'      => __('Add New Registration', 'katie-bray'),
        'edit_item'         => __('Edit Registration', 'katie-bray'),
        'new_item'          => __('New Registration', 'katie-bray'),
        'view_item'         => __('View Registration', 'katie-bray'),
        'search_items'      => __('Search Registrations', 'katie-bray'),
        'not_found'         => __('No registrations found', 'katie-bray'),
        'not_found_in_trash'=> __('No registrations found in trash', 'katie-bray'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=workshop',
        'capability_type'     => 'post',
        'capabilities'        => array(
            'create_posts' => false,
        ),
        'map_meta_cap'        => true,
        'supports'            => array('title'),
        'register_meta_box_cb'=> 'katie_bray_add_registration_meta_boxes'
    );

    register_post_type('registration', $args);
}
add_action('init', 'katie_bray_register_workshop_registration_post_type');

/**
 * Add Registration meta boxes
 */
function katie_bray_add_registration_meta_boxes() {
    add_meta_box(
        'registration_details',
        __('Registration Details', 'katie-bray'),
        'katie_bray_registration_details_callback',
        'registration',
        'normal',
        'high'
    );
}

/**
 * Registration details meta box callback
 */
function katie_bray_registration_details_callback($post) {
    $workshop_id = get_post_meta($post->ID, '_workshop_id', true);
    $workshop = get_post($workshop_id);
    ?>
    <div class="registration-meta-fields">
        <table class="form-table">
            <tr>
                <th><label><?php _e('Workshop:', 'katie-bray'); ?></label></th>
                <td>
                    <?php if ($workshop): ?>
                        <a href="<?php echo get_edit_post_link($workshop_id); ?>">
                            <?php echo esc_html($workshop->post_title); ?>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label><?php _e('Payment Status:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_payment_status', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Amount Paid:', 'katie-bray'); ?></label></th>
                <td>$<?php echo number_format(get_post_meta($post->ID, '_amount_paid', true), 2); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Payment Date:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_payment_date', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('First Name:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_first_name', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Last Name:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_last_name', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Email:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_email', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Phone:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_phone', true)); ?></td>
            </tr>
            <tr>
                <th><label><?php _e('Payment ID:', 'katie-bray'); ?></label></th>
                <td><?php echo esc_html(get_post_meta($post->ID, '_payment_id', true)); ?></td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Flush rewrite rules on theme activation
 */
function katie_bray_rewrite_flush() {
    // Register post types
    katie_bray_register_workshop_post_type();
    katie_bray_register_workshop_registration_post_type();
    
    // Register taxonomies
    katie_bray_register_workshop_taxonomy();
    
    // Flush rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'katie_bray_rewrite_flush');
