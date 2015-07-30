<ul class="nav nav-tabs">
	<li role="presentation" class="active"><a data-toggle="tab"
		href="#nowlist">当前(<?php echo count($nowlist);?>)</a></li>
	<li role="presentation"><a data-toggle="tab" href="#borrowlist">借入(<?php echo count($borrowlist);?>)</a></li>
	<li role="presentation"><a data-toggle="tab" href="#loanlist">借出</a></li>
	<li role="presentation"><a href="add">创建</a></li>
</ul>



<div class="tab-content" style='padding:10px 0px;'>
	<div id="nowlist" class="tab-pane in active">
	<?php foreach ($nowlist as $umb){?>
	<div class="media">
			<div class="media-left">
				<a href="info/id/<?php echo $umb->umbrellaid?>"> <img class="media-object" src="<?php echo $umb->img?>!50X50" alt="<?php echo $umb->des?>">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $umb->des?></h4>
			</div>
		</div>
	<?php }?>
	</div>
	<div id="borrowlist" class="tab-pane">
		<?php foreach ($borrowlist as $umb){?>
	<div class="media">
			<div class="media-left">
				<a href="info/id/<?php echo $umb->umbrellaid?>"> <img class="media-object" src="<?php echo $umb->img?>!50X50" alt="<?php echo $umb->des?>">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $umb->des?></h4>
			</div>
		</div>
	<?php }?>
	</div>
	<div id="loanlist" class="tab-pane">
	
	<?php foreach ($loanlist as $umb){?>
	<div class="media">
			<div class="media-left">
				<a href="info/id/<?php echo $umb->umbrellaid?>"> <img class="media-object" src="<?php echo $umb->img?>!50X50" alt="<?php echo $umb->des?>">
				</a>
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $umb->des?></h4>
			</div>
		</div>
	<?php }?>
	
	</div>
</div>

<script>

</script>