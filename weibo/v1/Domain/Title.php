<?php

/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/16
 * Time: 17:47
 */


class Domain_Title extends PhalApi_Domain
{

    private $userModel;
    private $titleModel;
    private $commentModel;
    private $praisesModel;
    private $imageModel;
    private $relationModel;


    public function __construct()
    {
        $this->userModel = new Model_User();
        $this->titleModel = new Model_Title();
        $this->commentModel = new Model_Comment();
        $this->praisesModel = new Model_Praise();
        $this->imageModel = new Model_Image();
        $this->relationModel = new Model_Relation();
    }

    

    //查看公共微博
    public function getAll()
    {
        //查询所有的微博信息
        $titles = $this->titleModel->getAll();
        //把对应的用户信息和评论加上
        $rs = array_map([$this,'insertInfo'], $titles);
        return $rs;
    }

    //查看关注人的微博列表
    public function getFollows()
    {
        $follows = $this->relationModel->getFollowListInCache($this->userId);//获取关注人的id列表
        $followsTitle = $this->getListByUserIds($follows);
        return $followsTitle;
    }

    //查看好友圈的微博列表
    public function getFriends()
    {
        $friends = $this->relationModel->getFriendListInCache($this->userId);
        $friendsTitle = $this->getListByUserIds($friends);
        return $friendsTitle;
    }

    //通userId数组获取微博数组
    public function getListByUserIds($userIds){
        $titles = $this->titleModel->getList($userIds, 'user_id');    //获取微博列表
        $rs = array_map([$this,'insertInfo'], $titles);
        return $rs;
    }

    //通titleId数组获取微博数组
    public function getListByTitleIds($titleIds){
        $titles = $this->titleModel->getList($titleIds, 'id');    //获取微博列表
        $rs = array_map([$this,'insertInfo'], $titles);
        return $rs;
    }

    //获取某用户的全部微博列表
    public function getList($userId)
    {
        $all = $this->titleModel->getList($userId, 'user_id');
        $rs = array_map([$this,'insertInfo'], $all);
        return $rs;
    }

    //发布新微博
    public function postTitle($text, $source){
        $rs = $this->titleModel->postTitle($this->userId, $text, $source);
        return $rs;
    }

    //map中插入用户  评论 点赞 图片信息
    private function insertInfo($title){
        $user_id = $title['user_id'];
        $title_id = $title['id'];

        $title['user'] = $this->userModel->getInfo($user_id);    //插入用户信息
        $title['comments'] = $this->commentModel->getCount($title_id);//插入评论
        $title['praises'] = $this->praisesModel->getCount($title_id);//插入点赞信息
        $title['images'] = $this->imageModel->getTitleImages($title_id);//插入图片数组

        return $title;
    }

}