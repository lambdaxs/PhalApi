<?php
/**
 * Demo 统一入口
 */

require_once dirname(__FILE__) . '/../init.php';


//内存KEY
define("MEM_USER_EMAIL","user_email_"); //注册邮箱key
define("MEM_USER_INFO","user_info_");   //用户信息key
define("MEM_USER_TOKEN","user_token_"); //用户token key
define("MEM_USER_FOLLOW","user_follows_"); //用户关注id key
define("MEM_USER_FAN","user_fans_"); //用户粉丝id key
define("MEM_USER_FRIEND","user_friend_"); //用户好友id key
define("MEM_IMAGE_URL","image_url_");    //配图路径缓存

//装载你的接口
DI()->loader->addDirs(array('weibo/v1', 'Library'));


//装载数据库
DI()->notorm = function (){
    $debug = !empty($_GET['debug']) ? true : false;
    return new PhalApi_DB_NotORM(DI()->config->get('db_wb'), $debug);
};

/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();
