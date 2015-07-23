<?php
class BaseController extends Controller
{
	public function init()
	{
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-2.1.4.min.js');
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap/css/bootstrap.css');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/bootstrap/js/bootstrap.min.js');
	}
	
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
}