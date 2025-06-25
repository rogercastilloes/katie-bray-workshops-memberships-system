<?php
/**
 * Template part for displaying workshop content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('workshop-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="aspect-w-16 aspect-h-9">
            <?php the_post_thumbnail('large', array('class' => 'object-cover w-full h-full')); ?>
        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <header class="entry-header">
            <?php
            if (is_singular()) :
                the_title('<h1 class="text-2xl font-heading mb-4">', '</h1>');
            else :
                the_title('<h2 class="text-xl font-heading mb-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;
            ?>
        </header>

        <div class="entry-content text-secondary mb-4">
            <?php
            if (is_singular()) :
                the_content();
            else :
                the_excerpt();
            endif;
            ?>
        </div>

        <?php if (!is_singular()) : ?>
            <div class="flex items-center justify-between">
                <?php 
                $workshop_date = get_post_meta(get_the_ID(), 'workshop_date', true);
                if ($workshop_date) : 
                ?>
                    <span class="text-sm text-secondary">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($workshop_date))); ?>
                    </span>
                <?php endif; ?>
                
                <a href="<?php the_permalink(); ?>" 
                   class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    <?php _e('Learn More', 'katie-bray'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>
