<?php

class PxlArrowCarousel_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_arrow_carousel';
    protected $title = 'Case Nav Carousel';
    protected $icon = 'eicon-animation';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"style_section","label":"Style","tab":"content","controls":[{"name":"style","label":"Style","type":"select","options":{"style-1":"Style 1","style-2":"Style 2","style-3":"Style 3"},"default":"style-1"},{"name":"color","label":"Color","type":"color","selectors":{"{{WRAPPER}} .pxl-navigation-arrow":"color: {{VALUE}} !important;"}},{"name":"color_hover","label":"Color Hover","type":"color","selectors":{"{{WRAPPER}} .pxl-navigation-arrow:hover ":"color: {{VALUE}} !important;"}},{"name":"bg_color","label":"Background Color","type":"color","selectors":{"{{WRAPPER}} .pxl-navigation-arrow ":"background-color: {{VALUE}} !important;"}},{"name":"bg_color_hv","label":"Background Color Hover","type":"color","selectors":{"{{WRAPPER}} .pxl-navigation-arrow:hover ":"background-color: {{VALUE}} !important;"}},{"name":"fs_ic","label":"Font Size Icon","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .pxl-navigation-arrow":"font-size: {{SIZE}}{{UNIT}};"}},{"name":"bs_ic","label":"Box Size","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .pxl-navigation-arrow":"width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}