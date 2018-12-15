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
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=gb2312;

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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=gb2312;

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
) ENGINE=MyISAM DEFAULT CHARSET=gb2312;

-- ----------------------------
-- Records of edu_notes
-- ----------------------------
INSERT INTO `edu_notes` VALUES ('1', '2007-12-04', '900#', '张三', '办公自动化管理系统的开发', '办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发办公自动化管理系统的开发');
