/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : cnpscy-community

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 05/09/2021 00:11:19
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_03`;
CREATE TABLE `cnpscy_admin_login_logs_2021_03`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_07`;
CREATE TABLE `cnpscy_admin_login_logs_2021_07`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_08`;
CREATE TABLE `cnpscy_admin_login_logs_2021_08`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_login_logs_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_login_logs_2021_09`;
CREATE TABLE `cnpscy_admin_login_logs_2021_09`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员登录日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_logs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_03`;
CREATE TABLE `cnpscy_admin_logs_2021_03`  (
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
-- Table structure for cnpscy_admin_logs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_07`;
CREATE TABLE `cnpscy_admin_logs_2021_07`  (
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
-- Table structure for cnpscy_admin_logs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_08`;
CREATE TABLE `cnpscy_admin_logs_2021_08`  (
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
-- Table structure for cnpscy_admin_logs_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_logs_2021_09`;
CREATE TABLE `cnpscy_admin_logs_2021_09`  (
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
-- Table structure for cnpscy_admin_menus_copy1
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_menus_copy1`;
CREATE TABLE `cnpscy_admin_menus_copy1`  (
  `menu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单栏目表',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `menu_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `vue_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vue_path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'VUE路由路径',
  `vue_redirect` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT 'Vue的redirect',
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_menus_old
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_menus_old`;
CREATE TABLE `cnpscy_admin_menus_old`  (
  `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单栏目表',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `menu_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `vue_path` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'javascript:;' COMMENT '控制器/方法',
  `api_url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `menu_icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `menu_level` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT '栏目层级',
  `check_auth` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否检测权限【0.不检测；1.检测】',
  `menu_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否展示左侧：1：展示；0：隐藏',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否可用：1：可用；0：禁用',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：未',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `vue_component` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'javascript:;' COMMENT '控制器/方法',
  `vue_meta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `external_links` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`menu_id`) USING BTREE,
  INDEX `admin_menus_created_time_index`(`created_time`) USING BTREE,
  INDEX `admin_menus_menu_sort_index`(`menu_sort`) USING BTREE,
  INDEX `admin_menus_is_left_index`(`is_hidden`) USING BTREE,
  INDEX `admin_menus_parent_id_index`(`parent_id`) USING BTREE,
  INDEX `admin_menus_check_auth_index`(`check_auth`) USING BTREE,
  INDEX `admin_menus_menu_level_index`(`menu_level`) USING BTREE,
  INDEX `admin_menus_is_check_index`(`is_check`) USING BTREE,
  INDEX `admin_menus_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_operation_logs_2020_04
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_operation_logs_2020_04`;
CREATE TABLE `cnpscy_admin_operation_logs_2020_04`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_action` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '请求参数',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `log_status`(`log_status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_admin_operation_logs_2020_05
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_admin_operation_logs_2020_05`;
CREATE TABLE `cnpscy_admin_operation_logs_2020_05`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1：成功；0：失败',
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员操作日志表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台-角色与管理员关联表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员认证表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章分类表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章标签表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章标签关联表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
-- Table structure for cnpscy_chat_records
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_chat_records`;
CREATE TABLE `cnpscy_chat_records`  (
  `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员的聊天记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `friend_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友Id',
  `friend_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '好友类型：1：好友；2：临时会话',
  `chat_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '内容格式：0：文本；1.图片；2.语音；3.视频',
  `chat_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '聊天内容',
  `is_recall` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  PRIMARY KEY (`record_id`) USING BTREE,
  INDEX `bbs_chat_records_user_id_index`(`user_id`) USING BTREE,
  INDEX `bbs_chat_records_friend_id_index`(`friend_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_chat_records_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_chat_records_2021_03`;
CREATE TABLE `cnpscy_chat_records_2021_03`  (
  `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员的聊天记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `friend_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友Id',
  `friend_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '好友类型：1：好友；2：临时会话',
  `chat_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '内容格式：0：文本；1.图片；2.语音；3.视频',
  `chat_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '聊天内容',
  `is_recall` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  PRIMARY KEY (`record_id`) USING BTREE,
  INDEX `bbs_chat_records_user_id_index`(`user_id`) USING BTREE,
  INDEX `bbs_chat_records_friend_id_index`(`friend_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_chat_records_2021_04
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_chat_records_2021_04`;
CREATE TABLE `cnpscy_chat_records_2021_04`  (
  `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员的聊天记录表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `friend_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友Id',
  `friend_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '好友类型：1：好友；2：临时会话',
  `chat_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '内容格式：0：文本；1.图片；2.语音；3.视频',
  `chat_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '聊天内容',
  `is_recall` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否撤回：1：是；0：否',
  PRIMARY KEY (`record_id`) USING BTREE,
  INDEX `bbs_chat_records_user_id_index`(`user_id`) USING BTREE,
  INDEX `bbs_chat_records_friend_id_index`(`friend_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '网站配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_douyin_authors
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_douyin_authors`;
CREATE TABLE `cnpscy_douyin_authors`  (
  `author_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `sec_uid` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uid` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `unique_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nick_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar_thumb` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `share_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抖音作者分享的URL',
  `total_favorited` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `follower_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关注数量',
  `signature` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '签名',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `last_sync` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上一次同步的时间',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `original_author` json NULL COMMENT '作者原始数据',
  PRIMARY KEY (`author_id`) USING BTREE,
  UNIQUE INDEX `cnpscy_douyin_authors_sec_uid_unique`(`sec_uid`) USING BTREE,
  UNIQUE INDEX `cnpscy_douyin_authors_uid_unique`(`uid`) USING BTREE,
  UNIQUE INDEX `cnpscy_douyin_authors_unique_id_unique`(`unique_id`) USING BTREE,
  INDEX `cnpscy_douyin_authors_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10039 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抖音作者表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_douyin_videos
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_douyin_videos`;
CREATE TABLE `cnpscy_douyin_videos`  (
  `video_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `author_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '作者Id',
  `aweme_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频Id',
  `cover` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图',
  `desc` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `images` json NULL COMMENT '多图',
  `video` json NULL COMMENT '视频信息',
  `statistics` json NULL COMMENT '视频统计信息',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`video_id`) USING BTREE,
  INDEX `cnpscy_douyin_videos_author_id_index`(`author_id`) USING BTREE,
  INDEX `cnpscy_douyin_videos_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13265 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抖音视频表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_dynamic_collections
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamic_collections`;
CREATE TABLE `cnpscy_dynamic_collections`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态收藏表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `dynamic_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态Id-收藏表',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_dynamic_collections_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_dynamic_collections_dynamic_id_index`(`dynamic_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_dynamic_comment_praises
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamic_comment_praises`;
CREATE TABLE `cnpscy_dynamic_comment_praises`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态点赞表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `dynamic_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态Id',
  `comment_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论表',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comment_praises_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comment_praises_dynamic_id_index`(`dynamic_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comment_praises_comment_id_index`(`comment_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '动态的评论点赞记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_dynamic_comments
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamic_comments`;
CREATE TABLE `cnpscy_dynamic_comments`  (
  `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态评论回复表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `reply_user` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `dynamic_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态Id',
  `author_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '作者Id',
  `comment_content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '回复内容',
  `top_level` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顶级的Id（顶级上一级的reply_id = 0）',
  `reply_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复评论的Id',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：0：否；1：是',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `comment_markdown` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '回复内容',
  `content_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容的格式：html；markdown',
  `praise_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞量',
  PRIMARY KEY (`comment_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comments_dynamic_id_index`(`dynamic_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comments_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_dynamic_comments_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_dynamic_praises
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamic_praises`;
CREATE TABLE `cnpscy_dynamic_praises`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态点赞表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `dynamic_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态Id-点赞表',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_dynamic_praises_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_dynamic_praises_dynamic_id_index`(`dynamic_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_dynamics
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_dynamics`;
CREATE TABLE `cnpscy_dynamics`  (
  `dynamic_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '动态表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `topic_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '话题/荟吧 Id',
  `dynamic_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `dynamic_images` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '多图',
  `video_path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频地址',
  `video_info` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频信息（JSON）',
  `dynamic_content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '动态内容',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否审核：0：待审核；1：通过；2.拒绝',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '公开度：0.私密；1.完全公开；2.密码访问',
  `access_password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '对于公开度的“密码访问”设置的密码',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态类型：0.动态；1.图文；2.视频；3.相册',
  `cache_extends` json NULL COMMENT '统计的扩展字段',
  `content_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容的格式：html；markdown',
  `dynamic_markdown` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '动态内容',
  `excellent_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '精选标记时间',
  PRIMARY KEY (`dynamic_id`) USING BTREE,
  INDEX `cnpscy_dynamics_topic_id_index`(`topic_id`) USING BTREE,
  INDEX `cnpscy_dynamics_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_dynamics_is_public_index`(`is_public`) USING BTREE,
  INDEX `cnpscy_dynamics_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `excellent_time`(`excellent_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_failed_jobs`;
CREATE TABLE `cnpscy_failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cnpscy_failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_friend_applies
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_friend_applies`;
CREATE TABLE `cnpscy_friend_applies`  (
  `apply_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '好友申请表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户Id',
  `friend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友Id',
  `message_record` json NULL COMMENT '消息记录 - 申请原因，申请方与接收方可以多次进行回复',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否审核：1：同意；0：待处理；2.拒绝',
  `is_finish` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已结束：0.否；1.是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`apply_id`) USING BTREE,
  INDEX `cnpscy_friend_applies_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_friend_applies_is_check_index`(`is_check`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_friend_groups
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_friend_groups`;
CREATE TABLE `cnpscy_friend_groups`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '好友分组表',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `group_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `friend_nums` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友数量',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `cnpscy_friend_groups_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '友情链接表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_friends
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_friends`;
CREATE TABLE `cnpscy_friends`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '好友表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户Id',
  `friend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '好友Id',
  `friend_remark` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `is_special` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否特别关心：1：是；0：否',
  `is_blacklist` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否拉黑：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_friends_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_friends_is_special_index`(`is_special`) USING BTREE,
  INDEX `cnpscy_friends_is_blacklist_index`(`is_blacklist`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_galleries
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_galleries`;
CREATE TABLE `cnpscy_galleries`  (
  `gallery_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图库表',
  `gallery_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图库名称',
  `gallery_cover` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
  `gallery_origin` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源链接',
  `total_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片总量',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`gallery_id`) USING BTREE,
  INDEX `galleries_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `galleries_created_time_index`(`created_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2742 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_gallery_details
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_gallery_details`;
CREATE TABLE `cnpscy_gallery_details`  (
  `detail_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图库详情表',
  `gallery_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片库Id',
  `gallery_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '文字内容',
  `gallery_pictures` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '图片列表（JSON）',
  `picture_nums` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片数量',
  `origin_link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源链接',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`detail_id`) USING BTREE,
  INDEX `gallery_details_gallery_id_index`(`gallery_id`) USING BTREE,
  INDEX `gallery_details_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2738 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_gallery_tags
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_gallery_tags`;
CREATE TABLE `cnpscy_gallery_tags`  (
  `tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图库标签表',
  `tag_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图库标签名称',
  `origin_link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源链接',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`tag_id`) USING BTREE,
  INDEX `gallery_tags_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `gallery_tags_created_time_index`(`created_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_gallery_with_tags
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_gallery_with_tags`;
CREATE TABLE `cnpscy_gallery_with_tags`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图库与标签关联表',
  `tag_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '标签Id',
  `gallery_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图库Id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gallery_with_tags_tag_id_index`(`tag_id`) USING BTREE,
  INDEX `gallery_with_tags_gallery_id_index`(`gallery_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4517 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_good_categories
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_good_categories`;
CREATE TABLE `cnpscy_good_categories`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级Id',
  `category_cover` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类封面',
  `category_sort` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`category_id`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_help_centers
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_help_centers`;
CREATE TABLE `cnpscy_help_centers`  (
  `help_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `help_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `help_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '内容',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：删除；0：正常',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`help_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '帮助中心表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_job_batches
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_job_batches`;
CREATE TABLE `cnpscy_job_batches`  (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int(11) NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_jobs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_jobs`;
CREATE TABLE `cnpscy_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cnpscy_jobs_queue_index`(`queue`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraw_configs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraw_configs`;
CREATE TABLE `cnpscy_luckydraw_configs`  (
  `detail_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动详情Id',
  `activity_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '活动Id',
  `reward_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '几等奖',
  `reward_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等',
  `reward_quota` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】',
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '产品Id',
  `awards_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '该奖励的发奖次数',
  `probability_of_winning` decimal(20, 10) NOT NULL DEFAULT 0.0000000000 COMMENT '获奖的概率',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  PRIMARY KEY (`detail_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_configs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_luckydraw_configs_activity_id_index`(`activity_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抽奖活动转盘配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraw_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraw_logs`;
CREATE TABLE `cnpscy_luckydraw_logs`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动抽奖记录Id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `activity_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '活动Id',
  `detail_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '活动配置Id',
  `reward_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '奖励类型：0.无奖励；1.虚拟奖；2.实物产品奖等等',
  `reward_quota` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '奖励的额度：虚拟奖，消费积分与抵扣积分；产品的数量【针对于：产品奖励】',
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '产品Id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_receive` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已领取：0.否；1.是',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_logs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抽奖活动的会员抽奖记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraw_products
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraw_products`;
CREATE TABLE `cnpscy_luckydraw_products`  (
  `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '产品Id',
  `product_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '产品名称',
  `product_cover` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '产品封面图',
  `product_stock` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '产品库存',
  `sales_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量',
  `on_sale` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否在售：0.否；1.是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  PRIMARY KEY (`product_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_products_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抽奖活动的产品表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraw_qualification_sources
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraw_qualification_sources`;
CREATE TABLE `cnpscy_luckydraw_qualification_sources`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `source_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '抽奖机会的来源：1.签到；',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `change_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '变更类型：0.减少；1.增加',
  `luckydraw_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '抽奖的次数【获取的次数】',
  `express_info` json NOT NULL COMMENT '扩展信息【JSON】',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraw_rewards
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraw_rewards`;
CREATE TABLE `cnpscy_luckydraw_rewards`  (
  `reward_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '领奖记录Id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `log_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '活动抽奖记录Id',
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '产品Id',
  `receive_info` json NOT NULL COMMENT '收货信息【JSON】',
  `express_info` json NOT NULL COMMENT '快递信息【JSON】',
  `reward_quota` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '获取商品的数量',
  `user_remarks` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '会员备注',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delivery_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发货时间',
  `collect_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收货时间',
  `reward_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '订单状态：0.待确认；1.已确认/待支付；2.已支付/待发货；3.已发货/待收货；4.已完成；5.已取消；6.已关闭',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  PRIMARY KEY (`reward_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_rewards_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_luckydraw_rewards_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_luckydraw_rewards_product_id_index`(`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抽奖活动的领取中奖产品的记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_luckydraws
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_luckydraws`;
CREATE TABLE `cnpscy_luckydraws`  (
  `activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动Id',
  `activity_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动名称',
  `turntable_config` smallint(6) NOT NULL DEFAULT 6 COMMENT '转盘配置对应的几个组【4、6、8、10】',
  `is_open` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否开启该抽奖活动',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`activity_id`) USING BTREE,
  INDEX `cnpscy_luckydraws_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '抽奖活动表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_migrations
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_migrations`;
CREATE TABLE `cnpscy_migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 55 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
-- Table structure for cnpscy_notifications
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifications`;
CREATE TABLE `cnpscy_notifications`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cnpscy_notifications_notifiable_type_notifiable_id_index`(`notifiable_type`, `notifiable_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies`;
CREATE TABLE `cnpscy_notifies`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息通知记录表',
  `notify_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '通知类型：0.系统通知/公告；1.提醒；2.私信',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `target_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标Id(比如动态ID)',
  `target_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标类型：0.动态',
  `sender_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者Id',
  `sender_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者类型：0.系统通知；1.指定会员',
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `notify_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '通知内容',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：1：是；0：否',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `notifies_is_read_index`(`is_read`) USING BTREE,
  INDEX `notifies_notify_type_index`(`notify_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_01`;
CREATE TABLE `cnpscy_notifies_2021_01`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息通知记录表',
  `notify_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '消息类型：0.系统消息；1.互动消息；',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `target_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标Id(比如动态ID)',
  `target_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标类型：0.注册；1.动态；2.关注',
  `sender_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者Id',
  `sender_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者类型：0.系统通知',
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `notify_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：0：否；1：是',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `cnpscy_notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_notifies_is_read_index`(`is_read`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_02`;
CREATE TABLE `cnpscy_notifies_2021_02`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息通知记录表',
  `notify_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '消息类型：0.系统消息；1.互动消息；',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `target_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标Id(比如动态ID)',
  `target_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标类型：0.注册；1.动态；2.关注',
  `sender_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者Id',
  `sender_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者类型：0.系统通知',
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `notify_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：0：否；1：是',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `cnpscy_notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_notifies_is_read_index`(`is_read`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_03`;
CREATE TABLE `cnpscy_notifies_2021_03`  (
  `notify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息通知记录表',
  `notify_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '消息类型：0.系统消息；1.互动消息；',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `target_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标Id(比如动态ID)',
  `target_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标类型：0.注册；1.动态；2.关注',
  `sender_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者Id',
  `sender_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者类型：0.系统通知',
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `notify_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已读：0：否；1：是',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `cnpscy_notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_notifies_is_read_index`(`is_read`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_07`;
CREATE TABLE `cnpscy_notifies_2021_07`  (
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
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `notifies_is_read_index`(`is_read`) USING BTREE,
  INDEX `notifies_notify_type_index`(`notify_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_08`;
CREATE TABLE `cnpscy_notifies_2021_08`  (
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
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
  PRIMARY KEY (`notify_id`) USING BTREE,
  INDEX `notifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `notifies_is_read_index`(`is_read`) USING BTREE,
  INDEX `notifies_notify_type_index`(`notify_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_notifies_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_notifies_2021_09`;
CREATE TABLE `cnpscy_notifies_2021_09`  (
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
  `dynamic_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态的类型：0.点赞；1.收藏；2.评论；3.分享；4.点赞评论；5.删除',
  `admin_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员Id',
  `extend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '扩展Id',
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
-- Table structure for cnpscy_regions
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_regions`;
CREATE TABLE `cnpscy_regions`  (
  `region_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级Id',
  `shortname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '简称',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `merger_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '全称',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '层级：1.省；2.市; 3.区县',
  `pinyin` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拼音',
  `code` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '长途区号',
  `zip_code` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮编',
  `first` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '首字母',
  `lng` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '纬度',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0：否；1：是',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`region_id`) USING BTREE,
  INDEX `name,level`(`name`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3446 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '省市区表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_sessions
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_sessions`;
CREATE TABLE `cnpscy_sessions`  (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cnpscy_sessions_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_sessions_last_activity_index`(`last_activity`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_start_diagrams
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_start_diagrams`;
CREATE TABLE `cnpscy_start_diagrams`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '启动图配置表',
  `diagram_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `diagram_cover` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图',
  `diagram_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cnpscy_start_diagrams_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_system_notify_users
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_system_notify_users`;
CREATE TABLE `cnpscy_system_notify_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统消息与会员的已读记录表',
  `notify_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '通知Id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `system_notify_users_user_id_index`(`user_id`) USING BTREE,
  INDEX `system_notify_users_notify_id_index`(`notify_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_table_backups
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_table_backups`;
CREATE TABLE `cnpscy_table_backups`  (
  `backup_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '数据库备份记录表',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作人',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  `tables_name` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备份的表名',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小：字节',
  `file_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件数量',
  `backup_files` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备份的文件',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：0.否；1.是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`backup_id`) USING BTREE,
  INDEX `cnpscy_table_backups_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_topic_follows
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_topic_follows`;
CREATE TABLE `cnpscy_topic_follows`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '话题关注表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `topic_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '话题Id',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_topic_follows_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_topic_follows_topic_id_index`(`topic_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_topic_statistics
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_topic_statistics`;
CREATE TABLE `cnpscy_topic_statistics`  (
  `with_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '话题 统计表',
  `topic_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '话题Id',
  `follow_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关注数量',
  `dynamic_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态数量【包含文章、视频、动态、提问】',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`with_id`) USING BTREE,
  INDEX `cnpscy_topic_statistics_topic_id_index`(`topic_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_topics
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_topics`;
CREATE TABLE `cnpscy_topics`  (
  `topic_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '话题 表',
  `topic_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `topic_description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `topic_cover` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标/封面',
  `topic_sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `dynamic_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '动态数量',
  `follow_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关注人数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `is_default` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认',
  PRIMARY KEY (`topic_id`) USING BTREE,
  INDEX `cnpscy_topics_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_topics_topic_sort_index`(`topic_sort`) USING BTREE,
  INDEX `is_default`(`is_default`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_upload_files
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_upload_files`;
CREATE TABLE `cnpscy_upload_files`  (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `storage` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `host_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小(字节)',
  `file_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `is_delete` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '软删除',
  `created_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`file_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员的所有图片记录表 == upload_file' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_upload_groups
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_upload_groups`;
CREATE TABLE `cnpscy_upload_groups`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_email_verifies
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_email_verifies`;
CREATE TABLE `cnpscy_user_email_verifies`  (
  `verify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '邮箱验证表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `verify_token` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证TOKEN',
  `auth_email` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '邮箱验证状态：0：否，1：是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`verify_id`) USING BTREE,
  INDEX `cnpscy_user_email_verifies_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_email_verifies_auth_email_index`(`auth_email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_experience_records
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_experience_records`;
CREATE TABLE `cnpscy_user_experience_records`  (
  `experience_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员经验变动记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `experience_num` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '获得多少经验',
  `get_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '获得类型：\n                0：会员登录【一次 + 1】；\n                1.签到【累加】；\n                2.支付【一次 + 5】；\n                3.绑定手机号码【 + 10 】；\n                4.验证邮箱【 + 10 】；\n                5.实名认证【 + 20 】',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`experience_id`) USING BTREE,
  INDEX `cnpscy_user_experience_records_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_experience_records_get_type_index`(`get_type`) USING BTREE,
  INDEX `cnpscy_user_experience_records_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_follow_fans
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_follow_fans`;
CREATE TABLE `cnpscy_user_follow_fans`  (
  `relation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员关注与粉丝记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员主键',
  `friend_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '对应会员Id',
  `cross_correlation` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否双方进行关注：0：否；1：是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `is_special` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否特别关心：1：是；0：否',
  `is_blacklist` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否拉黑：1：是；0：否',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`relation_id`) USING BTREE,
  INDEX `cnpscy_user_follow_fans_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_follow_fans_cross_correlation_index`(`cross_correlation`) USING BTREE,
  INDEX `is_blacklist`(`is_blacklist`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_grades
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_grades`;
CREATE TABLE `cnpscy_user_grades`  (
  `grade_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员等级表【禁止删除，允许修改】',
  `grade_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '等级名称',
  `min_value` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最小经验值',
  `max_value` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最大经验值',
  `grade_sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`grade_id`) USING BTREE,
  INDEX `cnpscy_user_grades_created_time_index`(`created_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_infos
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_infos`;
CREATE TABLE `cnpscy_user_infos`  (
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id-会员基本信息表',
  `user_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'UUID',
  `pay_pass` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付密码',
  `nick_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `user_avatar` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `background_cover` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '背景封面图',
  `user_sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别：0：男；1：女；2.保密',
  `user_birth` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '出生年月日',
  `city_info` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市信息：省份,城市',
  `get_likes` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '获赞数',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `user_grade` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户等级',
  `user_experience` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户经验',
  `auth_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实名认证状态：0：否，1：是',
  `auth_mobile` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '手机号验证状态：0：否，1：是',
  `auth_email` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '邮箱验证状态：0：否，1：是',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `last_actived_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上次活跃时间',
  `notification_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '未读消息',
  `sign_days` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '连续签到天数',
  `last_sign_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上次签到时间',
  `total_sign_days` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总共签到天数',
  `year_sign_days` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '今年总共签到天数',
  `register_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册方式：0：账户；1.邮箱；2.手机号；3.第三方登录',
  `other_extends` json NULL COMMENT '会员的其它扩展信息',
  `basic_extends` json NULL COMMENT '会员的基础扩展信息',
  `auth_extends` json NULL COMMENT '会员的认证扩展信息',
  `sign_extends` json NULL COMMENT '会员的签到扩展信息',
  `luckydraw_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '抽奖的次数',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `cnpscy_user_infos_user_grade_index`(`user_grade`) USING BTREE,
  INDEX `cnpscy_user_infos_auth_status_index`(`auth_status`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs`;
CREATE TABLE `cnpscy_user_login_logs`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs_2021_02`;
CREATE TABLE `cnpscy_user_login_logs_2021_02`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs_2021_03`;
CREATE TABLE `cnpscy_user_login_logs_2021_03`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs_2021_07`;
CREATE TABLE `cnpscy_user_login_logs_2021_07`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs_2021_08`;
CREATE TABLE `cnpscy_user_login_logs_2021_08`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_login_logs_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_login_logs_2021_09`;
CREATE TABLE `cnpscy_user_login_logs_2021_09`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `request_data` json NULL COMMENT '请求参数',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_login_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_login_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_logs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_logs`;
CREATE TABLE `cnpscy_user_logs`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `log_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` json NULL COMMENT '请求参数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '日志类型【0.登陆；1.退出；2.签到；……】',
  `login_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '登录类型【0.普通登录】',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_logs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_logs_2021_02`;
CREATE TABLE `cnpscy_user_logs_2021_02`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `log_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` json NULL COMMENT '请求参数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '日志类型【0.登陆；1.退出；2.签到；……】',
  `login_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '登录类型【0.普通登录】',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_logs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_logs_2021_03`;
CREATE TABLE `cnpscy_user_logs_2021_03`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `log_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` json NULL COMMENT '请求参数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '日志类型【0.登陆；1.退出；2.签到；……】',
  `login_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '登录类型【0.普通登录】',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_logs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_logs_2021_07`;
CREATE TABLE `cnpscy_user_logs_2021_07`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `log_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` json NULL COMMENT '请求参数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '日志类型【0.登陆；1.退出；2.签到；……】',
  `login_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '登录类型【0.普通登录】',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_logs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_logs_2021_08`;
CREATE TABLE `cnpscy_user_logs_2021_08`  (
  `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录日志记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时的IP',
  `browser_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时浏览器类型',
  `log_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1.成功；0.失败',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `log_duration` decimal(20, 12) NOT NULL DEFAULT 0.000000000000 COMMENT '请求时长',
  `log_action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `log_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求类型/请求方式',
  `request_data` json NULL COMMENT '请求参数',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除：1：是；0：否',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否展示：1.展示；0.会员删除；2.管理员删除',
  `log_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '日志类型【0.登陆；1.退出；2.签到；……】',
  `login_type` smallint(6) NOT NULL DEFAULT 0 COMMENT '登录类型【0.普通登录】',
  PRIMARY KEY (`log_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_delete_index`(`is_delete`) USING BTREE,
  INDEX `cnpscy_user_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_logs_is_public_index`(`is_public`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_otherlogins
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_otherlogins`;
CREATE TABLE `cnpscy_user_otherlogins`  (
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id-会员基本信息表',
  `qq_info` json NULL COMMENT 'QQ登录的标识',
  `weibo_info` json NULL COMMENT '微博登录的标识',
  `github_info` json NULL COMMENT 'github的标识',
  `user_origin` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '来源：\n                0：普通注册；\n                1：QQ快捷登录；\n                2：百度登录；\n                3：微博登录；\n                4：Github登录；\n                5：小丑疯狂吧账户同步登录【已下线，暂不考虑】\n                6：微信登录【不给个人，暂不考虑】\n                ',
  `change_account` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许更改账户：0.否；1.是【仅针对于第三方快捷登录的账户，仅可更改一次，值变动】',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs`;
CREATE TABLE `cnpscy_user_signs`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_01
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_01`;
CREATE TABLE `cnpscy_user_signs_2021_01`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_02
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_02`;
CREATE TABLE `cnpscy_user_signs_2021_02`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_03`;
CREATE TABLE `cnpscy_user_signs_2021_03`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_07`;
CREATE TABLE `cnpscy_user_signs_2021_07`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_08`;
CREATE TABLE `cnpscy_user_signs_2021_08`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_signs_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_signs_2021_09`;
CREATE TABLE `cnpscy_user_signs_2021_09`  (
  `sign_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员签到记录表',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的id',
  `sign_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到类型：0：会员签到；1：后台手动添加',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  PRIMARY KEY (`sign_id`) USING BTREE,
  INDEX `cnpscy_user_signs_created_time_index`(`created_time`) USING BTREE,
  INDEX `cnpscy_user_signs_sign_type_index`(`sign_type`) USING BTREE,
  INDEX `cnpscy_user_signs_user_id_index`(`user_id`) USING BTREE,
  INDEX `cnpscy_user_signs_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_user_today_online_records
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_user_today_online_records`;
CREATE TABLE `cnpscy_user_today_online_records`  (
  `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '每天在线会员的记录表',
  `day_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '当天时间戳 - 年月日即可',
  `user_json` json NULL COMMENT '会员Id记录JSON格式',
  PRIMARY KEY (`record_id`) USING BTREE,
  INDEX `cnpscy_user_today_online_records_day_time_index`(`day_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_users
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_users`;
CREATE TABLE `cnpscy_users`  (
  `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员登录表',
  `user_mobile` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `user_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `login_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'login_token【用于提示是否异地登录，jwt-token只能检测是否登录---这个效果其实用不用都可以的，需要看心情。】',
  `is_check` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否审核：1：正常；0：禁用；2.踢出登录，重新登录',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `cnpscy_users_is_check_index`(`is_check`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_versions
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_versions`;
CREATE TABLE `cnpscy_versions`  (
  `version_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '版本表',
  `version_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `version_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '版本号',
  `version_code` int(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 100 COMMENT '纯数字版本号',
  `version_desc` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `version_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `version_sort` smallint(3) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `publish_date` datetime(0) NULL DEFAULT NULL COMMENT '版本的发布时间',
  PRIMARY KEY (`version_id`) USING BTREE,
  INDEX `cnpscy_versions_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'web访问日志' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_03
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_03`;
CREATE TABLE `cnpscy_web_logs_2021_03`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'web访问日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_07
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_07`;
CREATE TABLE `cnpscy_web_logs_2021_07`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'web访问日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_08
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_08`;
CREATE TABLE `cnpscy_web_logs_2021_08`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 3049 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'web访问日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cnpscy_web_logs_2021_09
-- ----------------------------
DROP TABLE IF EXISTS `cnpscy_web_logs_2021_09`;
CREATE TABLE `cnpscy_web_logs_2021_09`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'web访问日志' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
