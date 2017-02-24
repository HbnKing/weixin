<?php
	require('weixin_oop_api.php');
	define("TOKEN","moocba");
	$echostr = $_GET['echostr'];//随机字符串

	$wx = new WeixinApi();

	if(isset($_GET['echostr']))
	{
		$wx->valid();
	}
	else
	{
		$wx->responseMsg();
	}
	
?>