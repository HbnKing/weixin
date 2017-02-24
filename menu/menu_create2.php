<?php

require("../weixin_oop_api.php");
//获取接口调用凭证
$appid = "wx972a617248938eb8";
$appsecret = "f076a87803a399a86024ccd0a4d6e143";

$wx = new WeixinApi($appid,$appsecret);
$post = ' {
		     "button":[
		      {	
		          "name":"关于我们",
		          "sub_button":[
		            {
		          		"type":"click",
		          		"name":"我们的故事",
		          		"key":"STORY"
		          	},
		          	{
		          		"type":"view",
		          		"name":"百度一下",
		          		"url":"http://www.baidu.com"
		          	}

		          ]
		      },
		      {
		           "name":"github",
		           "type":"view",
		           "url":"https://github.com/hbnking"
		       }]
		 }';
$result = $wx->menu_create($post);
print_r($result);
