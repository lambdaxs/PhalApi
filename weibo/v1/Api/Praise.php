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


    //点赞
    public function agree(){
        $this->domain->token = $this->token;
        return $this->domain->agree($this->titleId);
    }

    //取消赞
    public function cancel(){
        $this->domain->token = $this->token;
        return $this->domain->cancel($this->titleId);
    }

    //获取点赞人列表
    public function getUsers(){
        return $this->domain->getUsers($this->titleId);
    }

    //获取点过赞的微博列表
    public function getTitles(){
        return $this->domain->getTitles($this->userId);
    }

    //获取点赞数量
    public function count(){
        return $this->domain->getCount($this->titleId);
    }


}