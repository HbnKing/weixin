<?php
//封装成一个类
//1接收微信服务器get请求过来的四个参数。
//根据返回值进行输出
class WeixinApi
{
    private $appid;
    private $appsecret;

    //构造方法 对成员属性进行赋值操作的
    public function __construct($appid="",$appsecret="")
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }
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
    public function responseMsg()
    {
        //1、接收XML数据包
        $postData = $GLOBALS[HTTP_RAW_POST_DATA];//注意：这个需要设置成全局变量

        //2、处理XML数据包
        $xmlObj = simplexml_load_string($postData,"SimpleXMLElement",LIBXML_NOCDATA);

        $toUserName = $xmlObj->ToUserName; //获取开发者微信号
        $fromUserName = $xmlObj->FromUserName; //获取用户的OpenID
        $msgType = $xmlObj->MsgType; //消息的类型
        //根据消息类型来进行业务处理
        switch ($msgType) {
            case 'event':
                //接收事件推送
                echo $this->receiveEvent($xmlObj);
                break;
            case 'text':
                //接收文本消息
                echo $this->receiveText($xmlObj);//访问文本类下面的方法
                break;
            case 'image':
                //接收图片消息
                echo $this->receiveImage($xmlObj);//访问图片类下面的方法
                break;
            default:

                break;
        }
    }
    //接收事件推送
    //receiveEvent($obj)

    public function receiveEvent($obj)
    {

                switch ($obj->Event) {
                    //接收关注事件
                    case 'subscribe':
                        //下发欢迎语
                        $replyContent = "嗨～！终于等到你，你可以试试输入'男神' 或者'女神''多图文'。\n走心推肾！一直这么任性！";
                       /* $replyTextMsg = "<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[text]]></MsgType>
											<Content><![CDATA[%s]]></Content>
										</xml>";
                        return sprintf($replyTextMsg,$obj->FromUserName,$obj->ToUserName,time(),$replyContent);
                       */
                        return $this->replyText($obj,$replyContent);
                        break;
                    case 'unsubscribe':
                        //账号的解绑
                        break;
                    case 'CLICK':
                        switch($obj->EventKey){
                            case 'STORY':
                                $replyContent = "姓名：王恒
                                				email：hbn.king@gmail.com
                                                民族： 汉   出生年月：1989/05
                                                学历： 本科  居住地址：上海浦东
                                                性别： 男  电话： 15527156520
                                                求职意向 
                                                到岗时间：即时
                                                期望职位：php程序员
                                                工作性质：全职
                                                专业技能
                                                1、熟练掌握php，html，div+css，等web开发技术。
                                                2、熟练使用pdo、mysql方式操作mysql数据库系统，熟悉mysql事物及存储过程。
                                                3、熟悉html，div+css前台页面技术和xml的使用。
                                                4、熟悉网页静态化、smarty缓存，thinkphp缓存，掌握web防sql注入。
                                                5、 孰悉mvc架构开发思想模式，熟练使用smarty模板，thinkphp框架,zendframework框架。
                                                6、熟练掌握zend studio, phpstorm, eclipsephp等常用web开发工具；
                                                7、了解linux基本操作。";
                                return $this->replyText($obj,$replyContent);

                                break;
                              
                        }
                        break;
                    default:
                        # code...
                        break;
                }

    }
    //接收文本消息
    public function receiveText($obj) //访问文本类下面的方法
    {
        $content = trim($obj->Content); //获取文本消息的内容
        //关键字回复
        switch ($content) {
            case '女神':
                return $this->replyText($obj,"姓名：女神\nTEL：138000000 \n QQ：1559299956");
                break;
            case '男神':
                return $this->replyText($obj,"姓名：男神\n别称：大黑龙 \n QQ：1559299956");
                break;
              
            case '多图文':
                    //回复多图文消息
                    //采用数组遍历的模式添加列表

                    $newsArr = array(
                        array(
                            'Title'=>"开源中国？！",
                            'Description'=>"玩的就是免费就是这么任性！",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/1.jpg",
                            'Url'=>"https://www.oschina.net/"
                        ),
                        array(
                            'Title'=>"appach",
                            'Description'=>"很久以前……………………",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/3.jpg",
                            'Url'=>"http://kimi.it/339.html"
                        ),
                        array(
                            'Title'=>"php",
                            'Description'=>"你还在打工么",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/2.jpg",
                            'Url'=>"http://www.itheima.com/phpmap"
                        )
                    );
                return $this->replyNews($obj,$newsArr);//调用方法
                break;
            default:
                return $this->replyText($obj,$content);//调用方法
                break;
        }


    }


    //回复文本消息
    public function replyText($obj,$content)
    {
        //回复文本消息

        $replyTextMsg = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[text]]></MsgType>
								<Content><![CDATA[%s]]></Content>
							</xml>";
        return sprintf($replyTextMsg,$obj->FromUserName,$obj->ToUserName,time(),$content);
    }

    //接收图片消息
    public function receiveImage($obj)
    {
        $picUrl = $obj->PicUrl;//获取图片的URL
        $mediaId = $obj->MediaId;//获取图片消息媒体id
        $picArr = array('picUrl'=>$picUrl,'mediaId'=>$mediaId);
        return $this->replyImage($obj,$picArr);

        // return $this->replyText($obj,$mediaId);
    }


    //回复图片消息
    public function replyImage($obj,$array)
    {
        //回复图片消息
        $replyImageMsg = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[image]]></MsgType>
								<Image>
									<MediaId><![CDATA[%s]]></MediaId>
								</Image>
							</xml>";
        return sprintf($replyImageMsg,$obj->FromUserName,$obj->ToUserName,time(),$array['mediaId']);
    }

    //回复图文消息
    public function replyNews($obj,$newsArr)
    {
        $itemStr = "";        //初始化一个空值
        if(is_array($newsArr))//判断是否存在
        {

            foreach ($newsArr as $item)//foreach 遍历数组；
            {
                $itemTmpl = "<item>
									<Title><![CDATA[%s]]></Title>
									<Description><![CDATA[%s]]></Description>
									<PicUrl><![CDATA[%s]]></PicUrl>
									<Url><![CDATA[%s]]></Url>
							</item>";
                $itemStr .= sprintf($itemTmpl,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);//.=遍历得到的图文消息连接起来
            }
            $replyNewsMsg = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[news]]></MsgType>
									<ArticleCount>%s</ArticleCount>
									<Articles>".$itemStr."</Articles>
								</xml> ";

            return sprintf($replyNewsMsg,$obj->FromUserName,$obj->ToUserName,time(),count($newsArr));
        }
    }
    //https请求(GET和POST)
    public function https_request($url,$data=null)//设置$data默认值为空
    {
        $ch = curl_init();   //初始化curl

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存

        if(!empty($data))   //如果$data不为空则执行post请求
        {
            curl_setopt($ch, CURLOPT_POST, 1);//模拟POST请求

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST提交内容
        }

        $outopt = curl_exec($ch);//执行返回结果

        curl_close($ch);

        return json_decode($outopt,true);//返回数组结果
    }
    //获取接口调用凭证access_token
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $result = $this->https_request($url);
        return $result['access_token'];
    }
    //自定义菜单创建
    public function menu_create($post)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        return $this->https_request($url,$post);
    }

    //自定义菜单查询
    public function menu_select()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$access_token}";
        return $this->https_request($url);
    }

    //自定义菜单删除
    public function menu_delete()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$access_token}";
        return $this->https_request($url);
    }
    
    
}




