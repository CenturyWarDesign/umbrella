<?php
class WX {
	public static function actionAccessToken($get = false) {
		$ret = Wxaccesstoken::model ()->findByAttributes ( array ('appid' => Yii::app ()->params ['appid'] ) );
		if (! $ret) {
			$ret = new Wxaccesstoken ();
			$ret->appid = Yii::app ()->params ['appid'];
			$ret->appsecret = Yii::app ()->params ['appsecret'];
			$ret->create_at = date("Y-m-d H:i:s",time());
			$ret->save ();
		}
		if (empty ( $ret->accesstoken ) || $ret->expire_at < time () || $get) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$ret->appid}&secret={$ret->appsecret}";
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
		$ret = Wxaccesstoken::model ()->findByAttributes ( array ('appid' => Yii::app ()->params ['appid'] ) );
		if (! $ret) {
			$ret = new Wxaccesstoken ();
			$ret->appid = Yii::app ()->params ['appid'];
			$ret->appsecret = Yii::app ()->params ['appsecret'];
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
					print_R ( $ret->errors );
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
}