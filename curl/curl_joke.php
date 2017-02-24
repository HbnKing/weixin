<?php
	//curl模拟GET请求  调用笑话大全接口获取幽默段子

	$url = "http://japi.juhe.cn/joke/content/text.from?page=&pagesize=&key=b10bbc3126e377305ed9ce63763be02d";
	//1、初始化CURL
	$ch = curl_init();

	//2、设置传输选项

	curl_setopt($ch,CURLOPT_URL,$url);

	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存

	//3、执行CURL请求并获取结果

	$outopt = curl_exec($ch);
	
	echo $outopt;

	//4、关闭CURL
	curl_close($ch);
?>