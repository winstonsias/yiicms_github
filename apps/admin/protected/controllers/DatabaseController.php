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
**********DatabaseController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
class DatabaseController extends BackendBaseController
{
    public function actionIndex($type)
    {
        switch ($type) {
            /* 数据还原 */
            case 'import':
                //列出备份文件列表
                $path = realpath(C('DATA_BACKUP_PATH'));
                $flag = FilesystemIterator::KEY_AS_FILENAME;
                $glob = new FilesystemIterator($path,  $flag);

                $list = array();
                foreach ($glob as $name => $file) {
                    if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if(isset($list["{$date} {$time}"])){
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time']     = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                $title = '数据还原';
                break;

            /* 数据备份 */
            case 'export':
                $list  = app()->db->createCommand('SHOW TABLE STATUS')->queryAll();
                $list  = array_map('array_change_key_case', $list);
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }

        $this->render($type,array('list'=>$list));
    }
    //优化表
    public function actionOptimize($tables=null ){
        $tables=is_null($tables)?post('tables'):$tables;
        if($tables) {
            $Db   = app()->db;
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->createCommand("OPTIMIZE TABLE `{$tables}`")->queryAll();

                if($list){
                    $this->ajaxReturn("数据表优化完成！");
                } else {
                    $this->ajaxReturn("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->createCommand("OPTIMIZE TABLE `{$tables}`")->queryAll();
                if($list){
                    $this->ajaxReturn("数据表'{$tables}'优化完成！");
                } else {
                    $this->ajaxReturn("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->ajaxReturn("请指定要优化的表！");
        }
    }
    //修复表
    public function actionRepair($tables=null)
    {
        $tables=is_null($tables)?post('tables'):$tables;
        if($tables) {
                $Db   = app()->db;
                if(is_array($tables)){
                    $tables = implode('`,`', $tables);
                    $list = $Db->createCommand("REPAIR TABLE `{$tables}`")->queryAll();
    
                    if($list){
                        $this->ajaxReturn("数据表修复完成！");
                    } else {
                        $this->ajaxReturn("数据表修复出错请重试！");
                    }
                } else {
                    $list = $Db->createCommand("REPAIR TABLE `{$tables}`")->queryAll();
                    if($list){
                        $this->ajaxReturn("数据表'{$tables}'修复完成！");
                    } else {
                        $this->ajaxReturn("数据表'{$tables}'修复出错请重试！");
                    }
                }
            } else {
                $this->ajaxReturn("请指定要修复的表！");
            }
    }
    //备份
    public function actionExport($id=NULL,$start=NULL)
    {
        $tables=post('tables');
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
            $config = array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DS,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            
            if(is_file($lock)){
                $this->ajaxReturn('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->ajaxReturn('备份目录不存在或不可写，请检查后重试！');
            setSession('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            setSession('backup_file', $file);

            //缓存要备份的表
            setSession('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $addparams = array( 'tab'=>array('id' => 0, 'start' => 0),'tables'=>$tables);
                $this->ajaxReturn('初始化成功！', '',1,$addparams);
            } else {
                $this->ajaxReturn('初始化失败，备份文件创建失败！');
            }
        } elseif(!IS_POST && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = getSession('backup_tables');
            //备份指定表
            $Database = new Database(getSession('backup_file'), getSession('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->ajaxReturn('备份出错！','',1);
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $addparams = array( 'tab'=>array('id' => $id, 'start' => 0),'tables'=>$tables);
                    $this->ajaxReturn('备份完成！',  '',1,$addparams);
                } else { //备份完成，清空缓存
                    unlink(getSession('backup_config.path') . 'backup.lock');
                    setSession('backup_tables', null);
                    setSession('backup_file', null);
                    setSession('backup_config', null);
                    $this->ajaxReturn('备份完成！','',1);
                }
            } else {
                 $addparams = array( 'tab'=>array('id' => $id, 'start' => $start[0]),'tables'=>$tables);
                $rate = floor(100 * ($start[0] / $start[1]));
                $this->ajaxReturn("正在备份...({$rate}%)",  '',1,$addparams);
            }

        } else { //出错
            $this->ajaxReturn('参数错误！');
        }
    }
	/**
     * 还原数据库
     */
    public function actionImport($time = 0, $part = null, $start = null){
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                setSession('backup_list', $list); //缓存备份列表
                $this->ajaxReturn('初始化完成！', '',1,array('part' => 1, 'start' => 0));
            } else {
                $this->ajaxReturn('备份文件可能已经损坏，请检查！');
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = getSession('backup_list');
            $db = new Database($list[$part], array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));

            $start = $db->import($start);

            if(false === $start){
                $this->ajaxReturn('还原数据出错！');
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = array('part' => $part, 'start' => 0);
                    $this->ajaxReturn("正在还原...#{$part}", '',1, $data);
                } else {
                    setSession('backup_list', null);
                    $this->ajaxReturn('还原完成！','',1);
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->ajaxReturn("正在还原...#{$part} ({$rate}%)", '', 1,$data);
                } else {
                    $data['gz'] = 1;
                    $this->ajaxReturn("正在还原...#{$part}", '',1, $data);
                }
            }

        } else {
            $this->ajaxReturn('参数错误！');
        }
    }
     /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function actionDelete($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->ajaxReturn('备份文件删除失败，请检查权限！');
            } else {
                $this->ajaxReturn('备份文件删除成功！',url('database/index',array('type'=>'import')));
            }
        } else {
            $this->ajaxReturn('参数错误！');
        }
    }
    
}