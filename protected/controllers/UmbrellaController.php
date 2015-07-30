<?php

class UmbrellaController extends BaseController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionList(){
		$umbrellalist=Umbrella::model()->findAllByAttributes(array(),'(create_userid=:user_id or now_userid=:user_id) ',array(':user_id'=>$this->user_id));
		$nowlist=array();
		$borrowlist=array();
		$loanlist=array();
		$createlist=array();
		foreach ($umbrellalist as $umbrella){
			if($umbrella->now_userid==$this->user_id){
				$nowlist[]=$umbrella;
				if($umbrella->create_userid!=$this->user_id){
					$borrowlist[]=$umbrella;
				}
			}
			if($umbrella->create_userid==$this->user_id){
				$createlist[]=$umbrella;
				if($umbrella->now_userid!=$this->user_id){
					$loanlist[]=$umbrella;
				}
			}
		}
		$this->render('list',array(
				'nowlist'=>$nowlist,
				'borrowlist'=>$borrowlist,
				'loanlist'=>$loanlist,
				'createlist'=>$createlist,
				));
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
			if(SERVER_TEST){
				$model->img="http://umbrella.b0.upaiyun.com/991c/991c1b1f34d6d076bcf7e01925af1e43/7GTMyFS_QW00m6fdAzzdphLPJtGAvlF9Gd4ivkWNQ9-olR2mFgn6NBn8JW8e78H2.jpg";
			}
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
			$umbrella->status=STATUS::getStatus($umbrella->status);
			$actions=array('cancle');
			
			//在自己的手里
			if($umbrella->now_userid==$this->user_id){
				if($umbrella->create_userid!=$this->user_id){
					$actions[]='share';
				}
				else{
					$actions[]='share';
				}
			}else{
				//扫描不在自己手中的伞，如果创建者是自己，那么就是收回 
				if($umbrella->create_userid==$this->user_id){
					$actions[]='recovery';
				}
				else{
					$actions[]='borrow';
				}
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
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=wxbutton#wechat_redirect";
		$umbrella=urlencode('http://umbrella.centurywar.cn/umbrella/info/id/'.$umbrellaid);
		$tem= sprintf($url,APP_ID,$umbrella);
		$ret=WX::getShortUrl($tem);
		if(empty($ret)){
			return $tem;
		}
		return $ret;
	}
	
	public function actionBorrow(){
		$umbrellaid=$_POST['umbrellaid'];
		$umbrella=Umbrella::model()->findByAttributes(array('umbrellaid'=>$umbrellaid));
		if($umbrella->now_userid==$this->user_id){
			$this->jsonReturn("borrow ok", CODE::UMBRELLA_BORROW_SELF);
		}
		
		$borrowstatus=STATUS::BORROW_BORROW;
		$umbrellastatus=STATUS::BORROWED;
		
		$fromuserid=$umbrella->now_userid;
		$touserid=$this->user_id;
		
		if($umbrella->create_userid==$this->user_id){
			//这里是回收
			$borrowstatus=STATUS::BORROW_RETURN;
			$umbrellastatus=STATUS::IDLE;
		}
		
		$umblog=new BorrowLog();
		$umblog->umbrellaid=$umbrella->id;
		$umblog->user_id=intval($touserid);
		$umblog->borrowed_from=intval($fromuserid);
		$umblog->borrowed_at=strtotime($umbrella->update_at)==0?$umbrella->create_at:$umbrella->update_at;
		$umblog->repaid_at=$this->getTime();
		$umblog->borrowed_x=$_POST['latitude'];
		$umblog->borrowed_y=$_POST['longitude'];
		$umblog->borrowed_type=$borrowstatus;
		if(!$umblog->save()){
			$this->jsonReturn("unknow", CODE::ERROR);
		}
		
		//记录完成，要修改状态
		$umbrella->now_userid=$this->user_id;
		$umbrella->update_at=$this->getTime();
		$umbrella->status=$umbrellastatus;
		$umbrella->umbrellaid=uniqid('',true);
		if(!$umbrella->save()){
			$this->jsonReturn("unknow", CODE::ERROR);
		}
		
		$this->jsonReturn("borrow ok", CODE::OK);
	}
}


class STATUS{
	const ISNEW=null;
	const IDLE=0;
	const BORROWED=1;
	const REPAIR=-1;
	const OVERTIME=-2;
	const LOST=-3;
	const ORDER=2;
	
	const BORROW_RETURN=100;
	const BORROW_BORROW=101;
	const BORROW_ORDER=102;
	const BORROW_SEND=103;
	protected static $status = array(
			self::ISNEW=>'新创建',
			self::IDLE=>'空闲',
			self::BORROWED=>'已借出',
			self::REPAIR=>'在维修',
			self::OVERTIME=>'超期',
			self::LOST=>'丢失',
			self::ORDER=>'被预定',
			
			self::BORROW_RETURN=>'归还操作',
			self::BORROW_BORROW=>'借伞操作',
			self::BORROW_ORDER=>'预定操作',
			self::BORROW_SEND=>'送伞操作',
	);
	public static function getStatus($statuscode) {
		return isset(self::$status[$statuscode])?self::$status[$statuscode]:"";
	}
}
