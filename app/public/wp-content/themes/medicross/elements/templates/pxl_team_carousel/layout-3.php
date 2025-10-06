<?php
/**
 * Layout 3 - MSH Team Members (Manual Entry)
 * Uses manual team member data from widget settings
 */

$col_xs = $widget->get_setting('col_xs', '1');
$col_sm = $widget->get_setting('col_sm', '2');
$col_md = $widget->get_setting('col_md', '2');
$col_lg = $widget->get_setting('col_lg', '3');
$col_xl = $widget->get_setting('col_xl', '3');
$col_xxl = $widget->get_setting('col_xxl', '3');
if($col_xxl == 'inherit') {
    $col_xxl = $col_xl;
}

$slides_to_scroll = $widget->get_setting('slides_to_scroll', '1');
$arrows = $widget->get_setting('arrows', false);
$pagination = $widget->get_setting('pagination', false);
$pagination_type = $widget->get_setting('pagination_type', 'bullets');
$pause_on_hover = $widget->get_setting('pause_on_hover', false);
$autoplay = $widget->get_setting('autoplay', false);
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite', false);
$speed = $widget->get_setting('speed', '500');
$drap = $widget->get_setting('drap', false);

$opts = [
    'slide_direction'               => 'horizontal',
    'slide_percolumn'               => 1,
    'slide_mode'                    => 'slide',
    'slides_to_show'                => (int)$col_xl,
    'slides_to_show_xxl'            => (int)$col_xxl,
    'slides_to_show_lg'             => (int)$col_lg,
    'slides_to_show_md'             => (int)$col_md,
    'slides_to_show_sm'             => (int)$col_sm,
    'slides_to_show_xs'             => (int)$col_xs,
    'slides_to_scroll'              => (int)$slides_to_scroll,
    'arrow'                         => (bool)$arrows,
    'pagination'                    => (bool)$pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => (bool)$autoplay,
    'pause_on_hover'                => (bool)$pause_on_hover,
    'pause_on_interaction'          => true,
    'delay'                         => (int)$autoplay_speed,
    'loop'                          => (bool)$infinite,
    'speed'                         => (int)$speed
];

$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);

// Get team members from widget settings (manual entry)
$team_members = $widget->get_setting('team3', []);

if (isset($team_members) && !empty($team_members) && count($team_members)) :
?>

<style>
.msh-team-layout3 .pxl-item--inner {
    background: #F6F8FA;
    border: 1px solid #E5E9ED;
    padding: 30px 25px;
    margin: 15px;
    transition: all 0.3s ease;
    border-radius: 0;
}

.msh-team-layout3 .pxl-item--inner:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.msh-team-layout3 .pxl-item--image {
    text-align: left;
    margin-bottom: 25px;
}

.msh-team-layout3 .pxl-item--image img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.msh-team-layout3 .pxl-item--holder {
    text-align: left;
}

.msh-team-layout3 .pxl-item--position {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #5CB3CC;
    margin-bottom: 8px;
    font-family: 'Source Sans Pro', sans-serif;
}

.msh-team-layout3 .pxl-item--title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 18px;
    color: #2C3E50;
    line-height: 1.2;
    font-family: 'Source Sans Pro', sans-serif;
}

.msh-team-layout3 .pxl-item--title a {
    color: #2C3E50;
    text-decoration: none;
}

.msh-team-layout3 .pxl-item--description {
    font-size: 14px;
    line-height: 1.7;
    color: #5A6C7D;
    font-family: 'Source Sans Pro', sans-serif;
}

.msh-team-layout3 .pxl-item--description p {
    margin-bottom: 15px;
}

.msh-team-layout3 .pxl-item--description p:last-child {
    margin-bottom: 0;
}

.msh-team-layout3 .pxl-swiper-arrow-next,
.msh-team-layout3 .pxl-swiper-arrow-prev {
    color: #5CB3CC;
    background: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    box-shadow: 0 3px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.msh-team-layout3 .pxl-swiper-arrow-next:hover,
.msh-team-layout3 .pxl-swiper-arrow-prev:hover {
    background: #5CB3CC;
    color: white;
}

.msh-team-layout3 .pxl-swiper-dots .pxl-swiper-pagination-bullet {
    background: #D1D8E0;
    opacity: 1;
    width: 10px;
    height: 10px;
}

.msh-team-layout3 .pxl-swiper-dots .pxl-swiper-pagination-bullet.swiper-pagination-bullet-active {
    background: #5CB3CC;
    transform: scale(1.2);
}
</style>

<div class="pxl-swiper-slider pxl-team pxl-team-carousel1 msh-team-layout3" <?php if($drap !== false) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'medicross'); ?>"<?php endif; ?>>
    <div class="pxl-carousel-inner">
        <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
            <div class="pxl-swiper-wrapper">
                <?php foreach ($team_members as $key => $member) :
                    $image_id = !empty($member['image3']['id']) ? $member['image3']['id'] : '';
                    $title = !empty($member['title3']) ? $member['title3'] : '';
                    $position = !empty($member['position3']) ? $member['position3'] : '';
                    $description = !empty($member['desc3']) ? $member['desc3'] : '';
                    $link = !empty($member['item_link3']['url']) ? $member['item_link3']['url'] : '';
                    $link_target = !empty($member['item_link3']['is_external']) ? '_blank' : '_self';
                ?>
                <div class="pxl-swiper-slide">
                    <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">

                        <?php if (!empty($image_id)) : ?>
                        <div class="pxl-item--image">
                            <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                        </div>
                        <?php endif; ?>

                        <div class="pxl-item--holder">
                            <?php if (!empty($position)) : ?>
                            <div class="pxl-item--position"><?php echo esc_html($position); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($title)) : ?>
                            <h3 class="pxl-item--title">
                                <?php if (!empty($link)) : ?>
                                    <a href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($title); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($title); ?>
                                <?php endif; ?>
                            </h3>
                            <?php endif; ?>

                            <?php if (!empty($description)) : ?>
                            <div class="pxl-item--description">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if($arrows !== false): ?>
        <div class="pxl-swiper-arrow pxl-swiper-arrow-next"><span class="pxl-icon pxli-arrow-next"></span></div>
        <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"><span class="pxl-icon pxli-arrow-prev"></span></div>
        <?php endif; ?>
        <?php if($pagination !== false): ?>
        <div class="pxl-swiper-dots"></div>
        <?php endif; ?>
    </div>
</div>

<?php
else : ?>
    <p><?php esc_html_e('No team members added yet. Please add team members in the widget settings.', 'medicross'); ?></p>
<?php endif; ?>