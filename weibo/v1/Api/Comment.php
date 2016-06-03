<?php
/**
 * 评论接口 Api_Comment
 */
class Api_Comment extends PhalApi_Api {

    private $commentDomain;

    function __construct(){
        $this->commentDomain = new Domain_Comment();
    }


	public function getRules(){
		//接口参数
		$token = [
		'name' => 'token',
		'require' => true,
		'desc' => '用户口令',
		];

		$titleId = [
		'name' => 'titleId',
		'require' => true,
		'desc' => '评论的微博id'
		];


        $text = [
		'name' => 'text',
		'require' => true,
		'desc' => '评论内容',
		];

		$commentId = [
		'name' => 'commentId',
		'require' => true,
		'desc' => '评论ID',
		];

        $replyUserId = [
            'name' => 'replyUserId',
            'require' => true,
            'desc' => '回复用户id',
        ];


		$requestParams = [
		    //发送评论
			'post' => [
			'token' => $token,
			'titleId' => $titleId,
			'comment' =>$text,
			],

		    //删除评论
			'delete' => [
			'token' => $token,
			'commentId' => $commentId,
			],

			//回复评论
			'reply' => [
                'token' => $token,
                'titleId' => $titleId,
                'text' => $text,
                'replyUserId' => $replyUserId,
			],


		];

		return $requestParams;
	}

    /**
     * POST请求 发送评论
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string code success代表成功,failure代表失败
     * @return string message 请求描述 注册成功,注册失败
     */
	public function post(){
		$this->commentDomain->token = $this->token;
		$commentId = $this->commentDomain->post($this->titleId, $this->text);
        return $commentId;
	}


    /**
     * POST请求 删除评论
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string code success代表成功,failure代表失败
     * @return string message 请求描述 注册成功,注册失败
     */
    public function delete(){
        $this->commentDomain->token = $this->token;
        $rs = $this->commentDomain->delete($this->commentId);
        return $rs;
    }

    /**
     * POST请求 回复评论
     * @return int ret 请求码 200注册成功,300注册失败
     * @return string code success代表成功,failure代表失败
     * @return string message 请求描述 注册成功,注册失败
     */
    public function reply(){
        $this->commentDomain->token = $this->token;
        $rs = $this->commentDomain->reply($this->titleId, $this->replyUserId, $this->text);
        return $rs;
    }



}