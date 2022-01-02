<?php
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ADMINZ_Mailer extends Adminz {
    public $options_group = "adminz_mailer";
    public $title = 'Mailer';
    static $slug = 'adminz_mailer';
    static $options;
    static $test_sendmail;
    function __construct() {
        $this->title = $this->get_icon_html('email').$this->title;
        add_action( 'admin_init', [$this, 'register_option_setting']);
        add_filter( 'adminz_setting_tab', [$this, 'register_tab']);    
        add_action( 'phpmailer_init', [$this,'setup_phpmailer'] );
        
        $this::$options = get_option('adminz_mailer', []);
    }    
    function tab_html() {    
        ob_start();
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <h3> Email Test</h3>
                </th>
            </tr>
            <tr valign="top">
                <th scope="row">Your email checker</th>
                <td>
                    <form method="post" action="">
                        <input type="text" name="adminz_mailer[test_email]" value="<?php echo $this->get_option_value('admin_email');?>">
                        <button type="submit" class="button">Test Email</button>
                        <div>
                            <?php                                    
                            if(isset($_POST["adminz_mailer"]['test_email'])){
                                $this->test_sendmail($_POST["adminz_mailer"]['test_email']);                                
                            }; 
                            ?>
                        </div>
                    </form>                    
                </td>
            </tr>
        </table>
        <form method="post" action="options.php">
            <?php             
            settings_fields($this->options_group);
            do_settings_sections($this->options_group);
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <h3> SMTP Config</h3>
                    </th>
                </tr>
                <tr valign="top">
                    <th scope="row">Host</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_host]" placeholder="smtp.gmail.com" value="<?php echo $this->get_option_value('adminz_mailer_host'); ?>" />
                        <em>The SMTP server which will be used to send email. For example: smtp.gmail.com</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Username</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_username]" value="<?php echo $this->get_option_value('adminz_mailer_username'); ?>" />
                        <em>Your SMTP Username.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Password</th>
                    <td>                        
                        <input type="text" name="adminz_mailer[adminz_mailer_password]" placeholder="Hidden information" value="<?php echo $this->get_option_value('adminz_mailer_password');?>" />
                        <em><?php if(!$this->get_option_value('adminz_mailer_password')){ echo '<mark>Current No password</mark>';} ?> 
                        Your SMTP Password (The saved password is not shown for security reasons. You must <b>re-enter</b> the password when saving the information again).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">From</th>
                    <td>
                        <input type="email" name="adminz_mailer[adminz_mailer_from]" value="<?php echo $this->get_option_value('adminz_mailer_from'); ?>" />
                        <em>The email address which will be used as the From Address if it is not supplied to the mail function.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">FromName</th>
                    <td>
                        <input type="text" name="adminz_mailer[adminz_mailer_fromname]" value="<?php echo $this->get_option_value('adminz_mailer_fromname'); ?>" />
                        <em>The name which will be used as the From Name if it is not supplied to the mail function.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Port</th>
                    <td>                        
                        <input type="number" name="adminz_mailer[adminz_mailer_port]" placeholder="587" value="<?php echo $this->get_option_value('adminz_mailer_port'); ?>" />
                        <em>The port which will be used when sending an email (587/465/25). If you choose TLS it should be set to 587. For SSL use port 465 instead.</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTPAuth</th>
                    <td>
                        <?php $auth =  $this->get_option_value('adminz_mailer_smtpauth');?>                        
                        <input type="checkbox" <?php if($auth == "on") echo 'checked'; ?> name="adminz_mailer[adminz_mailer_smtpauth]" />
                        <em>Whether to use SMTP Authentication when sending an email (recommended: True).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">SMTPSecure</th>
                    <td>
                        <?php $secure = $this->get_option_value('adminz_mailer_smtpsecure'); ?>                        
                        <select name="adminz_mailer[adminz_mailer_smtpsecure]">
                            <option value="tls" <?php if($secure == 'tls') echo 'selected'; ?>>TLS</option>
                            <option value="ssl" <?php if($secure == 'ssl') echo 'selected'; ?>>SSL</option>
                        </select>
                        <em>The encryption which will be used when sending an email (recommended: TLS).</em>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <h3> Server info</h3>
                    </th>
                    <td>
                        <?php $this->server_info_settings(); ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>        
        <?php
        return ob_get_clean();
    }
    function register_tab($tabs) {
        if(!$this->title) return;
        $tabs[] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'html' => $this->tab_html()
        );
        return $tabs;
    }
    function test_sendmail($email){
        if(!self::$test_sendmail){

            require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
            require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
            require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
            $phpmailer = new PHPMailer(true);
            try {
                //Server settings
                $phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
                $phpmailer->IsSMTP();
                $phpmailer->Host        = $this->get_option_value('adminz_mailer_host');
                $phpmailer->Username    = $this->get_option_value('adminz_mailer_username');
                $phpmailer->Password    = $this->get_option_value('adminz_mailer_password');                
                $phpmailer->Port        = $this->get_option_value('adminz_mailer_port','587');
                $phpmailer->SMTPAuth    = $this->get_option_value('adminz_mailer_smtpauth');
                $phpmailer->SMTPSecure  = $this->get_option_value('adminz_mailer_smtpsecure');
                $phpmailer->CharSet = 'UTF-8';
                $phpmailer->Encoding = 'base64';
                $phpmailer->setFrom(
                    $this->get_option_value('adminz_mailer_from'),
                    $this->get_option_value('adminz_mailer_fromname')
                );

                //Recipients
                $phpmailer->addAddress($email);
                $phpmailer->isHTML(true);
                $phpmailer->Subject = $this->get_adminz_menu_title(). " Test Email Smtp Function";
                $phpmailer->Body    = 'Mailer is ok! - Tested from site: '.get_bloginfo( 'name' );
                $phpmailer->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "<pre>";print_r("Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}");echo "</pre>";
            }
            self::$test_sendmail = true;          
        }
    }
    function setup_phpmailer( $phpmailer ) {
        if($this->get_option_value('adminz_mailer_host') and $this->get_option_value('adminz_mailer_username') and $this->get_option_value('adminz_mailer_password')){
            $phpmailer->IsSMTP();
            $phpmailer->Host        = $this->get_option_value('adminz_mailer_host');
            $phpmailer->Username    = $this->get_option_value('adminz_mailer_username');
            $phpmailer->Password    = $this->get_option_value('adminz_mailer_password');
            $phpmailer->From        = $this->get_option_value('adminz_mailer_from');
            $phpmailer->FromName    = $this->get_option_value('adminz_mailer_fromname');
            $phpmailer->Port        = $this->get_option_value('adminz_mailer_port','587');
            $phpmailer->SMTPAuth    = $this->get_option_value('adminz_mailer_smtpauth');
            $phpmailer->SMTPSecure  = $this->get_option_value('adminz_mailer_smtpsecure');
        }
        
    }
    function register_option_setting() {        
        register_setting($this->options_group, 'adminz_mailer');
    }
    function server_info_settings() {
        // clone from smtp mailer
        $server_info = '';
        $server_info .= sprintf('OS: %s%s', php_uname(), PHP_EOL);
        $server_info .= sprintf('PHP version: %s%s', PHP_VERSION, PHP_EOL);
        $server_info .= sprintf('WordPress version: %s%s', get_bloginfo('version'), PHP_EOL);
        $server_info .= sprintf('WordPress multisite: %s%s', (is_multisite() ? 'Yes' : 'No'), PHP_EOL);
        $openssl_status = 'Available';
        $openssl_text = '';
        if(!extension_loaded('openssl') && !defined('OPENSSL_ALGO_SHA1')){
            $openssl_status = 'Not available';
            $openssl_text = ' (openssl extension is required in order to use any kind of encryption like TLS or SSL)';
        }      
        $server_info .= sprintf('openssl: %s%s%s', $openssl_status, $openssl_text, PHP_EOL);
        $server_info .= sprintf('allow_url_fopen: %s%s', (ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled'), PHP_EOL);
        $stream_socket_client_status = 'Not Available';
        $fsockopen_status = 'Not Available';
        $socket_enabled = false;
        if(function_exists('stream_socket_client')){
            $stream_socket_client_status = 'Available';
            $socket_enabled = true;
        }
        if(function_exists('fsockopen')){
            $fsockopen_status = 'Available';
            $socket_enabled = true;
        }
        $socket_text = '';
        if(!$socket_enabled){
            $socket_text = ' (In order to make a SMTP connection your server needs to have either stream_socket_client or fsockopen)';
        }
        $server_info .= sprintf('stream_socket_client: %s%s', $stream_socket_client_status, PHP_EOL);
        $server_info .= sprintf('fsockopen: %s%s%s', $fsockopen_status, $socket_text, PHP_EOL);
        ?>
        <textarea rows="10" cols="50" class="large-text code" disabled><?php echo $server_info;?></textarea>
        <?php
    }

}