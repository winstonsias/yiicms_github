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
**********sidemenu.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-12**********
*/
?>
 <h3>
 
 	<i class="icon <?php if(strpos('mydocument,draftbox,examine',strtolower($this->getAction()->getId()))===false){ echo 'icon-fold';}?> "></i>
 	个人中心
 </h3>
 	<ul class="side-sub-menu <?php if(strpos('mydocument,draftbox,examine',strtolower($this->getAction()->getId()))===false){ echo 'subnav-off';}?>  ">
 		<li <?php if(strtolower($this->getAction()->getId())=='mydocument'){ echo 'class=current';}?> ><a class="item" href="<?php echo url('article/mydocument')?>">我的文档</a></li>
 		
 		<?php if($this->assign['show_draftbox']==1){?>
 		<li <?php if(strtolower($this->getAction()->getId())=='draftbox'){ echo 'class=current';}?>><a class="item" href="<?php echo url('article/draftbox')?>">草稿箱</a></li>
 		
 		<?php }?>
		<li  <?php if(strtolower($this->getAction()->getId())=='draftbox'){ echo 'class=examine';}?> ><a class="item" href="<?php echo url('article/examine')?>">待审核</a></li>
 	</ul>
<?php foreach ($this->assign['nodes'] as $sub_menu){?>
    
        <!-- 子导航 -->
        <?php if(!empty($sub_menu)){?>
        
            <h3>
            	<i class="icon <?php echo $sub_menu['current']==1?'':'icon-fold'?> "></i>
            <?php if($sub_menu['allow_publish']>0)
            {
                
                echo '<a class="item" href="'.url($sub_menu['url']).'">'.$sub_menu['title'].'</a>';
            }else {
                
                echo $sub_menu['title'];
            }
            ?>
            
            </h3>
            <ul class="side-sub-menu <?php  echo $sub_menu['current']==1?'':'subnav-off'?> ">
            <?php foreach ($sub_menu['_child'] as $menu){?>
                    <li 
                    <?php if($menu['id']==$this->assign['cate_id']||$menu['current']==1){ echo 'class="current"';}?> >
                       
<?php if($menu['allow_publish']>0)
            {
                
                echo '<a class="item" href="'.url($menu['url']).'">'.$menu['title'].'</a>';
            }else {
                
                echo '<a class="item" href="javascript:void(0);">'.$menu['title'].'</a>';
            }
            ?>
                        <!-- 一级子菜单 -->
                        <?php if(!empty($menu['_child'])){?>
                       
                        <ul class="subitem">
                        <?php foreach ($menu['_child'] as $three_menu){?>
                        	
                            <li>
                                
<?php if($three_menu['allow_publish']>0)
            {
                
                echo '<a class="item" href="'.url($three_menu['url']).'">'.$three_menu['title'].'</a>';
            }else {
                
                echo '<a class="item" href="javascript:void(0);">'.$three_menu['title'].'</a>';
            }
            ?>
                                
                                <!-- 二级子菜单 -->
                                <?php if(!empty($three_menu['_child'])){?>
                                
                                <ul class="subitem">
                                <?php foreach ($three_menu['_child'] as $four_menu) {
                                  
                                ?>
                                	
                                    <li>
                                    <?php if($four_menu['allow_publish']==0)
            {
                
                echo '<a class="item" href="'.url('article/index',array('cate_id'=>$four_menu['id'])).'">'.$four_menu['title'].'</a>';
            }else {
                
                echo '<a class="item" href="javascript:void(0);">'.$four_menu['title'].'</a>';
            }
            ?>
                                        
                                        
                                        <!-- 三级子菜单 -->
                                        <?php if(!empty($four_menu['_child'])){?>
                                       
                                        <ul class="subitem">
                                        <?php foreach ($four_menu['_child'] as $five_menu){?>
                                        	
                                            <li>
                                            	
                                            	 <?php if($five_menu['allow_publish']==0)
            {
                
                echo '<a class="item" href="'.url('article/index',array('cate_id'=>$five_menu['id'])).'">'.$five_menu['title'].'</a>';
            }else {
                
                echo '<a class="item" href="javascript:void(0);">'.$five_menu['title'].'</a>';
            }
            ?>
                                            </li>
                                            
                                            <?php }?>
                                        </ul>
                                        
                                        <?php }?>
                                        <!-- end 三级子菜单 -->
                                    </li>
                                     
                                     <?php }?>
                                </ul>
                                
                                <?php }?>
                                <!-- end 二级子菜单 -->
                            </li>
                            
                            <?php }?>
                        </ul>
                        
                        <?php }?>
                        <!-- end 一级子菜单 -->
                    </li>

                <?php }?>
            </ul>
        <?php }?>
        <!-- /子导航 -->

    <?php }?>
    <!-- 回收站 -->
	<eq name="show_recycle" value="1">
    <h3>
        <em class="recycle"></em>
        <a href="<?php echo url('article/recycle')?>">回收站</a>
    </h3>
    </eq>

<script>
    $(function(){
        $(".side-sub-menu li").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });
    })
</script>
