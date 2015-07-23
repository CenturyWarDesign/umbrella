<?php

class WxmanageController extends WxBaseController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionUpdateItem(){
		$item=array('button'=>array(array('name'=>'我要借伞','type'=>'view','url'=>'http://umbrella.centurywar.cn/borrow'),
				array('name'=>'有奖活动','sub_button'=>array(
						array('name'=>'推荐有奖','type'=>'view','url'=>'http://umbrella.centurywar.cn/activityrecommend'),
						array('name'=>'快伞客','type'=>'view','url'=>'http://umbrella.centurywar.cn/activitybecome'),
						)),
				array('name'=>'快伞助手','sub_button'=>array(
						array('name'=>'帮助','type'=>'view','url'=>'http://umbrella.centurywar.cn/help'),
						array('name'=>'问题反馈','type'=>'view','url'=>'http://umbrella.centurywar.cn/feedback'),
						array('name'=>'我是网点','type'=>'view','url'=>'http://umbrella.centurywar.cn/community'),
						array('name'=>'个人中心','type'=>'view','url'=>'http://umbrella.centurywar.cn/usercenter'),
						))),
				);
		print_R($item);
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