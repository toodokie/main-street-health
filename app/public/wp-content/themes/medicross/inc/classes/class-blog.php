<?php
if (!class_exists('Medicross_Blog')) {
    class Medicross_Blog
    {

        public function get_archive_meta($post_id = 0) {
            $archive_category = medicross()->get_theme_opt( 'archive_category', true );
            $post_comments_on = medicross()->get_theme_opt('post_comments_on', true);
            $archive_author = medicross()->get_theme_opt( 'archive_author', true );
            if($archive_author || $archive_category || $post_comments_on) : ?>
                <div class="post-metas">
                    <div class="meta-inner ">
                        <?php if($archive_author) : ?>
                            <span class="post-author  ">
                                <span class="icon-post"><i class="flaticon-user"></i></span>
                                <span><?php echo esc_html__('By', 'medicross'); ?> <?php the_author_posts_link(); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if($archive_category && has_category('', $post_id)) : ?>
                            <span class="post-category">
                                <span class="icon-post"><i class="flaticon-tag"></i></span>
                                <span><?php the_terms( $post_id, 'category', '', ', ', '' ); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if($post_comments_on) : ?>
                            <span class="post-comments  ">
                                <a href="<?php echo get_comments_link($post_id); ?>">
                                    <span class="icon-post"><i class="flaticon-speech-bubble"></i></span>
                                    <span><?php comments_number(esc_html__('No Comments', 'medicross'), esc_html__(' 1 Comment', 'medicross'), esc_html__('%  Comments', 'medicross'), $post_id); ?></span>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; 
        }

        public function get_archive_meta_2($post_id = 0) { ?>
            <div class="post-metas-2">
                <div class="meta-inner ">
                    <span class="post-date-category">
                        <span class="post-date-post"><?php echo get_the_date('d M'); ?> </span>
                        <span><?php the_terms( $post_id, 'category', '', ', ', '' ); ?></span>
                    </span>
                </div>
            </div>
        <?php }

        public function get_excerpt( $length = 55 ){
            $pxl_the_excerpt = get_the_excerpt();
            if(!empty($pxl_the_excerpt)) {
                echo esc_html($pxl_the_excerpt);
            } else {
                echo wp_kses_post($this->get_excerpt_more( $length ));
            }
        }

        public function get_excerpt_more( $length = 55, $post = null ) {
            $post = get_post( $post );

            if ( empty( $post ) || 0 >= $length ) {
                return ''; 
            }

            if ( post_password_required( $post ) ) {
                return esc_html__( 'Post password required.', 'medicross' );
            }

            $content = apply_filters( 'the_content', strip_shortcodes( $post->post_content ) );
            $content = str_replace( ']]>', ']]&gt;', $content );

            $excerpt_more = apply_filters( 'medicross_excerpt_more', '&hellip;' );
            $excerpt      = wp_trim_words( $content, $length, $excerpt_more );

            return $excerpt;
        }

        public function get_post_metas(){
            $post_author_on = medicross()->get_theme_opt('post_author_on', true);
            $post_date_on = medicross()->get_theme_opt('post_date_on', true);
            $post_comments_on = medicross()->get_theme_opt('post_comments_on', true);
            $post_categories_on = medicross()->get_theme_opt('post_categories_on', true);
            if ($post_author_on || $post_date_on || $post_categories_on || $post_comments_on) : ?>
                <div class="post-metas">
                    <div class="meta-inner align-items-center">
                        <?php if($post_author_on) : ?>
                            <span class="post-author  ">
                                <span class="icon-post"><i class="flaticon-user"></i></span>
                                <span><?php echo esc_html__('By', 'medicross'); ?> <?php the_author_posts_link(); ?></span>
                            </span>
                        </span>
                    <?php endif; ?>
                    
                    <?php if($post_date_on) : ?>
                        <span class="pxl-item--date">
                            <span class="icon-post"><i class="fas fa-calendar"></i> </span>
                            <?php echo get_the_date('d M y'); ?>
                        </span>
                    <?php endif; ?>

                    <?php if($post_categories_on && has_category()) : ?>
                        <span class="post-category  align-items-center">
                            <span class="icon-post"><i class="flaticon-tag"></i></span>
                            <?php the_terms(get_the_ID(), 'category', '', ', '); ?> 
                        </span>
                    <?php endif; ?>
                    
                    <?php if($post_comments_on) : ?>
                        <span class="post-comments  align-items-center">
                            <span class="icon-post"><i class="caseicon-comment-solid"></i></span>
                            <a href="<?php comments_link(); ?>">
                                <span><?php comments_number(esc_html__('No Comments', 'medicross'), esc_html__(' 1 Comment', 'medicross'), esc_html__('%  Comments', 'medicross')); ?></span>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif;
    }

    public function medicross_set_post_views( $postID ) {
        $countKey = 'post_views_count';
        $count    = get_post_meta( $postID, $countKey, true );
        if ( $count == '' ) {
            $count = 0;
            delete_post_meta( $postID, $countKey );
            add_post_meta( $postID, $countKey, '0' );
        } else {
            $count ++;
            update_post_meta( $postID, $countKey, $count );
        }
    }

    public function get_post_tags(){
        $post_tag = medicross()->get_theme_opt( 'post_tag', true );
        if($post_tag != '1') return;
        $tags_list = get_the_tag_list();
        if ( $tags_list ){
            echo '<div class="post-tags ">';
            printf('%2$s', '', $tags_list);
            echo '</div>';
        }
    }

    public function get_post_share($post_id = 0) {
        $post_social_share = medicross()->get_theme_opt( 'post_social_share', false );
        $share_icons = medicross()->get_theme_opt( 'post_social_share_icon', [] );
        $social_facebook = medicross()->get_theme_opt( 'social_facebook', [] );
        $social_twitter = medicross()->get_theme_opt( 'social_twitter', [] );
        $social_pinterest = medicross()->get_theme_opt( 'social_pinterest', [] );
        $social_linkedin = medicross()->get_theme_opt( 'social_linkedin', [] );
        if($post_social_share != '1') return;
        $post = get_post($post_id);
        ?>
        <div class="post-shares align-items-center">
            <span class="label"><i class="fas fa-share-alt"></i> <?php echo esc_html__('Share', 'medicross'); ?> </span>
            <div class="social-share">
                <div class="social">
                    <?php if($social_facebook): ?>
                        <a class="pxl-icon " title="<?php echo esc_attr__('Facebook', 'medicross'); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink($post_id)); ?>">
                            <i class="caseicon-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if($social_twitter): ?>
                        <a class="pxl-icon " title="<?php echo esc_attr__('Twitter', 'medicross'); ?>" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<?php echo urldecode(home_url('/')); ?>&url=<?php echo urlencode(get_the_permalink($post_id)); ?>&text=<?php the_title();?>%20">
                            <span class="caseicon-twitter"></span>
                        </a>
                    <?php endif; ?>
                    <?php if($social_pinterest): ?>
                        <a class="pxl-icon " title="<?php echo esc_attr__('Pinterest', 'medicross'); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_the_post_thumbnail_url($post_id, 'full')); ?>&media=&description=<?php echo urlencode(the_title_attribute(array('echo' => false, 'post' => $post))); ?>">
                            <i class="caseicon-pinterest"></i>
                        </a>
                    <?php endif; ?>
                    <?php if($social_linkedin): ?>
                        <a class="pxl-icon " title="<?php echo esc_attr__('Linkedin', 'medicross'); ?>" target="_blank" href="https://www.linkedin.com/cws/share?url=<?php echo urlencode(get_the_permalink($post_id));?>">
                            <i class="caseicon-linkedin"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_post_nav() {
        $post_navigation = medicross()->get_theme_opt( 'post_navigation', false );
        if($post_navigation != '1') return;
        global $post;

        $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous )
            return;
        ?>
        <?php
        $next_post = get_next_post();
        $previous_post = get_previous_post();
        if(empty($previous_post) && empty($next_post)) return;

        ?>
        <div class="single-next-prev-nav row gx-0 justify-content-between align-items-center">
            <?php if(!empty($previous_post)): 
                $prev_img_id = get_post_thumbnail_id($previous_post->ID);
                $prev_img_url = wp_get_attachment_image_src($prev_img_id, 'thumbnail');

                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $prev_img_id,
                    'thumb_size' => '60x52',
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div class="nav-next-prev prev col relative text-start">
                    <div class="nav-inner">
                        <?php previous_post_link('%link',''); ?>
                        <div class="nav-label-wrap  align-items-center">
                            <span class="nav-icon pxli-angle-left"></span>
                            <span class="nav-label"><?php echo esc_html__('Previous Post', 'medicross'); ?></span>
                        </div>
                        <div class="nav-title-wrap  align-items-center d-none d-sm-flex">
                            <div class="nav-title"><?php echo get_the_title($previous_post->ID); ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="grid-archive">
                <a href="<?php echo get_post_type_archive_link( 'post' ); ?>">
                    <div class="nav-archive-button">
                        <div class="archive-btn-square square-1"></div>
                        <div class="archive-btn-square square-2"></div>
                        <div class="archive-btn-square square-3"></div>
                        <div class="archive-btn-square square-4"></div>
                    </div>
                </a>
            </div>
            <?php if(!empty($next_post)) : 
                $next_img_id = get_post_thumbnail_id($next_post->ID);
                $next_img_url = wp_get_attachment_image_src($next_img_id, 'thumbnail');

                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $next_img_id,
                    'thumb_size' => '60x52',
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div class="nav-next-prev next col relative text-end">
                    <div class="nav-inner">
                        <?php next_post_link('%link',''); ?>
                        <div class="nav-label-wrap  align-items-center justify-content-end">
                            <span class="nav-label"><?php echo esc_html__('Newer Post', 'medicross'); ?></span>
                            <span class="nav-icon pxli-angle-right"></span>
                        </div>
                        <div class="nav-title-wrap  align-items-center d-none d-sm-flex">
                            <div class="nav-title"><?php echo get_the_title($next_post->ID); ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div> 
        <?php  
    }
    public function get_project_nav() {
        global $post;
        $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );
        $link_grid = medicross()->get_theme_opt( 'link_grid', '' );
        if ( ! $next && ! $previous )
            return;
        ?>
        <?php
        $next_post = get_next_post();
        $previous_post = get_previous_post();

        if( !empty($next_post) || !empty($previous_post) ) { 
            ?>
            <div class="pxl-project--navigation">
                <div class="pxl--items">
                    <div class="pxl--item pxl--item-prev">
                        <?php if ( is_a( $previous_post , 'WP_Post' ) && get_the_title( $previous_post->ID ) != '') { 
                            ?>
                            <a  href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>"><i class="far fa-arrow-left"></i>Prev Project</a>
                        <?php } ?>
                    </div>
                    <div class="pxl--item pxl--item-grid">
                        <?php if (!empty($link_grid)) { ?>
                            <a  href="<?php echo esc_url($link_grid); ?>">
                                <span class="bl bl1"></span>
                                <span class="bl bl2"></span>
                                <span class="bl bl3"></span>
                                <span class="bl bl4"></span>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="pxl--item pxl--item-next">
                        <?php if ( is_a( $next_post , 'WP_Post' ) && get_the_title( $next_post->ID ) != '') {
                            ?>
                            <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>">Next Project <i class="far fa-arrow-right"></i> </a>
                        <?php } ?>
                    </div>
                </div><!-- .nav-links -->
            </div>
        <?php }
    }
    public function get_related_post(){
        $post_related_on = medicross()->get_theme_opt( 'post_related_on', false );

        if($post_related_on) {
            global $post;
            $current_id = $post->ID;
            $posttags = get_the_category($post->ID);
            if (empty($posttags)) return;

            $tags = array();

            foreach ($posttags as $tag) {

                $tags[] = $tag->term_id;
            }
            $post_number = '6';
            $query_similar = new WP_Query(array('posts_per_page' => $post_number, 'post_type' => 'post', 'post_status' => 'publish', 'category__in' => $tags));
            if (count($query_similar->posts) > 1) {
                wp_enqueue_script( 'swiper' );
                wp_enqueue_script( 'medicross-swiper' );
                $opts = [
                    'slide_direction'               => 'horizontal',
                    'slide_percolumn'               => '1', 
                    'slide_mode'                    => 'slide', 
                    'slides_to_show'                => 3, 
                    'slides_to_show_lg'             => 3, 
                    'slides_to_show_md'             => 2, 
                    'slides_to_show_sm'             => 2, 
                    'slides_to_show_xs'             => 1, 
                    'slides_to_scroll'              => 1, 
                    'slides_gutter'                 => 30, 
                    'arrow'                         => false,
                    'dots'                          => true,
                    'dots_style'                    => 'bullets'
                ];
                $data_settings = wp_json_encode($opts);
                $dir           = is_rtl() ? 'rtl' : 'ltr';
                ?>
                <div class="pxl-related-post">
                    <h3 class="widget-title"><?php echo esc_html__('Related Posts', 'medicross'); ?></h3>
                    <div class="class" data-settings="<?php echo esc_attr($data_settings) ?>" data-rtl="<?php echo esc_attr($dir) ?>">
                        <div class="pxl-related-post-inner pxl-swiper-wrapper swiper-wrapper">
                            <?php foreach ($query_similar->posts as $post):
                                $thumbnail_url = '';
                                if (has_post_thumbnail(get_the_ID()) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) :
                                    $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medicross-blog-small', false);
                            endif;
                            if ($post->ID !== $current_id) : ?>
                                <div class="pxl-swiper-slide swiper-slide grid-item">
                                    <div class="grid-item-inner">
                                        <?php if (has_post_thumbnail()) { ?>
                                            <div class="item-featured">
                                                <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumbnail_url[0]); ?>" /></a>
                                            </div>
                                        <?php } ?>
                                        <h3 class="item-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </div>
        <?php }
    }

    wp_reset_postdata();
}
}

}