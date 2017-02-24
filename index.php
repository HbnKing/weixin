<?php
require("weixin_oop_api.php");
define("TOKEN","goddess");
$echostr=$_GET["echostr"];//随机字符串。

$wx=new WeixinApi();
$wx->valid();