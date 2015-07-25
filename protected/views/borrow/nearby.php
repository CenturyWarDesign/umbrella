
<div id="l-map" style='width:100%;height:100%'></div>
<script>
$("html").css("width","100%");
$("html").css("height","100%");
$("body").css("width","100%");
$("body").css("height","100%");
$("#content").css("width","100%");
$("#content").css("height","100%");
$(".container").css("width","100%");
$(".container").css("height","100%");
$(".container").css("padding","0");

var map = new BMap.Map("l-map");  
var zoomControl=new BMap.ZoomControl();  
map.addControl(zoomControl);//添加缩放控件  
var scaleControl=new BMap.ScaleControl();  
map.addControl(scaleControl);//添加比例尺控件  
map.centerAndZoom(new BMap.Point(<?php echo Yii::app()->params['map_center_latitude']?>, <?php echo Yii::app()->params['map_center_longitude']?>), 16); 

wx.ready(function(){
	wx.getLocation({
	    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
	    success: function (res) {
	        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        var speed = res.speed; // 速度，以米/每秒计
	        var accuracy = res.accuracy; // 位置精度
	        $.post("getBaiduPoint",{from:1,to:3,x:longitude,y:latitude},function(result){
	        	map.panTo(new BMap.Point(result.x,result.y), 14);  
	        },"json");
	    }
	});
});




</script>



