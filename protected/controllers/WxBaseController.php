<?php

define ( "TOKEN", "dLneoDa897Dn2Ac" );
class WxBaseController extends Controller {
	protected $encodingAesKey='';
	protected $restype='';
	
	public function filterLogRequest($filterChain) {
		if(isset( $GLOBALS ["HTTP_RAW_POST_DATA"])){
			Yii::log ( CVarDumper::dumpAsString (  $GLOBALS ["HTTP_RAW_POST_DATA"] ), 'trace', 'WX_RAW_REQUEST_DATA' );
		}
		Yii::log ( CVarDumper::dumpAsString (  $_REQUEST ), 'trace', 'WX_RAW_REQUEST' );
		$filterChain->run ();
	}

}
