<?php
ArContactUsLoader::loadModel('ArContactUsModelAbstract');

class ArContactUsCallbackModel extends ArContactUsModelAbstract
{
    public $id;
    public $id_user;
    public $phone;
    public $name;
    public $email;
    public $referer;
    public $created_at;
    public $updated_at;
    public $status;
    public $comment;
    
    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_IGNORE = 2;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'id_user',
                    'phone',
                    'name',
                    'email',
                    'referer',
                    'created_at',
                    'updated_at',
                    'status',
                    'comment'
                ), 'safe'
            )
        );
    }
    
    public function scheme()
    {
        return array(
            'id' => self::FIELD_INT,
            'id_user' => self::FIELD_INT,
            'phone' => self::FIELD_STRING,
            'name' => self::FIELD_STRING,
            'email' => self::FIELD_STRING,
            'referer' => self::FIELD_STRING,
            'created_at' => self::FIELD_STRING,
            'updated_at' => self::FIELD_STRING,
            'status' => self::FIELD_INT,
            'comment' => self::FIELD_STRING
        );
    }
    
    public static function tableName()
    {
        return self::dbPrefix().'arcontactus_callback';
    }
    
    public static function createTable()
    {
        return self::getDb()->query("CREATE TABLE IF NOT EXISTS `" . self::tableName() . "` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_user` INT(10) UNSIGNED NULL DEFAULT NULL,
                `phone` VARCHAR(50) NULL DEFAULT NULL,
                `name` VARCHAR(255) NULL DEFAULT NULL,
                `email` VARCHAR(255) NULL DEFAULT NULL,
                `referer` VARCHAR(255) NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                `status` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
                `comment` TEXT NULL,
                PRIMARY KEY (`id`),
                INDEX `id_user` (`id_user`),
                INDEX `phone` (`phone`)
            )
            COLLATE='utf8_general_ci';");
    }
    
    public static function truncate()
    {
        return self::getDb()->query("TRUNCATE `" . self::tableName() . "`");
    }
    
    public static function dropTable()
    {
        return self::getDb()->query("DROP TABLE IF EXISTS `" . self::tableName() . "`");
    }
    
    public function getUserName()
    {
        if ($this->id_user){
            $user = get_user_by('id', $this->id_user);
            return $user->data->user_nicename;
        }
        return '-';
    }
    
    public function getStatusLabel()
    {
        switch ($this->status) {
            case self::STATUS_DONE:
                return __('Done', 'ar-contactus');
            case self::STATUS_IGNORE:
                return __('Ignore', 'ar-contactus');
            default:
                return __('New', 'ar-contactus');
        }
    }
    
    public function formatPhone()
    {
        $phone = preg_replace('{\W+}is', '', $this->phone);
        return '+' . $phone;
    }
    
    public static function newCount()
    {
        $models = self::find()->where(array('status' => 0))->all();
        return count($models);
    }
}
