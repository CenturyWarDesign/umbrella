<?php
require_once 'baseDebug.php';
class wx_vaild extends baseDebug {
	public $actionurl='index.php/wx';
}
$debug = new wx_vaild ('um55af2f8de2019');
$debug->debugDo ('event','unsubscribe');

// echo time();