<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/3/17
 * Time: 13:00
 */

class Model_Image extends PhalApi_Model_NotORM {


    //获取微博的图片URL数组
    public function getTitleImages($titleId){


        $key = MEM_IMAGE_URL.$titleId;
        $rs = DI()->cache->get($key);

        if (is_null($rs)) {
            $images = $this->getORM()->select('image_url')->where('title_id', $titleId)->fetchAll();

            $rs = array_map(function ($v){
                return $v['image_url'];
            }, $images);

            DI()->cache->set($key, $rs, 600);
        }

        return $rs;
    }


    //获取用户个人的图片URL数组
    public function getUserImages($userId){
        return $this->getORM()->select('image_url')->where('user_id', $userId)->fetchAll();
    }




    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        // TODO: Implement getTableName() method.
        return 'image';
    }
}