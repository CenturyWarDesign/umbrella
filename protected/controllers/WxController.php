<?php
include_once "WxBaseController.php";
class WxController extends WxBaseController {
	public function filters() {
		// return the filter configuration for this controller, e.g.:
		return array (
				'LogRequest'
		);
	}
	public function actionIndex() {
		if (Yii::app ()->params ['wxvaild']) {
			$this->valid ();
		} else {
			if ($this->checkSignature ()){
				$this->responseMsg ();
			}else{
				Yii::app()->end();
			}
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
	protected function messageEvent($postObj){
		$event = $postObj->Event;
		switch ($event){
			case 'subscribe':
				$user=User::model ()->findByAttributes ( array ('udid' => $postObj->FromUserName ) );
				if (! $user) {
					$user = new User ();
					$user->udid = $postObj->FromUserName;
					$user->create_at=$this->getTime();
				}else{
					$user->status = 1;
					$user->times++;
				}
				$user->save();
				WX::updateInfo($postObj->FromUserName);
				$this->returnText ( 'love,love', $postObj );
				break;
			case 'unsubscribe' :
				$user=User::model ()->findByAttributes(array('udid'=>$postObj->FromUserName));
				if($user){
					$user->status=0;
					$user->update_at=$this->getTime();
					$user->save();
				}
				$this->returnText ( 'dont,love,love', $postObj );
				break;
			case 'LOCATION' :
				$user=User::model ()->findByAttributes(array('udid'=>$postObj->FromUserName));
				if($user){
					$user->x=floatval($postObj->Latitude);
					$user->y=floatval($postObj->Longitude);
					$user->locate=floatval($postObj->Precision);
					$user->update_at=$this->getTime();
					$user->save();
				}
				$this->returnText ( 'get x,y', $postObj );
				break;
			case 'CLICK' :
				$keyvalue=$postObj->EventKey;
				if($keyvalue=='TEM_COMMUNITY'){
					//创建临时的网点
					CommunityController::createOrUpdate($postObj->FromUserName);
				}
				$this->returnText ( '创建临时网点成功', $postObj );
				break;
		}
	}
	
	public function responseMsg() {
		$postStr='';
		if(isset($_GET['encrypt_type'])&&$_GET['encrypt_type']=='aes'){
			$pc = new WXBizMsgCrypt(TOKEN,Yii::app()->params['encodingAesKey'], APP_ID);
			$errCode = $pc->decryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $GLOBALS ["HTTP_RAW_POST_DATA"], $postStr);
			if($errCode!=0){
				//如果第一次解密失败，那么再试一次上一个串
				$pc = new WXBizMsgCrypt(TOKEN,Yii::app()->params['encodingAesKey2'], APP_ID);
				$errCode = $pc->decryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $GLOBALS ["HTTP_RAW_POST_DATA"], $postStr);
				if($errCode>0){
					Yii::log ( $errCode, 'error', 'WX_MESSAGE_AES_ERROR' );
					Yii::app()->end();
				}
				$this->encodingAesKey=Encoding_Aes_Key;
			}else{
				$this->encodingAesKey=Encoding_Aes_Key2;
			}
			$this->restype=$_GET['encrypt_type'];
			Yii::log ( CVarDumper::dumpAsString ( $postStr ), 'trace', 'WX_MESSAGE_AES_RESPONSE' );
		}else{
			$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		}
		
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$messagetype = $postObj->MsgType;
			$time = time ();
			if($messagetype=='text'){
				$this->returnText('I love you',$postObj);
			}else if($messagetype=='event'){
				$this->messageEvent($postObj);
			}
			
			if($messagetype!='event'){
				$this->messageLog($postObj);
			}else{
				$this->eventLog($postObj);
			}
			
		} else {
			echo "";
			exit ();
		}
	}

	
	private function messageLog($postObj){
		$message=new MessageLog();
		$message->openid=$postObj->FromUserName;
		$message->createtime=intval($postObj->CreateTime);
		$message->msgtype=$postObj->MsgType;
		$message->msgid=$postObj->MsgId;
		$message->content=isset($postObj->Content)?$postObj->Content:'';
		$message->picurl=isset($postObj->PicUrl)?$postObj->PicUrl:'';
		$message->mediaid=isset($postObj->MediaId)?$postObj->MediaId:'';
		$message->format=isset($postObj->Format)?$postObj->Format:'';
		$message->recognition=isset($postObj->Recognition)?$postObj->Recognition:'';
		$message->thumbmediaid=isset($postObj->ThumbMediaId)?$postObj->ThumbMediaId:'';
		$message->location_x= floatval(isset($postObj->Location_X)?$postObj->Location_X:null);
		$message->location_y=floatval(isset($postObj->Location_Y)?$postObj->Location_Y:null);
		$message->scale=floatval(isset($postObj->Scale)?$postObj->Scale:null);
		$message->lable=isset($postObj->Label)?$postObj->Label:'';
		$message->title=isset($postObj->Title)?$postObj->Title:'';
		$message->description=isset($postObj->Description)?$postObj->Description:'';
		$message->url=isset($postObj->Url)?$postObj->Url:'';
		if(!$message->save()){
			Yii::trace(CVarDumper::dumpAsString($message->errors),'save message_log error');
		}
	}
	private function eventLog($postObj){
		$event=new EventLog();
		$event->openid=$postObj->FromUserName;
		$event->createtime=intval($postObj->CreateTime);
		$event->event=$postObj->Event;
		$event->eventkey=isset($postObj->EventKey)?$postObj->EventKey:'';
		$event->ticket=isset($postObj->Ticket)?$postObj->Ticket:'';
		$event->latitude=floatval(isset($postObj->Latitude)?$postObj->Latitude:null);
		$event->longitude=floatval(isset($postObj->Longitude)?$postObj->Longitude:null);
		$event->precision=floatval(isset($postObj->Precision)?$postObj->Precision:null);
		if(!$event->save()){
			Yii::trace(CVarDumper::dumpAsString($event->errors),'save event_log error');
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
		$this->retRes($resultStr);
	}
	
	private function retRes($resultStr){
		if($this->restype=='aes'){
			$pc = new WXBizMsgCrypt ( TOKEN, $this->encodingAesKey, APP_ID );
			$encryptMsg = '';
			$errCode = $pc->encryptMsg ( $resultStr, $_GET ['timestamp'], $_GET ['nonce'], $encryptMsg );
			if ($errCode > 0) {
				Yii::log ( $errCode, 'error', 'WX_MESSAGE_AES_ERROR' );
			} else {
				echo $encryptMsg;
			}
		}else{
			echo $resultStr;
		}
	}	
	
	
	private function checkSignature() {
		if(in_array(SERVER_NAME, array('mac','yicheng'))){
			return true;
		}
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
}
