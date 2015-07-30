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
				if(!$model->save()){
					Yii::log( CVarDumper::dumpAsString ($user->errors),'error',"Add umbrella save error");
				}
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
	
	/**
	 * 返回这个伞的状态
	 */
	public function actionId($umbrellaid=''){
		$umbrellaid=empty($umbrellaid)?$_GET['id']:$umbrellaid;
		if(empty($umbrellaid)){
			echo "empty umbrellaid";
			exit();
		}
		echo $this->getUmbrellaUrl($umbrellaid);
		
	}
	
	private function getUmbrellaUrl($umbrellaid){
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=wxbutton#wechat_redirect";
		$umbrella=urlencode('http://umbrella.centurywar.cn/umbrella/id/'.$umbrellaid);
		$tem= sprintf($url,APP_ID,$umbrella);
		return WX::getShortUrl($tem);
	}
	
	
	
}