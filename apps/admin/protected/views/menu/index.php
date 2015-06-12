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
**********index.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
?>
<block name="body">
    <div class="main-title">
        <h2><?php echo isset($data['title'])?"[ ".$data['title']." ]子":""?>菜单管理 </h2>
    </div>

    <div class="cf">
        <a class="btn" href="<?php echo url('menu/add',array('pid'=>get('pid')?get('pid'):0))?>">新 增</a>
        <button class="btn ajax-post confirm" url="<?php echo url('menu/delete')?>" target-form="ids">删 除</button>
        <a class="btn" href="<?php echo url('menu/import',array('pid'=>get('pid')?get('pid'):0))?>">导 入</a>
        <button class="btn list_sort" url="<?php echo url('menu/sort',array('pid'=>get('pid')?get('pid'):0))?>">排序</button>
        <!-- 高级搜索 -->
        <div class="search-form fr cf">
            <div class="sleft">
                <input type="text" name="title" class="search-input" value="<?php echo gp('title')?>" placeholder="请输入菜单名称">
                <a class="sch-btn" href="javascript:;" id="search" url="<?php echo url('menu/index')?>"><i class="btn-search"></i></a>
            </div>
        </div>
    </div>

    <div class="data-table table-striped">
        <form class="ids">
            <table>
                <thead>
                    <tr>
                        <th class="row-selected">
                            <input class="checkbox check-all" type="checkbox">
                        </th>
                        <th>ID</th>
                        <th>名称</th>
                        <th>上级菜单</th>
                        <th>分组</th>
                        <th>URL</th>
                        <th>排序</th>
                        <th>仅开发者模式显示</th>
                        <th>隐藏</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
				<?php foreach ($list as $menu){?>
               
                    <tr>
                        <td><input class="ids row-selected" type="checkbox" name="id[]" value="<?php echo $menu['id']?>"></td>
                        <td><?php echo $menu['id']?></td>
                        <td>
                            <a href="<?php echo url('menu/index',array('pid'=>$menu['id']))?>"><?php echo $menu['title']?></a>
                        </td>
                        <td><?php echo isset($menu['up_title'])?$menu['up_title']:"无"?></td>
                        <td><?php echo $menu['group']?></td>
                        <td><?php echo $menu['url']?></td>
                        <td><?php echo $menu['sort']?></td>
                        <td>
                            <a href="<?php echo url('menu/changestatus', array('id'=>$menu['id'],'value'=>abs($menu['is_dev']-1),'type'=>'dev'))?>" class="ajax-get">
                            
                            <?php echo $menu['is_dev_text']?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo url('menu/changestatus', array('id'=>$menu['id'],'value'=>abs($menu['hide']-1),'type'=>'hide'))?>" class="ajax-get">
                            
                            <?php echo $menu['hide_text']?>
                            </a>
                        </td>
                        <td>
                            <a title="编辑" href="<?php echo url('menu/edit',array('id'=>$menu['id']))?>">编辑</a>
                            <a class="confirm ajax-get" title="删除" href="<?php echo url('menu/delete',array('id'=>$menu['id']))?>">删除</a>
                        </td>
                    </tr>
             
				<?php }?>
                </tbody>
            </table>
        </form>
        <!-- 分页 -->
        <div class="page">

        </div>
    </div>
</block>

<block name="script">
    <script type="text/javascript">
        $(function() {
            //搜索功能
            $("#search").click(function() {
                var url = $(this).attr('url');
                var query = $('.search-form').find('input').serialize();
                query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
                query = query.replace(/^&/g, '');
                if (url.indexOf('?') > 0) {
                    url += '&' + query;
                } else {
                    url += '?' + query;
                }
                window.location.href = url;
            });
            //回车搜索
            $(".search-input").keyup(function(e) {
                if (e.keyCode === 13) {
                    $("#search").click();
                    return false;
                }
            });
            highlight_subnav("<?php echo url('Menu/index')?>");
            //点击排序
        	$('.list_sort').click(function(){
        		var url = $(this).attr('url');
        		var ids = $('.ids:checked');
        		var param = '';
        		if(ids.length > 0){
        			var str = new Array();
        			ids.each(function(){
        				str.push($(this).val());
        			});
        			param = str.join(',');
        		}

        		if(url != undefined && url != ''){
        			window.location.href = url + '/ids/' + param;
        		}
        	});
        });
    </script>
</block>