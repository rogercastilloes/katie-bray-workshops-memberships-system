<?php
/**
 * Theme Setup Class
 *
 * @package KatieBray\Theme
 */

namespace KatieBray\Theme;

class Setup {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_action('init', [$this, 'register_image_sizes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    public function setup_theme() {
        load_theme_textdomain('katie-bray', get_template_directory() . '/languages');

        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('editor-styles');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
        ]);

        // Register nav menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'katie-bray'),
            'footer'  => __('Footer Menu', 'katie-bray'),
        ]);
    }

    public function register_image_sizes() {
        add_image_size('company-logo', 300, 200, false);
        add_image_size('workshop-banner', 1200, 600, true);
        add_image_size('workshop-thumbnail', 600, 400, true);
    }

    public function register_sidebars() {
        register_sidebar([
            'name'          => __('Footer Widgets', 'katie-bray'),
            'id'            => 'footer-widgets',
            'description'   => __('Widgets in this area will be shown in the footer.', 'katie-bray'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);

        register_sidebar([
            'name'          => __('Workshop Sidebar', 'katie-bray'),
            'id'            => 'workshop-sidebar',
            'description'   => __('Widgets in this area will be shown on workshop pages.', 'katie-bray'),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }

    public function enqueue_assets() {
        // Enqueue Tailwind CSS
        wp_enqueue_style(
            'tailwindcss',
            'https://cdn.tailwindcss.com',
            [],
            null
        );

        // Enqueue Font Awesome
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
            [],
            '6.0.0-beta3'
        );

        // Theme styles
        wp_enqueue_style(
            'katie-bray-style',
            get_template_directory_uri() . '/assets/css/main.css',
            [],
            filemtime(get_template_directory() . '/assets/css/main.css')
        );

        // Theme scripts
        wp_enqueue_script(
            'katie-bray-navigation',
            get_template_directory_uri() . '/assets/js/navigation.js',
            [],
            filemtime(get_template_directory() . '/assets/js/navigation.js'),
            true
        );

        // Localize script
        wp_localize_script('katie-bray-navigation', 'katieBraySettings', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('katie-bray-nonce'),
        ]);

        // Load plugin assets if active
        if (class_exists('KB_Workshops')) {
            wp_enqueue_style('kb-workshops-style');
            wp_enqueue_script('kb-workshops-script');
        }

        if (class_exists('KB_Membership')) {
            wp_enqueue_style('kb-membership-style');
            wp_enqueue_script('kb-membership-script');
        }
    }

    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'katie-bray-editor-style',
            get_template_directory_uri() . '/assets/css/editor.css',
            [],
            filemtime(get_template_directory() . '/assets/css/editor.css')
        );
    }
}
