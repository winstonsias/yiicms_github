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
**********edit.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
?>
<block name="body">
    <div class="main-title">
        <h2><?php echo isset($info['id'])?'编辑':'新增'?>后台菜单</h2>
    </div>
    <form action="" method="post" class="form-horizontal">
        <div class="form-item">
            <label class="item-label">标题<span class="check-tips">（用于后台显示的配置标题）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="title" value="<?php echo isset($info['title'])?$info['title']:''?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">排序<span class="check-tips">（用于分组显示的顺序）</span></label>
            <div class="controls">
                <input type="text" class="text input-small" name="sort" value="<?php echo isset($info['sort'])?$info['sort']:0?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">链接<span class="check-tips">（U函数解析的URL或者外链）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="url" value="<?php echo isset($info['url'])?$info['url']:''?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">上级菜单<span class="check-tips">（所属的上级菜单）</span></label>
            <div class="controls">
                <select name="pid">
                <?php foreach ($menus as $menu){?>
                        <option value="<?php echo $menu['id']?>"><?php echo $menu['title_show']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">分组<span class="check-tips">（用于左侧分组二级菜单）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="group" value="<?php echo isset($info['group'])?$info['group']:''?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">是否隐藏<span class="check-tips"></span></label>
            <div class="controls">
                <label class="radio"><input type="radio" name="hide" value="1" <?php echo isset($info['hide'])?($info['hide']==1?'checked':''):''?>>是</label>
                <label class="radio"><input type="radio" name="hide" value="0" <?php echo isset($info['hide'])?($info['hide']==0?'checked':''):''?>>否</label>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">仅开发者模式可见<span class="check-tips"></span></label>
            <div class="controls">
                <label class="radio"><input type="radio" name="is_dev" value="1" <?php echo isset($info['is_dev'])?($info['is_dev']==1?'checked':''):''?>>是</label>
                <label class="radio"><input type="radio" name="is_dev" value="0" <?php echo isset($info['is_dev'])?($info['is_dev']==0?'checked':''):''?>>否</label>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">说明<span class="check-tips">（菜单详细说明）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="tip" value="<?php echo isset($info['tip'])?$info['tip']:''?>">
            </div>
        </div>
        <div class="form-item">
            <input type="hidden" name="id" value="<?php echo isset($info['id'])?$info['id']:''?>">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </form>
</block>

<block name="script">
    <script type="text/javascript">
        Think.setValue("pid", <?php echo $info['pid']?$info['pid']:0?>);
        Think.setValue("hide", <?php echo isset($info['hide'])?$info['hide']:0?>);
        Think.setValue("is_dev", <?php echo isset($info['is_dev'])?$info['is_dev']:0?>);
        //导航高亮
        highlight_subnav("<?php echo url('Menu/index')?>");
    </script>
</block>