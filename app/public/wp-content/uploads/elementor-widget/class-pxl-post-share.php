<?php

class PxlPostShare_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_post_share';
    protected $title = 'BR Share Post';
    protected $icon = 'eicon-tabs';
    protected $categories = array(  );
    protected $params = '{"sections":[{"name":"section_layout","label":"Content","tab":"content","controls":[{"name":"share_fb","label":"Facebook","type":"switcher"},{"name":"share_tw","label":"Twitter \/ X","type":"switcher"},{"name":"share_linked","label":"Linkedin","type":"switcher"},{"name":"share_skype","label":"Pinterest","type":"switcher"}]},{"name":"section_style_tm","label":"Style","tab":"style","controls":[{"name":"typography","label":"Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .pxl-post-share a"},{"name":"color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .pxl-post-share a":"color: {{VALUE}};"}},{"name":"color_hv","label":"Color Hover","type":"color","selectors":{"{{WRAPPER}} .pxl-post-share a:hover":"color: {{VALUE}} !important;"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}