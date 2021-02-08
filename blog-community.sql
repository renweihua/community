/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : blog-community

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 04/02/2021 16:54:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blog_protocol_categories
-- ----------------------------
DROP TABLE IF EXISTS `blog_protocol_categories`;
CREATE TABLE `blog_protocol_categories`  (
  `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '项目分类表',
  `category_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名',
  `category_sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`category_id`) USING BTREE,
  INDEX `blog_protocol_categories_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog_protocols
-- ----------------------------
DROP TABLE IF EXISTS `blog_protocols`;
CREATE TABLE `blog_protocols`  (
  `protocol_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '协议表',
  `protocol_title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '协议标题',
  `protocol_category` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '协议类型',
  `protocol_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '协议内容',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除（0.未删除；1.已删除）',
  PRIMARY KEY (`protocol_id`) USING BTREE,
  INDEX `blog_protocols_protocol_category_index`(`protocol_category`) USING BTREE,
  INDEX `blog_protocols_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_infos
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_infos`;
CREATE TABLE `cnpscy_admin_infos`  (
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `login_num` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `create_time`(`created_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_infos
-- ----------------------------
INSERT INTO `cnpscy_admin_infos` VALUES (1, 16, '', '', 1586429815, 1586429815);
INSERT INTO `cnpscy_admin_infos` VALUES (107, 0, '', '', 1600056174, 1600056174);
INSERT INTO `cnpscy_admin_infos` VALUES (108, 0, '', '', 1600056174, 1600056174);
INSERT INTO `cnpscy_admin_infos` VALUES (109, 0, '', '', 1600056174, 1600056174);
INSERT INTO `cnpscy_admin_infos` VALUES (110, 0, '', '', 1600056174, 1600056174);
INSERT INTO `cnpscy_admin_infos` VALUES (111, 0, '', '', 1597979069, 1597979069);
INSERT INTO `cnpscy_admin_infos` VALUES (112, 0, '0.0.0.0', '', 1597979413, 1597979413);
INSERT INTO `cnpscy_admin_infos` VALUES (113, 0, '0.0.0.0', '', 1597979452, 1597979452);
INSERT INTO `cnpscy_admin_infos` VALUES (114, 0, '0.0.0.0', '', 1597994535, 1597994535);
INSERT INTO `cnpscy_admin_infos` VALUES (117, 0, '0.0.0.0', '', 1600054407, 1600054407);
INSERT INTO `cnpscy_admin_infos` VALUES (118, 0, '0.0.0.0', '', 1600054522, 1600054522);
INSERT INTO `cnpscy_admin_infos` VALUES (119, 0, '0.0.0.0', '', 1600056188, 1600056188);
INSERT INTO `cnpscy_admin_infos` VALUES (123, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36', 1608798983, 1608798983);

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs`;
CREATE TABLE `cnpscy_admin_login_logs`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NULL DEFAULT 0.000000000000,
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2008
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2008`;
CREATE TABLE `cnpscy_admin_login_logs_2008`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2020_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2020_08`;
CREATE TABLE `cnpscy_admin_login_logs_2020_08`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2020_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2020_09`;
CREATE TABLE `cnpscy_admin_login_logs_2020_09`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2020_10
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2020_10`;
CREATE TABLE `cnpscy_admin_login_logs_2020_10`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2020_11
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2020_11`;
CREATE TABLE `cnpscy_admin_login_logs_2020_11`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2020_12
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2020_12`;
CREATE TABLE `cnpscy_admin_login_logs_2020_12`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NULL DEFAULT 0.000000000000,
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_01`;
CREATE TABLE `cnpscy_admin_login_logs_2021_01`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NULL DEFAULT 0.000000000000,
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_02`;
CREATE TABLE `cnpscy_admin_login_logs_2021_02`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登录状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NULL DEFAULT 0.000000000000,
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_login_logs_2021_02
-- ----------------------------
INSERT INTO `cnpscy_admin_login_logs_2021_02` VALUES (1, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36', 1, '登录成功', 'App\\Modules\\Admin\\Http\\Controllers\\AuthController@login', 'POST', '{\"admin_name\":\"admin\",\"password\":\"123456\"}', 0, 1612150016, 1612150016, 4.354428052902);
INSERT INTO `cnpscy_admin_login_logs_2021_02` VALUES (2, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36', 1, '登录成功', 'App\\Modules\\Admin\\Http\\Controllers\\AuthController@login', 'POST', '{\"admin_name\":\"admin\",\"password\":\"123456\"}', 0, 1612159202, 1612159202, 0.662736892700);
INSERT INTO `cnpscy_admin_login_logs_2021_02` VALUES (3, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36', 1, '登录成功', 'App\\Modules\\Admin\\Http\\Controllers\\AuthController@login', 'POST', '{\"admin_name\":\"admin\",\"password\":\"123456\"}', 0, 1612260878, 1612260878, 10.015691041946);

-- ----------------------------
-- Table structure for cnpscy_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs`;
CREATE TABLE `cnpscy_admin_logs`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NOT NULL DEFAULT 0.000000000000,
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_logs_2020_12
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2020_12`;
CREATE TABLE `cnpscy_admin_logs_2020_12`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED ZEROFILL NULL DEFAULT 00000000.000000000000,
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 205 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_logs_2021_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_01`;
CREATE TABLE `cnpscy_admin_logs_2021_01`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED ZEROFILL NULL DEFAULT 00000000.000000000000,
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 488 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_logs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_02`;
CREATE TABLE `cnpscy_admin_logs_2021_02`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `log_duration` decimal(20, 12) UNSIGNED NULL DEFAULT 0.000000000000,
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_menus`;
CREATE TABLE `cnpscy_admin_menus`  (
  `menu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单栏目表',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `menu_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `vue_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vue_path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'VUE路由路径',
  `vue_redirect` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Vue的redirect',
  `vue_icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `vue_component` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'VUE文件路径',
  `vue_meta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `external_links` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '外链',
  `api_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口路由',
  `api_method` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口的请求方式',
  `menu_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否隐藏菜单栏：1：是；0：否',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`menu_id`) USING BTREE,
  INDEX `admin_menus_parent_id_index`(`parent_id`) USING BTREE,
  INDEX `admin_menus_is_check_index`(`is_check`) USING BTREE,
  INDEX `admin_menus_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 78 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_menus
-- ----------------------------
INSERT INTO `cnpscy_admin_menus` VALUES (1, 0, '首页', 'Dashboard', '/', '/dashboard', 'dashboard', 'Layout', '', '', '', '', 1, 0, 1, 0, 1601434917, 1601437649);
INSERT INTO `cnpscy_admin_menus` VALUES (2, 0, '权限管理', 'permissionManage', '/rabc', '/rabc/menus', 'el-icon-lock', 'Layout', '', '', '', '', 2, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (3, 0, '系统设置', 'systemSettings', '', '/configs', 'el-icon-setting', 'Layout', '', '', '', '', 3, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (4, 0, '文章管理', 'articleManage', '/articles', '', 'tab', 'Layout', '', '', '', '', 4, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (5, 1, 'Dashboard', 'Dashboard', 'dashboard', '', '', 'dashboard/index', '', '', 'index', 'GET', 1, 0, 1, 1, 1601434917, 1601437498);
INSERT INTO `cnpscy_admin_menus` VALUES (6, 2, '菜单管理', 'menuManage', 'menus', '', '', 'admin_menus/index', '', '', 'admin/admin_menus', 'GET', 1, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (7, 2, '角色管理', 'roleManage', 'roles', '', '', 'admin_roles/index', '', '', 'admin/admin_roles', 'GET', 2, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (8, 2, '管理员管理', 'adminManage', 'admins', '', '', 'admins/index', '', '', 'admin/admins', 'GET', 3, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (9, 3, '配置管理', 'configManage', '/configs', '', '', 'configs/index', '', '', 'admin/configs', 'GET', 1, 0, 1, 0, 1609126826, 1609126826);
INSERT INTO `cnpscy_admin_menus` VALUES (10, 3, '友情链接', 'friendlinks', '/friendlinks', '', '', 'friendlinks/index', '', '', 'admin/friendlinks', 'GET', 2, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (11, 3, 'Banner管理', 'bannerManage', '/banners', '', '', 'banners/index', '', '', 'admin/banners', 'GET', 3, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (12, 4, '文章分类', 'articleCategory', 'categories', '', '', 'article_categories/index', '', '', 'admin/article_categories', 'GET', 1, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (13, 4, '文章列表', 'articleLists', '', '', '', 'articles/index', '', '', 'admin/articles', 'GET', 2, 0, 1, 0, 1609126826, 1609317417);
INSERT INTO `cnpscy_admin_menus` VALUES (14, 6, '下拉列表', '', '', '', '', '', '', '', 'admin/admin_menus/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (15, 6, '新增', '', '', '', '', '', '', '', 'admin/admin_menus/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (16, 6, '更新', '', '', '', '', '', '', '', 'admin/admin_menus/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (17, 6, '删除', '', '', '', '', '', '', '', 'admin/admin_menus/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (18, 6, '详情', '', '', '', '', '', '', '', 'admin/admin_menus/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (19, 7, '新增', '', '', '', '', '', '', '', 'admin/admin_roles/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (20, 7, '详情', '', '', '', '', '', '', '', 'admin/admin_roles/detail', 'GET', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (21, 7, '更新', '', '', '', '', '', '', '', 'admin/admin_roles/update', 'PUT', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (22, 7, '删除', '', '', '', '', '', '', '', 'admin/admin_roles/delete', 'DELETE', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (23, 7, '下拉列表', '', '', '', '', '', '', '', 'admin/admin_roles/getSelectLists', 'GET', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (24, 8, '新增', '', '', '', '', '', '', '', 'admin/admins/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (25, 8, '详情', '', '', '', '', '', '', '', 'admin/admins/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (26, 8, '更新', '', '', '', '', '', '', '', 'admin/admins/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (27, 8, '删除', '', '', '', '', '', '', '', 'admin/admins/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (28, 8, '下拉列表', '', '', '', '', '', '', '', 'admin/admins/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (29, 9, '新增', '', '', '', '', '', '', '', 'admin/configs/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (30, 3, '配置详情（禁止修改父级，VUE的路由层级嵌套问题）', '详情', '/configs/detail', '', '', 'configs/detail', '', '', 'admin/configs/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (31, 9, '更新', '', '', '', '', '', '', '', 'admin/configs/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (32, 9, '删除', '', '', '', '', '', '', '', 'admin/configs/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (33, 9, '下拉列表', '', '', '', '', '', '', '', 'admin/configs/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (34, 10, '新增', '', '', '', '', '', '', '', 'admin/friendlinks/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (35, 10, '详情', '', '', '', '', '', '', '', 'admin/friendlinks/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (36, 10, '更新', '', '', '', '', '', '', '', 'admin/friendlinks/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (37, 10, '删除', '', '', '', '', '', '', '', 'admin/friendlinks/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (38, 10, '下拉列表', '', '', '', '', '', '', '', 'admin/friendlinks/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (39, 11, '新增', '', '', '', '', '', '', '', 'admin/banners/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (40, 11, '详情', '', '', '', '', '', '', '', 'admin/banners/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (41, 11, '更新', '', '', '', '', '', '', '', 'admin/banners/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (42, 11, '删除', '', '', '', '', '', '', '', 'admin/banners/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (43, 11, '下拉列表', '', '', '', '', '', '', '', 'admin/banners/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (44, 12, '新增', '', '', '', '', '', '', '', 'admin/article_categories/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (45, 12, '详情', '', '', '', '', '', '', '', 'admin/article_categories/detail', 'GET', 2, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (46, 12, '更新', '', '', '', '', '', '', '', 'admin/article_categories/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (47, 12, '删除', '', '', '', '', '', '', '', 'admin/article_categories/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (48, 12, '下拉列表', '', '', '', '', '', '', '', 'admin/article_categories/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (49, 13, '新增', '', '', '', '', '', '', '', 'admin/articles/create', 'POST', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (50, 4, '文章详情（禁止修改父级，VUE的路由层级嵌套问题）', '', 'detail', '', '', 'articles/detail', '', '', 'admin/articles/detail', 'GET', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (51, 13, '更新', '', '', '', '', '', '', '', 'admin/articles/update', 'PUT', 3, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (52, 13, '删除', '', '', '', '', '', '', '', 'admin/articles/delete', 'DELETE', 4, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (53, 13, '下拉列表', '', '', '', '', '', '', '', 'admin/articles/getSelectLists', 'GET', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (54, 0, '日志管理', 'logManage', '/log', 'adminloginlogs', 'el-icon-s-order', 'Layout', '', '', '', '', 5, 0, 1, 0, 1609126942, 1609126965);
INSERT INTO `cnpscy_admin_menus` VALUES (55, 54, '管理员登录日志', 'adminLoginLog', 'adminloginlogs', '', '', 'adminloginlogs/index', '', '', 'admin/adminloginlogs', 'GET', 1, 0, 1, 0, 1609127601, 1609127601);
INSERT INTO `cnpscy_admin_menus` VALUES (56, 54, '管理员操作日志', 'adminOperationLog', 'adminlogs', '', '', 'adminlogs/index', '', '', 'admin/adminlogs', 'GET', 2, 0, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (57, 7, '状态变更', '', '', '', '', '', '', '', 'admin/admin_roles/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (58, 6, '状态变更', '', '', '', '', '', '', '', 'admin/admin_menus/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (59, 8, '状态变更', '', '', '', '', '', '', '', 'admin/admins/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (60, 55, '删除', '', '', '', '', '', '', '', 'admin/adminloginlogs/delete', 'DELETE', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (61, 56, '删除', '', '', '', '', '', '', '', 'admin/adminlogs/delete', 'DELETE', 1, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (62, 12, '状态变更', '', '', '', '', '', '', '', 'admin/article_categories/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (63, 11, '状态变更', '', '', '', '', '', '', '', 'admin/banners/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (64, 10, '状态变更', '', '', '', '', '', '', '', 'admin/friendlinks/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (65, 9, '状态变更', '', '', '', '', '', '', '', 'admin/configs/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (66, 13, '状态变更', '', '', '', '', '', '', '', 'admin/articles/changeFiledStatus', 'PUT', 5, 1, 1, 0, 1609127616, 1609127616);
INSERT INTO `cnpscy_admin_menus` VALUES (67, 3, '版本管理', 'versionManage', 'versions', '', '', 'versions/index', '', '', 'admin/versions', '', 4, 0, 1, 0, 1610001528, 1610001555);
INSERT INTO `cnpscy_admin_menus` VALUES (68, 67, '新增', '', '', '', '', '', '', '', 'admin/versions/create', 'POST', 1, 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (69, 67, '详情', '', '', '', '', '', '', '', 'admin/versions/detail', 'GET', 2, 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (70, 67, '更新', '', '', '', '', '', '', '', 'admin/versions/update', 'PUT', 3, 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (71, 67, '删除', '', '', '', '', '', '', '', 'admin/versions/delete', 'DELETE', 4, 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (72, 4, '文章标签', 'articleLabels', 'labels', '', '', 'article_labels/index', '', '', 'admin/article_labels', 'GET', 3, 0, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (73, 72, '新增', '', '', '', '', '', '', '', 'admin/article_labels/create', 'POST', 0, 0, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (74, 72, '详情', '', '', '', '', '', '', '', 'admin/article_labels/detail', 'GET', 0, 0, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (75, 72, '更新', '', '', '', '', '', '', '', 'admin/article_labels/update', 'PUT', 0, 0, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (76, 72, '删除', '', '', '', '', '', '', '', 'admin/article_labels/delete', 'DELETE', 0, 0, 1, 0, 0, 0);
INSERT INTO `cnpscy_admin_menus` VALUES (77, 4, '社区菜单', 'menuManage', 'menus', '', '', 'menus/index', '', '', 'admin/menus', 'GET', 1, 0, 1, 0, 1611125310, 1611125310);

-- ----------------------------
-- Table structure for cnpscy_admin_role_with_menus
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_role_with_menus`;
CREATE TABLE `cnpscy_admin_role_with_menus`  (
  `with_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色Id',
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单Id',
  PRIMARY KEY (`with_id`) USING BTREE,
  INDEX `role_with_menus_role_id_index`(`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 212 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_role_with_menus
-- ----------------------------
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (1, 1, 1);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (2, 1, 2);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (3, 1, 3);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (4, 1, 4);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (5, 1, 5);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (6, 1, 6);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (7, 1, 7);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (8, 1, 8);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (9, 1, 9);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (10, 1, 10);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (11, 1, 11);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (12, 1, 12);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (13, 1, 13);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (42, 1, 14);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (43, 1, 15);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (44, 1, 16);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (45, 1, 17);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (46, 1, 18);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (47, 1, 19);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (48, 1, 20);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (49, 1, 21);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (50, 1, 22);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (51, 1, 23);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (52, 1, 24);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (53, 1, 25);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (54, 1, 26);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (55, 1, 27);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (56, 1, 28);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (57, 1, 29);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (58, 1, 30);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (59, 1, 31);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (60, 1, 32);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (61, 1, 33);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (62, 1, 34);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (63, 1, 35);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (64, 1, 36);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (65, 1, 37);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (66, 1, 38);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (67, 1, 39);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (68, 1, 40);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (69, 1, 41);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (70, 1, 42);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (71, 1, 43);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (72, 1, 44);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (73, 1, 45);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (74, 1, 46);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (75, 1, 47);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (76, 1, 48);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (77, 1, 49);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (78, 1, 50);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (79, 1, 51);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (80, 1, 52);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (81, 1, 53);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (99, 2, 1);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (100, 2, 2);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (101, 2, 3);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (102, 2, 4);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (114, 1, 57);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (115, 1, 58);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (116, 1, 59);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (117, 1, 60);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (118, 1, 61);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (119, 1, 62);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (120, 1, 63);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (121, 1, 64);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (122, 1, 65);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (123, 1, 66);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (124, 1, 55);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (125, 1, 56);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (126, 12, 1);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (127, 12, 2);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (128, 12, 3);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (129, 12, 4);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (130, 12, 54);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (131, 15, 1);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (132, 15, 2);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (133, 14, 1);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (134, 15, 6);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (135, 14, 2);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (136, 15, 7);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (137, 14, 6);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (138, 15, 14);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (139, 14, 7);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (140, 15, 15);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (141, 14, 14);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (142, 15, 16);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (143, 14, 15);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (144, 15, 17);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (145, 14, 16);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (146, 15, 18);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (147, 14, 17);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (148, 15, 19);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (149, 14, 18);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (150, 15, 58);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (151, 14, 19);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (152, 14, 58);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (153, 15, 3);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (154, 15, 4);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (155, 15, 8);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (156, 15, 9);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (157, 15, 10);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (158, 15, 11);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (159, 15, 12);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (160, 15, 13);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (161, 15, 20);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (162, 15, 21);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (163, 15, 22);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (164, 15, 23);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (165, 15, 24);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (166, 15, 25);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (167, 15, 26);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (168, 15, 27);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (169, 15, 28);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (170, 15, 29);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (171, 15, 30);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (172, 15, 31);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (173, 15, 32);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (174, 15, 33);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (175, 15, 34);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (176, 15, 35);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (177, 15, 36);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (178, 15, 37);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (179, 15, 38);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (180, 15, 39);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (181, 15, 40);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (182, 15, 41);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (183, 15, 42);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (184, 15, 43);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (185, 15, 44);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (186, 15, 45);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (187, 15, 46);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (188, 15, 47);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (189, 15, 48);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (190, 15, 49);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (191, 15, 50);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (192, 15, 51);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (193, 15, 52);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (194, 15, 53);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (195, 15, 54);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (196, 15, 55);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (197, 15, 56);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (198, 15, 57);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (199, 15, 59);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (200, 15, 60);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (201, 15, 61);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (202, 15, 62);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (203, 15, 63);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (204, 15, 64);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (205, 15, 65);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (206, 15, 66);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (207, 1, 67);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (208, 1, 68);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (209, 1, 69);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (210, 1, 70);
INSERT INTO `cnpscy_admin_role_with_menus` VALUES (211, 1, 71);

-- ----------------------------
-- Table structure for cnpscy_admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_roles`;
CREATE TABLE `cnpscy_admin_roles`  (
  `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色表',
  `role_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `role_remarks` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`role_id`) USING BTREE,
  INDEX `blog_admin_roles_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_admin_roles_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_admin_roles_is_check_index`(`is_check`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_roles
-- ----------------------------
INSERT INTO `cnpscy_admin_roles` VALUES (1, '超级管理员', '权限最大的人', 1, 0, 1577671704, 1601293601);
INSERT INTO `cnpscy_admin_roles` VALUES (2, 'test', '备注信息', 0, 0, 1598348975, 1609318749);
INSERT INTO `cnpscy_admin_roles` VALUES (3, '111', '2121', 0, 1, 1608795030, 1609827748);
INSERT INTO `cnpscy_admin_roles` VALUES (7, '31232', '12321', 0, 0, 1608795106, 1609901582);
INSERT INTO `cnpscy_admin_roles` VALUES (10, '42342432', '42423', 1, 0, 1608795181, 1609901579);
INSERT INTO `cnpscy_admin_roles` VALUES (11, '3123213', '2132321', 0, 1, 1608796971, 1609817914);
INSERT INTO `cnpscy_admin_roles` VALUES (12, '111', '111', 0, 1, 1609828403, 1609830841);
INSERT INTO `cnpscy_admin_roles` VALUES (13, '111', '111', 1, 0, 1609831035, 1609831035);
INSERT INTO `cnpscy_admin_roles` VALUES (14, '全部权限的角色', '', 1, 1, 1609832355, 1609832468);
INSERT INTO `cnpscy_admin_roles` VALUES (15, '全部权限的角色', '', 1, 0, 1609832355, 1609832355);

-- ----------------------------
-- Table structure for cnpscy_admin_with_roles
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_with_roles`;
CREATE TABLE `cnpscy_admin_with_roles`  (
  `with_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色Id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  PRIMARY KEY (`with_id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台-角色与管理员关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admin_with_roles
-- ----------------------------
INSERT INTO `cnpscy_admin_with_roles` VALUES (1, 1, 2);
INSERT INTO `cnpscy_admin_with_roles` VALUES (3, 2, 102);
INSERT INTO `cnpscy_admin_with_roles` VALUES (6, 1, 107);
INSERT INTO `cnpscy_admin_with_roles` VALUES (9, 1, 119);
INSERT INTO `cnpscy_admin_with_roles` VALUES (11, 3, 119);
INSERT INTO `cnpscy_admin_with_roles` VALUES (12, 11, 119);
INSERT INTO `cnpscy_admin_with_roles` VALUES (14, 1, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (15, 10, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (16, 11, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (17, 1, 1);
INSERT INTO `cnpscy_admin_with_roles` VALUES (18, 11, 1);
INSERT INTO `cnpscy_admin_with_roles` VALUES (20, 3, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (21, 7, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (22, 2, 123);
INSERT INTO `cnpscy_admin_with_roles` VALUES (23, 2, 118);
INSERT INTO `cnpscy_admin_with_roles` VALUES (24, 7, 118);
INSERT INTO `cnpscy_admin_with_roles` VALUES (25, 10, 118);

-- ----------------------------
-- Table structure for cnpscy_admins
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admins`;
CREATE TABLE `cnpscy_admins`  (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm' COMMENT '密码',
  `admin_head` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `admin_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `login_token` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'login_token',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '登陆状态[0.尚未开放；1.正常；2.禁用]',
  `kick_out` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '是否踢出登录[0：表示在线；1：踢出登录；2.未登录]',
  `use_role` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '正在使用的角色Id',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  PRIMARY KEY (`admin_id`) USING BTREE,
  INDEX `is_check`(`is_check`) USING BTREE,
  INDEX `kick_out`(`kick_out`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 124 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员认证表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_admins
-- ----------------------------
INSERT INTO `cnpscy_admins` VALUES (1, 'admin', '$2y$10$HyOTnVzzX3RhoCXlQ88qfumA81elPAPnBaORTWBSZT8Xg0s68ttKm', '202012/2r8hEvdSVBQa1s3sBCdcXt6fI57nVebNoaFaluQK.png', '123456@qq.com', '', 1, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (107, 'test', '$2y$10$gcgnFEWG47jf2JqM8onRp.OJrnB6JiMOP0.pClO0T1MbQPoo7ZB2C', '1', 'test@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (108, 'test1', '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (109, 'test1', '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (110, 'test1', '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (111, 'test1', '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (112, 'test1', '$2y$10$t6rC29.BfNtQc.s8DB8UkeNIQ3ZxjOP0u231oiTtiA2qQLdeOuumm', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (113, 'test', 'test1', '86', 'test@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (114, 'test1', '$2y$10$AeU9vwBx0d4KttM6yKcdWu8mgy2qgcTVK/v.I4MJuEzNlqz.Li9GG', '86', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (117, 'test123', '$2y$10$lvVejUPm/lypn8Xr1YNHdOYdy7rrjqiv6DWsYlYAR4R4yidnLBg8e', '1', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (118, 'test12311111', '$2y$10$5hSULbXycOJGQgU2p9ZGaepyIW9qG5DvG1BvyipESM.8WUf3rNKvy', '1', '123@qq.com', '', 0, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (119, 'test1232', '$2y$10$tnqyaBCn7HMwuellMuaH2OQq9ax1S1Wmnp7MAtBQ5Tf.dQ6a1fyeu', '1', '123@qq.com', '', 1, 2, 0, 0);
INSERT INTO `cnpscy_admins` VALUES (123, 'demo', '$2y$10$BSCJxQYvJAvfbQK2uOQNT.0SdzPvWc8GOBl09HADOYzo17hgW/rti', '202012/3yNDfZMiAV0rmOR0mhqkbmk3GWUgSOySgB6OCGVw.png', '43243242@qq.com', '', 1, 2, 0, 0);

-- ----------------------------
-- Table structure for cnpscy_adverts
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_adverts`;
CREATE TABLE `cnpscy_adverts`  (
  `advert_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '广告表',
  `advert_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `advert_cover` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '封面',
  `advert_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '外链',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`advert_id`) USING BTREE,
  INDEX `adverts_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_article_categories
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_article_categories`;
CREATE TABLE `cnpscy_article_categories`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级Id',
  `category_sort` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`category_id`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `is_check`(`is_check`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 106 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_article_contents
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_article_contents`;
CREATE TABLE `cnpscy_article_contents`  (
  `article_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章Id',
  `article_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  INDEX `article_contents_article_id_index`(`article_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_article_labels
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_article_labels`;
CREATE TABLE `cnpscy_article_labels`  (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `label_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标签名',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`label_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `create_time`(`created_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章标签表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_article_with_labels
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_article_with_labels`;
CREATE TABLE `cnpscy_article_with_labels`  (
  `with_id` int(11) NOT NULL AUTO_INCREMENT,
  `label_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章标签Id',
  `article_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章Id',
  PRIMARY KEY (`with_id`) USING BTREE,
  INDEX `label_id`(`label_id`) USING BTREE,
  INDEX `article_id`(`article_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 135 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章标签关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_articles
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_articles`;
CREATE TABLE `cnpscy_articles`  (
  `article_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单Id',
  `article_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章标题',
  `article_keywords` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `article_description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `article_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `set_top` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '置顶',
  `is_recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推荐',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否公开：0：私密；1：是；2.密码访问',
  `access_password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '访问密码',
  `article_origin` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章来源',
  `article_author` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章作者',
  `article_link` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详情外链',
  `read_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读数量',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `praise_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `collection_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏数量',
  `comment_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数量',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否启用：0.否；1.是',
  `article_images` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '多图',
  PRIMARY KEY (`article_id`) USING BTREE,
  INDEX `articles_user_id_index`(`user_id`) USING BTREE,
  INDEX `articles_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `is_check`(`is_check`) USING BTREE,
  INDEX `menu_id`(`menu_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 171 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_banners
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_banners`;
CREATE TABLE `cnpscy_banners`  (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `banner_type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Banner类型：common_banner_type_list',
  `banner_cover` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '封面',
  `banner_link` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '外链',
  `banner_words` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文字描述',
  `banner_sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序[从小到大]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`banner_id`) USING BTREE,
  INDEX `banner_type`(`banner_type`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `is_check`(`is_check`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'banner表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_configs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_configs`;
CREATE TABLE `cnpscy_configs`  (
  `config_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '网站配置信息表',
  `config_title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `config_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参数名称',
  `config_value` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参数值',
  `config_group` smallint(6) NOT NULL DEFAULT 0 COMMENT '分组',
  `config_extra` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置项',
  `config_type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型：0.字符串；1.数字；2.文本；3.select下拉框；4.图片；5.富文本',
  `config_sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `config_remark` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '说明',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否审核/可用',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`config_id`) USING BTREE,
  INDEX `configs_created_time_index`(`created_time`) USING BTREE,
  INDEX `configs_config_type_index`(`config_type`) USING BTREE,
  INDEX `configs_is_check_index`(`is_check`) USING BTREE,
  INDEX `configs_config_sort_index`(`config_sort`) USING BTREE,
  INDEX `configs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '网站配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_configs
-- ----------------------------
INSERT INTO `cnpscy_configs` VALUES (1, '网站标题', 'site_web_title', '小丑路人博客', 1, '', 1, 1, '', 1, 1542609152, 1542609573, 0);
INSERT INTO `cnpscy_configs` VALUES (2, '网站作者', 'site_web_author', '小丑路人', 1, '', 1, 2, '', 1, 1542610198, 1542610198, 0);
INSERT INTO `cnpscy_configs` VALUES (3, '网站关键字搜索', 'site_web_keywords', '小丑路人,小丑,路人,小丑疯狂吧,小丑博客,博客,laravel,yii,mysql', 1, '', 1, 3, '', 1, 1542610270, 1542610270, 0);
INSERT INTO `cnpscy_configs` VALUES (4, '网站描述', 'site_web_description', '小丑路人,小丑,路人,小丑疯狂吧,小丑博客,博客,laravel,yii,mysql', 1, '', 1, 4, '', 1, 1542610411, 1542610411, 0);
INSERT INTO `cnpscy_configs` VALUES (5, '网站备案号', 'site_web_site_icp', '晋ICP备18007574号-1', 1, '', 1, 5, '', 1, 1542610443, 1542610443, 0);
INSERT INTO `cnpscy_configs` VALUES (6, '网站Logo', 'site_web_logo', '/statics/blog.jpg', 1, '', 1, 6, '', 1, 1542610488, 1542610488, 0);
INSERT INTO `cnpscy_configs` VALUES (7, '网站的JS/CSS版本号', 'resource_version_number', '20200410', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (8, '文章缓存时长', 'ARTICLE_CACHE_TIME', '60', 4, '', 4, 8, '前台文章详情数据\r\n文章缓存时长（s）', 1, 1542610592, 1542610592, 0);
INSERT INTO `cnpscy_configs` VALUES (9, '站点是否关闭', 'WEB_SITE_CLOSE', '1', 1, '0:关闭\r\n1:开启', 1, 9, '', 1, 1542610636, 1542610636, 0);
INSERT INTO `cnpscy_configs` VALUES (10, '配置分组', 'config_group_list', '0:不分组\r\n1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:博主\r\n6:开发者\r\n7:网站后台\r\n8:JWT\r\n9:小丑社区配置\r\n10:数据库', 4, '', 3, 10, '', 1, 1542610809, 1542617389, 0);
INSERT INTO `cnpscy_configs` VALUES (11, '配置类型列表', 'config_type_list', '0:字符串\r\n1:文本\r\n2:数字\r\n3:数组\r\n4:枚举\r\n5:图片', 4, '', 3, 11, '', 1, 1542610869, 1542610869, 0);
INSERT INTO `cnpscy_configs` VALUES (12, '全站默认展示图', 'web_detault_show_img', '/statics/blog.jpg', 1, '', 4, 12, '如果图片不存在，默认展示为该图片', 1, 1542610960, 1542610960, 0);
INSERT INTO `cnpscy_configs` VALUES (13, '网站站点URL', 'web_url', '', 1, '', 1, 0, '', 1, 1542611040, 1609825861, 0);
INSERT INTO `cnpscy_configs` VALUES (14, '网站作者QQ', 'web_qq', '2278757482', 5, '', 0, 13, '', 1, 1542611300, 1542611300, 0);
INSERT INTO `cnpscy_configs` VALUES (15, '网站作者微信', 'web_wx', 'ren2278757482', 5, '', 0, 14, '', 1, 1542611352, 1542611352, 0);
INSERT INTO `cnpscy_configs` VALUES (16, '网站作者邮箱', 'web_email', '2278757482@qq.com', 5, '', 0, 15, '', 1, 1542611366, 1542611366, 0);
INSERT INTO `cnpscy_configs` VALUES (17, 'QQ群', 'web_qq_group', '858925280', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (18, '后台接口前缀', 'api_admin_prefix', 'admin', 6, '', 0, 17, '', 1, 1542616391, 1542616391, 0);
INSERT INTO `cnpscy_configs` VALUES (19, 'QQ群组-二维码', 'web_qq_group_img', '/assets/qq_group.png', 0, '', 5, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (20, '作者笔名', 'author_pseudonym', '小丑路人', 5, '', 0, 0, '', 1, 1576028058, 1577070893, 0);
INSERT INTO `cnpscy_configs` VALUES (21, '作者的支付宝收款码', 'alipay_image_code', 'http://blog.localhost.com/storage/202102/6d7xf1b8eLmY8ZwmfO3JsS6VbrdCD6FvheG15xkP.png', 0, '', 5, 0, '', 1, 1586483569, 1612261302, 0);
INSERT INTO `cnpscy_configs` VALUES (22, '作者的微信收款码', 'wechat_image_code', '', 0, '', 5, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (23, '作者的QQ收款码', 'qq_image_code', '', 0, '', 5, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (24, '网站后台的TITLE', 'web_admin_title', '小丑路人博客后台管理', 4, '', 0, 26, '', 1, 1542616660, 1542616660, 0);
INSERT INTO `cnpscy_configs` VALUES (25, '网站后台的关键字', 'web_admin_keywords', '小丑路人博客后台', 4, '', 0, 27, '', 1, 1542616679, 1542616679, 0);
INSERT INTO `cnpscy_configs` VALUES (26, '网站后台描述', 'web_admin_description', '小丑路人博客后台', 4, '', 0, 28, '', 1, 1542616718, 1542616718, 0);
INSERT INTO `cnpscy_configs` VALUES (27, '前端菜单类型列表', 'menu_type_list', '0:作为频道页，不可作为栏目发布文章\r\n1:不直接发布内容，用于跳转页面\r\n2:作为发布栏目，文章列表模式\r\n3:单页面模式，例如企业简介', 2, '', 3, 29, '', 1, 1542616835, 1542616835, 0);
INSERT INTO `cnpscy_configs` VALUES (28, '网站后台访问路径前缀', 'admin_prefix', 'admin', 4, '', 0, 30, '', 1, 1542616866, 1542616866, 0);
INSERT INTO `cnpscy_configs` VALUES (29, 'JWT-私有Key', 'privateKey', '&lt;&lt;&lt;EOD-----BEGIN RSA PRIVATE KEY-----MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQABAoGAb/MXV46XxCFRx', 8, '', 8, 0, '', 1, 1542616912, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (30, 'JWT-公钥Key', 'publicKey', '&lt;&lt;&lt;EOD-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQ', 8, '', 8, 0, '', 1, 4294967295, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (31, 'JWT-token存活时间(s)', 'jwt_leeway', '60', 8, '', 8, 0, '', 1, 1542616912, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (33, '是否开启后台操作日志', 'start_admin_operation_logs', '1', 6, '0:关闭\r\n1:开启', 6, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (34, '网站后台的访问URL', 'admin_prefix_url', 'admin', 0, '', 0, 0, '', 0, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (35, '是否启用WEB日志', 'start_web_logs', '1', 6, '0:关闭\r\n1:开启', 6, 10, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (36, '后台-管理员默认密码', 'admin_default_pass', '123456', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (37, 'CDN地址', 'web_cdn_address', '', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (38, '版本号', 'cnpscy_version_number', 'v1.0', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (39, '时区', 'system_timezone', 'Asia/Shanghai', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (40, '禁止访问IP', 'forbiddenip', '', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 0);
INSERT INTO `cnpscy_configs` VALUES (41, '邮件发送方式', 'mail_type', '0:选择邮件发送方式1:SMTP2:2:mail()函数', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (42, 'SMTP[服务器]', 'mail_smtp_host', 'smtp.qq.com', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (43, 'SMTP[端口]', 'mail_smtp_port', '465', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (44, 'SMTP[用户名]', 'mail_smtp_user', '', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (45, 'SMTP[密码]', 'mail_smtp_pass', '', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (46, 'SMTP验证方式', 'mail_verify_type', '0:无1:TLS2:SSL', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (47, '发件人邮箱', 'mail_from_email', '2278757482@qq.com', 0, '', 0, 0, '', 1, 1586483569, 1586483569, 1);
INSERT INTO `cnpscy_configs` VALUES (48, '百度统计代码', 'baidu_statistics', 'var _hmt = _hmt || [];\n            (function() {\n                var hm = document.createElement(\"script\");\n                hm.src = \"https://hm.baidu.com/hm.js?dc25f837a6f07531a995939182324371\";\n                var s = document.getElementsByTagName(\"script\")[0];\n                s.parentNode.insertBefore(hm, s);\n            })();', 1, '', 1, 0, '', 1, 0, 1612772484, 0);

-- ----------------------------
-- Table structure for cnpscy_dynamics
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamics`;
CREATE TABLE `cnpscy_dynamics`  (
  `dynamic_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态表',
  `dynamic_cover` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '封面',
  `dynamic_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '公开度：0.私密；1.完全公开；2.密码访问',
  `access_password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '对于公开度的“密码访问”设置的密码',
  `read_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览量',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  PRIMARY KEY (`dynamic_id`) USING BTREE,
  INDEX `blog_dynamics_is_public_index`(`is_public`) USING BTREE,
  INDEX `blog_dynamics_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_dynamics_read_num_index`(`read_num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_friendlinks
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_friendlinks`;
CREATE TABLE `cnpscy_friendlinks`  (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `link_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接URL',
  `link_cover` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接图标',
  `link_sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序[从小到大]',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `open_window` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否打开新窗口：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  PRIMARY KEY (`link_id`) USING BTREE,
  INDEX `is_check`(`is_check`) USING BTREE,
  INDEX `link_sort`(`link_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '友情链接表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_menus
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_menus`;
CREATE TABLE `cnpscy_menus`  (
  `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台栏目表',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `menu_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `menu_tpltype` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '模板类型',
  `menu_listtpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '列表页模板',
  `menu_detailtpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详情模板',
  `menu_icon` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `menu_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由',
  `menu_cover` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '单页缩略图',
  `menu_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '单页内容内容',
  `menu_keywords` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'head头部展示的关键字搜索',
  `menu_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'head头部展示的描述',
  `menu_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示在首页：1：展示；0：隐藏',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`menu_id`) USING BTREE,
  INDEX `menu_sort`(`menu_sort`) USING BTREE,
  INDEX `is_show`(`is_show`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `menu_tpltype`(`menu_tpltype`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_menus
-- ----------------------------
INSERT INTO `cnpscy_menus` VALUES (1, 0, '首页', 0, 'index', '', 'fa-home', '/', '', '', '', '', 1, 1, 0, 0, 1611133012);
INSERT INTO `cnpscy_menus` VALUES (2, 0, '关于我', 2, 'single-page', '', 'fa-id-card-o', 'about', '/storage/admin/111144_1_933660.jpg', '<p><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\"><br/></span></p><p><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;小丑路人，QQ：<strong>2278757482</strong>。</span></p><p><span style=\"color:#555555;font-family:Microsoft YaHei, Arial, Helvetica, sans-serif\"><span style=\"font-size: 15px;\"><br/></span></span><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;自我描述：</span><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255); text-decoration: underline;\"></span><span style=\"color: rgb(153, 153, 153); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; text-align: justify; background-color: rgb(255, 255, 255);\">3年PHP开发经验，逻辑思维能力好，考虑交互功能完善提示； 数据库设计与优化，mysql读写分离。 Laravel，lumen，第三方登录、支付、邮件发送、短信发送、快递、花呗对接，redis与websocket的运用。 Apicloud，Ajax，Vue交互。 使用svn,git版本控制工具。 对web编码安全性有一定的认识，了解远程表单的提交、防SQL注入、防XSS攻击等。</span><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\"></span></p><p><br/></p><p><span style=\"color: rgb(85, 85, 85); font-family: &quot;Microsoft YaHei&quot;, Arial, Helvetica, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\"><br/></span></p>', '关于我,小丑路人,小丑,路人,小丑路人疯狂吧,小丑路人博客', '关于我,小丑路人,小丑,路人,小丑路人疯狂吧,小丑路人博客', 2, 1, 0, 0, 1611134258);
INSERT INTO `cnpscy_menus` VALUES (3, 0, '编程人生', 2, 'articles', 'detail', 'fa-pencil-square-o', 'development', '/storage/admin/111319_1_732875.jpg', '', '', '', 3, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (4, 0, '生活', 2, 'articles', 'detail', 'fa-twitch', 'life', '/storage/admin/111230_1_897202.jpg', '', '', '', 4, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (5, 0, '留言', 3, 'feedback', '', '', '', '', '', '', '', 5, 0, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (6, 3, 'PHP', 2, 'articles', 'detail', 'fa-wrench', 'php', '', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (7, 3, 'Mysql', 2, 'articles', 'detail', 'fa-windows', 'mysql', '', '', '', '', 2, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (8, 3, 'Vue', 2, 'articles', 'detail', 'fa-globe', 'vue', '', '', '', '', 3, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (9, 3, 'Javascript', 2, 'articles', 'detail', 'fa-code', 'javascript', '', '', '', '', 4, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (10, 3, 'Linux', 2, 'articles', 'detail', 'fa-play-circle', 'linux', '', '', '', '', 5, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (11, 4, '音乐', 2, 'articles', 'detail', 'fa-music', 'music', '', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (12, 4, '生活琐事', 2, 'articles', 'detail', 'fa-picture-o', 'life', '', '', '', '', 2, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (13, 0, '我的码云', 1, '', '', 'fa-link', 'https://gitee.com/cnpscy', '', '', '', '', 6, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (14, 0, '更多功能', 0, '', '', 'fa-flask', '', '', '', '', '', 7, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (15, 14, '文章归档', 0, 'time-axis', '', 'fa-list', 'time-axis', '', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO `cnpscy_menus` VALUES (16, 14, '友情链接', 0, 'friendlinks', '', 'fa-link', 'friendlinks', '', '', '友情链接,小丑路人,小丑,路人,小丑路人疯狂吧,小丑路人博客', '友情链接,小丑路人,小丑,路人,小丑路人疯狂吧,小丑路人博客', 2, 1, 0, 0, 0);

-- ----------------------------
-- Table structure for cnpscy_migrations
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_migrations`;
CREATE TABLE `cnpscy_migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_migrations
-- ----------------------------
INSERT INTO `cnpscy_migrations` VALUES (1, '2020_08_10_182650_create_admins_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (2, '2020_08_10_182726_create_admin_infos_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (3, '2020_08_10_182732_create_admin_logs_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (4, '2020_08_10_182738_create_admin_login_logs_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (6, '2020_08_10_182803_create_admin_roles_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (7, '2020_08_10_182820_create_roles_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (8, '2020_08_10_182831_create_configs_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (9, '2020_08_10_182837_create_banners_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (10, '2020_08_10_182850_create_articles_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (11, '2020_08_10_182859_create_article_categories_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (12, '2020_08_10_182929_create_article_contents_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (13, '2020_08_10_182939_create_friendlinks_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (14, '2020_08_10_182950_create_notices_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (15, '2020_08_10_182959_create_role_menus_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (16, '2020_08_10_182959_create_role_with_menus_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (17, '2020_08_10_183013_create_upload_files_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (18, '2020_08_10_183020_create_upload_groups_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (19, '2020_08_10_183032_create_protocols_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (20, '2020_08_25_151204_create_users_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (21, '2020_08_25_151205_create_user_infos_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (22, '2020_08_25_151447_create_bbs_files_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (23, '2020_08_25_151511_create_bbs_dynamics_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (24, '2020_08_25_151512_create_bbs_dynamic_praises_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (25, '2020_08_25_151523_create_bbs_dynamic_comments_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (26, '2020_08_25_151536_create_bbs_dynamic_collections_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (27, '2020_08_25_151551_create_bbs_settings_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (28, '2020_08_25_151615_create_bbs_chat_records_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (29, '2020_08_25_151630_create_bbs_guestbooks_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (30, '2020_08_25_151650_create_bbs_friends_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (31, '2020_08_25_151652_create_bbs_friend_groups_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (32, '2020_08_25_151705_create_bbs_friend_applies_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (33, '2020_08_25_151719_create_bbs_groups_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (34, '2020_08_25_151729_create_bbs_group_users_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (35, '2020_08_25_151738_create_bbs_group_chats_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (36, '2020_08_25_151748_create_bbs_group_notices_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (37, '2020_08_25_160317_create_bbs_user_settings_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (38, '2020_08_25_161238_create_bbs_albums_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (39, '2020_08_25_161239_create_bbs_album_users_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (40, '2020_08_25_161630_create_adverts_table', 1);
INSERT INTO `cnpscy_migrations` VALUES (41, '2020_09_15_152619_create_notifies', 1);
INSERT INTO `cnpscy_migrations` VALUES (42, '2020_09_15_153803_create_system_notify_users', 1);
INSERT INTO `cnpscy_migrations` VALUES (43, '2020_09_29_105920_create_gallery_tags', 2);
INSERT INTO `cnpscy_migrations` VALUES (44, '2020_09_29_105927_create_galleries', 2);
INSERT INTO `cnpscy_migrations` VALUES (45, '2020_09_29_105938_create_gallery_details', 2);
INSERT INTO `cnpscy_migrations` VALUES (46, '2020_09_29_111311_create_gallery_with_tags', 2);
INSERT INTO `cnpscy_migrations` VALUES (48, '2020_08_10_182755_create_admin_menus_table', 3);
INSERT INTO `cnpscy_migrations` VALUES (49, '2020_11_20_180501_create_bbs_user_signs', 4);
INSERT INTO `cnpscy_migrations` VALUES (50, '2020_11_20_180502_create_bbs_user_grades', 4);
INSERT INTO `cnpscy_migrations` VALUES (51, '2020_11_20_180503_create_bbs_user_experience_records', 4);
INSERT INTO `cnpscy_migrations` VALUES (52, '2020_11_20_180912_create_user_email_verifies', 4);
INSERT INTO `cnpscy_migrations` VALUES (53, '2020_11_20_181117_create_user_login_logs', 4);

-- ----------------------------
-- Table structure for cnpscy_notices
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notices`;
CREATE TABLE `cnpscy_notices`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies`;
CREATE TABLE `cnpscy_notifies`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息通知记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：1：是；0：否',
  `notify_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '通知类型：0.系统通知/公告；1.提醒；2.私信',
  `target_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标Id(比如动态ID)',
  `target_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标类型：0.动态',
  `sender_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者Id',
  `sender_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者类型：0.系统通知；1.指定会员',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `notify_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '通知内容',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `notifies_is_read_index`(`is_read`) USING BTREE,
  INDEX `notifies_notify_type_index`(`notify_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_protocols
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_protocols`;
CREATE TABLE `cnpscy_protocols`  (
  `protocol_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '协议表',
  `protocol_title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '协议标题',
  `protocol_category` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '协议类型',
  `protocol_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '协议内容',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除（0.未删除；1.已删除）',
  PRIMARY KEY (`protocol_id`) USING BTREE,
  INDEX `blog_protocols_protocol_category_index`(`protocol_category`) USING BTREE,
  INDEX `blog_protocols_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_versions
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_versions`;
CREATE TABLE `cnpscy_versions`  (
  `version_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '版本表',
  `version_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `version_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '版本号',
  `version_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `version_sort` smallint(3) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `publish_date` datetime(0) NULL DEFAULT NULL COMMENT '版本的发布时间',
  PRIMARY KEY (`version_id`) USING BTREE,
  INDEX `cnpscy_versions_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cnpscy_versions
-- ----------------------------
INSERT INTO `cnpscy_versions` VALUES (1, '基础版本发布', '1.0.0', '[1.0.0 分支](https://gitee.com/clown-passerby-community/laravel-vue-admin/tree/1.0.0)', 1610002630, 1610010586, 0, 1, '2021-01-05 16:56:32');
INSERT INTO `cnpscy_versions` VALUES (2, '1.0.1', '1.0.1', '[1.0.1 分支](https://gitee.com/clown-passerby-community/laravel-vue-admin/tree/1.0.1)\n\n* **文章详情完善**；\n* **文章列表：状态变更设置；**\n* **默认图片组件的border-radius可随意传参设置;**\n* **系统设置下的配置管理、友情链接、Banner文件目录更换至根目录。**', 1610002657, 1610010613, 0, 2, '2021-01-06 00:03:18');
INSERT INTO `cnpscy_versions` VALUES (3, '1.0.2', '1.0.2', '[1.0.2 分支](https://gitee.com/clown-passerby-community/laravel-vue-admin/tree/1.0.2)\n\n* **表单内的下拉框，默认100%宽度占比；**\n* **表单详情排版label不同页面不同宽度调整；**\n* **列表页状态对应图标展示效果；**\n* **scope.row替换为row简化代码写法；**\n* **菜单管理列表页：隐藏与展示图标对应效果。**', 1610002669, 1610010640, 0, 3, '2021-01-06 13:03:24');
INSERT INTO `cnpscy_versions` VALUES (4, '1.0.3', '1.0.3', '[1.0.3 分支](https://gitee.com/clown-passerby-community/laravel-vue-admin/tree/1.0.3)\n\n* **版本管理；**\n* **form input 宽度100%设定；**\n* **引入 moment；**\n* **vue-element-admin Markdown 输出样式排版问题修复方案；**\n* **首页：版本历史记录渲染；**\n* **markdown 渲染样式。**', 1610095906, 1610096043, 0, 4, '2021-01-07 18:50:02');
INSERT INTO `cnpscy_versions` VALUES (5, '1.0.4', '1.0.4', '[1.0.4 分支](https://gitee.com/clown-passerby-community/laravel-vue-admin/tree/1.0.4)\n\n* **同步使用多语言包;**\n* **update readme。**', 1610095993, 1610096019, 0, 5, '2021-01-07 23:00:10');

-- ----------------------------
-- Table structure for cnpscy_web_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs`;
CREATE TABLE `cnpscy_web_logs`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2019_12
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2019_12`;
CREATE TABLE `cnpscy_web_logs_2019_12`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 586 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_01`;
CREATE TABLE `cnpscy_web_logs_2020_01`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1336 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_02`;
CREATE TABLE `cnpscy_web_logs_2020_02`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1023 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_03`;
CREATE TABLE `cnpscy_web_logs_2020_03`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1620 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_04
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_04`;
CREATE TABLE `cnpscy_web_logs_2020_04`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1605 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_05
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_05`;
CREATE TABLE `cnpscy_web_logs_2020_05`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_2019_12_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1477 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_06
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_06`;
CREATE TABLE `cnpscy_web_logs_2020_06`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1577 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_07`;
CREATE TABLE `cnpscy_web_logs_2020_07`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1555 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_08`;
CREATE TABLE `cnpscy_web_logs_2020_08`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1403 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_09`;
CREATE TABLE `cnpscy_web_logs_2020_09`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2145 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_10
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_10`;
CREATE TABLE `cnpscy_web_logs_2020_10`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1877 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_11
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_11`;
CREATE TABLE `cnpscy_web_logs_2020_11`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1987 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2020_12
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2020_12`;
CREATE TABLE `cnpscy_web_logs_2020_12`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2025 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_01`;
CREATE TABLE `cnpscy_web_logs_2021_01`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1724 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_02`;
CREATE TABLE `cnpscy_web_logs_2021_02`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '前台API请求日志记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `log_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `this_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前URL',
  `request_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求URL',
  `log_action` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `request_data` json NULL COMMENT '请求参数',
  `log_duration` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '请求时长（s）',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `blog_api_web_logs_created_time_index`(`created_time`) USING BTREE,
  INDEX `blog_api_web_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `blog_api_web_logs_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 264 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;
