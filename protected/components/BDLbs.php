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
	public static function actionCreate(){
		$uri='/geodata/v3/poi/create';
		$querystring_arrays=array(
				'title'=>'老万家',
				'latitude'=>116.376269,
				'longitude'=>39.946246,
				'coord_type'=>1,
				'geotable_id'=>114911,
				'ak'=>MAP_AK,
				'location'=>'116.376269,39.946246',
				'q'=>'五道口'
		);
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