<?php

class WxmanageController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionUpdateItem(){
		$appid= APP_ID;
		$nearby=urlencode('http://umbrella.centurywar.cn/borrow/nearby');
		$book=urlencode('http://umbrella.centurywar.cn/borrow/book');
		$borrowlist=urlencode('http://umbrella.centurywar.cn/borrow/list');
		$umbrellalist=urlencode('http://umbrella.centurywar.cn/umbrella/list');
		$umbrella=urlencode('http://umbrella.centurywar.cn/umbrella/id/wb2398479286519871.837486');
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=wxbutton#wechat_redirect";
		$tem= sprintf($url,$appid,$umbrella);
		$item=array('button'=>array(
				array('name'=>'我要借伞','sub_button'=>array(
						array('name'=>'快速扫码','type'=>'scancode_push','key'=>'wechat_scan_push'),
						array('name'=>'附近网点','type'=>'view','url'=>sprintf($url,$appid,$nearby)),
						array('name'=>'我要预约','type'=>'view','url'=>sprintf($url,$appid,$nearby)),
						array('name'=>'我的二维码','type'=>'view','url'=>sprintf($url,$appid,$borrowlist)),
						)),
				array('name'=>'我是网点','sub_button'=>array(
						array('name'=>'扫码收伞','type'=>'scancode_push','key'=>'wechat_scan_push'),
						array('name'=>'库存管理','type'=>'view','url'=>sprintf($url,$appid,$umbrellalist)),
						array('name'=>'临时网点','type'=>'click','key'=>'TEM_COMMUNITY'),
						)),
				array('name'=>'快伞助手','sub_button'=>array(
						array('name'=>'帮助','type'=>'view','url'=>'http://umbrella.centurywar.cn/help'),
						array('name'=>'反馈','type'=>'view','url'=>'http://umbrella.centurywar.cn/feedback'),
						array('name'=>'最新活动','type'=>'view','url'=>'http://umbrella.centurywar.cn/activity'),
						array('name'=>'个人中心','type'=>'view','url'=>'http://umbrella.centurywar.cn/usercenter'),
						))),
				);
		echo json_encode($item);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}