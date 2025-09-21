<?php

class PxlPostNavigation_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_post_navigation';
    protected $title = 'Case  Post Navigation';
    protected $icon = 'eicon-navigation-horizontal';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_content","label":"Content","tab":"content","controls":[{"name":"type","label":"Type","type":"select","default":"pagination","options":{"navigation":"Navigation"}},{"name":"link_grid_page","label":"Link Gird Page","type":"text","default":"#"}]}]}';
    protected $styles = array(  );
    protected $scripts = array(  );
}