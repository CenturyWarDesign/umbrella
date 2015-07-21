<?php
require_once 'baseDebug.php';
class wx_vaild extends baseDebug {
	public $actionurl='wx';
	
	public function getFunctionParams(){
		$ret = $this->getBaseParams ();
		$ret ['username'] = 'wanbin';
		$ret ['username'] = 'wanbinwww';
		$ret ['password'] = 'b582677f0008517df9dd525068f6afca';
		$ret ['password'] = md5('992392');
// 		$ret ['password'] = '1df0a121b4cbb37ad9294276a63de350';
		return $ret;
	}
}
$debug = new wx_vaild ();
$debug->debugDo ();

// echo time();