<?php
/**
 * 点赞接口 Api_Praises
 */
class Api_Praise extends PhalApi_Api {


    private $domain;

    public function __construct()
    {
        $this->domain = new Domain_Praise();
    }

    public function getRules()
    {
        $token = [
            'name' => 'token',
            'require' => true,
            'desc' => '用户口令',
        ];

        $titleId = [
            'name' => 'titleId',
            'require' => true,
            'desc' => '微博id',
        ];

        $userId = [
            'name' => 'userId',
            'require' => true,
            'desc' => '用户id',
        ];

        $requestParams = [
            //点赞
            'agree' => [
              'token' => $token,
            ],

            //取消点赞
            'cancel' => [
                'token' => $token,
            ],

            //查看点赞人列表
            'getUsers' => [
                'titleId' => $titleId,
            ],

            //查看用户点过赞的微博列表
            'getTitles' => [
                'userId' => $userId,
            ],

            //获取微博点赞数量
            'count' => [
                'titleId' => $titleId,
            ],
        ];

        return $requestParams;
    }


    /**
     * POST请求 点赞
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function agree(){
        $this->domain->token = $this->token;
        return $this->domain->agree($this->titleId);
    }

    /**
     * POST请求 取消点赞
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function cancel(){
        $this->domain->token = $this->token;
        return $this->domain->cancel($this->titleId);
    }

    /**
     * GET请求 获取某条微博的点赞人列表
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function getUsers(){
        return $this->domain->getUsers($this->titleId);
    }

    /**
     * GET请求 获取某人点赞过的微博列表
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function getTitles(){
        return $this->domain->getTitles($this->userId);
    }

    /**
     * GET请求 获取某条微博的点赞数量
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function count(){
        return $this->domain->getCount($this->titleId);
    }


}