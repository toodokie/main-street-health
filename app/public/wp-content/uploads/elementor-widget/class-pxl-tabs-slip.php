<?php

class PxlTabsSlip_Widget extends Pxltheme_Core_Widget_Base{
    protected $name = 'pxl_tabs_slip';
    protected $title = 'BR Tabs Slip';
    protected $icon = 'eicon-tabs';
    protected $categories = array( 'pxltheme-core' );
    protected $params = '{"sections":[{"name":"section_layout","label":"Layout","tab":"layout","controls":[{"name":"layout","label":"Templates","type":"layoutcontrol","default":"1","options":{"1":{"label":"Layout 1","image":"https:\/\/main-street-health.local\/wp-content\/themes\/medicross\/elements\/templates\/pxl_tabs_slip\/layout-image\/layout1.jpg"}}}]},{"name":"tab_content","label":"Tabs","tab":"content","controls":[{"name":"tabs","label":"Content","type":"repeater","controls":[{"name":"content_template","label":"Select Templates","type":"select","options":{"0":"None","1753":"Tab - VR1","1756":"Tab - VR3","1755":"Tab - VR2","6595":"Tab - Mission","6981":"Tab - Dental Care","7230":"Tab - Pharmacology","7231":"Tab - Orthopedics","7232":"Tab - Hematology","7233":"Tab - Neurology","7234":"Tab - Arrhythmia","7235":"Tab - Atherosclerosis","7236":"Tab - Cardiomyopathy","7237":"Tab - Plastic Surgery","7238":"Tab - Ophthalmology","7690":"Tab - Post 1","7706":"Tab \u2013 Post 2","7707":"Tab \u2013 Post 3"},"default":"df","description":"Add new tab template: \"<a href=\"https:\/\/main-street-health.local\/wp-admin\/edit.php?post_type=pxl-template\" target=\"_blank\">Click Here<\/a>\""}]}]}]}';
    protected $styles = array(  );
    protected $scripts = array( 'medicross-tabs' );
}