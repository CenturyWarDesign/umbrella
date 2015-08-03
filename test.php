<?php
	header("Content-Type: text/html; charset=utf-8");
    $ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/idlocr/ocr';
    $header = array(
        'Content-Type:application/x-www-form-urlencoded',
        'apikey: bf322e07853db7c227c9621dfa4c8e6c',
    );
    $image=urlencode(strval(base64_encode(file_get_contents("aa.jpg")))) ;
    $data = "fromdevice=pc&clientip=10.10.10.0&detecttype=LocateRecognize&languagetype=CHN_ENG&imagetype=1&image={$image}";
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    // 添加参数
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    print_R(json_decode($res,true));
    file_put_contents("get.log",urldecode($res) );
?>
