<?php
class WX {
	public static function actionAccessToken($get = false) {
		$ret = Wxaccesstoken::model ()->findByAttributes ( array ('appid' => APP_ID) );
		if (! $ret) {
			$ret = new Wxaccesstoken ();
			$ret->appid =APP_ID;
			$ret->appsecret = APP_SECRET;
			$ret->create_at = date("Y-m-d H:i:s",time());
			$ret->save ();
		}
		if (empty ( $ret->accesstoken ) || $ret->expire_at < time () || $get) {
			$app_id=APP_ID;
			$app_sec=APP_SECRET;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$app_id}&secret={$app_sec}";
			$response = Yii::app ()->curl->get ( $url );
			$response = json_decode ( $response, true );
			if (isset ( $response ['errcode'] )) {
				Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get accesstoken ERROR' );
			} else {
				$ret->accesstoken = $response ['access_token'];
				$ret->expire_at = time () + $response ['expires_in'] - 300;
				if (! $ret->save ()) {
					print_R ( $ret->errors );
				}
			}
		}
		return $ret->accesstoken;
	}
	public static function actionTicketToken($get = false) {
		$ret = Wxaccesstoken::model ()->findByAttributes ( array ('appid' =>APP_ID) );
		if (! $ret) {
			$ret = new Wxaccesstoken ();
			$ret->appid = APP_ID;
			$ret->appsecret = APP_SECRET;
			$ret->create_at = date("Y-m-d H:i:s",time());
			$ret->save ();
		}
		if (empty ( $ret->ticket ) || $ret->ticket_expire_at < time () || $get) {
			$accesstoken = WX::actionAccessToken ();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$accesstoken}&type=jsapi";
			$response = Yii::app ()->curl->get ( $url );
			$response = json_decode ( $response, true );
			if ($response ['errcode'] > 0) {
				Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get ticket ERROR' );
			} else {
				$ret->ticket = $response ['ticket'];
				$ret->ticket_expire_at = time () + $response ['expires_in'] - 300;
				if (! $ret->save ()) {
					Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get ticket save ERROR' );
				}
			}
		}
		return $ret->ticket;
	}
	
	public static function updateInfo($openid, $first = true) {
		$accesstoken = WX::actionAccessToken ();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accesstoken}&openid={$openid}";
		$response = Yii::app ()->curl->get ( $url );
		$response = json_decode ( $response, true );
		Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get wx userinfo' );
		if (isset ( $response ['errcode'] )) {
			// 这一块，只进行两次，避免重复出现循环调用问题
			if ($response ['errcode'] == 40001 && $first) {
				WX::actionAccessToken ( true );
				WX::updateInfo ( $openid, false );
			}
		} else {
			$user = User::model ()->findByAttributes ( array ('udid' => $openid ) );
			$user->nickname = $response ['nickname'];
			$user->sex = $response ['sex'];
			$user->language = $response ['language'];
			$user->city = $response ['city'];
			$user->province = $response ['province'];
			$user->country = $response ['country'];
			$user->headimgurl = $response ['headimgurl'];
			$user->subscribe_time = $response ['subscribe_time'];
			$user->remark = $response ['remark'];
			$user->groupid = $response ['groupid'];
			$user->update_at = date("Y-m-d H:i:s",time());
			if (! $user->save ()) {
				Yii::trace ( CVarDumper::dumpAsString ( $user->errors ), 'Update user info error' );
			}
		
		}
	}
	public static function updateInfoByAccesstoken($openid, $accesstoken) {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$accesstoken}&openid={$openid}&lang=zh_CN";
		$response = Yii::app ()->curl->get ( $url );
		$response = json_decode ( $response, true );
		Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get wx userinfo' );
		if (!isset ( $response ['errcode'] )) {
			$user = User::model ()->findByAttributes ( array ('udid' => $openid ) );
			$user->nickname = $response ['nickname'];
			$user->sex = $response ['sex'];
			$user->language = $response ['language'];
			$user->city = $response ['city'];
			$user->province = $response ['province'];
			$user->country = $response ['country'];
			$user->headimgurl = $response ['headimgurl'];
			$user->subscribe_time = $response ['subscribe_time'];
			$user->remark = $response ['remark'];
			$user->groupid = $response ['groupid'];
			$user->update_at = date("Y-m-d H:i:s",time());
			if (! $user->save ()) {
				Yii::trace ( CVarDumper::dumpAsString ( $user->errors ), 'Update user info error' );
			}
		
		}
	}
	
	public static function getUserWebOpenid($code){
		$appid = APP_ID;
		$appsecret =APP_SECRET;
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
		Yii::trace ( $url, 'web accesstoken from code' );
		$response = Yii::app ()->curl->get ( $url );
		$response = json_decode ( $response, true );
		if (isset ( $response ['errcode'] )) {
			Yii::trace ( CVarDumper::dumpAsString ($response), 'Error Code from Web' );
		} else {
			Yii::trace ( CVarDumper::dumpAsString ( $response ), 'get user web openid' );
			$openid=$response['openid'];
			$user=User::model()->findByAttributes(array('udid'=>$openid));
			$newuser=false;
			if(!$user){
				$user = new User ();
				$user->udid = $openid;
				$user->create_at=WX::getTime();
				//如果是新用户，那么取下用户基本信息吧
				$newuser=true;
			}
			$user->accesstoken=$response['access_token'];
			$user->refresh_token=$response['refresh_token'];
			$user->expires_in=time()+$response['expires_in']-200;
			$user->token_refreshed=intval(0);
			if(!$user->save()){
				Yii::log ( CVarDumper::dumpAsString ($user->errors),'error', 'User Web AccessToken Save Error' );
			}
			if($newuser){
				WX::updateInfo($openid);
			}
			return $openid;
		}
	}
	
	public static function getShortUrl($url){
		$accesstoken=WX::actionAccessToken();
		$urlpost="https://api.weixin.qq.com/cgi-bin/shorturl?access_token=$accesstoken";
		$params=array('action'=>'long2short','long_url'=>$url);
		$response = Yii::app ()->curl->post ( $urlpost,$params,'json' );
		Yii::log ( $url ,'trace', 'GET short url' );
		$response = json_decode ( $response, true );
		Yii::log ( CVarDumper::dumpAsString ($response),'error', 'GET short url' );
		if($response['errcode']==0){
			return $response['short_url'];
		}else{
			if( $response ['errcode']==41001){
// 				WX::actionAccessToken(true);
			}
			return "";
		}
	}
	public static function getTime(){
		return date("Y-m-d H:i:s",time());
	}
}