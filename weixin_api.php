<?php
//封装成一个类
//1接收微信服务器get请求过来的四个参数。
//根据返回值进行输出
define("TOKEN","goddess");
$echostr=$_GET["echostr"];//随机字符串。

$wx=new WeixinApi();
$wx->valid();
class WeixinApi
{
   public function valid()
    {
    if($this->checkSignature())
    {
        echo $_GET["echostr"];
    }else{
        echo "error";
    }
    }
    //代码封装-封装成一个函数
   private function  checkSignature()
    {
        $signature = $_GET["signature"];//微信加密签名
        $timestamp = $_GET["timestamp"];//时间戳
        $nonce = $_GET["nonce"];//随机数


    //2加密校验
    //1将token、timestamp、nonce三个参数进行字典序排序
        $temarr = array(TOKEN, $timestamp, $nonce);//合为一个数组
        sort($temarr, SORT_STRING);//排序

    //2将三个参数字符串拼接成一个字符串进行sha1加密
        $temarr = implode($temarr);
        $temarr = sha1($temarr);

    //3开发者获得加密后的字符串可与signature对比，标识该请求来源于微信

        if ($temarr == $signature) {
            // echo $echostr;  //函数内一般都是return
           return true;
        } else {
            // echo"error";
           return false;
        }
    }
}




