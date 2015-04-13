<?php
/**
 * User: sunguide
 * Date: 15/1/21
 * Time: 01:56
 * Description:functions.php
 */
//获取公共静态文件
function getCDNFile($filePath){
    return CDN_DOMAIN.$filePath;
}

function getStaticFile($filePath){
    if(0 === strpos($filePath,"/")){
        return $filePath;
    }else{
        return "/".$filePath;
    }
}
function getAvatar($filePath){
    if(0 === strpos($filePath,"/")){
        return "/Upload/avatars".$filePath;
    }else{
        return "/Upload/avatars/".$filePath;
    }
}
function getDefaultAvatar(){
    return getStaticFile('Public/images/iconfont-default-avatar.png');
}
function getParamValue($data,$key,$default=""){
    if(is_object($data)){
        if(isset($data->$key)){
            return $data->$key;
        }
    }else if(is_array($data)){
        if(isset($data[$key])){
            return $data[$key];
        }
    }
    return $default;
}
function getUserNickname($userId = 0){
    if(!$userId){
        $nickname = session("user_nickname");
        if($nickname){
            return $nickname;
        }
    }
    $User = M("User");
    $userInfo = $User->find($userId);
    return $userInfo ? $userInfo['nickname']:"";
}

function getUserAvatar($userId = 0, $default=""){
    !$default && $default = getDefaultAvatar();
    if(!$userId){
        return $default;
    }
    $User = M("User");
    $userInfo = $User->find($userId);
    return $userInfo ? getAvatar($userInfo['avatar']):$default;
}

function getCurrentUserAvatar(){
    $avatar = session("user_avatar");
    return $avatar?:getDefaultAvatar();
}

function isLogin(){
    return session("user_id")?true:false;
}
function getSocialTime($timestamp){
    $date = new \Org\Util\Date();
    $desc = $date->timeDiff($timestamp);
    if(strpos($desc,"年")>0){
        $year = intval($desc);
        if($year > 5){
            return "很久以前";
        }
    }
    return $desc == "前"?"刚刚":$desc;
}
//符合互联网规则的验证邮箱的方法（尚且不支持中文域名）
function is_email($email){
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex){
        $isValid = false;
    }else{
        $domain = substr($email, $atIndex + 1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
            $isValid = false;
        } else if ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
            $isValid = false;
        } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
            // local part starts or ends with '.'
            $isValid = false;
        } else if (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
            $isValid = false;
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
            $isValid = false;
        } else if (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
            $isValid = false;
        } else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\", "", $local))) {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\", "", $local))){
                $isValid = false;
            }
        }
        if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
            // domain not found in DNS
            $isValid = false;
        }
    }
    return $isValid;
}
/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean
 */
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null, $config = array()){

    $config = empty($config)?C('THINK_EMAIL'):$config;
    require(APP_PATH."../vendor/phpmailer/phpmailer/PHPMailerAutoload.php");
    $mail             = new PHPMailer(); //PHPMailer对象

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

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $child 子标记
 * @return array
 * @author 孙贵德 <sunguide@qq.com>
 */
function listToTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent           =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }

    return $tree;
}

function listForObjectToArray($obj, $params)
{
    $dataArray = array();
    foreach ($obj as $key => $value) {
        foreach ($params as $k => $v) {
            $dataArray[$key][$v] = $value->$v;
        }
    }

    return $dataArray;
}

function cnTruncate($string, $strlen = 20, $etc = '...', $charset = 'utf-8')
{
    $slen = mb_strlen($string, $charset);
    if ($slen > $strlen + 2) {
        $tstr    = mb_substr($string, 0, $strlen, $charset);
        $matches = array();
        $mcount  = preg_match_all("/[\x{4e00}-\x{9fa5}]/u", $tstr, $matches);
        unset($matches);
        $offset = ($strlen - $mcount) * 0.35;
        return preg_replace('/\&\w*$/', '', mb_substr($string, 0, $strlen + $offset, $charset)) . $etc;
    } else {
        return $string;
    }
}
//检查是否是中文
function isCnCharacter($str)
{
    if (preg_match('/[^\x00-\x7F]/i', $str)) {
        return true;
    }
    return false;
}
