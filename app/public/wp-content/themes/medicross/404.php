<?php
/**
 * @package Case-Themes
 */
$subtitle_404 = medicross()->get_theme_opt('subtitle_404');
$title_404 = medicross()->get_theme_opt('title_404');
$des_404 = medicross()->get_theme_opt('des_404');
$button_404 = medicross()->get_theme_opt('button_404');
$background_404 = medicross()->get_opt( 'background_404', ['url' => get_template_directory_uri().'/assets/img/404.webp', 'id' => '' ] );
$img_404 = medicross()->get_opt( 'img_404', ['url' => get_template_directory_uri().'/assets/img/404-image.webp', 'id' => '' ] );
get_header(); ?>
<div class="wrap-content-404" >
    <div class="pxl-error-image" style="background-image:url(<?php echo esc_url($background_404['url']); ?>);"></div>
    <div class="pxl-error-inner">
        <div class="content">
            <span class="pxl-error-subtitle">
                <img src="<?php echo esc_url($img_404['url']); ?>" alt="404">
            </span>
            <h3 class="pxl-error-title">
                <?php if (!empty($title_404)) {
                    echo pxl_print_html($title_404);
                } else{
                    echo esc_html__('Page not found, return to homepage!', 'medicross'); 
                } ?>
                
            </h3>
            <p class="pxl-error-description">
                <?php if (!empty($des_404)) {
                    echo pxl_print_html($des_404);
                } else{
                    echo esc_html__('The page you are looking is not available or has been removed.Try going to HomePage by using the button below.', 'medicross');
                } ?>
            </p>
            <a class="btn-sm" href="<?php echo esc_url(home_url('/')); ?>">
                <span>
                    <?php if (!empty($button_404)) {
                        echo pxl_print_html($button_404);
                    } else{
                       echo esc_html__('back to homepage', 'medicross'); 
                    } ?>
                </span>
                <i class="flaticon-next rtl-icon"></i>
            </a>
        </div>
    </div>
</div>
<?php get_footer();
