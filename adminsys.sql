/*
 Navicat Premium Data Transfer

 Source Server         : adminsys.com
 Source Server Type    : MySQL
 Source Server Version : 50723
 Source Host           : localhost:3306
 Source Schema         : adminsys

 Target Server Type    : MySQL
 Target Server Version : 50723
 File Encoding         : 65001

 Date: 13/01/2019 22:08:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_roles
-- ----------------------------
DROP TABLE IF EXISTS `auth_roles`;
CREATE TABLE `auth_roles`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `pid` smallint(6) NULL DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) UNSIGNED NULL DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parentId`(`pid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_roles
-- ----------------------------
INSERT INTO `auth_roles` VALUES (1, 'Super', NULL, 1, '超级管理员', 1329633709, 1546577936);
INSERT INTO `auth_roles` VALUES (2, 'admin', NULL, 1, '网站管理员', 1482389092, 1546351469);
INSERT INTO `auth_roles` VALUES (3, 'anoymous', NULL, 1, '游客', 1546326222, 1546326417);
INSERT INTO `auth_roles` VALUES (4, 'duty', NULL, 1, '组长', 1546326477, 1546357831);

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `level` enum('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类别 1=》菜单，2=》页面，3=》操作',
  `rule_val` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `icon` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '图标',
  `pid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父类ID',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '账户最后更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES (1, '首页', '1', 'admin/index', 'fa fa-home', 0, 1484209924, NULL);
INSERT INTO `auth_rule` VALUES (2, 'Human Resource', '1', 'humn_resrc', 'fa fa-user-circle', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (3, 'Training Plan', '1', 'train_plan', 'fa fa-university', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (4, '登统计', '1', 'regst_statis', 'fa fa-pie-chart', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (5, '技术资料', '1', 'tech_data', 'fa fa-file-archive-o', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (6, '系统设置', '1', 'sys_setting', 'fa fa-gear', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (7, '员工信息', '2', 'admin/staffinfo', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (8, '人员在位', '2', 'admin/staffinposition', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (9, '周训练计划', '2', 'admin/weeklyplan', 'fa-calendar-minus-o', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (10, '月训练计划', '2', 'admin/monthlyplan', 'fa-calendar-plus-o', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (11, '三新训练计划', '2', 'admin/freshman', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (12, '日登记', '2', 'admin/daily', 'fa-database', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (13, '月统计', '2', 'admin/monthly', 'fa-line-chart', 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (14, '请假登记', '2', 'admin/leave', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (15, '公差勤务登记', '2', 'admin/service', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (16, '组别1', '2', 'admin/techdata/tv', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (17, '组别2', '2', 'admin/techdata/dp', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (18, '组别2', '2', 'admin/techdata/tp', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (19, '用户管理', '2', 'admin/authusers', NULL, 0, 1484145913, NULL);
INSERT INTO `auth_rule` VALUES (20, '角色管理', '2', 'admin/authroles/index', NULL, 0, 1484145913, NULL);
INSERT INTO `auth_rule` VALUES (21, '权限管理', '2', 'admin/authrules/index', NULL, 0, 1484145913, NULL);
INSERT INTO `auth_rule` VALUES (22, '请假新增', '3', 'admin/leave/add', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (23, '请假删除', '3', 'admin/leave/delete', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (24, '请假编辑', '3', 'admin/leave/edit', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (25, '请假提交', '3', 'admin/leave/savedata', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (26, '公差勤务新增', '3', 'admin/servicerecord/add', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (27, '公差勤务编辑', '3', 'admin/servicerecord/edit', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (28, '公差勤务删除', '3', 'admin/servicerecord/delete', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (29, '公差勤务提交', '3', 'admin/servicerecord/savedata', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (30, '用户编辑', '3', 'admin/authusers/edit', NULL, 0, 1484145913, NULL);
INSERT INTO `auth_rule` VALUES (31, '用户新增', '3', 'admin/authusers/add', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (32, '用户删除', '3', 'admin/authusers/delete', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (33, '用户提交', '3', 'admin/authusers/savedata', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (34, '角色新增', '3', 'admin/authroles/add', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (35, '角色编辑', '3', 'admin/authroles/edit', NULL, 0, 1484145913, NULL);
INSERT INTO `auth_rule` VALUES (36, '角色删除', '3', 'admin/authroles/delete', NULL, 0, NULL, NULL);
INSERT INTO `auth_rule` VALUES (37, '角色提交', '3', 'admin/authroles/savedata', NULL, 0, NULL, NULL);

-- ----------------------------
-- Table structure for auth_users
-- ----------------------------
DROP TABLE IF EXISTS `auth_users`;
CREATE TABLE `auth_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '账号',
  `pwd` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `status` int(11) NULL DEFAULT 0 COMMENT '状态 （0禁止 1可用）',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '帐号创建时间',
  `administrator` int(1) NULL DEFAULT 0 COMMENT '是否超级管理员，1是 0否',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '账户最后更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_users
-- ----------------------------
INSERT INTO `auth_users` VALUES (1, 'bingo', '068791f7d510a59c65b68feff1c8c6e1', 1, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for edu_notes
-- ----------------------------
DROP TABLE IF EXISTS `edu_notes`;
CREATE TABLE `edu_notes`  (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `time` date NULL DEFAULT NULL,
  `location` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lecturer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of edu_notes
-- ----------------------------
INSERT INTO `edu_notes` VALUES (1, '2007-12-04', '900#', '张三', '办公自动化管理系统的开发', '办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发');

-- ----------------------------
-- Table structure for leave
-- ----------------------------
DROP TABLE IF EXISTS `leave`;
CREATE TABLE `leave`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '1=>病假，2=>出差，3=>休假，4=>事假',
  `start` datetime(4) NULL DEFAULT NULL COMMENT '起始时间',
  `end` datetime(4) NULL DEFAULT NULL COMMENT '结束时间',
  `location` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave
-- ----------------------------
INSERT INTO `leave` VALUES (1, '1', '2019-01-07 00:00:00.0000', '2019-01-07 23:59:00.0000', '', 1, '', 1546864552, 1546864552);

-- ----------------------------
-- Table structure for role_access
-- ----------------------------
DROP TABLE IF EXISTS `role_access`;
CREATE TABLE `role_access`  (
  `role_id` mediumint(8) UNSIGNED NOT NULL COMMENT '角色',
  `rule_id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则唯一英文标识,全小写',
  INDEX `role_id`(`role_id`) USING BTREE,
  INDEX `rule_name`(`rule_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限授权表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_access
-- ----------------------------
INSERT INTO `role_access` VALUES (2, 3);
INSERT INTO `role_access` VALUES (2, 1);
INSERT INTO `role_access` VALUES (3, 2);
INSERT INTO `role_access` VALUES (1, 1);
INSERT INTO `role_access` VALUES (1, 2);
INSERT INTO `role_access` VALUES (1, 3);

-- ----------------------------
-- Table structure for service
-- ----------------------------
DROP TABLE IF EXISTS `service`;
CREATE TABLE `service`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` timestamp(4) NULL DEFAULT NULL,
  `end` timestamp(4) NULL DEFAULT NULL,
  `detail` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `depart` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `participant` json NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for staffs
-- ----------------------------
DROP TABLE IF EXISTS `staffs`;
CREATE TABLE `staffs`  (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sex` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `birth` date NULL DEFAULT NULL,
  `army_date` date NULL DEFAULT NULL,
  `party_date` date NULL DEFAULT NULL,
  `id_num` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `job_title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `job_tm` date NULL DEFAULT NULL,
  `insignia_rk` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `insignia_tm` date NULL DEFAULT NULL,
  `tel` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `depart` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_on` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of staffs
-- ----------------------------
INSERT INTO `staffs` VALUES (1, '王总', '男', '1985-02-25', '1985-02-25', '1985-02-25', '35032219880202314x', '主任', '1985-02-25', '上市', '1985-02-25', '136542***', 'xiaoliu@qq.com', '垫石', 1);
INSERT INTO `staffs` VALUES (2, '小丽', '女', '1985-02-25', '1985-02-25', '1985-02-25', '35032219880202314x', '员工', '1985-02-25', '中式', '1985-02-25', '136542***', 'xiaoliu@qq.com', '迪奥1', 1);
INSERT INTO `staffs` VALUES (3, '小军', '男', '1985-02-25', '1985-02-25', '1985-02-25', '35032219880202314x', '12级', '1985-02-25', '少伟', '1985-02-25', '136542***', 'xiaoliu@qq.com', '配1', 1);
INSERT INTO `staffs` VALUES (4, '小张', '女', '2010-08-12', '1985-02-25', '1985-02-25', '35032219880202314x', '组长', '1985-02-25', '少伟', '1985-02-25', '136542***', 'xiaoliu@qq.com', '配1', 1);
INSERT INTO `staffs` VALUES (5, '小刘', '男', '1985-02-25', '1985-02-25', '1985-02-25', '35032219880202314x', '九级', '1985-02-25', '上士', '1985-02-25', '136542***', 'xiaoliu@qq.com', '垫石', 1);

-- ----------------------------
-- Table structure for train_plan
-- ----------------------------
DROP TABLE IF EXISTS `train_plan`;
CREATE TABLE `train_plan`  (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `category` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '训练类别',
  `date` date NULL DEFAULT NULL COMMENT '日期',
  `start_time` time(0) NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` time(0) NULL DEFAULT NULL COMMENT '结束时间',
  `content` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '内容',
  `organization` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '组织方式',
  `expt_par` int(4) NOT NULL COMMENT '应训人数',
  `pers_in_char` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '负责人',
  `location` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地点',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of train_plan
-- ----------------------------
INSERT INTO `train_plan` VALUES (1, '共同训练', '2019-01-13', '10:00:01', '11:00:00', '管理系统项目开发', '集中', 28, '张三', '900#', NULL);
INSERT INTO `train_plan` VALUES (2, '专业训练', '2019-01-13', '10:00:02', '11:00:00', '办公自动化管理系统的开发', '集中', 29, '周八', '900#', NULL);
INSERT INTO `train_plan` VALUES (3, '专业训练', '2019-01-13', '00:00:01', '10:30:00', '整理光盘', '单独', 25, '张三', '900#', NULL);
INSERT INTO `train_plan` VALUES (4, '共同训练', '2019-01-14', '15:01:56', '17:00:00', '工作计划工作', '集中fda', 28, '赵柳', '900#', NULL);
INSERT INTO `train_plan` VALUES (5, '其他训练', '2019-01-15', '09:30:02', '10:00:00', '开发图书管理系统', '集中', 20, '张三', '900#', NULL);
INSERT INTO `train_plan` VALUES (6, '其他训练', '2019-01-16', '15:00:01', '16:00:00', '测试办公自动化fda', '单独', 29, '王五', '900#', NULL);
INSERT INTO `train_plan` VALUES (7, '共同训练', '2019-01-17', '17:00:02', '18:00:00', '办公自动化程序三', '集中', 29, '李四', '900#', NULL);
INSERT INTO `train_plan` VALUES (8, '共同训练', '2019-01-18', '00:00:03', '10:00:03', '博客系统开发', '单独', 29, '张三', '900#', NULL);
INSERT INTO `train_plan` VALUES (9, '共同训练', '2019-01-19', '16:42:23', '18:00:00', '办公自动化、个人博客等程序开发', '集中', 20, '张三', '900#', NULL);

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `uid` smallint(5) UNSIGNED NOT NULL COMMENT 'user id',
  `role_id` smallint(5) UNSIGNED NOT NULL COMMENT 'role id',
  INDEX `user_id`(`uid`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES (1, 2);

SET FOREIGN_KEY_CHECKS = 1;
