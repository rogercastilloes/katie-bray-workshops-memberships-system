<?php
/**
 * Company Logos Block
 *
 * @package KatieBray\Theme\Blocks
 */

namespace KatieBray\Theme\Blocks;

class CompanyLogos {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'register_block']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    public function register_block() {
        register_block_type('katie-bray/company-logos', [
            'editor_script' => 'company-logos-editor',
            'editor_style'  => 'company-logos-editor',
            'style'         => 'company-logos',
            'render_callback' => [$this, 'render_block'],
            'attributes'    => [
                'columns' => [
                    'type'    => 'number',
                    'default' => 4,
                ],
                'className' => [
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function enqueue_editor_assets() {
        wp_enqueue_script(
            'company-logos-editor',
            get_template_directory_uri() . '/assets/js/blocks/company-logos.js',
            ['wp-blocks', 'wp-element', 'wp-editor'],
            filemtime(get_template_directory() . '/assets/js/blocks/company-logos.js')
        );
    }

    public function render_block($attributes) {
        // Get company logos from ACF options
        $logos = get_field('company_logos', 'option');
        if (!$logos) {
            return '';
        }

        $columns = isset($attributes['columns']) ? absint($attributes['columns']) : 4;
        $className = isset($attributes['className']) ? $attributes['className'] : '';

        ob_start();
        ?>
        <div class="company-logos-grid grid grid-cols-2 md:grid-cols-<?php echo esc_attr($columns); ?> gap-8 items-center justify-center <?php echo esc_attr($className); ?>">
            <?php foreach ($logos as $logo) : ?>
                <?php if (!empty($logo['image'])) : ?>
                    <div class="company-logo-item flex items-center justify-center p-4 bg-white rounded-lg shadow-sm">
                        <?php if (!empty($logo['url'])) : ?>
                            <a href="<?php echo esc_url($logo['url']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="block w-full">
                        <?php endif; ?>
                        
                        <img src="<?php echo esc_url($logo['image']['sizes']['company-logo']); ?>"
                             alt="<?php echo esc_attr($logo['company_name']); ?>"
                             class="max-h-12 w-auto mx-auto object-contain"
                             loading="lazy"
                             width="<?php echo esc_attr($logo['image']['sizes']['company-logo-width']); ?>"
                             height="<?php echo esc_attr($logo['image']['sizes']['company-logo-height']); ?>">
                             
                        <?php if (!empty($logo['url'])) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Register shortcode for backward compatibility
     */
    public function register_shortcode() {
        add_shortcode('company_logos', [$this, 'render_shortcode']);
    }

    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'columns' => 4,
            'class' => '',
        ], $atts);

        return $this->render_block([
            'columns' => $atts['columns'],
            'className' => $atts['class'],
        ]);
    }
}
