<?php
/**
 * UCenter客户端配置文件
 * 注意：该配置文件请使用常量方式定义
 */

define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_AUTH_KEY', 'rIk4RB2(&}W[>n+D|0?ac)5sOlQx"w.C{jLbS`_;'); //加密KEY
define('UC_DB_DSN', 'mysql://root:@127.0.0.1:3306/yiicms'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'winston_'); // 数据表前缀，使用Model方式调用API必须配置此项
