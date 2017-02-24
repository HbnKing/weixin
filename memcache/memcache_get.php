<?php
/*
 * @authors 慕课吧-军哥 
 * @qq      413920268
 * @weixin  wx_jayjun
 * @url     http://www.moocba.com
 * @date    2016-07-26 11:18:14
 */

//实例化对象
$mmc = new Memcache();

$mmc->connect();//使用当前应用的memcache

echo $mmc->get("name");
