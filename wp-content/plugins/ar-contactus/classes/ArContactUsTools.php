<?php
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH.'wp-includes/l10n.php');

class ArContactUsTools 
{
    public static function escJsString($value, $nl2br = false)
    {
        $value = esc_js($value);
        //$value = nl2br($value);
        //$value = str_replace(array("\n", "\r"), '', $value);
        if ($nl2br) {
            $value = str_replace(array('\n'), '<br/>', $value);
        }
        
        return $value;
    }
    
    public static function isWPML()
    {
        return is_plugin_active('sitepress-multilingual-cms/sitepress.php');
    }
    
    public static function getLanguages()
    {
        return apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
    }
    
    public static function getDefaultLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_default_language', null);
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
    
    public static function getCurrentLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_current_language', null);
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
}
