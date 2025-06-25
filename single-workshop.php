<?php
/**
 * Template for displaying single workshop
 * 
 * Template hierarchy: Loads for single posts of type 'workshop'
 * Verify with: is_singular('workshop')
 */

// Verify template and plugin load status (remove in production)
add_action('wp_head', function() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<!-- Template: single-workshop.php -->';
        echo '<!-- Post Type: ' . get_post_type() . ' -->';
        echo '<!-- KB Plugin Active: ' . (is_plugin_active('katie-bray/katie-bray.php') ? 'Yes' : 'No') . ' -->';
    }
});

get_header();

// Verify we're on a workshop post
if (!is_singular('workshop')) {
    wp_redirect(home_url());
    exit;
}
?>

<div class="container-custom py-12">
    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden'); ?>>
        <?php if (has_post_thumbnail()): ?>
        <div class="aspect-w-16 aspect-h-9">
            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
        </div>
        <?php endif; ?>

        <div class="p-8">
            <header class="mb-8">
                <h1 class="text-4xl font-heading mb-4"><?php the_title(); ?></h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <?php
                    $date = get_post_meta(get_the_ID(), '_workshop_date', true);
                    $time = get_post_meta(get_the_ID(), '_workshop_time', true);
                    $location = get_post_meta(get_the_ID(), '_workshop_location', true);
                    $price = get_post_meta(get_the_ID(), '_workshop_price', true);
                    ?>
                    
                    <?php if ($date): ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date</h3>
                        <p class="mt-1 text-lg"><?php echo esc_html($date); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($time): ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Time</h3>
                        <p class="mt-1 text-lg"><?php echo esc_html($time); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($location): ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Location</h3>
                        <p class="mt-1 text-lg"><?php echo esc_html($location); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($price): 
                    // Get max tickets from post meta
                    $max_tickets = get_post_meta(get_the_ID(), '_max_tickets', true) ?: 10; // Default to 10 if not set
                    $remaining_tickets = get_post_meta(get_the_ID(), '_remaining_tickets', true);
                    $max_allowed = $remaining_tickets ? min($max_tickets, intval($remaining_tickets)) : $max_tickets;
                ?>
                <div class="bg-primary/5 rounded-lg p-6 mb-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-medium mb-2">Workshop Price</h3>
                            <p class="text-3xl font-bold">€<?php echo number_format($price, 2); ?></p>
                            <?php if (is_user_logged_in() && current_user_can('premium_member')): ?>
                                <?php 
                                    $discount = get_option('kb_member_discount', 25);
                                    $discounted_price = $price * (1 - ($discount/100));
                                ?>
                                <p class="text-green-600 mt-1">Premium Member Price: €<?php echo number_format($discounted_price, 2); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="quantity-selector">
                                <label for="ticket-quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button type="button" class="quantity-btn minus px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-md" aria-label="Decrease quantity">-</button>
                                    <input type="number" 
                                           id="ticket-quantity" 
                                           name="ticket_quantity" 
                                           value="1" 
                                           min="1" 
                                           max="<?php echo esc_attr($max_allowed); ?>" 
                                           class="w-16 text-center border-x border-gray-300 py-2"
                                           aria-label="Ticket quantity">
                                    <button type="button" class="quantity-btn plus px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-md" aria-label="Increase quantity">+</button>
                                </div>
                                <?php if ($remaining_tickets): ?>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo esc_html($remaining_tickets); ?> tickets remaining</p>
                                <?php endif; ?>
                            </div>
                            <button id="register-button" 
                                    class="bg-primary text-white px-8 py-3 rounded-md hover:bg-primary/90 transition-colors">
                                Register for this Workshop
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </header>

            <div class="prose max-w-none">
                <?php the_content(); ?>
            </div>

            <!-- Registration Form Modal -->
            <div id="registration-modal" class="hidden fixed inset-0 bg-black/50 z-50 backdrop-blur-sm transition-opacity duration-300">
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-heading">Register for Workshop</h2>
                                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <?php echo do_shortcode('[workshop_registration workshop_id="' . get_the_ID() . '"]'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

<?php
// Enqueue scripts
add_action('wp_enqueue_scripts', function() {
    if (is_singular('workshop')) {
        wp_enqueue_script(
            'kb-workshop-quantity',
            get_template_directory_uri() . '/assets/js/workshop-quantity.js',
            ['jquery'],
            '1.0.0',
            true
        );

        // Localize script with workshop data
        wp_localize_script('kb-workshop-quantity', 'kbWorkshop', [
            'price' => get_post_meta(get_the_ID(), '_workshop_price', true),
            'maxTickets' => get_post_meta(get_the_ID(), '_max_tickets', true) ?: 10,
            'remainingTickets' => get_post_meta(get_the_ID(), '_remaining_tickets', true),
            'isPremiumMember' => current_user_can('premium_member'),
            'memberDiscount' => get_option('kb_member_discount', 25)
        ]);
    }
});
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    const quantityInput = document.getElementById('ticket-quantity');
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');

    if (quantityInput && minusBtn && plusBtn) {
        minusBtn.addEventListener('click', () => {
            const currentVal = parseInt(quantityInput.value);
            if (currentVal > parseInt(quantityInput.min)) {
                quantityInput.value = currentVal - 1;
                quantityInput.dispatchEvent(new Event('change'));
            }
        });

        plusBtn.addEventListener('click', () => {
            const currentVal = parseInt(quantityInput.value);
            if (currentVal < parseInt(quantityInput.max)) {
                quantityInput.value = currentVal + 1;
                quantityInput.dispatchEvent(new Event('change'));
            }
        });

    quantityInput.addEventListener('change', () => {
        let val = parseInt(quantityInput.value);
        const min = parseInt(quantityInput.min);
        const max = parseInt(quantityInput.max);

        if (isNaN(val) || val < min) val = min;
        if (val > max) val = max;
        
        quantityInput.value = val;

        // Update hidden quantity input in registration form
        const hiddenQuantityInput = document.getElementById('form-ticket-quantity');
        if (hiddenQuantityInput) {
            hiddenQuantityInput.value = val;
            hiddenQuantityInput.dispatchEvent(new Event('change'));
        }
    });
    }

    const modal = document.getElementById('registration-modal');
    const modalContent = modal.querySelector('.bg-white');
    const registerButton = document.getElementById('register-button');
    const closeButton = document.getElementById('close-modal');

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        // Trigger reflow to enable transitions
        void modal.offsetWidth;
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }

    function closeModal() {
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        
        // Wait for animation to complete
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    registerButton?.addEventListener('click', openModal);
    closeButton?.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal?.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Prevent modal close when clicking modal content
    modalContent?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>

<?php
get_footer();
