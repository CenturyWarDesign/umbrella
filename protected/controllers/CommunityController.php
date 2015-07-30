<?php

class CommunityController extends BaseController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	
	public static function createOrUpdate($udid){
		$type='update';
		$userid=UserIdentity::getuserid($udid);
		$community=Community::model()->findByAttributes(array('user_id'=>$userid));
		if(!$community){
			$community=new Community();
			$type='create';
		}
		$userlocate=User::model()->get_locate()->findByPk($userid);
		$community->user_id=$userid;
		$community->begin_time=self::getTime();
		$community->lng=$userlocate->y;
		$community->lat=$userlocate->x;
		$community->type=1;
		$community->des=$userlocate->nickname."的漂流伞";
		if(!$community->save()){
			Yii::log(CVarDumper::dumpAsString ( $community->errors ),'error','Community new save error');
		}else {
			BDLbs::createOrUpdate(intval($community->communityid),$community->des, $community->des, $community->lat, $community->lng,$type);
			return true;
		}
	}
}