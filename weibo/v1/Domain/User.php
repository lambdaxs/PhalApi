<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/1/20
 * Time: 15:58
 */


class Domain_User extends PhalApi_Domain
{
    private $userModel;
    private $tokenModel;
    private $relationModel;

    public function __construct(){
        $this->userModel = new Model_User();
        $this->tokenModel = new Model_Token();
        $this->relationModel = new Model_Relation();
    }




    //登录 成功后返回一个有效的token
    public function login($userName, $password)
    {
        
        $pwd = $this->userModel->getPasswordByuserName($userName);
     
        if ($pwd != md5($password)) {
            $this->showErrorMessage("帐号或密码错误");
        }
        return md5($userName);
    }


    //验证帐号和邮箱
    public function registerAccount($userName, $email){
        //校验帐号邮箱是否唯一
        $isExistuserName = $this->userModel->isExistuserName($userName);
        $isExistEmail = $this->userModel->isExistEmail($email);

        if ($isExistEmail) {
            $this->showErrorMessage('邮箱已注册');
        }

        if ($isExistuserName) {
            $this->showErrorMessage('用户名已注册');
        }

        //发送验证码
        $this->sendEmail($email);
        return '发送成功';
    }

    //发送验证码到邮箱 120秒过期
    private function sendEmail($email){
        $code = mt_rand(1000,9999);
        
        //发送验证码到注册邮箱
        $isOk = Tool_SimpleEmail::sendverifCode($email, strval($code));
        if ($isOk){
            //将验证码写入内存
            $key = MEM_USER_EMAIL.$email;
            DI()->cache->set($key,$code,120);
        }else{
            $this->showErrorMessage('邮件发送失败');
        }

    }

    //验证邮箱正确 写库
    public function registerConfirm($email, $code, $userName, $password){

        $key = MEM_USER_EMAIL.$email;
        $confirmCode = DI()->cache->get($key);

        if (is_null($confirmCode)){
            $this->showErrorMessage('验证码过期');
        }

        if ($confirmCode != $code){
            $this->showErrorMessage('验证码错误');
        }

        return $this->register($userName, $password, $email);
    }

    //注册写库
    private function register($userName, $password, $email)
    {

        $nick_name = 'user_' . $userName;
        $icon_url = 'http://'.$_SERVER['HTTP_HOST'].'/images/icons/default.jpeg';

        $token = md5($userName);

        //开启事务 先插入注册信息到用户表 再插入到token表中
        DI()->notorm->beginTransaction('db_wb');
        $user_id = $this->userModel->save($userName, $password, $email, $nick_name, $icon_url);
        $token_id = $this->tokenModel->save($user_id, $userName, $token);
        DI()->notorm->commit('db_wb');

        if ($token_id === false || $user_id === false) {
            $this->showErrorMessage('注册失败');
        }

        //成功返回token
        $rs['token'] = $token;
        return $rs;
    }


    //获取用户信息
    public function getInfo()
    {
        $rs['userInfo'] = $this->userModel->getInfo($this->userId);
        return $rs;
    }

    //个人相册
    public function getPhotos(){
        $imageModel = new Model_Image();
        $photosURL = $imageModel->getUserImages($this->userId);
        return $photosURL;
    }

    //更新用户信息
    public function updateInfo($data, $option)
    {

        $rs = null;
        if ($option == 'nickName'){
            $rs = $this->userModel->updateNickName($this->userId, $data);
        }

        if ($option == 'descInfo'){
            $rs = $this->userModel->updateDescInfo($this->userId, $data);
        }

        if ($rs === false) {
            $this->showErrorMessage('更新失败');
        }

        return $rs;
    }


    //上传头像
    public function uploadIcon($icon)
    {
        //将上传文件移动到指定目录
        if (!Tool_SimpleFile::uploadIcon($this->userId, $icon)) {
            $this->showErrorMessage('上传失败');
        }

        return $this->userModel->getInfo($this->userId);
    }

    //关注和取消关注用户
    public function follow($fuserId, $option){

        $rs = null;
        if ($option == 'follow'){
            $rs = $this->relationModel->follow($this->userId, $fuserId);
        }

        if ($option == 'unfollow'){
            $rs = $this->relationModel->unfollow($this->userId, $fuserId);
        }

        if ($rs === false){
            $this->showErrorMessage('操作失败');
        }

        return $rs;
    }


}


