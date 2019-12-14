<?php
include "./curl.php";
include "./qiniu.php";
include "./wangyi.php";
include "./ucloud.php";
include "./tencent.php";
include "./aws.php";

//$r = 七牛对象存储上传('index.html', 'F:/index.html');print_r($r);
//$r = 七牛对象存储删除('index.html');print_r($r);
//$r = 七牛对象存储列表('&limit=&marker=&prefix=&delimiter=');print_r($r);

//$r = UCloud对象存储上传('index.html', 'F:/index.html');print_r($r);
//$r = UCloud对象存储删除('index.html');print_r($r);
//$r = UCloud对象存储列表('?list&limit=&marker=&prefix=');print_r($r);

//$r = 网易对象存储上传('index.html', 'F:/index.html');print_r($r);
//$r = 网易对象存储删除('index.html');print_r($r);
//$r = 网易对象存储列表('?max-keys=&marker=&prefix=&delimiter=');print_r($r);

//$r = 腾讯对象存储上传('index.html', 'F:/index.html');print_r($r);
//$r = 腾讯对象存储删除('index.html');print_r($r);
//$r = 腾讯对象存储列表('?max-keys=&marker=&prefix=&delimiter=');print_r($r);

//$r = 亚马逊对象存储上传('index.html', 'F:/index.html');print_r($r);
//$r = 亚马逊对象存储删除('index.html');print_r($r);
//$r = 亚马逊对象存储列表('?delimiter=&encoding-type=url&marker=&max-keys=&prefix=');print_r($r);