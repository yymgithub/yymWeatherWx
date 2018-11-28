<?php 
  //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'yym';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }


else{

        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号,此公众号为测试公众号！';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }
      
      //判断该数据包是否是文本消息
        if( strtolower( $postObj->MsgType) == 'text'){
             //接受文本信息
    		$content =trim($postObj->Content);
             //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
          		$str = mb_substr($content,-2,2,"UTF-8");
                $str_key = mb_substr($content,0,-2,"UTF-8");
                $fp=fopen("./data.txt",'a');
          		fwrite($fp,$str);
         		fwrite($fp,$str_key);
                
                if($str == '天气' && !empty($str_key)){
                  $cityCode = file_get_contents("http://154.8.195.15/city/".$str_key);
                  $data = json_decode($cityCode,true);
                  $cCode = $data["data"];
                  $weather = file_get_contents("http://154.8.195.15/weather/".$cCode);
                  $dataw = json_decode($weather,true);
                  if(empty($dataw['data'])){
                    $weather = file_get_contents("http://154.8.195.15/weather/101010100");
                    $dataw = json_decode($weather,true);
                  }
                  $datas= $dataw['data'];
                  fwrite($fp,$datas[0]["id"]);
                  $content = "【".$str_key."天气预报】\n".$datas[0]["update_time"]."时发布\n\n实时天气\n".$datas[0]["today_weather"]." ".$datas[0]["today_temperature"]." ".$datas[0]["today_wind"]."\n\n温馨提示:".$datas[0]["today_suggestion"]."\n\n明天\n".$datas[0]["one_weather"]." ".$datas[0]["one_temperature"]." ".$datas[0]["one_wind"]."\n\n后天\n".$datas[0]["two_weather"]." ".$datas[0]["two_temperature"]." ".$datas[0]["two_wind"];
                }
                else{
                  $content = "请输入正确查询格式";
                }
                fclose($fp);
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
        }
}

 