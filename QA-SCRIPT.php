<?php
/**
 * QA Script for Katie Bray Core
 * 
 * Run this script to verify the installation and configuration
 */

// Ensure we're running in WordPress context
if (!defined('ABSPATH')) {
    die('This script must be run within WordPress');
}

class KB_QA_Script {
    private $tests_passed = 0;
    private $tests_failed = 0;
    private $results = [];

    public function run() {
        $this->header('Katie Bray Core - QA Tests');
        
        // Run tests
        $this->test_style_css_hash();
        $this->test_theme_folder();
        $this->test_prohibited_strings();
        $this->test_plugin_activation();
        $this->test_required_pages();
        $this->test_stripe_config();
        $this->test_email_config();
        $this->test_premium_access();
        $this->test_chat_functionality();

        // Display results
        $this->display_results();
    }

    private function test_style_css_hash() {
        $original_hash = ''; // TODO: Set this to the original style.css hash
        $current_hash = sha1_file(get_template_directory() . '/style.css');
        
        $this->assert(
            'style.css hash unchanged',
            $current_hash === $original_hash,
            "Expected: {$original_hash}\nGot: {$current_hash}"
        );
    }

    private function test_theme_folder() {
        $this->assert(
            'No katie-bray theme folder exists',
            !is_dir(get_template_directory() . '/katie-bray'),
            'katie-bray folder found in theme directory'
        );
    }

    private function test_prohibited_strings() {
        $prohibited = ['tribe_', 'the-events-calendar', 'event_tickets'];
        $found = [];
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(get_template_directory())
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                foreach ($prohibited as $string) {
                    if (strpos($content, $string) !== false) {
                        $found[] = basename($file->getPathname()) . ": {$string}";
                    }
                }
            }
        }

        $this->assert(
            'No prohibited strings found',
            empty($found),
            'Found prohibited strings: ' . implode(', ', $found)
        );
    }

    private function test_plugin_activation() {
        $this->assert(
            'Plugin is active',
            is_plugin_active('kb-core/kb-core.php'),
            'kb-core plugin is not active'
        );
    }

    private function test_required_pages() {
        $required_pages = [
            'mi-cuenta' => '[kb_dashboard]',
            'empresas' => '[kb_corporate_form]',
        ];

        foreach ($required_pages as $slug => $shortcode) {
            $page = get_page_by_path($slug);
            $this->assert(
                "Page '{$slug}' exists",
                $page instanceof WP_Post,
                "Page '{$slug}' not found"
            );

            if ($page) {
                $this->assert(
                    "Page '{$slug}' contains required shortcode",
                    strpos($page->post_content, $shortcode) !== false,
                    "Shortcode '{$shortcode}' not found in '{$slug}'"
                );
            }
        }
    }

    private function test_stripe_config() {
        $required_keys = [
            'kb_stripe_test_publishable_key',
            'kb_stripe_test_secret_key',
            'kb_stripe_webhook_secret',
            'kb_stripe_premium_price_id',
        ];

        foreach ($required_keys as $key) {
            $this->assert(
                "Stripe setting '{$key}' exists",
                get_option($key) !== false,
                "Missing Stripe configuration: {$key}"
            );
        }
    }

    private function test_email_config() {
        $this->assert(
            'Email configuration exists',
            get_option('kb_smtp_enabled') !== false,
            'Email configuration not found'
        );

        if (get_option('kb_smtp_enabled')) {
            $required_smtp = [
                'kb_smtp_host',
                'kb_smtp_port',
                'kb_smtp_user',
                'kb_smtp_pass',
            ];

            foreach ($required_smtp as $key) {
                $this->assert(
                    "SMTP setting '{$key}' exists",
                    get_option($key) !== false,
                    "Missing SMTP configuration: {$key}"
                );
            }
        }
    }

    private function test_premium_access() {
        $role = get_role('kb_member');
        $this->assert(
            'Premium member role exists',
            $role !== null,
            'kb_member role not found'
        );

        if ($role) {
            $this->assert(
                'Premium member has required capabilities',
                $role->has_cap('kb_access_premium'),
                'kb_member missing required capabilities'
            );
        }
    }

    private function test_chat_functionality() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kb_chat_messages';
        
        $this->assert(
            'Chat messages table exists',
            $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name,
            'Chat messages table not found'
        );
    }

    private function assert($test_name, $condition, $error_message = '') {
        if ($condition) {
            $this->tests_passed++;
            $this->results[] = [
                'name' => $test_name,
                'status' => 'PASS',
                'message' => '',
            ];
        } else {
            $this->tests_failed++;
            $this->results[] = [
                'name' => $test_name,
                'status' => 'FAIL',
                'message' => $error_message,
            ];
        }
    }

    private function header($text) {
        echo str_repeat('=', 80) . "\n";
        echo $text . "\n";
        echo str_repeat('=', 80) . "\n\n";
    }

    private function display_results() {
        echo "\nTest Results:\n";
        echo str_repeat('-', 80) . "\n";

        foreach ($this->results as $result) {
            $status_color = $result['status'] === 'PASS' ? '32' : '31';
            echo sprintf(
                "\033[%dm%s\033[0m: %s\n",
                $status_color,
                $result['status'],
                $result['name']
            );

            if ($result['message']) {
                echo "  {$result['message']}\n";
            }
        }

        echo "\nSummary:\n";
        echo "Passed: {$this->tests_passed}\n";
        echo "Failed: {$this->tests_failed}\n";
        echo "Total: " . ($this->tests_passed + $this->tests_failed) . "\n";
    }
}

// Run tests
$qa = new KB_QA_Script();
$qa->run();
