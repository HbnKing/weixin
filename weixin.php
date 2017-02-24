<?php


//一、实现验证服务器地址有效性
//1、接收微信服务器get请求过来的4个参数

define("TOKEN","goddess");
$signature = $_GET['signature'];//微信加密签名
$timestamp = $_GET['timestamp'];//时间戳
$nonce = $_GET['nonce'];//随机数
$echostr = $_GET['echostr'];//随机字符串


if(isset($_GET['echostr'])) //判断是否是进行验证服务器地址的有效性
{
    //2、加密/校验
    // 1. 将token、timestamp、nonce三个参数进行字典序排序；
    $tmpArr = array(TOKEN,$timestamp,$nonce);
    sort($tmpArr,SORT_STRING);

    // 2. 将三个参数字符串拼接成一个字符串进行sha1加密；
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    // 3. 开发者获得加密后的字符串与signature对比。
    if($tmpStr == $signature)
    {
        echo $echostr;
        exit;
    }
    else
    {
        echo "Error";
    }

}
else
{

    //二、接收和处理微信服务器转发过来的XML数据包

    //1、接收XML数据包
    $postData = $HTTP_RAW_POST_DATA;

    //2、处理XML数据包
    $xmlObj = simplexml_load_string($postData,"SimpleXMLElement",LIBXML_NOCDATA);

    $toUserName = $xmlObj->ToUserName; //获取开发者微信号
    $fromUserName = $xmlObj->FromUserName; //获取用户的OpenID
    $msgType = $xmlObj->MsgType; //消息的类型
    //根据消息类型来进行业务处理
    switch ($msgType) {
        case 'event':
            switch ($xmlObj->Event) {
                //接收关注事件
                case 'subscribe':
                    //下发欢迎语
                    $replyContent = "嗨～！终于等到你，从此我将助你在互联网业行业一臂之力。\n走心推肾！一直这么任性！";
                    $replyTextMsg = "<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[text]]></MsgType>
											<Content><![CDATA[%s]]></Content>
										</xml>";
                    echo sprintf($replyTextMsg,$fromUserName,$toUserName,time(),$replyContent);
                    break;
                default:
                    # code...
                    break;
            }
            break;

        case 'text':
            $content = trim($xmlObj->Content); //获取文本消息的内容//使用trim函数将两端的空格去掉
            switch ($content) {
                case '胖子':
                    //回复单图文消息
                    $replyNewsMsg = "<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[news]]></MsgType>
											<ArticleCount>1</ArticleCount>
											<Articles>
												<item>
													<Title><![CDATA[%s]]></Title>
													<Description><![CDATA[%s]]></Description>
													<PicUrl><![CDATA[%s]]></PicUrl>
													<Url><![CDATA[%s]]></Url>
												</item>
											</Articles>
										</xml> ";
                    $title = "约吗？亲！";
                    $description = "玩的就是免费就是这么任性！";
                    $picUrl = "http://1.goddess.applinzi.com/images/1.jpg";
                    $url = "http://health.sohu.com/20170218/n481075720.shtml";
                    echo sprintf($replyNewsMsg,$fromUserName,$toUserName,time(),$title,$description,$picUrl,$url);
                    break;
                case '多图文':
                    //回复多图文消息
                    //采用数组遍历的模式添加列表

                    $newsArr = array(
                        array(
                            'Title'=>"wamp，你确定？！",
                            'Description'=>"玩的就是免费就是这么任性！",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/1.jpg",
                            'Url'=>"http://health.sohu.com/20170218/n481075720.shtml"
                        ),
                        array(
                            'Title'=>"appach",
                            'Description'=>"很久很久以前……………………",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/1.jpg",
                            'Url'=>"http://kimi.it/339.html"
                        ),
                        array(
                            'Title'=>"php",
                            'Description'=>"你还在打工么",
                            'PicUrl'=>"http://1.goddess.applinzi.com/images/1.jpg",
                            'Url'=>"http://www.itheima.com/phpmap"
                        )
                    );

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

                    echo sprintf($replyNewsMsg,$fromUserName,$toUserName,time(),count($newsArr));
                    break;
                default:
                    //回复文本消息

                    $replyTextMsg = "<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[text]]></MsgType>
											<Content><![CDATA[%s]]></Content>
										</xml>";
                    echo sprintf($replyTextMsg,$fromUserName,$toUserName,time(),$content);
                    break;
            }

            break;
        case 'image':
            $picUrl = $xmlObj->PicUrl;//获取图片的URL
            $mediaId = $xmlObj->MediaId;//获取图片消息媒体id
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
            echo sprintf($replyImageMsg,$fromUserName,$toUserName,time(),$mediaId);
            break;
        default:

            break;
    }



    //三、响应消息

}
?>