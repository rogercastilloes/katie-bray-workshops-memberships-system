<?php
/**
 * Template part for displaying the contact form
 */
?>

<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
      method="post" 
      class="space-y-6">
    
    <?php wp_nonce_field('contact_form_submit', 'contact_nonce'); ?>
    <input type="hidden" name="action" value="contact_form_submit">

    <div>
        <label for="name" class="block text-sm font-medium mb-2">
            <?php _e('Name', 'katie-bray'); ?> <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               required 
               class="input-field"
               placeholder="<?php esc_attr_e('Your name', 'katie-bray'); ?>">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-2">
            <?php _e('Email', 'katie-bray'); ?> <span class="text-red-500">*</span>
        </label>
        <input type="email" 
               id="email" 
               name="email" 
               required 
               class="input-field"
               placeholder="<?php esc_attr_e('your.email@example.com', 'katie-bray'); ?>">
    </div>

    <div>
        <label for="subject" class="block text-sm font-medium mb-2">
            <?php _e('Subject', 'katie-bray'); ?> <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="subject" 
               name="subject" 
               required 
               class="input-field"
               placeholder="<?php esc_attr_e('What is this about?', 'katie-bray'); ?>">
    </div>

    <div>
        <label for="message" class="block text-sm font-medium mb-2">
            <?php _e('Message', 'katie-bray'); ?> <span class="text-red-500">*</span>
        </label>
        <textarea id="message" 
                  name="message" 
                  required 
                  rows="5" 
                  class="input-field"
                  placeholder="<?php esc_attr_e('Your message...', 'katie-bray'); ?>"></textarea>
    </div>

    <button type="submit" 
            class="w-full px-8 py-3 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors">
        <?php _e('Send Message', 'katie-bray'); ?>
    </button>

    <?php
    // Display form success/error messages if any
    if (isset($_GET['status'])) {
        $message_class = 'p-4 rounded-md mt-6 ';
        $message = '';

        if ($_GET['status'] === 'success') {
            $message_class .= 'bg-green-50 text-green-800';
            $message = __('Thank you for your message! We\'ll get back to you soon.', 'katie-bray');
        } elseif ($_GET['status'] === 'error') {
            $message_class .= 'bg-red-50 text-red-800';
            $message = __('There was an error sending your message. Please try again.', 'katie-bray');
        }

        if ($message) {
            echo '<div class="' . esc_attr($message_class) . '">' . esc_html($message) . '</div>';
        }
    }
    ?>
</form>
