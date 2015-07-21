<?php

$removeFile=array('.','..','index.php','baseDebug.php','Curl.php','.svn','.DS_Store');
$dirs=scandir(dirname(__FILE__));
$dirs=array_diff($dirs, $removeFile);

foreach ( $dirs as $key => $value ) {
	echo "<a href='$value'>$value</a><br/>";
}