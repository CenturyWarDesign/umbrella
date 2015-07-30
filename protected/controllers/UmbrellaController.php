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
// 			$model->status=UMBRELLASTATUS::IDLE;
			if($model->validate())
			{
				$model->img=$this->updateWxImage($model->img);
				if(!$model->save()){
					Yii::log( CVarDumper::dumpAsString ($user->errors),'error',"Add umbrella save error");
				}
				$this->actionInfo($model->umbrellaid);
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
	public function actionInfo($id){
		$umbrellaid=strval($id);
		if(empty($umbrellaid)){
			echo "empty umbrellaid";
			exit();
		}
		$umbrella=Umbrella::model()->findByAttributes(array('umbrellaid'=>$umbrellaid));
		if($umbrella){
			$create_user=User::model()->findByPk($umbrella->create_userid);
			$now_user=User::model()->findByPk($umbrella->now_userid);
			$umbrella->status=UMBRELLASTATUS::getStatus($umbrella->status);
			$actions=array('cancle');
			if ($umbrella->create_userid != $umbrella->now_userid) {
				//目前在外面，可以收回
				if($this->user_id!=$umbrella->create_userid){
					$actions[]='recovery';
				}else{
					$actions[]='borrow';
				}
			}else{
				$actions[]='share';
			}
			Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/css/jquery-qrcode/jquery.qrcode.min.js');
			$this->render('info',array('umbrella'=>$umbrella,
					'create_user'=>$now_user,
					'now_user'=>$create_user,
					'url'=>$this->getUmbrellaUrl($umbrellaid),
					'actions'=>$actions,'message'
					));
		}else{
			
		}
	}
	
	private function getUmbrellaUrl($umbrellaid){
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=wxbutton#wechat_redirect";
		$umbrella=urlencode('http://umbrella.centurywar.cn/umbrella/id/'.$umbrellaid);
		$tem= sprintf($url,APP_ID,$umbrella);
		return WX::getShortUrl($tem);
	}
}

class UMBRELLASTATUS{
	const ISNEW=null;
	const IDLE=0;
	const BORROWED=1;
	const REPAIR=-1;
	const OVERTIME=-2;
	const LOST=-3;
	const ORDER=2;
	protected static $status = array(
			self::ISNEW=>'新创建',
			self::IDLE=>'空闲',
			self::BORROWED=>'已借出',
			self::REPAIR=>'在维修',
			self::OVERTIME=>'超期',
			self::LOST=>'丢失',
			self::ORDER=>'被预定',
			);
	public static function getStatus($statuscode) {
		return isset(self::$status[$statuscode])?self::$status[$statuscode]:"";
	}
}