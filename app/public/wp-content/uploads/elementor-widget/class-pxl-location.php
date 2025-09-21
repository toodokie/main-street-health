<?php

class PxlLocation_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_location';
    protected $title = 'Case Location';
    protected $icon = 'eicon-settings';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_layout","label":"Layout","tab":"layout","controls":[{"name":"layout","label":"Templates","type":"layoutcontrol","default":"1","options":{"1":{"label":"Layout 1","image":"https:\/\/main-street-health.local\/wp-content\/themes\/medicross\/elements\/widgets\/img-layout\/pxl_location\/layout1.jpg"}}}]},{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"img","label":"Image","type":"media"},{"name":"title","label":"Title","type":"text","label_block":true},{"name":"sub_title","label":"Sub Title","type":"textarea"},{"name":"lists","label":"List","type":"repeater","controls":[{"name":"content","label":"Content","type":"textarea","rows":10,"show_label":false}],"title_field":"{{{ content }}}"},{"name":"btn_text","label":"Button Text","type":"text","label_block":true},{"name":"btn_link","label":"Button Link","type":"url","label_block":true}]},{"name":"section_style","label":"Style","tab":"style","controls":[{"name":"title_color","label":"Popular Color","type":"color","selectors":{"{{WRAPPER}} .pxl-location .pxl-item--popular":"color: {{VALUE}};"}},{"name":"title_typography","label":"Typography","type":"typography","control_type":"group","selector":"{{WRAPPER}} .pxl-button .btn"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}