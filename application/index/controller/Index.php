<?php
namespace app\index\controller;
use think\Db;

class Index{	
	//全局私有appId
	private $appId = 'wx12c922c5c036efef';
	//入口方法
	public function index()	{
		//获取消息回复并插入数据库
		$this->getData();
		//读取最新token
		//dump($this->read_token());
		//语义理解测试
		//$this->understand('oqQ9HwdyJWNgPoH4QdFvfHUJ2tiw');
    }
	
	//获取消息数据并且插入数据库
	private function getData(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
		  $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //转换成对象
		  $fromUsername = $postObj->FromUserName;
		  $toUsername = $postObj->ToUserName;
		  $keywordText = trim($postObj->Content);
		  $keywordVoice = trim($postObj->Recognition);
		  $time = time();
		  $textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
			if($keywordText == "时间" || $keywordText == "time" ||$keywordVoice == "时间。"){ //获取用户信息
				$contentStr = date("孙玉康告诉你现在时间：Y-m-d H:i:s",time()); // 回复的内容
			}elseif($keywordText == "我爱你" || $keywordText == "I love you" ||$keywordVoice == "我爱你。"){
				$contentStr = "我也爱你。"; // 回复的内容
			}elseif($keywordText == "我儿子都五岁了" ||$keywordVoice == "我儿子都五岁了。"){
				$contentStr = "就喜欢你这样的老女人。"; // 回复的内容
			}else{
				// default回复的内容
				$contentStr = "我已经和你确认过眼神，你就是我要找的人。"; 
			}
				$msgType = "text";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				
				$data = [ 'FromUserName'=>$fromUsername,'ToUserName'=>$toUsername,'CreateTime'=>$time,'MsgType'=>$postObj->MsgType,'MediaID'=>$postObj->MediaId,'Format'=>$postObj->Format,'Content'=>$keywordText,'Recognition'=>$keywordVoice,'Reply'=>$contentStr ];
				Db::table('wx_message')->insertGetId($data);
		}else{
		  echo "";
		  exit;
		}
	}
		
	//语义理解接口调用
	private function understand($openId){
		//调用最新access_token
		$access_token = $this->read_token();
		if(!$access_token)$this->understand();
		$url = 'https://api.weixin.qq.com/semantic/semproxy/search?access_token='.$access_token;
		dump($url);
		$send_string = '{
			"query":"查一下明天从北京到上海的南航机票",
			"city":"北京","category": "flight,hotel",
			"appid":"'.$this->appId.'",
			"uid":"'.$openId.'"
			}';
		dump($send_string);
		//$send_json = json_encode($send_string);
		//dump($send_json);
		$data = $this->http_post($url,$send_string);
		return $data;
		dump($data);
	}
		
    //读取最新access_token
    private function read_token(){
		$token = Db::table('wx_developer')->order('id desc')->limit(1)->select();
		//dump($token);
		if($token){
			if(($token[0]['expires_in'] + $token[0]['createAt']) < time())$this->getAccessToken();
				return $token[0]['accessToken'];			
		}else{
			$this->getAccessToken();
			exit;
		}
    }
	
	//获取access_token
	private function getAccessToken(){
		//请求url地址
		$appId = $this->appId;
		$appSecret = 'e198b60841a4c26e169237d16edb6469';
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
        $data = json_decode($this->http_get($url));
		//dump($data);
        if($data->access_token){
			//access_token写入数据库
			$accessData = [ 'accessToken'=>$data->access_token,'createAt'=>time(),'expires_in'=>$data->expires_in ];
			$maxId = Db::table('wx_developer')->insertGetId($accessData);
			//只保留2条数据
			if( Db::table('wx_developer')->count() >2 )$token = Db::table('wx_developer')->where( 'id','<',$maxId-1 )->delete();
        }else{
            echo $data->errmsg;
        }
		$this->read_token();//重新读取最新token
	}
	
	  /**
     * 传入json数据进行HTTP Get请求
     *
     * @param string $url $data_string
     * @return string
     */
	public function http_get($url)
	{
		  $curl = curl_init(); // 启动一个CURL会话
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
		    $tmpInfo = curl_exec($curl);     //返回api的json对象
		    //关闭URL请求
		    curl_close($curl);
		    return $tmpInfo;    //返回json对象
		
	}
	
	/**
     * 传入json数据进行HTTP POST请求
     *
     * @param string $url $data_string
     * @return string
     */
    public static function http_post($url,$data_string,$timeout = 60)
    {
        //curl验证成功
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//// 跳过证书检查 
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
 
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
}
?>