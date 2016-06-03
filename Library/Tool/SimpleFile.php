<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/6/3
 * Time: 12:17
 */
class Tool_SimpleFile{

    //上传头像
    public static function uploadIcon($uid, $iconFile) {

        $desPath = $_SERVER['DOCUMENT_ROOT'].'/images/icons/'.$uid.'.jpeg';

        if ($this->uploadFile($desPath, $iconFile)) {
            return true;
        }
        return false;
    }

    //上传微博配图
    public function uploadTitleImage(){

    }

    //移动上传文件到指定目录
    public static function uploadFile($desPath, $file) {
        $tempPath = $file['tmp_name'];

        if (is_uploaded_file($tempPath)) {//检查是否post上传成功
            move_uploaded_file($tempPath, $desPath);
            return true;
        }
        return false;
    }


    //一次上传多个文件
    public function uploadMoreFile() {

    }
}