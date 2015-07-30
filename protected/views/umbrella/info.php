<div class='text-center container-fluid' style='margin:20px 0px'>
		<img id='imgumbrella' style='height:200px;margin:10px' alt='<?php echo $umbrella->des?>'src='<?php echo $umbrella->img?>!200X200' onclick='showImg("<?php echo $umbrella->img?>")'>
		<h4><?php echo $umbrella->des ?></h4>
		<div class="row">
			<h5><?php echo '伞币：'.round(floatval($umbrella->price),2) ?></h5>
			<h5><?php echo '状态：'.$umbrella->status ?></h5>
			<h5><?php echo '所有者：'.$create_user->nickname ?></h5>
			<h5><?php echo '持有者：'.$now_user->nickname ?></h5>
			<!-- 判断是借伞还是收伞 -->
			<?php if(in_array('borrow',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4" onclick='borrow()'><?php echo Yii::t("button", "我要借入")?></button>
			<?php };?>
			<?php if(in_array('recovery',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4" onclick='borrow()'><?php echo Yii::t("button", "我要收回")?></button>
			<?php }?>
			<?php if(in_array('share',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4" onclick="qrcode()"><?php echo Yii::t("button", "生成二维码,可借出")?></button>
			<?php }?>
		
		<button type="button" class="btn btn-link btn-block col-md-2" onclick="scan()"><?php echo Yii::t("button", "扫一扫")?></button>
					
		</div>
		
<div  id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title"><?php echo $umbrella->des ?></h5>
      </div>
    <div id='output' style='padding:5px'></div>
    </div>
  </div>
</div>

</div>
<script>
function showImg(img){
	wx.previewImage({
	    current: img, // 当前显示图片的http链接
	    urls: [img] // 需要预览的图片http链接列表
	});
}
function scan(){
	wx.scanQRCode({
	    needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
	    success: function (res) {
	   	 var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
		}
	});
}
function close(){
	wx.closeWindow();
}
function qrcode(){
	$('#myModal').modal({
		  keyboard: true
		})
	if($("#output").html()==""){
		var width=$("#myModal .modal-dialog").width()-40;
		console.log(width);
		jQuery('#output').qrcode({width:width,height:width,correctLevel:0,text:"<?php echo $url?>"}); 
	}
}
var latitude=0;
var longitude=0;

wx.ready(function(){
	wx.getLocation({
	    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
	    success: function (res) {
	    	latitude= res.latitude; // 纬度，浮点数，范围为90 ~ -90
	        longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
	        console.log(res);
	    },
	});
});
function borrow(){
	$.post("/index.php/umbrella/borrow",{umbrellaid:'<?php echo $umbrella->umbrellaid?>',latitude:latitude,longitude:longitude},function(result){
	    if(result.code==0){
		    alert("成功");
		    window.location.href=window.location.pathname; 
	    }
	    else{
		    alert(result.message);
	    }
	 },'json');
}

</script>