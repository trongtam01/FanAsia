<?php
ArContactUsLoader::loadController('ArContractUsControllerAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadClass('ArContactUsUpdater');

class ArContactUsMenuController extends ArContractUsControllerAbstract
{
    protected function ajaxActions()
    {
        return array(
            'arcontactus_save_menu_item' => 'saveItem',
            'arcontactus_reload_menu_items' => 'reloadItems',
            'arcontactus_reorder_menu_items' => 'reorderItems',
            'arcontactus_switch_menu_item' => 'switchItem',
            'arcontactus_edit_menu_item' => 'editItem',
            'arcontactus_delete_menu_item' => 'deleteItem',
            'arcontactus_export_data' => 'exportData',
            'arcontactus_import_data' => 'importData',
            'arcontactus_migrate_settings' => 'migrateSettings',
            'arcontactus_activate' => 'activate',
            'arcontactus_deactivate' => 'deactivate',
        );
    }
    
    public function activate()
    {
        $this->assertAccess();
        
        $updater = new ArContactUsUpdater();
        $code = trim($_GET['code']);
        update_option('AR_CONTACTUS_PURCHASE_CODE', $code);
        wp_die($this->returnJson($updater->activate()));
    }
    
    public function deactivate()
    {
        $this->assertAccess();
        
        $updater = new ArContactUsUpdater();
        $code = get_option('AR_CONTACTUS_PURCHASE_CODE');
        wp_die($this->returnJson($updater->deactivate()));
    }
    
    public function migrateSettings()
    {
        die('migrateSettings');
    }

    public function exportData()
    {
        $this->assertAccess();
        
        $params = $_POST;
        
        $generalConfig = new ArContactUsConfigGeneral('arcug_');
        $buttonConfig = new ArContactUsConfigButton('arcub_');
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobileButton')){
            $mobileButtonConfig = new ArContactUsConfigMobileButton('arcumb_');
            $mobileButtonConfig->loadFromConfig();
        }
        $menuConfig = new ArContactUsConfigMenu('arcum_');
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobileMenu')){
            $mobileMenuConfig = new ArContactUsConfigMobileMenu('arcumm_');
            $mobileMenuConfig->loadFromConfig();
        }
        $popupConfig = new ArContactUsConfigPopup('arcup_');
        $promptConfig = new ArContactUsConfigPrompt('arcupr_');
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobilePrompt')){
            $mobilePromptConfig = new ArContactUsConfigMobilePrompt('arcumpr_');
            $mobilePromptConfig->loadFromConfig();
        }
        $integrationConfig = new ArContactUsConfigLiveChat('arcul_');
        
        $generalConfig->loadFromConfig();
        $buttonConfig->loadFromConfig();
        $menuConfig->loadFromConfig();
        
        $popupConfig->loadFromConfig();
        $promptConfig->loadFromConfig();
        $integrationConfig->loadFromConfig();
        
        $data = array();
        if ($params['settings']){
            $data['general'] = $generalConfig->getAttributes();
            $data['button'] = $buttonConfig->getAttributes();
            if (isset($mobileButtonConfig)){
                $data['mobileButton'] = $mobileButtonConfig->getAttributes();
            }
            $data['menu'] = $menuConfig->getAttributes();
            if (isset($mobileMenuConfig)){
                $data['mobileMenu'] = $mobileMenuConfig->getAttributes();
            }
            $data['popup'] = $popupConfig->getAttributes();
            $data['prompt'] = $promptConfig->getAttributes();
            if (isset($mobilePromptConfig)){
                $data['mobilePrompt'] = $mobilePromptConfig->getAttributes();
            }
            $data['integrations'] = $integrationConfig->getAttributes();
        }
        if ($params['menu']){
            $menuItems = ArContactUsModel::find()->all();
            if (ArContactUsTools::isWPML()) {
                foreach ($menuItems as $k => $item) {
                    $menuItems[$k]->loadLangData();
                }
            }
            $data['menuItems'] = $menuItems;
        }
        if ($params['prompts']){
            $promptItems = ArContactUsPromptModel::find()->all();
            if (ArContactUsTools::isWPML()) {
                foreach ($promptItems as $k => $item) {
                    $promptItems[$k]->loadLangData();
                }
            }
            $data['promptItems'] = $promptItems;
        }
        if ($params['callbacks']){
            $data['callbackItems'] = ArContactUsCallbackModel::find()->all();
        }
        
        file_put_contents(AR_CONTACTUS_PLUGIN_DIR . 'uploads/export.json', $this->returnJson($data));
        
        $fileUrl = AR_CONTACTUS_PLUGIN_DIR . 'uploads/export.json';
        
        header('Content-Type: application/json');
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=\"" . basename($fileUrl) . "\""); 
        readfile($fileUrl);
        exit();
    }
    
    public function saveItem()
    {
        $this->assertAccess();
        
        $id = isset($_POST['id'])? $_POST['id'] : null;
        if (!$id){
            $model = new ArContactUsModel();
            $model->status = 1;
            $model->position = ArContactUsModel::getLastPostion() + 1;
        }else{
            $model = ArContactUsModel::findOne($id);
        }
        $model->load($_POST['data']);
        $model->validate();
        if (ArContactUsTools::isWPML()){
            foreach (ArContactUsTools::getLanguages() as $lang){
                $model->content[$lang['code']] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $model->content[$lang['code']]);
                $model->content[$lang['code']] = trim($model->content[$lang['code']]);
            }
        } else {
            $model->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $model->content);
            $model->content = trim($model->content);
        }
        $errors = $model->getErrors();
        
        switch ($model->type){
            case ArContactUsModel::TYPE_LINK:
                if (empty($model->link)){
                    $errors['link'] = array(__('Link field is required', 'ar-contactus'));
                }
                break;
            case ArContactUsModel::TYPE_JS:
                if (empty($model->js)){
                    $errors['js'] = array(__('Custom JS code field is required', 'ar-contactus'));
                }
                break;
            case ArContactUsModel::TYPE_INTEGRATION:
                if (empty($model->integration)){
                    $errors['integration'] = array(__('Integration field is required', 'ar-contactus'));
                }
                break;
            case ArContactUsModel::TYPE_CONTENT:
                if (is_array($model->content)) {
                    foreach ($model->content as $l => $v) {
                        if (empty($v)){
                            if (!is_array($errors['content'])) {
                                $errors['content'] = array();
                            }
                            $errors['content'][] = sprintf(__('Content field is required "%s" language', 'ar-contactus'), $l);
                        }
                    }
                }
                if (empty($model->content)){
                    $errors['content'] = array(__('Content field is required', 'ar-contactus'));
                }
                break;
        }
        
        if ($model->icon == 'FontAwesome icon') {
            $model->icon = $_POST['data']['fa_icon'];
            $model->icon = str_replace("'", '"', $model->icon);
            if (!preg_match('#^<i(.*?)></i>$#is', $model->icon)) {
                $errors['fa_icon'] = array(__('Icon is incorrect', 'ar-contactus'));
            }
        }
        
        if (empty($errors)){
            wp_die($this->returnJson(array(
                'success' => (int)$model->save()
            )));
        }else{
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $errors
            )));
        }
    }
    
    public function reloadItems()
    {
        $this->assertAccess();
        
        if (ArContactUsTools::isWPML()) {
            $lang = ArContactUsTools::getCurrentLanguage();
            if ($lang == 'all') {
                $lang = ArContactUsTools::getDefaultLanguage();
            }
            $items = ArContactUsModel::find()->join(ArContactUsModel::langTableName() . ' `_lang`', 'id_item = id')->where(array('lang' => $lang))->orderBy('`position` ASC')->all();
        } else {
            $items = ArContactUsModel::find()->orderBy('`position` ASC')->all();
        }
        
        
        wp_die($this->returnJson(array(
            'success' => 1,
            'content' => $this->render('/admin/_items_table.php', array(
                'items' => $items
            ))
        )));
    }
    
    public function reorderItems()
    {
        $this->assertAccess();
        
        $data = $_POST['data'];
        foreach ($data as $item) {
            $k = explode('_', $item);
            ArContactUsModel::updateAll(array(
                'position' => (int)$k[1]
            ), array(
                'id'  => (int)$k[0]
            ));
        }
        wp_die($this->returnJson(array()));
    }
    
    public function switchItem()
    {
        $this->assertAccess();
        
        $id = $_POST['id'];
        $model = ArContactUsModel::find()->where(array('id' => $id))->one();
        $model->status = $model->status? 0 : 1;
        $model->save();
        wp_die($this->returnJson(array(
            'success' => 1
        )));
    }
    
    public function editItem()
    {
        $this->assertAccess();
        
        $id = $_GET['id'];
        $model = ArContactUsModel::find()->where(array('id' => $id))->one();
        $model->shortcode = $model->getShortcode();
        if (ArContactUsTools::isWPML()) {
            $model->loadLangData();
        }
        if ($model->isFontAwesome()) {
            $model->fa_icon = $model->icon;
            $model->icon = 'FontAwesome icon';
        }
        wp_die($this->returnJson($model));
    }
    
    public function deleteItem()
    {
        $this->assertAccess();
        
        $id = $_POST['id'];
        $model = ArContactUsModel::find()->where(array('id' => $id))->one();
        wp_die($this->returnJson(array(
            'success' => $model->delete()
        )));
    }
}
