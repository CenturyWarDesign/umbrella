<?php

class UmbrellaController extends BaseController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionList(){
		$this->render('index');
	}
	public function actionAdd()
	{

		$model=new Umbrella;
		if(isset($_POST['Umbrella']))
		{
			
			$model->attributes=$_POST;
			$model->umbrellaid=uniqid('',true);
			$model->attributes=$_POST['Umbrella'];
			$model->create_userid=$this->user_id;
			$model->now_userid=$this->user_id;
			$model->create_at=$this->getTime();
			if($model->validate())
			{
				$model->img=$this->updateWxImage($model->img);
				$model->save();
				// form inputs are valid, do something here
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-qrcode/jquery.qrcode.min.js');
				$this->render('addsuccess',array('umbrellaid'=>$model->umbrellaid,'status'=>'success'));
				return;
			}
		}
		$this->render('add',array('model'=>$model,'status'=>'new'));
	}
	
	public function actionAddSuccess(){
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-qrcode/jquery.qrcode.min.js');
		$this->render('addsuccess',array('umbrellaid'=>uniqid('',true),'status'=>'success'));
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