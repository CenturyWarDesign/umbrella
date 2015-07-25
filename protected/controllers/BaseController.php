<?php
class BaseController extends Controller
{
	public $signPackage='';
	public $openid='';
	public function init()
	{
		Yii::log(Yii::app()->getRequest()->getRequestUri(), 'trace', 'API_RAW_REQUEST_URL');
		if(!empty($_GET['code'])&&!empty($_GET['state'])&&$_GET['state']=='wxbutton'){
			$temopenid=WX::getUserWebOpenid($_GET['code']);
			if(!empty($temopenid)){
				Yii::app()->session['openid']=$this->openid;
			}
		}
		$this->openid=Yii::app()->session['openid'];
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-2.1.4.min.js');
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/css/bootstrap.css');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap/js/bootstrap.min.js');
		Yii::app()->clientScript->registerScriptFile("http://res.wx.qq.com/open/js/jweixin-1.0.0.js");
		
		$this->signPackage=$this->getSignPackage();
	}
	
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	public function getSignPackage() {
		$jsapiTicket = WX::actionTicketToken();
	
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
	
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	
		$signature = sha1($string);
	
		$signPackage = array(
				"appId"     => Yii::app()->params['appid'],
				"nonceStr"  => $nonceStr,
				"timestamp" => $timestamp,
				"url"       => $url,
				"signature" => $signature,
				"rawString" => $string
		);
		return $signPackage;
	}
	
	public function actiongetBaiduPoint(){
		$latitude=$_POST['x'];
		$longitude=$_POST['y'];
		$url = "http://api.map.baidu.com/ag/coord/convert";
		$params=array('from'=>0,'to'=>4,'x'=>$latitude,'y'=>$longitude);
		$response = Yii::app ()->curl->get ( $url,$params );
		$response = json_decode ( $response, true );
		if($response['error']===0){
			$response['x']=base64_decode($response['x']);
			$response['y']=base64_decode($response['y']);
		}
		echo json_encode($response);
	}
}