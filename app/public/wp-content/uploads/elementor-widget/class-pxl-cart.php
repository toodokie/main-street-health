<?php

class PxlCart_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_cart';
    protected $title = ' BR Cart';
    protected $icon = 'eicon-cart-medium';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"source_section","label":"Source Settings","tab":"content","controls":[{"name":"icon_image_type","label":"Icon Image Type","type":"select","options":{"img":"Image","ic":"Icon"},"default":"img"},{"name":"pxl_icon","label":"Icon","type":"icons","fa4compatibility":"icon","condition":{"icon_image_type":["ic"]}},{"name":"image","label":"Icon Image","type":"media","condition":{"icon_image_type":["img"]}},{"name":"icon_color","label":"Icon Color","type":"color","selectors":{"{{WRAPPER}} .pxl-cart-sidebar-button":"color: {{VALUE}};"}},{"name":"icon_color_hover","label":"Icon Color Hover","type":"color","selectors":{"{{WRAPPER}} .pxl-cart-sidebar-button:hover":"color: {{VALUE}};"}},{"name":"box_color","label":"Box Color","type":"color","selectors":{"{{WRAPPER}} .pxl-cart-sidebar-button":"background-color: {{VALUE}};"}},{"name":"box_height","label":"Box Height","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .pxl-cart-sidebar-button":"height: {{SIZE}}{{UNIT}};"}},{"name":"box_width","label":"Box Width","type":"slider","control_type":"responsive","size_units":["px"],"range":{"px":{"min":0,"max":300}},"selectors":{"{{WRAPPER}} .pxl-cart-sidebar-button":"width: {{SIZE}}{{UNIT}};"}}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}