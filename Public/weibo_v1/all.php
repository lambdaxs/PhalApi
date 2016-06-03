<?php
/**
 * PhalApi接口列表 - 自动生成
 *
 * - 对Api_系列的接口，进行罗列
 * - 按service进行字典排序
 * - 支持多级目录扫描
 * 
 * <br>使用示例：<br>
```
 * <?php
 * class Api_Demo extends PhalApi_Api {
 *
 *      /**
 *       * 1.1 可在这里输入接口的服务名称
 *       * /
 *      public function index() {
 *          // todo ...    
 *      }
 * }
 *
```
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      xiaoxunzhao 2015-10-25
 * @modify      Aevit, dogstar <chanzonghuang@gmail.com> 2014-10-29
 */

define("D_S", DIRECTORY_SEPARATOR);
$root = dirname(__FILE__);

/**
 * 项目的文件夹名 - 如有需要，请更新此值
 */
$apiDirName = 'weibo/v1';

require_once implode(D_S, array($root, '..', 'init.php'));
DI()->loader->addDirs($apiDirName);
$files = listDir(implode(D_S, array($root, '..', '..', $apiDirName, 'Api')));
$allPhalApiApiMethods = get_class_methods('PhalApi_Api');

$allApiS = array();

foreach ($files as $value) {
    $value = realpath($value);
    $subValue = substr($value, strpos($value, D_S . 'Api' . D_S) + 1);
    $apiServer = str_replace(array(D_S, '.php'), array('_', ''), $subValue);

    if (!class_exists($apiServer)) {
        continue;
    }

    $method = array_filter(array_diff(get_class_methods($apiServer), $allPhalApiApiMethods),function($v){
        return substr($v,0,2) != '__' ? true: false;
    });

    foreach ($method as $mValue) {
        $rMethod = new Reflectionmethod($apiServer, $mValue);

        if (!$rMethod->isPublic()) {
            continue;
        }

        $title = '//请检测函数注释';
        $docComment = $rMethod->getDocComment();
        
        if ($docComment !== false) {
            $docCommentArr = explode("\n", $docComment);
            $comment = trim($docCommentArr[1]);
            $title = trim(substr($comment, strpos($comment, '*') + 1));
        }

        //获取类的信息
        $rClass = new ReflectionClass($apiServer);

        $parent_title = '//请检测类注释';
        $classDocComment = $rClass->getDocComment();
        if ($classDocComment !== false) {
            $classDocCommentArr = explode("\n", $classDocComment);
            $classComment = trim($classDocCommentArr[1]);
            $parent_title = trim(substr($classComment, strpos($classComment, '*') + 1));
        }

        $master = substr($apiServer, 4);
        $allApiS[$master]['title'] = $parent_title;
        $allApiS[$master]['api'][] = array(
            'name' => ucfirst($mValue),
            'title' => $title,
        );

    }
}

//字典排列
ksort($allApiS);

function listDir($dir) {
    $dir .= substr($dir, -1) == D_S ? '' : D_S;
    $dirInfo = array();
    foreach(glob($dir.'*') as $v) {
        if (is_dir($v)) {
            $dirInfo = array_merge($dirInfo, listDir($v));
        } else {
            $dirInfo[] = $v; 
        }
    }
    return $dirInfo;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $apiDirName; ?> - 接口列表</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style type="text/css">
        .container {
            margin-top: 50px;
            height: 500px;
            overflow: hidden;
        }
        .container .row {
            height: 100%;
        }
        .scroll {
            height: 100%;
            overflow-y: scroll;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3 scroll">
            <ul id="myTabs" class="nav nav-pills nav-stacked">
                <?php
                    $num = 0;
                    foreach ($allApiS as $key => $item) {
                ?>
                    <li role="presentation" <?php if($num == 0) { ?>class="active"<?php } ?>><a href="#<?php echo $key;?>"><?php echo $item['title'];?></a></li>
                <?php 
                        $num++;
                    } 
                ?>
            </ul>
        </div>
        <div class="col-md-5">
            <div id="myTabContent" class="tab-content">
                <?php 
                    $num2 = 0;
                    foreach ($allApiS as $key => $item) { 
                ?>
                    <div class="tab-pane fade <?php if($num2 == 0) { ?>in active<?php } ?>" id="<?php echo $key;?>">
                        <div class="list-group">
                            <?php
                                $uri = str_ireplace('all.php', 'checkApiParams.php', $_SERVER['REQUEST_URI']);
                                foreach ($item['api'] as $k => $v) { 
                            ?>
                                <a href="<?php echo $uri .'?service='.$key.'.'. $v['name'] ;?>" target="_blank" class="list-group-item"><?php echo $v['title'];?></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php 
                    $num2++;
                    } 
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#myTabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
</script>

</body>
</html>
