<div class='text-center container-fluid' style='margin:50px 0px'>
		<img id='imgumbrella' style='height:100px;margin:10px' alt='<?php echo $umbrella->des?>'src='<?php echo $umbrella->img?>!100X100' onclick='showImg("<?php echo $umbrella->img?>")'>
		<h4><?php echo $umbrella->des ?></h4>
		<div class="row">
			<h5><?php echo '伞币：'.round(floatval($umbrella->price),2) ?></h5>
			<h5><?php echo '状态：'.$umbrella->status ?></h5>
			<h5><?php echo '所有者：'.$create_user->nickname ?></h5>
			<h5><?php echo '持有者：'.$now_user->nickname ?></h5>
			<!-- 判断是借伞还是收伞 -->
			<?php if(in_array('borrow',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4"><?php echo Yii::t("button", "我要借入")?></button>
			<?php };?>
			<?php if(in_array('recovery',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4"><?php echo Yii::t("button", "我要收回")?></button>
			<?php }?>
			<?php if(in_array('share',$actions)){?>
				<button type="button" class="btn btn-default btn-block col-md-4" onclick="qrcode()"><?php echo Yii::t("button", "生成二维码")?></button>
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
		  keyboard: false
		})
	if($("#output").html()==""){
		var width=$("#myModal").width()-40;
		jQuery('#output').qrcode({width:width,height:width,correctLevel:0,text:"<?php echo $url?>"}); 
	}
}
</script>