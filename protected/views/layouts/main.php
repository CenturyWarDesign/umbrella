<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="zh">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>

<body>

<div class="container">
	<?php echo $content; ?>
</div>
<script>
wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $this->signPackage["appId"];?>',
    timestamp: <?php echo $this->signPackage["timestamp"];?>,
    nonceStr: '<?php echo $this->signPackage["nonceStr"];?>',
    signature: '<?php echo $this->signPackage["signature"];?>',
    jsApiList: ['getLocation','openLocation','scanQRCode','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','chooseImage','previewImage','uploadImage','downloadImage','getNetworkType','hideOptionMenu','chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
alert(<?php echo $_GET['CODE']?>);
</script>
</body>
</html>
