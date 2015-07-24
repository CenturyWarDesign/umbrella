<div style='padding:50px 0px'>
<button type="button" class="btn btn-primary btn-lg btn-block" onclick='scan()'>直接扫码</button>
<button type="button" class="btn btn-primary btn-lg btn-block" onclick='chooseimage()'>选择图片</button>
<button type="button" class="btn btn-default btn-lg btn-block" onclick='getlocation()'>附近网点</button>
<script>
function scan(){
	wx.scanQRCode({
	    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
	    success: function (res) {
	    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
		}
	});
}
function chooseimage(){
	wx.chooseImage({
	    count: 1, // 默认9
	    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
	    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
	    success: function (res) {
	        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
	        wx.uploadImage({
	            localId: localIds, // 需要上传的图片的本地ID，由chooseImage接口获得
	            isShowProgressTips: 1, // 默认为1，显示进度提示
	            success: function (res) {
	                var serverId = res.serverId; // 返回图片的服务器端ID
	            }
	        });
	    }
	});
}



function getlocation(){
	wx.getLocation({
	    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
	    success: function (res) {
	        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度
	        wx.openLocation({
	    	    latitude: latitude, // 纬度，浮点数，范围为90 ~ -90
	    	    longitude: longitude, // 经度，浮点数，范围为180 ~ -180。
	    	    name: '', // 位置名
	    	    address: '', // 地址详情说明
	    	    scale: 28, // 地图缩放级别,整形值,范围从1~28。默认为最大
	    	    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
	    	});
	    }
	});
	
}
</script>c
</div>