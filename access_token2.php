<?php
require("./weixin_oop_api.php");
$appID="wx972a617248938eb8";
$appsecret="f076a87803a399a86024ccd0a4d6e143";

/*curl初始化
$ch=curl_init();
//设置传输选项
curl_setopt($ch,CURLOPT_URL,$URL);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存

//执行curl
$outopt = curl_exec($ch);
$outoptArr = json_decode($outopt,TRUE);
echo $outoptArr['access_token'];
//关闭curl
curl_close($ch);
 *
 */
$wx = new WeixinApi($appid,$appsecret);

echo $wx->getAccessToken();