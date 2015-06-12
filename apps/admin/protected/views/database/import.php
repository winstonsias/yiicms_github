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
**********import.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
?>
<block name="body">
    <!-- 标题栏 -->
    <div class="main-title">
        <h2>数据备份</h2>
    </div>
    <!-- /标题栏 -->

    <!-- 应用列表 -->
    <div class="data-table table-striped">
        <table>
            <thead>
                <tr>
                    <th width="200">备份名称</th>
                    <th width="80">卷数</th>
                    <th width="80">压缩</th>
                    <th width="80">数据大小</th>
                    <th width="200">备份时间</th>
                    <th>状态</th>
                    <th width="120">操作</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key=>$data){?>
               
                    <tr>
                        <td><?php echo date('Ymd-His',$data['time'])?></td>
                        <td><?php echo $data['part']?></td>
                        <td><?php echo $data['compress']?></td>
                        <td><?php echo formatFileSize($data['size'])?></td>
                        <td><?php echo $key?></td>
                        <td>-</td>
                        <td class="action">
                            <a class="db-import" href="<?php echo url('database/import',array('time'=>$data['time']))?>">还原</a>&nbsp;
                            <a class="ajax-get confirm" href="<?php echo url('database/delete',array('time'=>$data['time']))?>">删除</a>
                        </td>
                    </tr>
                
                <?php }?>
            </tbody>
        </table>
    </div>
    <!-- /应用列表 -->
</block>

<block name="script">
    <script type="text/javascript">
        $(".db-import").click(function(){
            var self = this, status = ".";
            $.get(self.href, success, "json");
            window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
            return false;
        
            function success(data){
                if(data.status){
                    if(data.gz){
                        data.info += status;
                        if(status.length === 5){
                            status = ".";
                        } else {
                            status += ".";
                        }
                    }
                    $(self).parent().prev().text(data.info);
                    if(data.part){
                        $.get(self.href, 
                            {"part" : data.part, "start" : data.start}, 
                            success, 
                            "json"
                        );
                    }  else {
                        window.onbeforeunload = function(){ return null; }
                    }
                } else {
                    updateAlert(data.info,'alert-error');
                }
            }
        });
        highlight_subnav('<?php echo url('Database/index').'?type=import'?>');
    </script>
</block>