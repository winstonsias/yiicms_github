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
**********DATE:2015-1-14**********
*/
?>
<header class="jumbotron subhead" id="overview">
        <div class="container">
            <h2>源自相同起点，演绎不同精彩！</h2>
            <p class="lead"></p>
        </div>
    </header>
<div id="main-container" class="container">
    <div class="row">

        <!-- 左侧 nav
        ================================================== -->
            <div class="span3 bs-docs-sidebar">
                <block name="publish"></block>
                <ul class="nav nav-list bs-docs-sidenav">
                     <?php $this->widget('CategoryWidget',array('cate'=>1,'child'=>true)); ?>  
                </ul>
            </div>

       	 <div class="span9">
        <!-- Contents
        ================================================== -->
<?php $this->widget('ArticleWidget',array('category'=>1,'child'=>true)); ?>  
       

       
    </div>
        </div>
    </div>
</div>