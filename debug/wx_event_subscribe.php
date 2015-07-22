<?php
require_once 'baseDebug.php';
class wx_vaild extends baseDebug {
	public $actionurl='index.php/wx';
}
$debug = new wx_vaild ();
$debug->debugDo ('event','subscribe');

// echo time();