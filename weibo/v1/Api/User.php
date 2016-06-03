<?php

/**
 * 用户接口 Api_User
 */


//用户接口
class Api_User extends PhalApi_Api {

    private $domain;

    function __construct(){
        $this->domain = new Domain_User();
    }

    public function getRules() {

        //设置请求成功消息体
        DI()->response->setMsg('success');

        //请求参数
        $token = [
            'name' => 'token',
            'require' => true,
            'desc' => '用户口令'];

        $userId = [
            'name' => 'userId',
            'require' => true,
            'desc' => '用户id'];

        $userName = [
            'name' => 'userName',
            'type' => 'string',
            'require' => true,
            'min' => 6,
            'max' => 12,
            'desc' => '登录名'];

        $password = [
            'name' => 'password',
            'type'  => 'string',
            'require' => true,
            'desc' => '用户密码'];

        $email = [
            'name' => 'email',
            'type' => 'string',
            'require' => true,
            'desc' => '注册邮箱'];

        $icon = [
            'name' => 'icon',
            'type' => 'file',
            'min' => 0,
            'max' => 1024*1024*2,
            'require' => true,
            'range' => ['image/jpeg', 'image/png' ,'image/jpg','image/gif'],
            'desc' => '图片文件0~2M',];

        $userData = [
            'name' => 'userData',
            'type' => 'string',
            'require' => true,
            'desc' => '用户信息'];


        $code = [
            'name' => 'code',
            'type' => 'string',
            'require' => true,
            'desc' => '验证码'
        ];

        $updateOption = [
            'name' => 'option',
            'type' => 'enum',
            'range' => ['nickName', 'descInfo'],
            'require' => true,
            'desc' => '更新用户信息操作选项'];

        $followOption = [
            'name' => 'option',
            'type' => 'enum',
            'range' => ['follow', 'unfollow'],
            'require' => true,
            'desc' => '关注用户操作选项'];

        $requestParam = [

            //注册帐号
            'registerAccount' => [
                'userName' => $userName,
                'email' => $email
            ],

            //验证注册信息
            'registerConfirm' => [
                'userName' => $userName,
                'password' => $password,
                'email'    => $email,
                'code' => $code
            ],

            //用户登录
            'login' => [
                'userName' => $userName,
                'password' => $password
            ],

            //用户信息
            'info' => [
                'token' => $token,
            ],

            //个人相册
            'photos' => [
                'token' => $token,
            ],

            //修改用户信息
            'updateInfo' => [
                'token' => $token,
                'userData' => $userData,
                'option' => $updateOption,
            ],

            //上传头像
             'uploadIcon' => [
                'token' => $token,
                'icon' => $icon,
             ],

            //关注其他用户
            'follow' => [
                'token' => $token,
                'userId' => $userId,
                'option' => $followOption,
            ],



        ];
        return $requestParam;
    }


    /**
     * POST请求 注册邮箱和帐号
     * @return int ret 响应码 200成功 or 401失败
     * @return string data 发送成功:即验证码已发送,有效期120秒
     * @return string message success or 错误信息
     */
    public function registerAccount() {
        $this->checkParam('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',$this->email,'邮箱格式错误');
        $this->checkParam('/^\w*$/', $this->userName, '用户名格式错误');
        return $this->domain->registerAccount($this->userName, $this->email);
    }

    /**
     * POST请求 验证邮箱
     * @return int ret 响应码:200注册成功,401注册失败
     * @return object data token 此token保存于客户端 用于访问其他接口
     * @return string message 请求描述 注册成功,注册失败
     */
    public function registerConfirm() {
        return $this->domain->registerConfirm($this->email, $this->code, $this->userName, $this->password);
    }


    /**
     * POST请求 用户登录
     * @return int ret 响应码:200成功,300失败
     * @return object data.userInfo 用户信息对象
     * @return string message 请求描述
     */
    public function login() {
        return $this->domain->login($this->userName, $this->password);
    }

    /**
     * GET请求 获取用户信息
     * @return int ret 响应码,200成功,300失败
     * @return object data.userInfo 用户信息对象,失败返回null
     * @return string message 请求描述
     */
    public function info() {
        $this->domain->token = $this->token;
        return $this->domain->getInfo();
    }


    /**
     * GET请求 获取个人相册URL列表
     * @return int ret 响应码 200成功,300失败
     * @return array data 包含图片URL的数组
     * @return string message success代表成功,其他为返回报错信息
     */
    public function photos(){
        $this->domain->token = $this->token;
        return $this->domain->getPhotos();
    }

    /**
     * POST请求 修改用户信息
     * @return int ret 响应码 200修改成功,401修改失败
     * @return string data
     * @return string message success代表成功,failure代表失败
     */
    public function updateInfo() {
        $this->domain->token = $this->token;
        return $this->domain->updateInfo($this->userData, $this->updateOption);
    }

    /**
     * POST请求 上传头像
     * @return int ret 响应码 200上传成功,300上传失败
     * @return string data
     * @return string message success代表成功,failure代表失败
     */
    public function uploadIcon() {
        $this->domain->token = $this->token;
        return $this->domain->uploadIcon($this->icon);
    }


    /**
     * POST请求 关注用户
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function follow(){
        $this->domain->token = $this->token;
        return $this->domain->follow($this->userId, $this->followOption);
    }

    

}
