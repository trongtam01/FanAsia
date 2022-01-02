<?php
ArContactUsLoader::loadModel('ArContactUsConfigMenuAbstract');

class ArContactUsConfigMobileMenu extends ArContactUsConfigMenuAbstract
{   
    public function getJsonConfigKey()
    {
        return 'arcumm';
    }
    
    public function overrideUnsafeAttributes()
    {
        return array(
            'menu_width',
            'menu_style'
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'auto_open' => 0,
            'menu_popup_style' => 'popup',
            'popup_animation' => 'fadeindown',
            'sidebar_animation' => 'elastic',
            'items_animation' => 'downtoup',
            'menu_size' => 'small',
            'menu_width' => '0',
            'item_style' => 'rounded',
            'item_border_style' => 'dashed',
            'item_border_color' => 'dddddd',
            'menu_header_on' => '1',
            'menu_header' => 'How would you like to contact us?',
            'header_close' => '1',
            'header_close_bg' => '008749',
            'header_close_color' => 'ffffff',
            'menu_bg' => 'ffffff',
            'menu_color' => '3b3b3b',
            'menu_subtitle_color' => '787878',
            'menu_subtitle_hcolor' => '787878',
            'menu_hbg' => 'f0f0f0',
            'menu_hcolor' => '3b3b3b',
            'shadow_size' => '30',
            'shadow_opacity' => '0.2'
        );
    }
}
