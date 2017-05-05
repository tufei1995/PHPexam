-- ----------------------------
-- Table structure for `access`
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `key` varchar(50) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `icon` varchar(25) DEFAULT 'file',
  `is_show` int(1) DEFAULT '1',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='权限信息';

-- ----------------------------
-- Records of access
-- ----------------------------
INSERT INTO `access` VALUES ('1', '0', 'Exam', '我的考试', 'file', '1');
INSERT INTO `access` VALUES ('2', '1', 'selftest', '自我测试', 'file', '1');
INSERT INTO `access` VALUES ('3', '1', 'collection', '我的收藏', 'file', '1');
INSERT INTO `access` VALUES ('4', '1', 'notebook', '错题记录', 'table', '1');
INSERT INTO `access` VALUES ('5', '1', 'test', '统一考试', 'table', '1');
INSERT INTO `access` VALUES ('6', '1', 'start_self_test', '开始自测', 'file', '0');
INSERT INTO `access` VALUES ('7', '1', 'report', '查看报告', 'file-text', '0');
INSERT INTO `access` VALUES ('8', '0', 'Question', '题库管理', 'file-text', '1');
INSERT INTO `access` VALUES ('9', '8', 'choice', '选择题管理', 'list-ul', '1');
INSERT INTO `access` VALUES ('10', '8', 'judge', '判断题管理', 'check-square-o', '1');
INSERT INTO `access` VALUES ('11', '8', 'filling', '填空题管理', 'pencil-square', '1');
INSERT INTO `access` VALUES ('12', '8', 'subjective', '主观题管理', 'pencil-square-o', '1');
INSERT INTO `access` VALUES ('13', '0', 'Test', '考试管理', 'file', '1');
INSERT INTO `access` VALUES ('14', '16', 'lists', '考试列表', 'file', '1');
INSERT INTO `access` VALUES ('15', '16', 'selftest', '学生自测', 'file', '1');
INSERT INTO `access` VALUES ('16', '16', 'mark', '批改试卷', 'file', '1');
INSERT INTO `access` VALUES ('17', '16', 'student_list', '考生列表', 'users', '0');
INSERT INTO `access` VALUES ('18', '16', 'add_student', '新增考生', 'plus', '0');
INSERT INTO `access` VALUES ('19', '16', 'report', '查看报告', 'eye', '0');
INSERT INTO `access` VALUES ('20', '0', 'Setting', '系统设置', 'gears', '1');
INSERT INTO `access` VALUES ('21', '20', 'classes', '班级管理', 'university', '1');
INSERT INTO `access` VALUES ('22', '20', 'chapter', '章节管理', 'list-ol', '1');
INSERT INTO `access` VALUES ('23', '20', 'group', '组别管理', 'users', '1');
INSERT INTO `access` VALUES ('24', '20', 'user', '用户管理', 'user', '1');
INSERT INTO `access` VALUES ('25', '20', 'verify', '用户审核', 'legal', '1');
INSERT INTO `access` VALUES ('26', '20', 'access', '权限管理', 'user-md', '1');
INSERT INTO `access` VALUES ('27', '20', 'grant', '用户授权', 'wrench', '0');
INSERT INTO `access` VALUES ('28', '0', 'add', '新增内容', 'plus', '0');
INSERT INTO `access` VALUES ('29', '28', 'access', '新增权限', 'plus', '0');
INSERT INTO `access` VALUES ('30', '28', 'user', '新增用户', 'plus', '0');
INSERT INTO `access` VALUES ('31', '28', 'chapter', '新增章节', 'plus', '0');
INSERT INTO `access` VALUES ('32', '28', 'class', '新增班级', 'plus', '0');
INSERT INTO `access` VALUES ('33', '28', 'group', '新增组别', 'plus', '0');
INSERT INTO `access` VALUES ('34', '28', 'test', '新增考试', 'plus', '0');
INSERT INTO `access` VALUES ('35', '28', 'choice', '新增选择题', 'plus', '0');
INSERT INTO `access` VALUES ('36', '28', 'judge', '新增判断题', 'plus', '0');
INSERT INTO `access` VALUES ('37', '28', 'filling', '新增填空题', 'plus', '0');
INSERT INTO `access` VALUES ('38', '28', 'subjective', '新增主观题', 'plus', '0');
INSERT INTO `access` VALUES ('39', '0', 'edit', '修改内容', 'pencil', '0');
INSERT INTO `access` VALUES ('40', '39', 'access', '修改权限', 'pencil', '0');
INSERT INTO `access` VALUES ('41', '39', 'user', '修改用户', 'pencil', '0');
INSERT INTO `access` VALUES ('42', '39', 'chapter', '修改章节', 'pencil', '0');
INSERT INTO `access` VALUES ('43', '39', 'class', '修改班级', 'pencil', '0');
INSERT INTO `access` VALUES ('44', '39', 'group', '修改组别', 'pencil', '0');
INSERT INTO `access` VALUES ('45', '39', 'test', '修改考试', 'pencil', '0');
INSERT INTO `access` VALUES ('46', '39', 'choice', '修改选择题', 'pencil', '0');
INSERT INTO `access` VALUES ('47', '39', 'judge', '修改判断题', 'pencil', '0');
INSERT INTO `access` VALUES ('48', '39', 'filling', '修改填空题', 'pencil', '0');
INSERT INTO `access` VALUES ('49', '39', 'subjective', '修改主观题', 'pencil', '0');
INSERT INTO `access` VALUES ('50', '0', 'delete', '删除内容', 'trash', '0');
INSERT INTO `access` VALUES ('51', '50', 'access', '删除权限', 'trash', '0');
INSERT INTO `access` VALUES ('52', '50', 'user', '删除用户', 'trash', '0');
INSERT INTO `access` VALUES ('53', '50', 'chapter', '删除章节', 'trash', '0');
INSERT INTO `access` VALUES ('54', '50', 'class', '删除班级', 'trash', '0');
INSERT INTO `access` VALUES ('55', '50', 'group', '删除组别', 'trash', '0');
INSERT INTO `access` VALUES ('56', '50', 'test', '删除考试', 'trash', '0');
INSERT INTO `access` VALUES ('57', '50', 'choice', '删除选择题', 'trash', '0');
INSERT INTO `access` VALUES ('58', '50', 'judge', '删除判断题', 'trash', '0');
INSERT INTO `access` VALUES ('59', '50', 'subjective', '删除主观题', 'trash', '0');
INSERT INTO `access` VALUES ('60', '50', 'filling', '删除填空题', 'trash', '0');
INSERT INTO `access` VALUES ('61', '50', 'report', '删除考生', 'trash', '0');

