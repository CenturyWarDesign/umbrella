<?php
require_once 'baseDebug.php';
class wx_vaild extends baseDebug {
	public $actionurl='index.php/wx';
}
$debug = new wx_vaild ('um55af4994b9ff4');
$debug->debugDo ('event','unsubscribe');

// echo time();