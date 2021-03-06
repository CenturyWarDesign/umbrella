<?php
class BaseController extends Controller
{
	public $signPackage='';
	public $openid='';
	public $user_id=-1;
	public function init()
	{
		Yii::log(Yii::app()->getRequest()->getRequestUri(), 'trace', 'API_RAW_REQUEST_URL');
		if(!empty($_GET['code'])&&!empty($_GET['state'])&&$_GET['state']=='wxbutton'){
			$temopenid=WX::getUserWebOpenid($_GET['code']);
			if(!empty($temopenid)){
				Yii::app()->session['openid']=$temopenid;
			}
		}
		$this->openid=Yii::app()->session['openid'];
		if(in_array(SERVER_NAME, array('yicheng','mac'))){
			$this->openid='opYNAwg9MS6Btoo0cVmvvQLSaoHY';
		}
		$this->user_id=UserIdentity::getuserid($this->openid);
		
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-2.1.4.min.js');
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/css/bootstrap.css');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap/js/bootstrap.min.js');
		Yii::app()->clientScript->registerScriptFile("http://res.wx.qq.com/open/js/jweixin-1.0.0.js");

		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/yunba/socket.io-1.3.5.min.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/yunba/yunba-js-sdk.js');
		
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
				"appId"     => APP_ID,
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
	public function getTime(){
		return date('Y-m-d H:i:s');
	}
	
	/**
	 * 把微信的图片上传到又拍云服务器上
	 * @param string $medil
	 * @return string
	 */
	public function updateWxImage($medil){
		if(substr($medil, 0,4)=='http'){
			return $medil;
		}
		$accesstoken = WX::actionAccessToken ();
		$imgurl = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$accesstoken}&media_id={$medil}";
		$upyun = new UpYun ( UPYUN_BUCKET, UPYUN_USER, UPYUN_PASSWORD );
		$fh = file_get_contents ( $imgurl );
		if (empty ( $fh )) {
			return "";
		}
		$md5=md5($this->openid);
		$path="/".substr($md5, 0,4).'/'.$md5.'/'.$medil.'.jpg';
		$rsp = $upyun->writeFile($path, $fh, True);   // 上传图片，自动创建目录
		return UPYUN_CDN.$path;
	}
	
	public function jsonReturn($message,$code,$data=array()){
		$return=array('code'=>$code,'message'=>$message,'data'=>$data);
		echo json_encode($return);
		Yii::app()->end();
	}
	
	/**
	 * 某个玩家的伞被借走了
	 * 
	 * @param $openid unknown_type       	
	 * @param $umbrella unknown_type       	
	 */
	public function pushUmbrellaBeBorred($user_id, $umbrella) {
		$params = array ('umbrellaid' => $umbrella,'action'=>'borrowed' );
		$this->pushToUserid ( $user_id, $params );
	}
	
	private function pushToUserid($user_id,$message=array()){
		$array=array(
				"method"=>'publish_to_alias',
				"appkey"=>YUNBA_APPKEY,
				"seckey"=>YUNBA_SECKEY,
				"alias"=>$user_id,
				"msg"=>json_encode($message),
				);
		Yii::trace(CVarDumper::dumpAsString($array),'push to openid Value');
		Yii::app()->curl->post('http://rest.yunba.io:8080',$array,'json');
	}
}