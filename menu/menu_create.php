<?php

//获取接口调用凭证
$appid = "wx972a617248938eb8";
$appsecret = "f076a87803a399a86024ccd0a4d6e143";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret=$appsecret";

//curl初始化
$ch = curl_init();

//设置传输选项
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存

//执行curl
$outopt = curl_exec($ch);
$outoptArr = json_decode($outopt,TRUE);
$access_token = $outoptArr['access_token'];
//关闭curl
curl_close($ch);


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
$post = '{
		     "button":[
		      {	
		          "name":"关于我们",
		          "sub_button":
		          [
		            {
		          		"type":"click",
		          		"name":"看一看",
		          		"key":"STORY"
		          	 },
		          	 {
		          		"type":"click",
		          		"name":"瞧一瞧",
		          		"key":"WORKS"
		            	}
		          	
		          ]
		      },
		      {"name":"相关网站",
		          "sub_button":[
		          {
		           "name":"github",
		           "type":"view",
		           "url":"https://github.com/hbnking"
		           }
		           ]
		       }

		       ]
		 }';
//curl初始化
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$outopt = curl_exec($ch);
curl_close($ch);
echo $outopt;
