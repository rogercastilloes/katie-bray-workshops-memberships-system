<?php
/**
 * Template Name: Premium Dashboard
 * 
 * This is the template that displays the premium member dashboard.
 */

// Redirect non-logged in users to login page
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header();
?>

<div class="bg-background min-h-screen py-12">
    <div class="container-custom">
        <!-- Dashboard Header -->
        <header class="mb-12">
            <h1 class="text-4xl font-heading mb-4">
                <?php _e('Welcome back', 'katie-bray'); ?>, 
                <?php echo esc_html(wp_get_current_user()->display_name); ?>!
            </h1>
            <?php if (katie_bray_user_has_premium_access()): ?>
                <div class="inline-block bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium">
                    <?php _e('Premium Member', 'katie-bray'); ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Upcoming Workshops -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-2xl font-heading"><?php _e('Your Upcoming Workshops', 'katie-bray'); ?></h2>
                    </div>
                    <div class="p-6">
                        <?php
                        $user_workshops = get_posts(array(
                            'post_type' => 'workshop',
                            'posts_per_page' => 5,
                            'meta_query' => array(
                                array(
                                    'key' => '_workshop_attendees',
                                    'value' => get_current_user_id(),
                                    'compare' => 'LIKE'
                                ),
                                array(
                                    'key' => '_workshop_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            ),
                            'orderby' => 'meta_value',
                            'meta_key' => '_workshop_date',
                            'order' => 'ASC'
                        ));

                        if ($user_workshops): ?>
                            <div class="space-y-4">
                                <?php foreach ($user_workshops as $workshop): ?>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h3 class="font-medium mb-1">
                                                <?php echo esc_html($workshop->post_title); ?>
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                <?php 
                                                $date = get_post_meta($workshop->ID, '_workshop_date', true);
                                                $time = get_post_meta($workshop->ID, '_workshop_time', true);
                                                echo esc_html(date_i18n(get_option('date_format'), strtotime($date)));
                                                if ($time) echo ' at ' . esc_html($time);
                                                ?>
                                            </p>
                                        </div>
                                        <a href="<?php echo get_permalink($workshop->ID); ?>" 
                                           class="text-primary hover:text-primary/80 transition-colors">
                                            <?php _e('View Details', 'katie-bray'); ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600">
                                <?php _e('You have no upcoming workshops.', 'katie-bray'); ?>
                                <a href="<?php echo get_post_type_archive_link('workshop'); ?>" 
                                   class="text-primary hover:text-primary/80 transition-colors">
                                    <?php _e('Browse available workshops', 'katie-bray'); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Premium Resources -->
                <?php if (katie_bray_user_has_premium_access()): ?>
                    <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b">
                            <h2 class="text-2xl font-heading"><?php _e('Premium Resources', 'katie-bray'); ?></h2>
                        </div>
                        <div class="p-6">
                            <?php
                            $resources = new WP_Query(array(
                                'post_type' => 'attachment',
                                'posts_per_page' => -1,
                                'post_status' => 'inherit',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'premium_category',
                                        'field' => 'slug',
                                        'terms' => 'premium-resource'
                                    )
                                )
                            ));

                            if ($resources->have_posts()): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php while ($resources->have_posts()): $resources->the_post(); ?>
                                        <div class="p-4 border rounded-lg hover:border-primary transition-colors">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <?php 
                                                    $mime_type = get_post_mime_type();
                                                    $icon_class = 'fa-file';
                                                    
                                                    if (strpos($mime_type, 'pdf') !== false) {
                                                        $icon_class = 'fa-file-pdf';
                                                    } elseif (strpos($mime_type, 'image') !== false) {
                                                        $icon_class = 'fa-file-image';
                                                    } elseif (strpos($mime_type, 'video') !== false) {
                                                        $icon_class = 'fa-file-video';
                                                    }
                                                    ?>
                                                    <i class="far <?php echo esc_attr($icon_class); ?> text-2xl text-primary"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium mb-1"><?php the_title(); ?></h3>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <?php echo esc_html(size_format(filesize(get_attached_file(get_the_ID())))); ?>
                                                    </p>
                                                    <a href="<?php echo esc_url(wp_get_attachment_url(get_the_ID())); ?>" 
                                                       class="inline-flex items-center text-sm text-primary hover:text-primary/80 transition-colors"
                                                       download>
                                                        <i class="fas fa-download mr-2"></i>
                                                        <?php _e('Download', 'katie-bray'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-600">
                                    <?php _e('No premium resources available yet.', 'katie-bray'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Membership Status -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-2xl font-heading"><?php _e('Membership', 'katie-bray'); ?></h2>
                    </div>
                    <div class="p-6">
                        <?php if (katie_bray_user_has_premium_access()): 
                            $subscription = get_user_meta(get_current_user_id(), '_stripe_subscription_data', true);
                        ?>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1"><?php _e('Status', 'katie-bray'); ?></p>
                                    <p class="font-medium text-green-600">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <?php _e('Active Premium Member', 'katie-bray'); ?>
                                    </p>
                                </div>
                                <?php if ($subscription): ?>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1"><?php _e('Next Payment', 'katie-bray'); ?></p>
                                        <p class="font-medium">
                                            <?php 
                                            $next_payment = isset($subscription['current_period_end']) 
                                                ? date_i18n(get_option('date_format'), $subscription['current_period_end']) 
                                                : __('N/A', 'katie-bray');
                                            echo esc_html($next_payment);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1"><?php _e('Benefits', 'katie-bray'); ?></p>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-percentage w-5 text-primary"></i>
                                            <?php printf(
                                                __('%d%% discount on all workshops', 'katie-bray'),
                                                get_option('katie_bray_membership_discount', 25)
                                            ); ?>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-unlock w-5 text-primary"></i>
                                            <?php _e('Access to premium resources', 'katie-bray'); ?>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <i class="fas fa-comments w-5 text-primary"></i>
                                            <?php _e('Priority support via chat', 'katie-bray'); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <p class="mb-4"><?php _e('Upgrade to Premium for exclusive benefits!', 'katie-bray'); ?></p>
                                <a href="#" 
                                   class="inline-block w-full px-6 py-3 bg-primary text-white text-center rounded-md hover:bg-primary/90 transition-colors">
                                    <?php _e('Upgrade Now', 'katie-bray'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Mini Chat -->
                <?php if (katie_bray_user_has_premium_access()): ?>
                    <section class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b">
                            <h2 class="text-2xl font-heading"><?php _e('Support Chat', 'katie-bray'); ?></h2>
                        </div>
                        <div class="p-6">
                            <div id="chat-messages" class="h-64 overflow-y-auto mb-4 space-y-4">
                                <!-- Messages will be loaded here via AJAX -->
                            </div>
                            <form id="chat-form" class="space-y-4">
                                <?php wp_nonce_field('kb_chat_message', 'kb_chat_nonce'); ?>
                                <textarea name="message" 
                                          class="w-full px-4 py-2 border rounded-md resize-none focus:border-primary focus:ring-1 focus:ring-primary"
                                          rows="3"
                                          placeholder="<?php esc_attr_e('Type your message...', 'katie-bray'); ?>"
                                          required></textarea>
                                <button type="submit" 
                                        class="w-full px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                                    <?php _e('Send Message', 'katie-bray'); ?>
                                </button>
                            </form>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (katie_bray_user_has_premium_access()): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    
    // Load initial messages
    loadMessages();
    
    // Set up periodic refresh
    setInterval(loadMessages, 30000); // Refresh every 30 seconds
    
    // Handle form submission
    chatForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(chatForm);
        formData.append('action', 'kb_send_chat_message');
        
        fetch(katieBrayAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatForm.reset();
                loadMessages();
            } else {
                console.error('Error sending message:', data.data);
            }
        })
        .catch(error => console.error('Error:', error));
    });
    
    function loadMessages() {
        fetch(katieBrayAjax.ajaxurl + '?action=kb_get_chat_messages&_wpnonce=' + katieBrayAjax.nonce)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chatMessages.innerHTML = data.data.messages.map(message => `
                        <div class="flex gap-4 ${message.is_from_admin ? 'flex-row-reverse' : ''}">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas ${message.is_from_admin ? 'fa-headset' : 'fa-user'}"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="bg-gray-100 rounded-lg p-3 ${message.is_from_admin ? 'rounded-tr-none' : 'rounded-tl-none'}">
                                    <p class="text-sm">${message.message}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">${message.date}</p>
                            </div>
                        </div>
                    `).join('');
                    
                    // Scroll to bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>
<?php endif; ?>

<?php
get_footer();
