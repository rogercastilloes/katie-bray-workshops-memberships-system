<?php
/**
 * Base email template
 *
 * @var string $content Email content
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?></title>
</head>
<body style="
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    color: #212529;
">
    <table cellpadding="0" 
           cellspacing="0" 
           border="0" 
           width="100%" 
           style="background-color: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table cellpadding="0" 
                       cellspacing="0" 
                       border="0" 
                       width="600" 
                       style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px;">
                            <?php if (has_custom_logo()) :
                                $custom_logo_id = get_theme_mod('custom_logo');
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            ?>
                                <img src="<?php echo esc_url($logo[0]); ?>" 
                                     alt="<?php echo get_bloginfo('name'); ?>" 
                                     style="max-width: 200px; height: auto;">
                            <?php else : ?>
                                <h1 style="
                                    margin: 0;
                                    font-size: 24px;
                                    font-weight: bold;
                                    color: #212529;
                                "><?php echo get_bloginfo('name'); ?></h1>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <?php echo $content; ?>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="
                            padding: 40px;
                            background-color: #f8f9fa;
                            border-bottom-left-radius: 8px;
                            border-bottom-right-radius: 8px;
                            font-size: 14px;
                            color: #6c757d;
                            text-align: center;
                        ">
                            <p style="margin: 0 0 10px;">
                                <?php echo get_bloginfo('name'); ?>
                                <br>
                                <?php echo nl2br(get_option('kb_company_address')); ?>
                            </p>

                            <p style="margin: 0;">
                                <a href="<?php echo home_url(); ?>" 
                                   style="color: #007bff; text-decoration: none;">
                                    <?php echo home_url(); ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
