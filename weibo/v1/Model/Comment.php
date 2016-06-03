<?php

class Model_Comment extends PhalApi_Model_NotORM {


    //新增评论
    public function post($titleId, $userId, $commentText){
        $data = [
            'title_id' => $titleId,
            'user_id' => $userId,
            'text' => $commentText,
        ];

        return $this->insert($data);
    }

    //回复评论
    public function reply($titleId, $userId, $replyUserId, $text){
        $data = [
            'title_id' => $titleId,
            'user_id' => $userId,
            'reply_user_id' => $replyUserId,
            'text' => $text,
        ];
        return $this->insert($data);
    }

    //删除评论
    public function delete($commentId, $userId){
        $con = [
            'user_id' => $userId,
            'id' => $commentId
        ];
        return $this->getORM()->where($con)->delete();
    }


    //展示微博的评论
    public function getList($titleId){
        return $this->getORM()
          ->select(
          'id',
          'user_id',
          'reply_user_id',
          'text',
          'create_time')
          ->where('title_id', $titleId)
          ->order('create_time desc')
          ->fetchAll();
    }

    //获取微博评论数量
    public function getCount($titleId){
        return $this->getORM()
          ->select('id')
          ->where('title_id', $titleId)
          ->count();
    }



    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        return 'comment';
    }
}