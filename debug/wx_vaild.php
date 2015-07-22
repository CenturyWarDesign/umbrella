<?php
require_once 'baseDebug.php';
class wx_vaild extends baseDebug {
	public $actionurl='wx';
}
$debug = new wx_vaild ();
$debug->vaild();

// echo time();