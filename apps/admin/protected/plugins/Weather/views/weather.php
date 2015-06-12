<style>
.weather{padding: 10px 0 10px;float: right;line-height: 20px;color:#FFF;position:relative;}
.weather a{color:#999;}
.weather a:hover{color:#fff;}
ul.weather_list{overflow:hidden;}
ul.weather_list li{float:left;list-style: none outside none;}
ul.weather_list li p{width:140px;text-align:center;}
</style>
<div class="weather">
	<a href="javascript:void(0)"><?php echo $location?>：<?php echo $lists[1]['weather']?>&nbsp;&nbsp;<?php echo $lists[1]['temperature']?></a>
	<div  class="selected" style="display:none;position:absolute;background:#E04040;right:0;top:40px;">
		<ul class="weather_list" style="width:<?php echo $width?>px">
		<?php foreach($lists as $i=>$vo){?>
			<li>
				<p><?php echo $lists[$i]['date']?></p>
				<p><img src="<?php echo $lists[$i]['pictureUrl']?>"></p>
				<p><?php echo $lists[$i]['temperature']?></p>
				<p><?php echo $lists[$i]['weather']?></p>
				<p><?php echo $lists[$i]['wind']?></p>
			</li>

			<?php } ?>
		</ul>
	</div>
</div>

<script>
	$('.weather').mouseover(function(){
		$(this).find('.selected').show();
	});
	$('.weather').mouseout(function(){
		$(this).find('.selected').hide();
	});
</script>