<?php

define ( "TOKEN", "dLneoDa897Dn2Ac" );
class WxController extends Controller {
	public function actionIndex() {
		if (Yii::app ()->params ['wxvaild']) {
			$this->valid ();
		} else {
			$this->responseMsg ();
		}
	}
	public function valid() {
		$echoStr = $_GET ["echostr"];
		// valid signature , option
		if ($this->checkSignature ()) {
			echo $echoStr;
			exit ();
		}
	}
	protected function messageText(){
		
	}
	protected function messageEvent($postObj){
		$event = $postObj->Event;
		switch ($event){
			case 'subscribe':
				$user=UmbUser::model ()->findByAttributes ( array ('udid' => $postObj->FromUserName ) );
				if (! $user) {
					$user = new UmbUser ();
					$user->udid = $postObj->FromUserName;
					$user->create_at=$this->getTime();
				}else{
					$user->status = 1;
					$user->times++;
				}
				$user->save();
				$this->actionUpdateInfo();
				$this->returnText ( 'love,love', $postObj );
				break;
			case 'unsubscribe' :
				$user=UmbUser::model ()->findByAttributes(array('udid'=>$postObj->FromUserName));
				if($user){
					$user->status=0;
					$user->update_at=$this->getTime();
					$user->save();
				}
				$this->returnText ( 'dont,love,love', $postObj );
				break;
		}
	}
	
	public function responseMsg() {
		// get post data, May be due to the different environments
		// $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		// var_dump($_POST);
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		// extract post data
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$messagetype = $postObj->MsgType;
			$time = time ();
			if($messagetype=='text'){
				$this->returnText('I love you',$postObj);
			}else if($messagetype=='event'){
				$this->messageEvent($postObj);
			}
		} else {
			echo "";
			exit ();
		}
	}
	
	private function returnText($contentStr='Welcome to wechat world!',$postObj){
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";
		$time = time ();
		$resultStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, "text", $contentStr );
		echo $resultStr;
	}
	
	private function checkSignature() {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		$token = TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	public function filters() {
		// return the filter configuration for this controller, e.g.:
		return array (
				'LogRequest' 
		);
	}
	public function filterLogRequest($filterChain) {
		Yii::log ( CVarDumper::dumpAsString ( $_REQUEST ), 'trace', 'API_RAW_REQUEST' );
		$filterChain->run ();
	}
	
	
	public function actionAccessToken(){
		$ret=Wxaccesstoken::model()->findByAttributes ( array ('appid' => Yii::app()->params['appid']) );
		if(!$ret){
			$ret=new Wxaccesstoken();
			$ret->appid=Yii::app()->params['appid'];
			$ret->appsecret=Yii::app()->params['appsecret'];
			$ret->create_at=$this->getTime();
			$ret->save();
		}
		if(empty($ret->accesstoken)||$ret->expire_at<time()){
			$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$ret->appid}&secret={$ret->appsecret}";
			$response=Yii::app()->curl->get($url);
			$response=json_decode($response,true);
			if(isset($response['errcode'])){
				Yii::trace(CVarDumper::dumpAsString($response),'get accesstoken ERROR');
			}else{
				$ret->accesstoken=$response['access_token'];
				$ret->expire_at=time()+$response['expires_in']-60;
				if(!$ret->save()){
					print_R($ret->errors);
				}
			}
		}
		return $ret->accesstoken;
	}
	public function actionUpdateInfo(){
		$openid=$_GET['openid'];
		$accesstoken=$this->actionAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accesstoken}&openid={$openid}";
		$response=Yii::app()->curl->get($url);
		$response=json_decode($response,true);
		Yii::trace(CVarDumper::dumpAsString($response),'get wx userinfo');
		if(isset($response['errcode'])){
			
		}else{
			$user=UmbUser::model ()->findByAttributes ( array ('udid' => $openid) );
			$user->nickname=$response['nickname'];
			$user->sex=$response['sex'];
			$user->language=$response['language'];
			$user->city=$response['city'];
			$user->province=$response['province'];
			$user->country=$response['country'];
			$user->headimgurl=$response['headimgurl'];
			$user->subscribe_time=$response['subscribe_time'];
			$user->remark=$response['remark']; 
			$user->groupid=$response['groupid']; 
			$user->update_at=$this->getTime();
			if (!$user->save()){
				Yii::trace(CVarDumper::dumpAsString($user->errors),'Update user info error');
			}
			
		}
	}
	// Uncomment the following methods and override them if needed
	/*
	 * public function actions() { // return external action classes, e.g.:
	 * return array( 'action1'=>'path.to.ActionClass', 'action2'=>array(
	 * 'class'=>'path.to.AnotherActionClass', 'propertyName'=>'propertyValue',
	 * ), ); }
	 */
}
