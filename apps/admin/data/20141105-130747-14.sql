-- -----------------------------
-- SQL Data Transfer 
-- 
-- DSN     : mysql:host=localhost;dbname=yiicms
-- 
-- Part : #14
-- Date : 2014-11-05 13:07:48
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `winston_menu` VALUES ('93', '其他', '0', '5', 'other', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('96', '新增', '75', '0', 'Menu/add', '0', '', '系统设置', '0');
INSERT INTO `winston_menu` VALUES ('98', '编辑', '75', '0', 'Menu/edit', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('104', '下载管理', '102', '0', 'Think/lists?model=download', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('105', '配置管理', '102', '0', 'Think/lists?model=config', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('106', '行为日志', '16', '0', 'Action/actionlog', '0', '', '行为管理', '0');
INSERT INTO `winston_menu` VALUES ('108', '修改密码', '16', '0', 'User/updatePassword', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('109', '修改昵称', '16', '0', 'User/updateNickname', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('110', '查看行为日志', '106', '0', 'action/edit', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('112', '新增数据', '58', '0', 'think/add', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('113', '编辑数据', '58', '0', 'think/edit', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('114', '导入', '75', '0', 'Menu/import', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('115', '生成', '58', '0', 'Model/generate', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('116', '新增钩子', '57', '0', 'Addons/addHook', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('117', '编辑钩子', '57', '0', 'Addons/edithook', '0', '', '', '0');
INSERT INTO `winston_menu` VALUES ('118', '文档排序', '3', '0', 'Article/sort', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('119', '排序', '70', '0', 'Config/sort', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('120', '排序', '75', '0', 'Menu/sort', '1', '', '', '0');
INSERT INTO `winston_menu` VALUES ('121', '排序', '76', '0', 'Channel/sort', '1', '', '', '0');

-- -----------------------------
-- Table structure for `winston_ucenter_member`
-- -----------------------------
DROP TABLE IF EXISTS `winston_ucenter_member`;
CREATE TABLE `winston_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(255) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- -----------------------------
-- Records of `winston_ucenter_member`
-- -----------------------------
INSERT INTO `winston_ucenter_member` VALUES ('1', 'winston', '900745484932ba618a8cbe33447add63', 'aa123@163.com', '', '1414392908', '2130706433', '1415149124', '127.0.0.1', '1414392908', '1');
INSERT INTO `winston_ucenter_member` VALUES ('2', 'a', '900745484932ba618a8cbe33447add63', 'test@163.com', '', '0', '0', '1415076089', '127.0.0.1', '0', '1');
INSERT INTO `winston_ucenter_member` VALUES ('3', 'd', '900745484932ba618a8cbe33447add63', 'winstonsias@qq.com', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `winston_ucenter_member` VALUES ('4', 'x', '900745484932ba618a8cbe33447add63', 'xdd@163.com', '', '0', '0', '0', '0', '0', '1');
