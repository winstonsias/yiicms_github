<?php
/*
                               _oo0oo_
                              o8888888o
                              88" . "88
                              (| -_- |)
                              0\  =  /0
                            ___/`---'\___
                          .' \\|     |// '.
                         / \\|||  :  |||// \
                        / _||||| -:- |||||- \
                       |   | \\\  -  /// |   |
                       | \_|  ''\---/''  |_/ |
                       \  .-\___ '-' ___/-.  /
                   ____`. .'   /--.--\  `. .'____
                   ."" '< `.___\_<|>_/___.' >' "".
                  | | : `- \`.; \ _ /`;.`/ - ` : | |
                  \ \`_.   \_ ___\ / ___ _/  .-` / /
             =====`-.____`.____\_____/____.-`____.-`=====
                               '=---='
*/
/*
**********sort.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
?>
<block name="body">
	<div class="main-title cf">
		<h2>
			菜单排序 [ <a href="<?php echo url('menu/index',array('pid'=>gp('pid')))?>">返回列表</a> ]
		</h2>
	</div>
	<div class="sort">
		<form action="<?php echo url('menu/sort')?>" method="post">
<!-- 			<div class="sort_top">
				查找：<input type="text"><button class="btn search" type="button">查找</button>
			</div> -->
			<div class="sort_center">
				<div class="sort_option">
					<select value="" size="8">
					<?php foreach ($list as $vo){?>

							<option class="ids" title="<?php echo $vo['title']?>" value="<?php echo $vo['id']?>"><?php echo $vo['title']?></option>

						<?php }?>
					</select>
				</div>
				<div class="sort_btn">
					<button class="top btn" type="button">第 一</button>
					<button class="up btn" type="button">上 移</button>
					<button class="down btn" type="button">下 移</button>
					<button class="bottom btn" type="button">最 后</button>
				</div>
			</div>
			<div class="sort_bottom">
				<input type="hidden" name="ids">
				<button class="sort_confirm btn submit-btn" type="button">确 定</button>
				<button class="sort_cancel btn btn-return" type="button" url="<?php echo $forward?>">返 回</button>
			</div>
		</form>
	</div>
</block>

<block name="script">
	<script type="text/javascript">
	
		$(function(){
			sort();
			$(".top").click(function(){
				rest();
				$("option:selected").prependTo("select");
				sort();
			})
			$(".bottom").click(function(){
				rest();
				$("option:selected").appendTo("select");
				sort();
			})
			$(".up").click(function(){
				rest();
				$("option:selected").after($("option:selected").prev());
				sort();
			})
			$(".down").click(function(){
				rest();
				$("option:selected").before($("option:selected").next());
				sort();
			})
			$(".search").click(function(){
				var v = $("input").val();
				$("option:contains("+v+")").attr('selected','selected');
			})
			function sort(){
				$('option').text(function(){return ($(this).index()+1)+'.'+$(this).text()});
			}

			//重置所有option文字。
			function rest(){
				$('option').text(function(){
					return $(this).text().split('.')[1]
				});
			}

			//获取排序并提交
			$('.sort_confirm').click(function(){
				var arr = new Array();
				$('.ids').each(function(){
					arr.push($(this).val());
				});
				$('input[name=ids]').val(arr.join(','));
				$.post(
					$('form').attr('action'),
					{
					'ids' :  arr.join(',')
					},
					function(data){
						if (data.status) {
	                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
	                    }else{
	                        updateAlert(data.info,'alert-success');
	                    }
	                    setTimeout(function(){
	                        if (data.status) {
	                        	$('.sort_cancel').click();
	                        }
	                    },1500);
					},
					'json'
				);
			});

			//点击取消按钮
			$('.sort_cancel').click(function(){
				window.location.href = $(this).attr('url');
			});
		})
	</script>
</block>