<?php
/**
 * Created by PhpStorm.
 * User: xiaos
 * Date: 16/1/20
 * Time: 16:09
 */
class Model_User extends PhalApi_Model_NotORM {


    //插入注册的用户信息
    public function save($userName, $password, $email, $nickName, $iconUrl) {
        $data = [
          'user_name' => $userName,
          'pwd' => md5($password),
          'email' => $email,
          'nick_name' => $nickName,
          'icon_url' => $iconUrl,
        ];

        return $this->insert($data);
    }

    //修改用户昵称
    public function updateNickName($userId ,$nickName){
        $data = [
            'nick_name' => $nickName,
        ];
        return $this->update($userId, $data);
    }

    //修改用户描述
    public function updateDescInfo($userId ,$descInfo){
        $data = [
            'desc_info' => $descInfo,
        ];
        return $this->update($userId, $data);
    }

    //修改用户头像url
    public function updateIconUrl($userId ,$iconUrl){
        $data = [
            'icon_url' => $iconUrl,
        ];
        return $this->update($userId, $data);
    }

    //更新用户信息 成功返回 >=1 无更新返回===0 更新错误返回===false
    public function update($userId, $data) {
        return $this->getORM()->where('id',$userId)->update($data);;
    }

    //查询用户信息
    public function getInfo($id) {
        $userInfo = $this->getUserInfoInCache($id);
        return $userInfo;
    }

    //用户信息缓存
    private function getUserInfoInCache($id){
        $key = MEM_USER_INFO.$id;
        $userInfo = DI()->cache->get($key);

        if(is_null($userInfo)){
            $userInfo = $this->get($id,
                ['id',
                'user_name',
                'nick_name',
                'icon_url',
                'desc_info',
                'create_time']);
            DI()->cache->set($key,$userInfo, 600);
        }
        return $userInfo;
    }

    //email是否存在
    public function isExistEmail($email) {
        $num = $this->getORM()
            ->where('email',$email)
            ->count('id');
        return $num > 0? true: false;
    }

    //userName是否存在
    public function isExistuserName($userName) {
        $num = $this->getORM()
            ->where('user_name',$userName)
            ->count('id');
        return $num > 0 ? true: false;
    }

    //userName登录的密码
    public function getPasswordByuserName($userName) {
        $rs = $this->getORM()
            ->select('pwd')
            ->where('user_name',$userName)
            ->fetch();
        return $rs['pwd'];
    }

    //邮箱登录的密码
    public function getPasswordByEmail($email) {
        $rs = $this->getORM()
            ->select('pwd')
            ->where('email',$email)
            ->fetch();
        return $rs['pwd'];
    }


    /**
     * 根据主键值返回对应的表名，注意分表的情况
     */
    protected function getTableName($id)
    {
        return 'user';
    }
}