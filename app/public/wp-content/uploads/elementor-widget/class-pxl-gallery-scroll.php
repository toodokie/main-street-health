<?php

class PxlGalleryScroll_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_gallery_scroll';
    protected $title = 'Case Gallery Scroll';
    protected $icon = 'eicon-image-before-after';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"gallery","label":"Gallery","type":"repeater","controls":[{"name":"title","label":"Title","type":"text","label_block":true},{"name":"imgs","label":"Images","type":"gallery","label_block":true}],"title_field":"{{{ title }}}"},{"name":"img_size","label":"Image Size","type":"text","description":"Enter image size (Example: \"thumbnail\", \"medium\", \"large\", \"full\" or other sizes defined by theme). Alternatively enter size in pixels (Default: 370x300 (Width x Height))."}]},{"name":"section_style_title","label":"Title","tab":"style","controls":[{"name":"title_color","label":"Title Color","type":"color","selectors":{"{{WRAPPER}} .pxl-gallery-scroll .pxl-item--title":"color: {{VALUE}};"}},{"name":"title_typography","label":"Title Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .pxl-gallery-scroll .pxl-item--title"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}