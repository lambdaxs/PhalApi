<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/17
 * Time: 13:29
 */
class Model_Praise extends PhalApi_Model_NotORM {

    //点赞
    public function agree($userId, $titleId){
        $data = [
            'user_id' => $userId,
            'title_id' => $titleId,
        ];
        return $this->insert($data);
    }

    //取消点赞
    public function cancel($userId, $titleId){
        $condition = [
            'user_id' => $userId,
            'title_id' => $titleId,
        ];
        return $this->getORM()
            ->where($condition)
            ->delete();
    }

    //获取微博的点赞数量
    public function getCount($titleId){
        return $this->getORM()
            ->where('title_id', $titleId)
            ->count();
    }

    //获取对微博点赞的用户id列表
    public function getUserIds($titleId){
        $rs = $this->getORM()
        ->select('user_id')
        ->where('title_id', $titleId)
        ->fetchAll();

        $userIds = array_map(function ($v){
            return $v['user_id'];
        }, $rs);

        return $userIds;
    }

    //获取用户点赞的微博列表
    public function getTitlesIds($userId){
        $rs = $this->getORM()
        ->select('title_id')
        ->where('user_id', $userId)
        ->fetchAll();

        $titleIds = array_map(function ($v){
            return $v['title_id'];
        }, $rs);

        return $titleIds;
    }




    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'praise';
    }
}