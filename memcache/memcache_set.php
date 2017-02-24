<?php
//实例化对象
$mmc=new memcache();
$mmc->connect();//使用当前应用的memcache
$mmc->set("name","wang",0,10);//1.名称2值 3将参数压缩起来4缓存时间设置为0时为永久有效
echo $mmc->get("name");