</main><!-- End of main content -->

    <footer class="bg-primary text-white">
        <div class="container-custom py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-heading mb-6"><?php _e('Contact', 'katie-bray'); ?></h3>
                    <div class="space-y-3">
                        <p class="flex items-center text-sm">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            Madrid, Spain
                        </p>
                        <p class="flex items-center text-sm">
                            <i class="fas fa-envelope w-6"></i>
                            <?php echo antispambot('contact@katiebray.com'); ?>
                        </p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-heading mb-6"><?php _e('Quick Links', 'katie-bray'); ?></h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?php echo esc_url(get_post_type_archive_link('workshop')); ?>" 
                               class="text-sm hover:text-accent transition-colors">
                                <i class="fas fa-chevron-right w-4 text-accent"></i>
                                <?php _e('Workshops', 'katie-bray'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" 
                               class="text-sm hover:text-accent transition-colors">
                                <i class="fas fa-chevron-right w-4 text-accent"></i>
                                <?php _e('About', 'katie-bray'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" 
                               class="text-sm hover:text-accent transition-colors">
                                <i class="fas fa-chevron-right w-4 text-accent"></i>
                                <?php _e('Contact', 'katie-bray'); ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Social Links -->
                <div>
                    <h3 class="text-xl font-heading mb-6"><?php _e('Follow', 'katie-bray'); ?></h3>
                    <div class="flex space-x-4">
                        <a href="https://instagram.com" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-accent transition-colors"
                           aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://twitter.com" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-accent transition-colors"
                           aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://linkedin.com" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-accent transition-colors"
                           aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-white/10">
                <p class="text-center text-sm text-white/70">
                    © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'katie-bray'); ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- End of min-h-screen flex flex-col -->

<?php wp_footer(); ?>
</body>
</html>
