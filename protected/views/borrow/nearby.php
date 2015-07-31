<div id='error'></div>
<div id="l-map" style='width: 100%; height: 100%'></div>
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



function panToNear(){
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
}

function getBound(swlat,swlng,nelat,nelng){
	$.get("boundsearch",{swlat:swlat,swlng:swlng,nelat:nelat,nelng:nelng},function(result){
		map.clearOverlays();
		for(var key in result.contents){
			var value=result.contents[key];
			var marker = new BMap.Marker(new BMap.Point(value.location[0], value.location[1]));        // 创建标注      
			marker.addEventListener("click", function(){   
				openWindow(this,value.title);
			});
			map.addOverlay(marker);
        }    
	},"json");
}

function openWindow(marker){
	var opts = {      
	    width : 100,     // 信息窗口宽度      
	    height: 50,     // 信息窗口高度      
	    title : "ss"  // 信息窗口标题     
	}      
	var infoWindow = new BMap.InfoWindow("adfahd", opts);  // 创建信息窗口对象      
	map.openInfoWindow(infoWindow, marker.getPosition());    
}

map.addEventListener("moveend", function(){  
	var bound=map.getBounds();
	getBound(bound._swLat,bound._swLng,bound._neLat,bound._neLng);
});


//定义一个控件类，即function      
function ZoomControl(){      
    // 设置默认停靠位置和偏移量    
    this.defaultAnchor = BMAP_ANCHOR_BOTTOM_LEFT;      
    this.defaultOffset = new BMap.Size(10, 50);      
}      
// 通过JavaScript的prototype属性继承于BMap.Control     
ZoomControl.prototype = new BMap.Control();   
//自定义控件必须实现initialize方法，并且将控件的DOM元素返回     
//在本方法中创建个div元素作为控件的容器，并将其添加到地图容器中     
ZoomControl.prototype.initialize = function(map){      
	//创建一个DOM元素     
	var div = document.createElement("div");      
	//添加文字说明      
	div.appendChild(document.createTextNode("当前"));      
	//设置样式      
	div.style.cursor = "pointer";      
	div.style.border = "1px solid gray";      
	div.style.backgroundColor = "white";      
	//绑定事件，点击一次放大两级      
	div.onclick = function(e){    
		panToNear();
	}      
	//添加DOM元素到地图中     
	map.getContainer().appendChild(div);    
	return div;    
}  

//创建控件实例      
var myZoomCtrl = new ZoomControl();      
// 添加到地图当中      
map.addControl(myZoomCtrl); 



wx.ready(function(){
	panToNear();
});
</script>



