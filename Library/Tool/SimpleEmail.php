<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/1/21
 * Time: 09:48
 */


require_once dirname(__FILE__).'/../PHPMailer/PHPMailerAutoload.php';

//发送邮件的类
class Tool_SimpleEmail{

    public static function sendEmail($from, $to, $subject, $message){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet='UTF-8';
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = 'xs199438';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 25;
        $mail->setFrom($from, 'CShare');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        return $mail->send();
    }


    //发送注册时的邮箱验证码
    public static function sendverifCode($target, $vfCode){
        $message = '您的验证码是:'.$vfCode;
        return Tool_SimpleEmail::sendEmail('18844124100@163.com', $target, 'CShare注册验证码', $message);
    }
}


