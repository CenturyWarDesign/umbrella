<?php
include_once "BaseController.php";
class BDLbs extends Controller
{
	protected static $baiduapi='http://api.map.baidu.com';
	
	public static function Nearby(){
		$uri='/geosearch/v3/nearby';
		$querystring_arrays=array(
				'geotable_id'=>114911,
				'ak'=>MAP_AK,
				'coord_type'=>3,
				'radius'=>5000,
				'location'=>'116.376269,39.946246',
				'q'=>''
				);
		$sn=BDLbs::caculateAKSN( $uri, $querystring_arrays);
		$querystring_arrays['sn']=$sn;
		$ret=Yii::app()->curl->get(Yii::app()->params['baiduapi'].$uri,$querystring_arrays);
		echo $ret;
	}
	
	public static function Bound(){
		$nelat=$_GET['nelat'];
		$nelng=$_GET['nelng'];
		$swlat=$_GET['swlat'];
		$swlng=$_GET['swlng'];
		$uri='/geosearch/v3/bound';
		$querystring_arrays=array(
				'geotable_id'=>114911,
				'ak'=>MAP_AK,
				'coord_type'=>3,
				'bounds'=>"$swlng,$swlat;$nelng,$nelat",
				);
		$sn=BDLbs::caculateAKSN( $uri, $querystring_arrays);
		$querystring_arrays['sn']=$sn;
		$ret=Yii::app()->curl->get(Yii::app()->params['baiduapi'].$uri,$querystring_arrays);
		echo $ret;
	}
	public static function createOrUpdate($id,$title,$address,$latitude,$longitude,$type='create'){
		$querystring_arrays=array();
		$uri='';
		if($type=='create'){
			//这里是创建
			$uri='/geodata/v3/poi/create';
			$querystring_arrays=array(
					'id'=>$id,
					'title'=>$title,
					'latitude'=>$latitude,
					'longitude'=>$longitude,
					'coord_type'=>1,
					'geotable_id'=>GEOTABLE_ID,
					'ak'=>MAP_AK,
			);
		}else{
			//这里是更新
			$uri='/geodata/v3/poi/update';
			$querystring_arrays=array(
					'id'=>$id,
					'title'=>$title,
					'latitude'=>$latitude,
					'longitude'=>$longitude,
					'coord_type'=>1,
					'geotable_id'=>GEOTABLE_ID,
					'ak'=>MAP_AK,
			);
		}
		$sn=BDLbs::caculateAKSN( $uri, $querystring_arrays,"POST");
		$querystring_arrays['sn']=$sn;
		Yii::trace ( CVarDumper::dumpAsString ( $querystring_arrays ), 'create or update baidu poi post' );
		$ret=Yii::app()->curl->post(Yii::app()->params['baiduapi'].$uri,$querystring_arrays);
		$ret=json_decode($ret,true);
		Yii::trace ( CVarDumper::dumpAsString ( $ret ), 'create or update baidu poi' );
		if($ret['status']==0){
			return $ret['id'];
		}else{
			Yii::log ( CVarDumper::dumpAsString ( $ret ),'error', 'create or update baidu poi ERROR' );
			return -1;
		}
	}
	
	
	
	protected static function caculateAKSN( $url, $querystring_arrays, $method = 'GET')
	{
		$ak=MAP_AK;
		$sk=MAP_SN;
		
		if ($method === 'POST'){
			ksort($querystring_arrays);
		}
		$querystring = http_build_query($querystring_arrays);
		return md5(urlencode($url.'?'.$querystring.$sk));
	}
}