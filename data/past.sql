/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.6.123
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 192.168.6.123:3306
 Source Schema         : past

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 25/10/2019 12:57:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tp6_action_log
-- ----------------------------
DROP TABLE IF EXISTS `tp6_action_log`;
CREATE TABLE `tp6_action_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行用户id',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '执行的链接',
  `ip` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '执行者ip',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志备注',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态',
  `type` int(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '日志类型',
  `header` json NOT NULL COMMENT '信息头json',
  `data` json NOT NULL COMMENT '数据json',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 93 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '行为日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_action_log
-- ----------------------------
INSERT INTO `tp6_action_log` VALUES (91, 1, '/Login/login.html', '3232237166', '超级管理员 在 2019-06-19 14:06:52 登录了系统', 1571979383, 1, 1, '{\"dnt\": \"1\", \"host\": \"past.7.com\", \"accept\": \"*/*\", \"cookie\": \"past_name=4d8621d7158f55ecdf78b92de6e1bec1; remember=test001\", \"origin\": \"http://past.7.com\", \"referer\": \"http://past.7.com/Login/index.html\", \"connection\": \"keep-alive\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36\", \"content-type\": \"application/x-www-form-urlencoded; charset=UTF-8\", \"content-length\": \"62\", \"accept-encoding\": \"gzip, deflate\", \"accept-language\": \"zh-HK,zh-CN;q=0.9,zh;q=0.8\", \"x-requested-with\": \"XMLHttpRequest\"}', '{\"id\": 1, \"qq\": \"\", \"sex\": 0, \"login\": 440, \"score\": 100, \"avatar\": 18, \"reg_ip\": 0, \"status\": 1, \"user_id\": 1, \"birthday\": \"2019-06-19\", \"nickname\": \"超级管理员\", \"reg_time\": \"2017-12-02 15:33:38\", \"create_time\": \"2019-06-19 16:31:14\", \"update_time\": \"2019-10-25 12:56:23\", \"last_login_ip\": \"192.168.6.110\", \"last_login_time\": \"2019-06-19 14:06:52\"}');
INSERT INTO `tp6_action_log` VALUES (92, 1, '/Login/login.html', '3232237166', '超级管理员 在 2019-06-19 14:06:52 登录了系统', 1571979410, 1, 1, '{\"dnt\": \"1\", \"host\": \"past.7.com\", \"accept\": \"*/*\", \"cookie\": \"past_name=4d8621d7158f55ecdf78b92de6e1bec1; remember=admin\", \"origin\": \"http://past.7.com\", \"referer\": \"http://past.7.com/Login/index.html\", \"connection\": \"keep-alive\", \"user-agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36\", \"content-type\": \"application/x-www-form-urlencoded; charset=UTF-8\", \"content-length\": \"60\", \"accept-encoding\": \"gzip, deflate\", \"accept-language\": \"zh-HK,zh-CN;q=0.9,zh;q=0.8\", \"x-requested-with\": \"XMLHttpRequest\"}', '{\"id\": 1, \"qq\": \"\", \"sex\": 0, \"login\": 441, \"score\": 100, \"avatar\": 18, \"reg_ip\": 0, \"status\": 1, \"user_id\": 1, \"birthday\": \"2019-06-19\", \"nickname\": \"超级管理员\", \"reg_time\": \"2017-12-02 15:33:38\", \"create_time\": \"2019-06-19 16:31:14\", \"update_time\": \"2019-10-25 12:56:50\", \"last_login_ip\": \"192.168.6.110\", \"last_login_time\": \"2019-06-19 14:06:52\"}');

-- ----------------------------
-- Table structure for tp6_auth_extend
-- ----------------------------
DROP TABLE IF EXISTS `tp6_auth_extend`;
CREATE TABLE `tp6_auth_extend`  (
  `group_id` mediumint(10) UNSIGNED NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) UNSIGNED NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) UNSIGNED NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE INDEX `group_extend_type`(`group_id`, `extend_id`, `type`) USING BTREE,
  INDEX `uid`(`group_id`) USING BTREE,
  INDEX `group_id`(`extend_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户组与分类的对应关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_auth_extend
-- ----------------------------
INSERT INTO `tp6_auth_extend` VALUES (1, 1, 1);

