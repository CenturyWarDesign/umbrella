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
	// Uncomment the following methods and override them if needed
	/*
	 * public function actions() { // return external action classes, e.g.:
	 * return array( 'action1'=>'path.to.ActionClass', 'action2'=>array(
	 * 'class'=>'path.to.AnotherActionClass', 'propertyName'=>'propertyValue',
	 * ), ); }
	 */
}
