<?php
/**
 * 微博接口 Api_Title
 */

class Api_Title extends PhalApi_Api {

    private $titleDomain;

    function __construct()
    {
        $this->titleDomain = new Domain_Title();
    }


    public function getRules() {
        //设置请求成功消息体
        DI()->response->setMsg('success');

        //请求参数
        $token = [
          'name' => 'token',
          'require' => true,
          'desc' => '用户口令',
        ];

        $userId = [
        'name' => 'userId',
        'require' => true,
        'desc' => '用户ID',
        ];

        $text = [
            'name' => 'text',
            'require' => true,
            'min' => 1,
            'max' => 140,
            'desc' => '微博正文',
        ];

        $source = [
            'name' => 'source',
            'require' => true,
            'desc' => '来源'
        ];

        $titleId = [
            'name' => 'titleId',
            'require' => true,
            'desc' => '微博id',
        ];


        $requestParam = [

           //显示公共微博
          'getAll' => [
              'token' => $token,
            ],

            //显示关注人微博
            'getFollows' => [
                'token' => $token,
            ],

            //显示好友圈微博
            'getFriends' => [
                'token' => $token,
            ],

            //显示单条微博详情
            'getDetail' => [
                'token' => $token,
            ],

            //显示个人全部微博
            'getList' => [
              'userId' => $userId,
            ],

            //发布微博
            'post' => [
                'token' => $token,
                'text' => $text,
                'source' => $source,
            ],

            //删除微博
            'delete' => [
                'token' => $token,
                'titleId' => $titleId,
            ],

        ];
        return $requestParam;
    }


  /**
   * 获取公共微博列表
   * @return int ret 请求码 200注册成功,300注册失败
   * @return string data 
   * @return string message success 错误信息
   */
    public function getAll(){
        $this->titleDomain->token = $this->token;
        return $this->titleDomain->getAll();
    }


  /**
   * 获取关注人微博列表
   * @return int ret 请求码 200注册成功,300注册失败
   * @return string data 
   * @return string message success 错误信息
   */    
    public function getFollows(){
        $this->titleDomain->token = $this->token;
        return $this->titleDomain->getFollows();
    }

    /**
     * 获取好友圈微博列表
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string data
     * @return string message success 错误信息
     */
    public function getFirens(){
        $this->titleDomain->token = $this->token;
        return $this->titleDomain->getFriends();
    }

  /**
   * 获取微博详情
   * @return int ret 请求码 200注册成功,300注册失败
   * @return string data 
   * @return string message success 错误信息
   */
    public function getDetail(){

    }

  /**
   * 获取用户个人微博列表
   * @return int ret 请求码 200注册成功,300注册失败
   * @return string data 
   * @return string message success 错误信息
   */
    public function getList(){
        return $this->titleDomain->getList($this->userId);
    }


  /**
   * 发送微博
   * @return int ret 请求码 200注册成功,300注册失败
   * @return string data 
   * @return string message success 错误信息
   */
    public function post(){
        $this->titleDomain->token = $this->token;
        return $this->titleDomain->postTitle($this->text, $this->source);
    }

}