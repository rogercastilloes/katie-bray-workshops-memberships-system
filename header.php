<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class('antialiased'); ?>>
<?php wp_body_open(); ?>

<div class="min-h-screen flex flex-col">
    <nav class="fixed w-full z-50 bg-background/95 backdrop-blur-md border-b">
        <div class="container-custom">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-heading font-medium">
                    <?php bloginfo('name'); ?>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-10">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex space-x-10',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" 
                            class="p-2 hover:bg-gray-100 rounded-md"
                            aria-label="Toggle mobile menu">
                        <svg id="menu-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg id="close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden">
                <div class="py-4 space-y-3">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'space-y-3',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-20">
