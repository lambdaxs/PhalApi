<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/6/2
 * Time: 18:25
 */
class Model_Number extends PhalApi_Model_NotORM {

    //微博数加减
    public function changeTitle($userId, $con){
        return $this->changeNumber('statuses', $userId, $con);
    }

    //关注数加减
    public function changeFollow($userId, $con){
        return $this->changeNumber('follows', $userId, $con);
    }

    //粉丝数加减
    public function changeFan($userId, $con){
        return $this->changeNumber('fans', $userId, $con);
    }

    //收藏数加减
    public function changeCollection($userId, $con){
        return $this->changeNumber('collections', $userId, $con);
    }



    //字段+1
    private function changeNumber($field, $userId, $con){
        return $this->getORM()
            ->where('user_id', $userId)
            ->update([$field => new NotORM_Literal($field.$con.'1')]);
    }




    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'number';
    }
}