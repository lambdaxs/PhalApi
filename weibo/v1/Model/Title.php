<?php

class Model_Title extends PhalApi_Model_NotORM {

    //通过userId数组 获取微博列表
    public function getList($ids, $field){
        $rs = $this->getORM()
          ->select('*')
          ->where($field ,$ids)
          ->order('create_time desc')
          ->fetchAll();

        return $rs;
    }

    //通过titleId数组 获取微博列表
    public function getListByTitleIds($titleIds){
        $rs = $this->getORM()
            ->select('*')
            ->where('id',$titleIds)
            ->order('create_time desc')
            ->fetchAll();

        return $rs;
    }

//    //获取个人微博条数
//    public function getCount($userId){
//      $rs = $this->getORM()
//      ->select('id')
//      ->where('user_id', $userId)
//      ->count();
//
//      return $rs;
//    }

    //获取公共微博列表
    public function getAll(){
        $rs = $this->getORM()
          ->select('*')
          ->order('create_time desc')
          ->fetchAll();

        return $rs;
    }

    //获取关注人的微博列表 传入关注人的id数组
    public function getFollows($fuserIds){
        $rs = $this->getORM()
          ->select('*')
          ->where('user_id',$fuserIds)
          ->order('create_time desc')
          ->fetchAll();

        return $rs;
    }

    //获取单条微博详情
    public function getDetail($titleId){
        $rs = $this->get($titleId);
        return $rs;
    }

    //发布微博
    public function postTitle($userId, $text, $source){
        $data = [
            'user_id' => $userId,
            'text' => $text,
            'source' => $source,
        ];

        $rs = $this->insert($data);
        return $rs;
    }

    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        return 'title';
    }
}