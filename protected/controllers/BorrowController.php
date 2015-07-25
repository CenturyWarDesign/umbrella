<?php
include_once "BaseController.php";
class BorrowController extends BaseController
{
	
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionNearby(){
		Yii::app()->clientScript->registerScriptFile("http://developer.baidu.com/map/jsdemo/demo/convertor.js");
		Yii::app()->clientScript->registerScriptFile("http://api.map.baidu.com/api?type=quick&ak=rodKy1ym1a848VUrDE3vqj4w&v=1.0");
		$this->render('nearby');
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