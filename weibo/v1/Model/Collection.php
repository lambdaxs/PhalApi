<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/6/2
 * Time: 22:43
 */

class Model_Collection extends PhalApi_Model_NotORM{

    //添加收藏
    public function add($titleId, $userId){
        $data = [
            'title_id' => $titleId,
            'user_id' => $userId
        ];
        return $this->insert($data);
    }

    //取消收藏
    public function cancel($titleId, $userId){
        $con = [
            'title_id' => $titleId,
            'user_id' => $userId
        ];
        return $this->getORM()->where($con)->delete();
    }

    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'collection';
    }
}