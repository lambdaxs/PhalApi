<?php


class Domain_Comment extends PhalApi_Domain {
	
    private $commentModel;

    function __construct(){
        $this->commentModel = new Model_Comment();
    }

    //新增评论
	public function post($titleId, $commentText){
		$commentId = $this->commentModel->post($titleId, $this->userId, $commentText);

        if ($commentId === false) $this->showErrorMessage('评论失败');
        return $commentId;
	}

    //删除评论
    public function delete($commentId){

        $rs = $this->commentModel->deleteComment($commentId, $this->userId);
        
        if($rs == 0)$this->showErrorMessage('删除失败');
        return $rs;
    }

    //回复评论
    public function reply($titleId, $replyId, $text){
        $rs = $this->commentModel->reply($titleId, $this->userId, $replyId, $text);

        return $rs;
    }


}