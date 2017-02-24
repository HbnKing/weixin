<?php
	//curl模拟GET请求  抓取百度首页

	$url = "http://www.baidu.com";
	//1、初始化CURL
	$ch = curl_init();

	//2、设置传输选项

	curl_setopt($ch,CURLOPT_URL,$url);

	//3、执行CURL请求

	curl_exec($ch);

	//4、关闭CURL
	curl_close($ch);
?>