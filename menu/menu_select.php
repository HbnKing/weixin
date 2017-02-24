<?php

require("../weixin_oop_api.php");
//获取接口调用凭证
$appid = "";
$appsecret = "";

$wx = new WeixinApi($appid,$appsecret);
$result = $wx->menu_select();
print_r($result);