-- ----------------------------
-- Table structure for `chapter`
-- ----------------------------
DROP TABLE IF EXISTS `chapter`;
CREATE TABLE `chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `courseid` int(11) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `courseid` (`courseid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='章节信息';

-- ----------------------------
-- Table structure for `choice`
-- ----------------------------
DROP TABLE IF EXISTS `choice`;
CREATE TABLE `choice` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `title` text,
  `ansa` text,
  `ansb` text,
  `ansc` text,
  `ansd` text,
  `right_ans` varchar(7) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `difficulty` varchar(5) DEFAULT NULL,
  `type` enum('single','multiple') DEFAULT 'single',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`),
  KEY `uid` (`uid`),
  KEY `right_ans` (`right_ans`),
  KEY `chapter_id` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='选择题';

-- ----------------------------
-- Table structure for `class`
-- ----------------------------
DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='班级信息';

-- ----------------------------
-- Table structure for `code`
-- ----------------------------
DROP TABLE IF EXISTS `code`;
CREATE TABLE `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) DEFAULT NULL,
  `to` varchar(25) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='验证码';

-- ----------------------------
-- Table structure for `course`
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL,
  `cover` text,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='课程信息';

-- ----------------------------
-- Table structure for `filling`
-- ----------------------------
DROP TABLE IF EXISTS `filling`;
CREATE TABLE `filling` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `difficulty` varchar(5) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `group`
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `access` text,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='组别信息';

