<?php
/**
 * The main template file
 */

get_header();
?>

<div class="hero" style="background-image: url('<?php echo esc_url(get_theme_file_uri('assets/images/hero-bg.jpg')); ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading mb-6">
            <?php _e('Welcome to Katie Bray\'s Creative World', 'katie-bray'); ?>
        </h1>
        <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto">
            <?php _e('Join our creative illustration workshops in Madrid and explore your artistic potential.', 'katie-bray'); ?>
        </p>
        <a href="<?php echo esc_url(get_post_type_archive_link('workshop')); ?>" 
           class="inline-block px-8 py-3 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors">
            <?php _e('Explore Workshops', 'katie-bray'); ?>
        </a>
    </div>
</div>

<section class="py-20 bg-white">
    <div class="container-custom">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-heading text-center mb-12">
            <?php _e('Featured Workshops', 'katie-bray'); ?>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $workshops_query = new WP_Query(array(
                'post_type' => 'workshop',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if ($workshops_query->have_posts()) :
                while ($workshops_query->have_posts()) : $workshops_query->the_post();
            ?>
                <article class="workshop-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <?php the_post_thumbnail('large', array('class' => 'object-cover w-full h-full')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-6">
                        <h3 class="text-xl font-heading mb-2">
                            <?php the_title(); ?>
                        </h3>
                        <div class="text-secondary mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" 
                           class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            <?php _e('Learn More', 'katie-bray'); ?>
                        </a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="text-center mt-12">
            <a href="<?php echo esc_url(get_post_type_archive_link('workshop')); ?>" 
               class="inline-block px-8 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors rounded-md">
                <?php _e('View All Workshops', 'katie-bray'); ?>
            </a>
        </div>
    </div>
</section>

<section class="py-20 bg-background">
    <div class="container-custom">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-heading mb-6">
                <?php _e('About Katie Bray', 'katie-bray'); ?>
            </h2>
            <p class="text-lg text-secondary mb-8">
                <?php _e('Discover the story behind the artist and workshop host who\'s passionate about sharing creative knowledge and inspiration.', 'katie-bray'); ?>
            </p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" 
               class="inline-block px-8 py-3 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                <?php _e('Learn More About Me', 'katie-bray'); ?>
            </a>
        </div>
    </div>
</section>

<?php
get_footer();
