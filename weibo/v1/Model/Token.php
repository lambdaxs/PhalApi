<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/16
 * Time: 15:06
 */

class Model_Token extends PhalApi_Model_NotORM{


    //插入token表
    public function save($user_id, $uid ,$token) {
        $data = [
          'user_id' => $user_id,
          'user_name' => $uid,
          'token' => $token,
        ];
        return $this->insert($data);
    }

    //在数据库中查找与token对应的uid
    public function getUserIdByToken($token) {
        $rs = $this->getORM()->select('user_id')->where('token',$token)->fetch();
        return $rs['user_id'];
    }


    //在缓存中查找与token对应的userId
    public function getUserIdInMemcache($token) {
        $key = MEM_USER_TOKEN.$token;
        $userId = DI()->cache->get($key);
        if (is_null($userId)) {
            $userId = $this->getUserIdByToken($token);
            DI()->cache->set($key, $userId, 600);
        }
        return $userId;
    }


    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'token';
    }
}