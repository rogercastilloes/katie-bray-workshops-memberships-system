<?php
/**
 * Template Name: Corporate Workshops
 * 
 * This is the template for the corporate workshops page.
 */

// Enqueue the corporate form script
wp_enqueue_script('kb-corporate-form');

// Get template parts directory
$parts_dir = 'template-parts/corporate/sections/';

get_header();

// Load hero section
get_template_part($parts_dir . 'hero');
?>

<div class="bg-background min-h-screen">
    <!-- Main Content -->
    <div class="container-custom py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <!-- Corporate Form -->
            <div>
                <?php get_template_part('template-parts/corporate/contact-form'); ?>
            </div>

            <!-- Features & Benefits -->
            <?php get_template_part($parts_dir . 'features'); ?>
        </div>
    </div>
</div>

<?php 
get_footer(); 
?>
