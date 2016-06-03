<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/21
 * Time: 14:08
 */


class Domain_Praise extends PhalApi_Domain {

    private $model;

    public function __construct()
    {
        $this->model = new Model_Praise();
    }


    //点赞
    public function agree($titleId){
        return $this->model->agree($this->userId, $titleId);
    }

    //取消赞
    public function cancel($titleId){
        return $this->model->cancel($this->userId, $titleId);
    }

    //获取点赞用户列表
    public function getUsers($titleId){
        $userModel = new Model_User();
        $userIds =  $this->model->getUserIds($titleId);
        foreach ($userIds as $id){
            $rs[] = $userModel->getInfo($id);
        }
        return $rs;
    }

    //获取点过赞的微博列表
    public function getTitles($userId){
        $titleDomain = new Domain_Title();
        $titleIds = $this->model->getTitlesIds($userId);
        $titles = $titleDomain->getTitlesByTitleIds($titleIds);
        return $titles;
    }


    //获取微博的点赞数量
    public function getCount($titleId){
        return $this->model->getCount($titleId);
    }

}