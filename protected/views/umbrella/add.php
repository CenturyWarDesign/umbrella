<?php
/* @var $this UmbrellaController */
/* @var $model Umbrella */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'umbrella-addumbrella-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	 <div class="form-group">
		<?php echo $form->labelEx($model,'des'); ?>
		<?php echo $form->textField($model,'des'); ?>
		<?php echo $form->error($model,'des'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->dropDownList($model,'price',array('10'=>'￥10.0','20'=>'￥20.0','30'=>'￥30.0')); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'img'); ?>
		<?php echo CHtml::button('选择图片',array('onclick'=>'chooseImg()'));?>
		<img id='imgumbrella' style='width:100px;height:100px;' hidden alt="" class="img-rounded" onclick='chooseImg()'>
		<?php echo $form->hiddenField($model,'img'); ?>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<?php echo CHtml::submitButton('创建');?>

<?php $this->endWidget(); ?>

</div>
<!-- form -->

<script>
function chooseImg(){
	wx.chooseImage({
	    count: 1, // 默认9
	    sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
	    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
	    success: function (res) {
	        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
	        $("#imgumbrella").attr("src",localIds);
	        $("#Umbrella_img").val(localIds);
	    }
	});
}
$("#imgumbrella").hide();

</script>