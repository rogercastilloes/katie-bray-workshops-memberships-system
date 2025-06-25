<?php
/**
 * Company Logos Block Handler
 *
 * @package KB\Corporate
 */

namespace KB\Corporate;

class Logos {
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
        add_action('init', [$this, 'register_block']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_company_logo', [$this, 'save_meta']);
    }

    /**
     * Register company logo post type
     */
    public function register_post_type() {
        register_post_type('company_logo', [
            'labels' => [
                'name' => __('Company Logos', 'kb-core'),
                'singular_name' => __('Company Logo', 'kb-core'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'kb-core',
            'supports' => ['title'],
            'menu_position' => 30,
        ]);
    }

    /**
     * Register Gutenberg block
     */
    public function register_block() {
        register_block_type('kb-core/company-logos', [
            'editor_script' => 'kb-logos-editor',
            'editor_style' => 'kb-logos-editor',
            'render_callback' => [$this, 'render_block'],
            'attributes' => [
                'columns' => [
                    'type' => 'number',
                    'default' => 4,
                ],
                'grayscale' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
            ],
        ]);

        wp_register_script(
            'kb-logos-editor',
            KB_CORE_ASSETS . 'js/blocks/company-logos.js',
            ['wp-blocks', 'wp-element', 'wp-editor'],
            KB_CORE_VERSION
        );

        wp_register_style(
            'kb-logos-editor',
            KB_CORE_ASSETS . 'css/blocks/company-logos-editor.css',
            [],
            KB_CORE_VERSION
        );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'company_logo_details',
            __('Logo Details', 'kb-core'),
            [$this, 'render_meta_box'],
            'company_logo',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box
     *
     * @param \WP_Post $post Post object
     */
    public function render_meta_box($post) {
        wp_nonce_field('company_logo_meta', 'company_logo_meta_nonce');

        $logo_id = get_post_meta($post->ID, '_logo_image_id', true);
        $url = get_post_meta($post->ID, '_company_url', true);
        ?>
        <div class="company-logo-meta">
            <p>
                <label><?php _e('Logo Image', 'kb-core'); ?></label>
                <div class="logo-preview">
                    <?php if ($logo_id) : ?>
                        <?php echo wp_get_attachment_image($logo_id, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <input type="hidden" 
                       name="logo_image_id" 
                       id="logo_image_id" 
                       value="<?php echo esc_attr($logo_id); ?>">
                <button type="button" 
                        class="button" 
                        id="upload_logo_button">
                    <?php _e('Upload Logo', 'kb-core'); ?>
                </button>
                <?php if ($logo_id) : ?>
                    <button type="button" 
                            class="button" 
                            id="remove_logo_button">
                        <?php _e('Remove Logo', 'kb-core'); ?>
                    </button>
                <?php endif; ?>
            </p>

            <p>
                <label for="company_url"><?php _e('Company URL', 'kb-core'); ?></label>
                <input type="url" 
                       id="company_url" 
                       name="company_url" 
                       value="<?php echo esc_url($url); ?>" 
                       class="widefat">
            </p>
        </div>

        <script>
        jQuery(document).ready(function($) {
            var frame;
            $('#upload_logo_button').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php _e('Select or Upload Logo', 'kb-core'); ?>',
                    button: {
                        text: '<?php _e('Use this logo', 'kb-core'); ?>'
                    },
                    multiple: false,
                    library: {
                        type: ['image/svg+xml', 'image/png']
                    }
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#logo_image_id').val(attachment.id);
                    $('.logo-preview').html('<img src="' + attachment.url + '">');
                    $('#remove_logo_button').show();
                });

                frame.open();
            });

            $('#remove_logo_button').on('click', function(e) {
                e.preventDefault();
                $('#logo_image_id').val('');
                $('.logo-preview').empty();
                $(this).hide();
            });
        });
        </script>

        <style>
        .company-logo-meta label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .logo-preview {
            margin: 10px 0;
            max-width: 200px;
        }
        .logo-preview img {
            max-width: 100%;
            height: auto;
        }
        </style>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta($post_id) {
        if (!isset($_POST['company_logo_meta_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['company_logo_meta_nonce'], 'company_logo_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['logo_image_id'])) {
            update_post_meta($post_id, '_logo_image_id', absint($_POST['logo_image_id']));
        }

        if (isset($_POST['company_url'])) {
            update_post_meta($post_id, '_company_url', esc_url_raw($_POST['company_url']));
        }
    }

    /**
     * Render block
     *
     * @param array $attributes Block attributes
     * @return string
     */
    public function render_block($attributes) {
        $logos = get_posts([
            'post_type' => 'company_logo',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        if (!$logos) {
            return '';
        }

        $columns = isset($attributes['columns']) ? absint($attributes['columns']) : 4;
        $grayscale = isset($attributes['grayscale']) ? $attributes['grayscale'] : true;

        ob_start();
        ?>
        <div class="kb-company-logos" 
             data-columns="<?php echo esc_attr($columns); ?>"
             data-grayscale="<?php echo $grayscale ? 'true' : 'false'; ?>">
            <?php foreach ($logos as $logo) : ?>
                <?php
                $logo_id = get_post_meta($logo->ID, '_logo_image_id', true);
                $url = get_post_meta($logo->ID, '_company_url', true);
                if (!$logo_id) {
                    continue;
                }
                ?>
                <div class="kb-company-logo">
                    <?php if ($url) : ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           target="_blank" 
                           rel="noopener">
                    <?php endif; ?>

                    <?php echo wp_get_attachment_image($logo_id, 'full', false, [
                        'class' => 'lazy',
                        'loading' => 'lazy',
                        'alt' => get_the_title($logo),
                    ]); ?>

                    <?php if ($url) : ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <style>
        .kb-company-logos {
            display: grid;
            grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
            gap: 2rem;
            align-items: center;
        }
        .kb-company-logo img {
            width: 100%;
            height: auto;
            <?php if ($grayscale) : ?>
            filter: grayscale(100%);
            transition: filter 0.3s ease;
            <?php endif; ?>
        }
        <?php if ($grayscale) : ?>
        .kb-company-logo img:hover {
            filter: grayscale(0%);
        }
        <?php endif; ?>
        @media (max-width: 768px) {
            .kb-company-logos {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