-- ----------------------------
-- Table structure for `history`
-- ----------------------------
DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `qid` int(11) DEFAULT NULL,
  `type` enum('choice','judge','subjective') DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `answer` text,
  `collect` int(11) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`qid`,`type`,`uid`) USING BTREE,
  KEY `uid` (`uid`),
  KEY `chapter_id` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='做题历史';

-- ----------------------------
-- Table structure for `judge`
-- ----------------------------
DROP TABLE IF EXISTS `judge`;
CREATE TABLE `judge` (
  `jid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `title` text,
  `right_ans` enum('正确','错误') DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `difficulty` varchar(5) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`jid`),
  KEY `chapter_id` (`pid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='判断题';

-- ----------------------------
-- Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `read` enum('Y','N') NOT NULL DEFAULT 'N',
  `content` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='站内消息';

-- ----------------------------
-- Table structure for `report`
-- ----------------------------
DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `start_time` varchar(19) DEFAULT NULL,
  `end_time` varchar(19) DEFAULT NULL,
  `qids` text,
  `answer` text,
  `statistic` text,
  `is_mark` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '-1',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `unique` (`uid`,`tid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='考试报告';

-- ----------------------------
-- Table structure for `subjective`
-- ----------------------------
DROP TABLE IF EXISTS `subjective`;
CREATE TABLE `subjective` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `title` text,
  `pid` int(11) DEFAULT NULL,
  `difficulty` varchar(5) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`),
  KEY `chapter_id` (`pid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='主观题';

-- ----------------------------
-- Table structure for `test`
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT '120',
  `courseid` int(11) DEFAULT NULL,
  `range` text,
  `type` text,
  `choice` int(11) DEFAULT NULL,
  `judge` int(11) DEFAULT NULL,
  `filling` int(11) DEFAULT NULL,
  `subjective` int(11) DEFAULT NULL,
  `start` varchar(10) DEFAULT '0000-00-00',
  `end` varchar(10) DEFAULT '0000-00-00',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='统一考试信息';

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `email` varchar(25) NOT NULL DEFAULT '',
  `username` varchar(25) DEFAULT '',
  `password` varchar(32) DEFAULT NULL,
  `gid` int(11) NOT NULL DEFAULT '1',
  `name` varchar(25) DEFAULT NULL,
  `college` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `create_time` varchar(19) NOT NULL DEFAULT '',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `access` text,
  `verify` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`email`),
  KEY `password` (`password`),
  KEY `type` (`gid`),
  KEY `email` (`email`),
  KEY `test` (`college`),
  KEY `class` (`class`)
) ENGINE=MyISAM AUTO_INCREMENT=1008 DEFAULT CHARSET=utf8 COMMENT='用户信息';

DROP TRIGGER IF EXISTS `delete_choice`;
DELIMITER ;;
CREATE TRIGGER `delete_choice` AFTER DELETE ON `choice` FOR EACH ROW BEGIN
  delete from history where type='choice' and qid NOT IN (select cid from choice);
END
;;
DELIMITER ;

DROP TRIGGER IF EXISTS `delete_judge`;
DELIMITER ;;
CREATE TRIGGER `delete_judge` AFTER DELETE ON `judge` FOR EACH ROW BEGIN
  delete from history where type='judge' and qid NOT IN (select jid from judge);
END
;;
DELIMITER ;

DROP TRIGGER IF EXISTS `delete_filling`;
DELIMITER ;;
CREATE TRIGGER `delete_filling` AFTER DELETE ON `filling` FOR EACH ROW BEGIN
  delete from history where type='filling' and qid NOT IN (select fid from filling);
END
;;
DELIMITER ;

DROP TRIGGER IF EXISTS `delete_subjective`;
DELIMITER ;;
CREATE TRIGGER `delete_subjective` AFTER DELETE ON `subjective` FOR EACH ROW BEGIN
  delete from history where type='subjective' and qid NOT IN (select sid from subjective);
END
;;
DELIMITER ;