-- ----------------------------
-- Table structure for tp6_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tp6_auth_group`;
CREATE TABLE `tp6_auth_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
  `main_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '主节点ID',
  `module` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(10) NOT NULL DEFAULT 0 COMMENT '组类型',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_auth_group
-- ----------------------------
INSERT INTO `tp6_auth_group` VALUES (1, 0, 0, 'admin', 1, '测试组', '', 1, '1,2,3,4,6,7,10,12,15,16,18,20,25,26,27,28,29,30,31,40,41,42,43,44,45,46,47,48,49,50,51,58,63,64,65,66,67,70,73,82,85,87,89,91,95,97,111,112,115,116,117,120,121');
INSERT INTO `tp6_auth_group` VALUES (2, 0, 0, 'admin', 1, '总经理', '测试用户', 1, '1');
INSERT INTO `tp6_auth_group` VALUES (3, 0, 0, 'admin', 1, '开发部门', '开发的', 1, '');
INSERT INTO `tp6_auth_group` VALUES (4, 3, 3, 'admin', 1, '测试部', '测试\n', 1, NULL);
INSERT INTO `tp6_auth_group` VALUES (5, 4, 3, 'admin', 1, 'IOS测试组', 'iOS测试', 1, NULL);
INSERT INTO `tp6_auth_group` VALUES (6, 3, 3, 'admin', 1, '开发部', '写代码的,哈哈\n', 1, '');
INSERT INTO `tp6_auth_group` VALUES (7, 6, 3, 'admin', 1, 'android开发组', 'android开发的', 1, '');
INSERT INTO `tp6_auth_group` VALUES (8, 0, 0, 'admin', 1, '演示组', '', 1, '1,2,3,4,6,7,8,10,12,15,16,18,20,25,26,27,28,29,30,31,40,41,42,43,44,45,46,48,49,50,51,58,63,66,67,70,73,77,82,85,91,95,97,111,112,115,116,117,120,121');

-- ----------------------------
-- Table structure for tp6_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tp6_auth_group_access`;
CREATE TABLE `tp6_auth_group_access`  (
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户组id',
  `main_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '主节点ID',
  UNIQUE INDEX `uid_group_id`(`user_id`, `group_id`) USING BTREE,
  INDEX `uid`(`user_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_auth_group_access
-- ----------------------------
INSERT INTO `tp6_auth_group_access` VALUES (2, 8, 0);

-- ----------------------------
-- Table structure for tp6_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tp6_auth_rule`;
CREATE TABLE `tp6_auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1-url;2-主菜单',
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单id',
  `name` char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `module`(`module`, `status`, `type`) USING BTREE,
  INDEX `menu_id`(`menu_id`) USING BTREE COMMENT '菜单索引'
) ENGINE = InnoDB AUTO_INCREMENT = 460 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_auth_rule
-- ----------------------------
INSERT INTO `tp6_auth_rule` VALUES (343, 'admin', 2, 1, 'admin/Index/index', '首页', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (344, 'admin', 2, 2, 'admin/System/index', '系统', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (345, 'admin', 2, 3, 'admin/Member/index', '用户', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (347, 'admin', 1, 6, 'admin/Menu/index', '菜单管理', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (348, 'admin', 1, 7, 'admin/Config/index', '配置列表', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (349, 'admin', 1, 8, 'admin/Menu/renew', '新增或编辑提交菜单', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (350, 'admin', 1, 9, 'admin/Menu/setStatus', '菜单设置状态', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (351, 'admin', 1, 10, 'admin/Menu/add', '菜单添加', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (353, 'admin', 1, 12, 'admin/Menu/edit', '菜单编辑', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (356, 'admin', 1, 15, 'admin/Member/index', '会员中心', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (357, 'admin', 1, 16, 'admin/UserCenter/index', '用户中心', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (359, 'admin', 1, 18, 'admin/Action/log', '用户日志', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (360, 'admin', 1, 20, 'admin/Config/edit', '配置编辑', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (361, 'admin', 1, 21, 'admin/Config/deploy', '系统配置', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (363, 'admin', 1, 25, 'admin/Member/add', '新增会员', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (364, 'admin', 1, 26, 'admin/Competence/index', '权限组别', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (365, 'admin', 1, 27, 'admin/Competence/add', '添加权限组', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (366, 'admin', 1, 28, 'admin/Competence/edit', '编辑权限组', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (367, 'admin', 1, 29, 'admin/Competence/user', '用户授权组', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (368, 'admin', 1, 30, 'admin/Member/detail', '会员详情', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (369, 'admin', 1, 31, 'admin/UserCenter/userCenterInterface', '用户中心数据接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (378, 'admin', 1, 40, 'admin/Category/index', '分类管理', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (379, 'admin', 1, 41, 'admin/Category/add', '添加分类', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (380, 'admin', 1, 42, 'admin/Category/edit', '编辑分类', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (381, 'admin', 2, 43, 'admin/Navigation/index', '网站', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (382, 'admin', 1, 44, 'admin/Navigation/index', '前端导航', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (383, 'admin', 1, 45, 'admin/Links/index', '友情链接', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (384, 'admin', 1, 46, 'admin/Banner/index', 'Banner管理', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (385, 'admin', 1, 47, 'admin/Document/myDocument', '我的文档', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (386, 'admin', 1, 48, 'admin/Document/examine', '待审核', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (387, 'admin', 1, 49, 'admin/Document/draftbox', '草稿箱', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (388, 'admin', 1, 50, 'admin/Document/recycle', '回收站', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (389, 'admin', 1, 51, 'admin/Document/index', '文章分类', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (390, 'admin', 1, 59, 'admin/Document/setStatus', '状态更改', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (391, 'admin', 1, 54, 'admin/Document/approved', '通过审核', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (392, 'admin', 1, 55, 'admin/Document/censor', '删除', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (393, 'admin', 1, 56, 'admin/Document/physicalDelete', '物理删除', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (394, 'admin', 1, 58, 'admin/Document/edit', '新增或编辑', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (395, 'admin', 1, 60, 'admin/Document/move', '移动', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (396, 'admin', 1, 61, 'admin/Document/copy', '复制', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (397, 'admin', 1, 62, 'admin/Document/paste', '粘贴', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (398, 'admin', 1, 63, 'admin/Document/documentInterface', '数据接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (399, 'admin', 1, 66, 'admin/Document/creativeWorkInterface', '文章接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (400, 'admin', 1, 67, 'admin/Document/myDocumentInterface', '文章接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (401, 'admin', 1, 68, 'admin/Document/documentPicture', '文章图片上传', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (402, 'admin', 1, 69, 'admin/document/documentFile', '附件上传', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (403, 'admin', 1, 70, 'admin/Document/ueditorCheck', '富文本编辑器检测', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (404, 'admin', 1, 71, 'admin/Document/picture', '富文本上传图片', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (405, 'admin', 1, 72, 'admin/Document/renew', '文章提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (406, 'admin', 1, 73, 'admin/Member/memberInterface', '会员列表接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (407, 'admin', 1, 74, 'admin/Member/renew', '新增会员提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (408, 'admin', 1, 75, 'admin/Competence/cleanInvalidCompetence', '清理失效权限', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (409, 'admin', 1, 76, 'admin/Member/setStatus', '会员更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (410, 'admin', 1, 77, 'admin/UserCenter/setStatus', '用户更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (411, 'admin', 1, 78, 'admin/Action/actionInterface', '用户行为接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (412, 'admin', 1, 79, 'admin/Action/edit', '添加或新增', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (413, 'admin', 1, 80, 'admin/Action/setStatus', '用户行为更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (414, 'admin', 1, 81, 'admin/Action/renew', '用户行为提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (415, 'admin', 1, 82, 'admin/Action/actionLogInterface', '用户日志接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (416, 'admin', 1, 83, 'admin/Action/clearAll', '用户行为清空', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (417, 'admin', 1, 84, 'admin/Action/actionLogSetStatus', '行为日志更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (418, 'admin', 1, 85, 'admin/Competence/groupInterface', '权限组别接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (419, 'admin', 1, 86, 'admin/Competence/setStatus', '权限组别更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (420, 'admin', 1, 87, 'admin/Competence/access', '访问授权', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (421, 'admin', 1, 88, 'admin/Competence/renew', '权限组提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (422, 'admin', 1, 89, 'admin/competence/nodeInterface', '访问授权接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (423, 'admin', 1, 90, 'admin/competence/updateAuthorization', '访问授权提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (424, 'admin', 1, 91, 'admin/Competence/authAccessInterface', '用户授权组接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (425, 'admin', 1, 92, 'admin/Competence/addUserToGroup', '用户添加到组提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (426, 'admin', 1, 93, 'admin/Competence/removeToGroup', '用户移除授权操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (427, 'admin', 1, 94, 'admin/Competence/removeUserFromGroup', '组移除用户操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (428, 'admin', 1, 95, 'admin/Menu/menuInterface', '菜单接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (429, 'admin', 1, 96, 'admin/Menu/toogle', '菜单功能状态更改', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (430, 'admin', 1, 97, 'admin/Config/configInterface', '配置列表接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (431, 'admin', 1, 98, 'admin/Config/renew', '配置提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (432, 'admin', 1, 99, 'admin/Config/setStatus', '配置状态更改', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (433, 'admin', 1, 100, 'admin/Config/setConfig', '系统配置提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (434, 'admin', 1, 101, 'admin/category/categoryPicture', '分类图片上传', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (435, 'admin', 1, 102, 'admin/Category/renew', '分类提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (436, 'admin', 1, 103, 'admin/Category/renewFast', '分类快速更新', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (437, 'admin', 1, 104, 'admin/Category/setStatus', '分类更新操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (438, 'admin', 1, 105, 'admin/Category/remove', '分类删除', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (439, 'admin', 1, 106, 'admin/Category/move', '分类移动', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (440, 'admin', 1, 107, 'admin/menu/currentSort', '菜单排序', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (441, 'admin', 1, 108, 'admin/DataBase/export', '数据备份操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (442, 'admin', 1, 109, 'admin/DataBase/revert', '数据还原操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (443, 'admin', 1, 110, 'admin/DataBase/deleted', '数据文件删除', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (444, 'admin', 1, 111, 'admin/navigation/navigationInterface', '前端导航接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (445, 'admin', 1, 112, 'admin/Navigation/add', '导航新增', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (446, 'admin', 1, 113, 'admin/Navigation/setStatus', '前端导航状态更改', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (447, 'admin', 1, 114, 'admin/Navigation/renew', '前端导航提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (448, 'admin', 1, 115, 'admin/Navigation/edit', '前端导航编辑', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (449, 'admin', 1, 116, 'admin/Links/linksInterface', '链接列表接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (450, 'admin', 1, 117, 'admin/Links/edit', '添加或新增', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (451, 'admin', 1, 118, 'admin/Links/setStatus', '链接状态更改', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (452, 'admin', 1, 119, 'admin/Links/renew', '链接提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (453, 'admin', 1, 120, 'admin/Banner/bannerInterface', 'Banner列表接口', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (454, 'admin', 1, 121, 'admin/Banner/edit', 'Banner新增或编辑', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (455, 'admin', 1, 122, 'admin/Banner/renew', 'Banner提交', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (456, 'admin', 1, 123, 'admin/Banner/setStatus', 'Banner状态操作', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (458, 'admin', 2, 4, 'admin/Document/myDocument', '内容', 1, '');
INSERT INTO `tp6_auth_rule` VALUES (459, 'admin', 1, 125, 'admin/Menu/getControllerFileName', '获取控制器文件名称', 1, '');

-- ----------------------------
-- Table structure for tp6_banner
-- ----------------------------
DROP TABLE IF EXISTS `tp6_banner`;
CREATE TABLE `tp6_banner`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '链接',
  `parameter` json NOT NULL COMMENT '参数',
  `picture` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片id',
  `remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '备注',
  `position` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '位置',
  `sort` tinyint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态 -1删除 1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'banner表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tp6_category
-- ----------------------------
DROP TABLE IF EXISTS `tp6_category`;
CREATE TABLE `tp6_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标志',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分类ID',
  `level` tinyint(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '级别',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序（同级有效）',
  `list_row` tinyint(3) UNSIGNED NOT NULL DEFAULT 10 COMMENT '列表每页行数',
  `meta_title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `type` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `allow_publish` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许发布内容',
  `display` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '可见性',
  `reply` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许回复',
  `check` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布的文章是否需要审核',
  `extend` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '扩展设置',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '数据状态',
  `icon` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类图标',
  `groups` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分组定义',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_name`(`name`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_category
-- ----------------------------
INSERT INTO `tp6_category` VALUES (1, 'default', '默认分类', 0, 0, 0, 0, '', '', '', '', '', '', '', '2', 0, 1, 0, 0, '', 1473405650, 1552703708, 1, 0, '斯蒂芬森就');

-- ----------------------------
-- Table structure for tp6_configuration
-- ----------------------------
DROP TABLE IF EXISTS `tp6_configuration`;
CREATE TABLE `tp6_configuration`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置分组',
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `area` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '作用区域 0前后台 1前台 2后台',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `group`(`group`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_configuration
-- ----------------------------
INSERT INTO `tp6_configuration` VALUES (1, 'web_site_title', 1, '网站标题', 4, '', '网站标题前台显示标题', 1378898976, 1531750158, 1, '', 13, 1);
INSERT INTO `tp6_configuration` VALUES (2, 'web_site_description', 2, '网站描述', 4, '', '网站搜索引擎描述', 1378898976, 1478966404, 1, '', 1, 1);
INSERT INTO `tp6_configuration` VALUES (3, 'web_site_keyword', 2, '网站关键字', 4, '', '网站搜索引擎关键字', 1378898976, 1381390100, 1, 'static7,thinkphp', 8, 1);
INSERT INTO `tp6_configuration` VALUES (4, 'web_site_close', 4, '关闭站点', 3, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1561100476, -1, '0', 2, 1);
INSERT INTO `tp6_configuration` VALUES (10, 'web_site_icp', 1, '网站备案号', 4, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '', 9, 1);
INSERT INTO `tp6_configuration` VALUES (11, 'document_position', 3, '文档推荐位', 1, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1561100365, 1, '1:列表推荐\n2:频道推荐\n4:首页推荐', 3, 2);
INSERT INTO `tp6_configuration` VALUES (12, 'document_display', 3, '文档可见性', 1, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1561100365, 1, '0:所有人可见\n1:仅注册会员可见\n2:仅管理员可见', 4, 2);
INSERT INTO `tp6_configuration` VALUES (23, 'open_draftbox', 4, '是否开启草稿功能', 1, '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', 1379484332, 1561100365, 1, '0', 1, 2);
INSERT INTO `tp6_configuration` VALUES (24, 'draft_aotosave_interval', 1, '自动保存草稿时间', 1, '', '自动保存草稿的时间间隔，单位：秒', 1379484574, 1561100365, 1, '60', 2, 2);
INSERT INTO `tp6_configuration` VALUES (25, 'list_rows', 5, '后台每页记录数', 5, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 10, 0);
INSERT INTO `tp6_configuration` VALUES (26, 'user_allow_register', 5, '是否允许用户注册', 5, '0:关闭注册\r\n1:允许注册', '是否开放用户注册', 1379504487, 1561100541, -1, '0', 3, 0);
INSERT INTO `tp6_configuration` VALUES (28, 'data_backup_path', 1, '数据库备份根路径', 3, '', '路径必须以 / 结尾', 1381482411, 1561100342, -1, '../data/', 5, 2);
INSERT INTO `tp6_configuration` VALUES (29, 'data_backup_part_size', 5, '数据库备份卷大小', 3, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1561098575, -1, '20971520', 7, 2);
INSERT INTO `tp6_configuration` VALUES (30, 'data_backup_compress', 4, '数据库备份文件是否启用压缩', 3, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1561098575, -1, '1', 9, 2);
INSERT INTO `tp6_configuration` VALUES (31, 'data_backup_compress_level', 4, '数据库备份文件压缩级别', 3, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1561098575, -1, '9', 10, 2);
INSERT INTO `tp6_configuration` VALUES (32, 'develop_mode', 4, '开启开发者模式', 3, '0:关闭\r\n1:开启', '是否开启开发者模式', 1383105995, 1571969408, 1, '1', 11, 2);
INSERT INTO `tp6_configuration` VALUES (33, 'allow_visit', 3, '不受限控制器方法', 3, '', '', 1386644047, 1571969408, 1, '1:Login/index\n2:Index/index\n3:Index/clearRuntime\n4:Login/logout', 0, 2);
INSERT INTO `tp6_configuration` VALUES (34, 'deny_visit', 3, '超管专限控制器方法', 3, '', '仅超级管理员可访问的控制器方法', 1386644141, 1571969408, 1, '', 0, 2);
INSERT INTO `tp6_configuration` VALUES (35, 'reply_list_rows', 5, '回复列表每页条数', 1, '', '', 1386645376, 1561100427, -1, '10', 0, 1);
INSERT INTO `tp6_configuration` VALUES (36, 'admin_allow_ip', 2, '后台允许访问IP', 3, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1561100504, -1, '', 12, 2);
INSERT INTO `tp6_configuration` VALUES (46, 'domain', 1, '本站域名', 4, '', '请带上http://或者https:// ', 1481617913, 1515837377, 1, '', 0, 0);
INSERT INTO `tp6_configuration` VALUES (47, 'introduction', 2, '本站简介', 4, '', '', 1515502659, 1515502659, 1, '', 0, 1);

-- ----------------------------
-- Table structure for tp6_document
-- ----------------------------
DROP TABLE IF EXISTS `tp6_document`;
CREATE TABLE `tp6_document`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `name` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) UNSIGNED NOT NULL COMMENT '所属分类',
  `group_id` smallint(3) UNSIGNED NOT NULL COMMENT '所属分组',
  `description` char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '根节点',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属ID',
  `check` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否审核 0未审核 1已审核 2审核失败',
  `praise` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `position` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推荐位',
  `label` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '标签 ',
  `cover_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '封面',
  `display` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '可见性',
  `deadline` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '截至时间',
  `draft` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '草稿 0否 1是 ',
  `view` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览量',
  `comment` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数',
  `extend` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展统计字段',
  `level` int(10) NOT NULL DEFAULT 0 COMMENT '优先级',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '数据状态',
  `original` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '原文链接',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE COMMENT '文章ID',
  INDEX `idx_category_status`(`category_id`, `status`) USING BTREE,
  INDEX `idx_status_type_pid`(`status`, `user_id`, `pid`) USING BTREE,
  FULLTEXT INDEX `title`(`title`, `description`)
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文档模型基础表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tp6_document_article
-- ----------------------------
DROP TABLE IF EXISTS `tp6_document_article`;
CREATE TABLE `tp6_document_article`  (
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档ID',
  `parse` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '内容解析类型',
  `template` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏数',
  `keywords` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '关键词',
  `file_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件ID',
  `download` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '下载次数',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章内容',
  PRIMARY KEY (`document_id`) USING BTREE,
  UNIQUE INDEX `document_id`(`document_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文档模型文章表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tp6_file
-- ----------------------------
DROP TABLE IF EXISTS `tp6_file`;
CREATE TABLE `tp6_file`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '远程地址',
  `md5` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态 1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传时间',
  `original_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '原始文件名',
  `file_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '保存名称',
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `mime` char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件后缀',
  `location` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件保存位置 0本地,1远程,2其他',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_file
-- ----------------------------
INSERT INTO `tp6_file` VALUES (14, '/storage/picture/5d5104fac3880.jpg', 'https://source.calm7.com/picture/5d5104fac3880.jpg', '9aa2f9a1a40152a318feead01af52764', 'c693b92cb2266904ae9284e8816552325cd0853e', 1, 1565590778, '2518-11091022301758.jpg', 'picture/5d5104fac3880.jpg', 161266, 'image/jpeg', 'jpg', 1);
INSERT INTO `tp6_file` VALUES (15, '/storage/picture/5d5104fac2b45.jpg', 'https://source.calm7.com/picture/5d5104fac2b45.jpg', '9aa2f9a1a40152a318feead01af52764', 'c693b92cb2266904ae9284e8816552325cd0853e', 1, 1565590778, '2518-11091022301758.jpg', 'picture/5d5104fac2b45.jpg', 161266, 'image/jpeg', 'jpg', 1);
INSERT INTO `tp6_file` VALUES (16, '/storage/picture/5d51050fad347.jpg', 'https://source.calm7.com/picture/5d51050fad347.jpg', 'e5c05bfba3b431f039b771bf5e755c50', 'a0d14d6c16ab0b164903fc1aa1ae6b70d5ac6b03', 1, 1565590799, '503d269759ee3d6da24c86014a166d224e4ade76.jpg', 'picture/5d51050fad347.jpg', 36896, 'image/jpeg', 'jpg', 1);
INSERT INTO `tp6_file` VALUES (17, '/storage/picture/5d51051ba5006.jpg', 'https://source.calm7.com/picture/5d51051ba5006.jpg', '38958f79023c95adfbac9a3f8d296242', 'c83d45e97691b06769be3449aa10bb9832cfcd20', 1, 1565590811, 'qrcode_for_gh_c9b688b4549a_258.jpg', 'picture/5d51051ba5006.jpg', 27893, 'image/jpeg', 'jpg', 1);
INSERT INTO `tp6_file` VALUES (18, '/storage/picture/5d9eed470dd08.png', 'https://source.calm7.com/picture/5d9eed470dd08.png', 'da38e89595fd2082806920c42444468d', 'a03dadde7f8aa03045fdf9e25ad65152b83a7cde', 1, 1570696519, '7.png', 'picture/5d9eed470dd08.png', 29665, 'image/png', 'png', 1);
INSERT INTO `tp6_file` VALUES (19, '/storage/picture/5db138b1ef51f.jpg', 'https://source.calm7.com/picture/5db138b1ef51f.jpg', 'f6502de35514960c5cdff6229f98ab17', '9a3fa7790f87aa34cb83d849905ad0766a7c1511', 1, 1571895473, '99d58PIC6vm_1024.jpg', 'picture/5db138b1ef51f.jpg', 59662, 'image/jpeg', 'jpg', 1);
INSERT INTO `tp6_file` VALUES (20, '/storage/picture/5db138b895586.jpg', 'https://source.calm7.com/picture/5db138b895586.jpg', 'e8d29a3b0aff7d695764b718969b3720', 'f57215f40bf75495beaadd7f653bc61d1bb1db54', 1, 1571895480, '123.jpg', 'picture/5db138b895586.jpg', 33533, 'image/jpeg', 'jpg', 1);

-- ----------------------------
-- Table structure for tp6_links
-- ----------------------------
DROP TABLE IF EXISTS `tp6_links`;
CREATE TABLE `tp6_links`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点名称',
  `link` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `summary` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点描述',
  `contact` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站长联系方式',
  `sort` int(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '优先级',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态（0：禁用，1：正常 -1：删除）',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '友情连接表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tp6_member
-- ----------------------------
DROP TABLE IF EXISTS `tp6_member`;
CREATE TABLE `tp6_member`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `nickname` char(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `birthday` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '出生日期',
  `qq` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT 0 COMMENT '用户积分',
  `login` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '注册IP',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '会员状态',
  `avatar` tinyint(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户头像',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`, `user_id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_member
-- ----------------------------
INSERT INTO `tp6_member` VALUES (1, 1, '超级管理员', 0, 1560933074, '', 100, 441, 0, 1512200018, 3232237166, 1560924412, 1, 18, 1560933074, 1571979410);
INSERT INTO `tp6_member` VALUES (2, 2, 'test001', 0, 0, '', 3, 17, 17382, 1552644168, 3232237166, 1559965648, 1, 0, 1560933074, 1571969140);

-- ----------------------------
-- Table structure for tp6_menu
-- ----------------------------
DROP TABLE IF EXISTS `tp6_menu`;
CREATE TABLE `tp6_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `module` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '所属模块',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分类ID',
  `main_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '主导航菜单ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序（同级有效）',
  `url` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否隐藏',
  `tip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 127 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_menu
-- ----------------------------
INSERT INTO `tp6_menu` VALUES (1, '首页', 'admin', 0, 0, 1, 'Index/index', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (2, '系统', 'admin', 0, 0, 4, 'System/index', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (3, '用户', 'admin', 0, 0, 3, 'Member/index', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (4, '内容', 'admin', 0, 0, 2, 'Document/myDocument', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (6, '菜单管理', 'admin', 2, 2, 1, 'Menu/index', 0, '', '系统管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (7, '配置列表', 'admin', 2, 2, 2, 'Config/index', 0, '', '系统管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (8, '新增或编辑提交菜单', 'admin', 6, 2, 0, 'Menu/renew', 0, '新增,编辑公用', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (9, '菜单设置状态', 'admin', 6, 2, 0, 'Menu/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (10, '菜单添加', 'admin', 6, 2, 1, 'Menu/add', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (11, '备份数据库', 'admin', 2, 2, 5, 'DataBase/index', 0, '', '数据库管理', 0, -1);
INSERT INTO `tp6_menu` VALUES (12, '菜单编辑', 'admin', 6, 2, 3, 'Menu/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (13, '表单生成', 'admin', 2, 2, 7, 'Develop/formGeneration', 0, '', '开发管理', 1, 1);
INSERT INTO `tp6_menu` VALUES (14, '数据表格生成', 'admin', 2, 2, 8, 'Develop/dataGeneration', 0, '', '开发管理', 1, 1);
INSERT INTO `tp6_menu` VALUES (15, '会员中心', 'admin', 3, 3, 0, 'Member/index', 0, '', '用户管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (16, '用户中心', 'admin', 3, 3, 0, 'UserCenter/index', 0, '', '用户管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (17, '用户行为', 'admin', 3, 3, 0, 'Action/index', 0, '', '行为管理', 0, -1);
INSERT INTO `tp6_menu` VALUES (18, '用户日志', 'admin', 3, 3, 0, 'Action/log', 0, '', '日志管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (20, '配置编辑', 'admin', 7, 2, 0, 'Config/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (21, '系统配置', 'admin', 2, 2, 3, 'Config/deploy', 0, '', '系统管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (22, '还原数据库', 'admin', 2, 2, 6, 'DataBase/import', 0, '', '数据库管理', 0, -1);
INSERT INTO `tp6_menu` VALUES (25, '新增会员', 'admin', 15, 3, 0, 'Member/add', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (26, '权限组别', 'admin', 3, 3, 0, 'Competence/index', 0, '', '权限管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (27, '添加权限组', 'admin', 26, 3, 0, 'Competence/add', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (28, '编辑权限组', 'admin', 26, 3, 0, 'Competence/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (29, '用户授权组', 'admin', 3, 3, 0, 'Competence/user', 0, '', '权限管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (30, '会员详情', 'admin', 15, 3, 0, 'Member/detail', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (31, '用户中心数据接口', 'admin', 16, 3, 0, 'UserCenter/userCenterInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (40, '分类管理', 'admin', 2, 2, 4, 'Category/index', 0, '', '系统管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (41, '添加分类', 'admin', 40, 2, 1, 'Category/add', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (42, '编辑分类', 'admin', 40, 2, 0, 'Category/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (43, '网站', 'admin', 0, 0, 5, 'Navigation/index', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (44, '前端导航', 'admin', 43, 43, 0, 'Navigation/index', 0, '', '前端管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (45, '友情链接', 'admin', 43, 43, 0, 'Links/index', 0, '', '前端管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (46, 'Banner管理', 'admin', 43, 43, 0, 'Banner/index', 0, '', '前端管理', 0, 1);
INSERT INTO `tp6_menu` VALUES (47, '我的文档', 'admin', 4, 4, 0, 'Document/myDocument', 0, '', '个人中心', 0, 1);
INSERT INTO `tp6_menu` VALUES (48, '待审核', 'admin', 4, 4, 0, 'Document/examine', 0, '', '个人中心', 0, 1);
INSERT INTO `tp6_menu` VALUES (49, '草稿箱', 'admin', 4, 4, 0, 'Document/draftbox', 0, '', '个人中心', 0, 1);
INSERT INTO `tp6_menu` VALUES (50, '回收站', 'admin', 4, 4, 0, 'Document/recycle', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (51, '文章分类', 'admin', 4, 4, 0, 'Document/index', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (52, '状态更改', 'admin', 47, 4, 0, 'Document/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (53, '状态更改', 'admin', 48, 4, 0, 'Document/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (54, '通过审核', 'admin', 48, 4, 0, 'Document/approved', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (55, '删除', 'admin', 49, 4, 0, 'Document/censor', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (56, '物理删除', 'admin', 50, 4, 0, 'Document/physicalDelete', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (57, '还原', 'admin', 50, 4, 0, 'Document/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (58, '新增或编辑', 'admin', 51, 4, 0, 'Document/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (59, '状态更改', 'admin', 51, 4, 0, 'Document/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (60, '移动', 'admin', 51, 4, 0, 'Document/move', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (61, '复制', 'admin', 51, 4, 0, 'Document/copy', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (62, '粘贴', 'admin', 51, 4, 0, 'Document/paste', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (63, '数据接口', 'admin', 51, 4, 0, 'Document/documentInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (64, '文章接口', 'admin', 50, 4, 0, 'Document/creativeWorkInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (65, '文章接口', 'admin', 49, 4, 0, 'Document/creativeWorkInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (66, '文章接口', 'admin', 48, 4, 0, 'Document/creativeWorkInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (67, '文章接口', 'admin', 47, 4, 0, 'Document/myDocumentInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (68, '文章图片上传', 'admin', 51, 4, 0, 'Document/documentPicture', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (69, '附件上传', 'admin', 51, 4, 0, 'document/documentFile', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (70, '富文本编辑器检测', 'admin', 51, 4, 0, 'Document/ueditorCheck', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (71, '富文本上传图片', 'admin', 51, 4, 0, 'Document/picture', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (72, '文章提交', 'admin', 51, 4, 0, 'Document/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (73, '会员列表接口', 'admin', 15, 3, 0, 'Member/memberInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (74, '新增会员提交', 'admin', 15, 3, 0, 'Member/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (75, '清理失效权限', 'admin', 26, 3, 0, 'Competence/cleanInvalidCompetence', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (76, '会员更新操作', 'admin', 15, 3, 0, 'Member/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (77, '用户更新操作', 'admin', 16, 3, 0, 'UserCenter/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (78, '用户行为接口', 'admin', 17, 3, 0, 'Action/actionInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (79, '添加或新增', 'admin', 17, 3, 0, 'Action/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (80, '用户行为更新操作', 'admin', 17, 3, 0, 'Action/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (81, '用户行为提交', 'admin', 17, 3, 0, 'Action/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (82, '用户日志接口', 'admin', 18, 3, 0, 'Action/actionLogInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (83, '用户行为清空', 'admin', 18, 3, 0, 'Action/clearAll', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (84, '行为日志更新操作', 'admin', 18, 3, 0, 'Action/actionLogSetStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (85, '权限组别接口', 'admin', 26, 3, 0, 'Competence/groupInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (86, '权限组别更新操作', 'admin', 26, 3, 0, 'Competence/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (87, '访问授权', 'admin', 26, 3, 0, 'Competence/access', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (88, '权限组提交', 'admin', 26, 3, 0, 'Competence/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (89, '访问授权接口', 'admin', 26, 3, 0, 'competence/nodeInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (90, '访问授权提交', 'admin', 26, 3, 0, 'competence/updateAuthorization', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (91, '用户授权组接口', 'admin', 29, 3, 0, 'Competence/authAccessInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (92, '用户添加到组提交', 'admin', 29, 3, 0, 'Competence/addUserToGroup', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (93, '用户移除授权操作', 'admin', 29, 3, 0, 'Competence/removeToGroup', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (94, '组移除用户操作', 'admin', 29, 3, 0, 'Competence/removeUserFromGroup', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (95, '菜单接口', 'admin', 6, 2, 0, 'Menu/menuInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (96, '菜单功能状态更改', 'admin', 6, 2, 0, 'Menu/toogle', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (97, '配置列表接口', 'admin', 7, 2, 0, 'Config/configInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (98, '配置提交', 'admin', 7, 2, 0, 'Config/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (99, '配置状态更改', 'admin', 7, 2, 0, 'Config/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (100, '系统配置提交', 'admin', 21, 2, 0, 'Config/setConfig', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (101, '分类图片上传', 'admin', 40, 2, 0, 'category/categoryPicture', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (102, '分类提交', 'admin', 40, 2, 0, 'Category/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (103, '分类快速更新', 'admin', 40, 2, 0, 'Category/renewFast', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (104, '分类更新操作', 'admin', 40, 2, 0, 'Category/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (105, '分类删除', 'admin', 40, 2, 0, 'Category/remove', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (106, '分类移动', 'admin', 40, 2, 0, 'Category/move', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (107, '菜单排序', 'admin', 6, 2, 2, 'menu/currentSort', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (108, '数据备份操作', 'admin', 11, 2, 0, 'DataBase/export', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (109, '数据还原操作', 'admin', 22, 2, 0, 'DataBase/revert', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (110, '数据文件删除', 'admin', 22, 2, 0, 'DataBase/deleted', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (111, '前端导航接口', 'admin', 44, 43, 0, 'navigation/navigationInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (112, '导航新增', 'admin', 44, 43, 0, 'Navigation/add', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (113, '前端导航状态更改', 'admin', 44, 43, 0, 'Navigation/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (114, '前端导航提交', 'admin', 44, 43, 0, 'Navigation/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (115, '前端导航编辑', 'admin', 44, 43, 0, 'Navigation/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (116, '链接列表接口', 'admin', 45, 43, 0, 'Links/linksInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (117, '添加或新增', 'admin', 45, 43, 0, 'Links/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (118, '链接状态更改', 'admin', 45, 43, 0, 'Links/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (119, '链接提交', 'admin', 45, 43, 0, 'Links/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (120, 'Banner列表接口', 'admin', 46, 43, 0, 'Banner/bannerInterface', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (121, 'Banner新增或编辑', 'admin', 46, 43, 0, 'Banner/edit', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (122, 'Banner提交', 'admin', 46, 43, 0, 'Banner/renew', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (123, 'Banner状态操作', 'admin', 46, 43, 0, 'Banner/setStatus', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (124, '接口调用', 'admin', 2, 2, 9, 'Develop/interface', 0, '', '开发管理', 1, 1);
INSERT INTO `tp6_menu` VALUES (125, '获取控制器文件名称', 'admin', 6, 2, 0, 'Menu/getControllerFileName', 0, '', '', 0, 1);
INSERT INTO `tp6_menu` VALUES (126, '控制器获取方法', 'admin', 6, 2, 0, 'Menu/getFunctionName', 0, '', '', 0, 1);

-- ----------------------------
-- Table structure for tp6_navigation
-- ----------------------------
DROP TABLE IF EXISTS `tp6_navigation`;
CREATE TABLE `tp6_navigation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级频道ID',
  `title` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '频道标题',
  `url` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '频道连接',
  `module` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '所属模块 ',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '导航排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '新窗口打开',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '前端导航' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tp6_user_center
-- ----------------------------
DROP TABLE IF EXISTS `tp6_user_center`;
CREATE TABLE `tp6_user_center`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `email` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户手机',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '注册IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后登录IP',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户状态',
  `email_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '邮箱状态 0 未激活 1 已经激活',
  `mobile_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '手机状态 0 未验证 1 已验证',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tp6_user_center
-- ----------------------------
INSERT INTO `tp6_user_center` VALUES (1, 'admin', '3899cab270688cd7146bb9d8da296473', 'static7@qq.com', '', 1512200018, 0, 1571979409, 3232237166, 0, 1571979410, 1, 0, 0);
INSERT INTO `tp6_user_center` VALUES (2, 'test001', '1f75538ba4647fa95265f139cad5bbe6', 'test001@qq.com', '', 1552644168, 2907885656, 1571969139, 3232237166, 0, 1571969139, 1, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
