<?php


$url = "http://1.goddess.applinzi.com/curl/upload.php";//定义url地址

$post = array("filename"=>"@../img/shuqi.jpg");
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, 1);//模拟POST请求

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//POST提交内容

curl_exec($ch);

curl_close($ch);

