-- ----------------------------
-- Table structure for auth_users
-- 定义用户表
-- ----------------------------
DROP TABLE IF EXISTS `auth_users`;
CREATE TABLE `auth_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) DEFAULT NULL COMMENT '账号',
  `pwd` varchar(32) DEFAULT NULL COMMENT '密码',
  `status` int(11) DEFAULT '0' COMMENT '状态 （0禁止 1可用）',
  `create_time` int(11) DEFAULT NULL COMMENT '帐号创建时间',
  `administrator` int(1) DEFAULT '0' COMMENT '是否超级管理员，1是 0否',
  `update_time` int(11) DEFAULT NULL COMMENT '账户最后更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of auth_users
-- ----------------------------
INSERT INTO `auth_users` (`id`, `username`, `pwd`, `status`,`administrator`) VALUES ('1', 'bingo','068791f7d510a59c65b68feff1c8c6e1','1','1');

-- ----------------------------
-- Table structure for auth_roles
-- 定义角色表
-- ----------------------------
DROP TABLE IF EXISTS `auth_roles`;

CREATE TABLE `auth_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of auth_roles
-- ----------------------------
INSERT INTO `auth_roles` (`id`, `name`, `pid`, `status`, `remark`, `create_time`, `update_time`)
VALUES
  (1,'超级管理员1',0,1,'网站最高管理员权限！',1329633709,1329633709),
  (2,'测试角色',NULL,0,'测试角色',1482389092,0);

-- ----------------------------
-- Table structure for auth_rule
-- 定义权限规则
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;

CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `rule_val` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `update_time` int(11) DEFAULT NULL COMMENT '账户最后更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` (`id`, `title`, `rule_val`, `pid`, `update_time`, `delete_time`)
VALUES
  (1,'内容管理','admin/index/index',0,1484209924,NULL),
  (2,'用户管理','admin/user/index',0,1484145913,NULL);

-- ----------------------------
-- Table structure for user_role
-- 定义用户角色表
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role`(
  `uid` SMALLINT unsigned NOT NULL COMMENT 'user id',
  `role_id` SMALLINT unsigned NOT NULL COMMENT 'role id',
  KEY `user_id` (`uid`),
  KEY `role_id` (`role_id`)
);

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` (`uid`, `role_id`)
VALUES
  (1,1);

-- ----------------------------
-- Table structure for role_access
-- 定义角色权限表
-- ----------------------------
DROP TABLE IF EXISTS `role_access`;

CREATE TABLE `role_access` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色',
  `rule_id` mediumint(8) unsigned NOT NULL COMMENT '规则唯一英文标识,全小写',
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限授权表';
-- ----------------------------
-- Records of role_access
-- ----------------------------
INSERT INTO `role_access` (`role_id`, `rule_id`)
VALUES
  (2,3),
  (2,1),
  (3,2),
  (1,1),
  (1,2),
  (1,3);

-- ----------------------------
-- Table structure for staffs
-- ----------------------------
DROP TABLE IF EXISTS `staffs`;
CREATE TABLE `staffs` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `army_date` date DEFAULT NULL,
  `party_date` date DEFAULT NULL,
  `id_num` varchar(50) DEFAULT NULL,
  `job_title` varchar(20) DEFAULT NULL,
  `job_tm` date DEFAULT NULL,
  `insignia_rk` varchar(20) DEFAULT NULL,
  `insignia_tm` date DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `depart` varchar(20) NOT NULL,
  `is_on` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staffs
-- ----------------------------
INSERT INTO `staffs` VALUES ('26', '小军', '男', '1985-02-25', '1985-02-25', '1985-02-25',
'35032219880202314x', '12级', '1985-02-25', '少伟', '1985-02-25', '136542***', 'xiaoliu@qq.com', '配1', '1');
INSERT INTO `staffs` VALUES ('29', '小刘', '男', '1985-02-25', '1985-02-25', '1985-02-25',
'35032219880202314x', '九级', '1985-02-25',  '上士', '1985-02-25', '136542***', 'xiaoliu@qq.com', '垫石', '1');
INSERT INTO `staffs` VALUES ('25', '小丽', '女', '1985-02-25', '1985-02-25', '1985-02-25',
'35032219880202314x', '员工', '1985-02-25',  '中式', '1985-02-25', '136542***', 'xiaoliu@qq.com', '迪奥1', '1');
INSERT INTO `staffs` VALUES ('24', '王总', '男', '1985-02-25', '1985-02-25', '1985-02-25',
'35032219880202314x', '主任', '1985-02-25', '上市', '1985-02-25', '136542***', 'xiaoliu@qq.com', '垫石', '1');
INSERT INTO `staffs` VALUES ('28', '小张', '女', '2010-08-12', '1985-02-25', '1985-02-25',
'35032219880202314x', '组长', '1985-02-25', '少伟', '1985-02-25', '136542***', 'xiaoliu@qq.com', '配1', '1');


-- ----------------------------
-- Table structure for plan
-- ----------------------------
DROP TABLE IF EXISTS `plan`;
CREATE TABLE `plan` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `content` varchar(50) NOT NULL,
  `time` date DEFAULT NULL,
  `location` varchar(30) NOT NULL,
  `expt_par` int(4) NOT NULL,
  `act_par` int(4) NOT NULL,
  `pers_in_char` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of plan
-- ----------------------------
INSERT INTO `plan` VALUES ('35', '周训练计划', '共同训练', '办公自动化、个人博客等程序开发', '2007-12-05', '900#', '29', '27', '张三');
INSERT INTO `plan` VALUES ('34', '周训练计划', '共同训练', '博客系统开发', '2007-12-05', '900#', '29', '27', '张三');
INSERT INTO `plan` VALUES ('33', '周训练计划', '共同训练', '办公自动化程序', '2007-12-05', '900#', '29', '27', '李四');
INSERT INTO `plan` VALUES ('32', '周训练计划', '其他训练', '测试办公自动化', '2007-12-05', '900#', '28', '27', '王五');
INSERT INTO `plan` VALUES ('31', '周训练计划', '其他训练', '开发图书管理系统', '2007-12-05', '900#', '19', '18', '张三');
INSERT INTO `plan` VALUES ('30', '周训练计划', '共同训练', '工作计划工作计划', '2007-12-04', '900#', '28', '21', '赵柳');
INSERT INTO `plan` VALUES ('20', '周训练计划', '共同训练', '管理系统项目开发', '2007-12-04', '900#', '28', '21', '张三');
INSERT INTO `plan` VALUES ('21', '周训练计划', '专业训练', '办公自动化管理系统的开发', '2007-12-04', '900#', '29', '21', '周八');
INSERT INTO `plan` VALUES ('22', '周训练计划', '专业训练', '整理光盘', '2007-12-04', '900#', '25', '21', '张三');


-- ----------------------------
-- Table structure for edu_notes
-- ----------------------------
DROP TABLE IF EXISTS `edu_notes`;
CREATE TABLE `edu_notes`(
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `time` date DEFAULT NULL,
  `location` varchar(30) NOT NULL,
  `lecturer` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_notes
-- ----------------------------
INSERT INTO `edu_notes` VALUES ('1', '2007-12-04', '900#', '张三', '办公自动化管理系统的开发', '办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发');

