<?php
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadModel('ArContactUsConfigGeneral');
ArContactUsLoader::loadModel('ArContactUsConfigButton');
ArContactUsLoader::loadModel('ArContactUsConfigMobileButton');
ArContactUsLoader::loadModel('ArContactUsConfigMenu');
ArContactUsLoader::loadModel('ArContactUsConfigMobileMenu');
ArContactUsLoader::loadModel('ArContactUsConfigPopup');
ArContactUsLoader::loadModel('ArContactUsConfigPrompt');
ArContactUsLoader::loadModel('ArContactUsConfigMobilePrompt');
ArContactUsLoader::loadModel('ArContactUsConfigLiveChat');

abstract class ArContactUsAbstract
{
    public $generalConfig;
    public $mobileButtonConfig;
    
    /**
     * 
     * @return ArContactUsConfigGeneral
     */
    public function getGeneralConfig()
    {
        if (!$this->generalConfig) {
            $this->generalConfig = new ArContactUsConfigGeneral('arcug_');
        }
        if (!$this->generalConfig->isLoaded()) {
            $this->generalConfig->loadFromConfig();
        }
        return $this->generalConfig;
    }
    
    /**
     * 
     * @return ArContactUsConfigMobileButton
     */
    public function getMobileButtonConfig()
    {
        if (!$this->mobileButtonConfig) {
            $this->mobileButtonConfig = new ArContactUsConfigMobileButton('arcumb_');
        }
        if (!$this->mobileButtonConfig->isLoaded()) {
            $this->mobileButtonConfig->loadFromConfig();
        }
        return $this->mobileButtonConfig;
    }
    
    abstract public function init();
    
    public function activate()
    {
        return true;
    }
    
    public function deactivate()
    {
        wp_clear_scheduled_hook('arcontactus_check_event');
        return true;
    }

    public function css()
    {
        return array();
    }
    
    public function js()
    {
        return array();
    }
    
    public function registerJs()
    {
        if ($this->getGeneralConfig()->disable_jquery) {
            $deps = array();
        } else {
            $deps = array('jquery');
        }
        
        foreach ($this->js() as $key => $file){
            if ($file){
                wp_register_script($key, AR_CONTACTUS_PLUGIN_URL . $file, $deps, AR_CONTACTUS_VERSION);
            }
            wp_enqueue_script($key);
        }
    }
    
    public function registerCss()
    {
        foreach ($this->css() as $key => $file){
            if (preg_match('/https?:/is', $file)){
                $url = $file;
            } else {
                $url = AR_CONTACTUS_PLUGIN_URL . $file;
            }
            if (strpos($key, 'generated') !== false) {
                wp_register_style($key, $url, array(), get_option('arcu_css_generated'));
            }else{
                wp_register_style($key, $url, array(), AR_CONTACTUS_VERSION);
            }
            
            wp_enqueue_style($key);
        }
    }
    
    public static function render($view, $viewData = array())
    {
        ob_start();
        extract($viewData);
	include AR_CONTACTUS_PLUGIN_DIR . 'views/' . $view;
	$output = ob_get_clean();
	return $output;
    }
    
    public static function isSubmit($submit)
    {
        return (
            isset($_POST[$submit]) || isset($_POST[$submit.'_x']) || isset($_POST[$submit.'_y'])
            || isset($_GET[$submit]) || isset($_GET[$submit.'_x']) || isset($_GET[$submit.'_y'])
        );
    }
    
    public function isMobile()
    {
        if (function_exists('wp_is_mobile')){
            return wp_is_mobile();
        }
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}
        
	return apply_filters('wp_is_mobile', $is_mobile);
    }
}
