<?php
/**
 * Email 邮件发送服务
 * User: sunguide
 * Date: 14/12/8
 * Time: 00:59
 * Description:EmailService.php
 */

namespace Common\Service;

require(APP_PATH."../vendor/phpmailer/phpmailer/PHPMailerAutoload.php");

class EmailService extends Service {

    static $_instance;
    static $_config = array();
    static $_phpMailer;
    /**
     * 取得队列类实例
     * @static
     * @access public
     * @return mixed
     */
    public static function getInstance($config = array()) {
        if(self::$_instance){
            return self::$_instance;
        }
        return self::$_instance = new EmailService($config);
    }
    public function __construct($config = array()){
        if(!empty($config)){
            self::$_config = $config;
        }else{
            self::$_config = C('THINK_EMAIL');
        }
        self::$_phpMailer = $this->getPHPMailer();
    }
    private function getPHPMailer(){
        if(self::$_phpMailer){
            return self::$_phpMailer;
        }
        $config  = self::$_config;
        $mail             = new \PHPMailer(); //PHPMailer对象

        $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码

        $mail->IsSMTP();  // 设定使用SMTP服务

        $mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能

        // 1 = errors and messages

        // 2 = messages only

        $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能

        $mail->SMTPSecure = 'ssl';                 // 使用安全协议

        $mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器

        $mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号

        $mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名

        $mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码

        return  self::$_phpMailer = $mail;
    }

    /**
     * 发送邮件
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function send($to, $name, $subject = '', $body = '', $attachment = null){
        $config = self::$_config;

        $mail = $this->getPHPMailer();

        $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);

        $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];

        $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];

        $mail->AddReplyTo($replyEmail, $replyName);

        $mail->Subject    = $subject;

        $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";

        $mail->MsgHTML($body);

        $mail->AddAddress($to, $name);

        if(is_array($attachment)){ // 添加附件

            foreach ($attachment as $file){

                is_file($file) && $mail->AddAttachment($file);

            }

        }

        return  $mail->Send() ? true : $mail->ErrorInfo;
    }
}
