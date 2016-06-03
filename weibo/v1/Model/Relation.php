<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/17
 * Time: 14:11
 */
class Model_Relation extends PhalApi_Model_NotORM {



    //a关注b
    public function follow($userId, $fuserId){
        $data = [
            'user_id' => $userId,
            'fuser_id' => $fuserId,
        ];

        //插入关注关系
        $rs = $this->insert($data);
        //刷新关注缓存列表
        $this->deleteRelationCache('follow',$userId);
        return $rs;
    }


    //a取消关注b
    public function unfollow($userId, $fuserId){

        $condition = [
            'user_id' => $userId,
            'fuser_id' => $fuserId,
        ];
        //删除关注关系
        $rs = $this->getORM()->where($condition)->delete();
        //刷新关注缓存列表
        $this->deleteRelationCache('follow',$userId);

        return $rs;
    }


    //判断a是否关注了b
    public function isFollowed($userId, $fuserId){
        $follows = $this->getFollowListInCache($userId);

        if(in_array($fuserId, $follows))
            return true;
        else
            return false;
    }

    //判断a和b是否为互粉好友
    public function isFriend($userId, $fuserId){
        if($this->isFollowed($userId, $fuserId) && $this->isFollowed($fuserId,$userId))
            return true;
        else
            return false;
    }

    //获取a的关注数
    public function getFollowCount($userId){
        $follows = $this->getFollowListInCache($userId);
        return count($follows);
    }

    //获取a的粉丝数
    public function getFansCount($userId){
        $fans = $this->getFansListInCache($userId);
        return count($fans);
    }

    //获取a的好友数
    public function getFriendCount($userId){
        $friends = $this->getFriendListInCache($userId);
        return count($friends);
    }

    //建立a的follow列表缓存
    public function getFollowListInCache($userId){

        $key = MEM_USER_FOLLOW.$userId;
        $follows = DI()->cache->get($key);

        if(is_null($follows)){
            $follows = $this->getORM()
                ->select('fuser_id')
                ->where('user_id',$userId)
                ->fetchAll();
            DI()->cache->set($key, $follows, 600);
        }

        return $follows;
    }

    //建立a的fans列表缓存
    public function getFansListInCache($userId){

        $key = MEM_USER_FAN.$userId;
        $fans = DI()->cache->get($key);

        if(is_null($fans)) {
            $fans = $this->getORM()
                ->select('user_id')
                ->where('fuser_id',$userId)
                ->fetchAll();
            DI()->cache->set($key, $fans, 600);
        }
        return $fans;
    }

    //建立a的好友列表缓存
    public function getFriendListInCache($userId){
        $key = MEM_USER_FRIEND.$userId;
        $friends = DI()->cache->get($key);

        if(is_null($friends)) {
            $follows = $this->getFollowListInCache($userId);
            $fans = $this->getFansListInCache($userId);
            $friends = array_intersect($follows, $fans);
            DI()->cache->set($key, $friends, 600);
        }
        return $friends;
    }

    //清除关系缓存
    public function deleteRelationCache($type, $userId){
        $key = $type.$userId;
        DI()->cache->delete($key);
    }


    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'relation';
    }
}