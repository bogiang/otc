DROP TABLE IF EXISTS `tw_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tw_chat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fromuid` int(11) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `addtime` int(10) NOT NULL COMMENT '时间',
  `orderid` bigint(20) NOT NULL COMMENT '所属订单ID',
  `content` varchar(255) NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `fromuid` (`fromuid`) USING BTREE,
  KEY `touid` (`touid`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `type` (`ordertype`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;




SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tw_ad_buy
-- ----------------------------
DROP TABLE IF EXISTS `tw_ad_buy`;
CREATE TABLE `tw_ad_buy` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ad_no` varchar(15) NOT NULL COMMENT '广告唯一编号',
  `userid` int(11) NOT NULL COMMENT '发布用户id',
  `location` int(10) NOT NULL COMMENT '地区',
  `currency` int(10) NOT NULL COMMENT '货币',
  `due_time` int(10) NOT NULL COMMENT '购买付款期限(分钟)',
  `safe_option` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全选项,0不开启,1开启',
  `trust_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仅限受信任的交易者(0关闭,1开启)',
  `open_time` varchar(100) NOT NULL DEFAULT '1,1,1,1,1,1,1' COMMENT '开启时间(单个1开启,单个0关闭,0-1表示0点到1点开启)',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(1进行中,2下架,3已完成)',
  `finished_time` int(10) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认值，不用赋值',
  `coin` int(10) NOT NULL DEFAULT '0',
  `fee` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_no` (`ad_no`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `currency` (`currency`) USING BTREE,
  KEY `trust_only` (`trust_only`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE,
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tw_ad_sell`;
CREATE TABLE `tw_ad_sell` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ad_no` varchar(15) NOT NULL COMMENT '广告唯一编号',
  `userid` int(11) NOT NULL COMMENT '发布用户id',
  `message` varchar(500) NOT NULL COMMENT '留言',
  `safe_option` tinyint(1) NOT NULL DEFAULT '0' COMMENT '安全选项,0不开启,1开启',
  `trust_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT '仅限受信任的交易者(0关闭,1开启)',
  `open_time` varchar(100) NOT NULL DEFAULT '1,1,1,1,1,1,1' COMMENT '开启时间(单个1开启,单个0关闭,0-1表示0点到1点开启)',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(1进行中,2下架,3完成)',
  `finished_time` int(10) DEFAULT NULL COMMENT '完成时间',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `coin` int(10) NOT NULL DEFAULT '0',
  `fee` tinyint(4) NOT NULL DEFAULT '0',
  `skaccount` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_no` (`ad_no`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `currency` (`currency`) USING BTREE,
  KEY `trust_only` (`trust_only`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE,
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for tw_admin
-- ----------------------------
DROP TABLE IF EXISTS `tw_admin`;
CREATE TABLE `tw_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `moble` varchar(50) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员表';

-- ----------------------------
-- Records of tw_admin
-- ----------------------------
INSERT INTO `tw_admin` VALUES ('1', '', 'admin', '', '', '821f3157e1a3456bfe1a000a1adf0862', '0', '0', '0', '0', '0', '1');

-- ----------------------------
-- Table structure for tw_adver
-- ----------------------------
DROP TABLE IF EXISTS `tw_adver`;
CREATE TABLE `tw_adver` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `look` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 电脑端 1手机端',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告图片表';

-- ----------------------------
-- Table structure for tw_area_code
-- ----------------------------
DROP TABLE IF EXISTS `tw_area_code`;
CREATE TABLE `tw_area_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_zh` varchar(255) NOT NULL COMMENT '中文',
  `name_en` varchar(255) NOT NULL COMMENT '英文名',
  `shortname` varchar(255) NOT NULL COMMENT '简写',
  `areacode` int(20) NOT NULL COMMENT '区号',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tw_article
-- ----------------------------
DROP TABLE IF EXISTS `tw_article`;
CREATE TABLE `tw_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8,
  `adminid` int(10) unsigned NOT NULL DEFAULT '1',
  `type` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `footer` int(11) unsigned NOT NULL DEFAULT '0',
  `index` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `img` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `adminid` (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tw_article_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_article_type`;
CREATE TABLE `tw_article_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `remark` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `index` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `footer` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shang` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` text COLLATE utf8_unicode_ci,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tw_auth_extend
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_extend`;
CREATE TABLE `tw_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_auth_extend
-- ----------------------------
INSERT INTO `tw_auth_extend` VALUES ('1', '1', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '1', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '2', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '2', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '3', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '3', '2');
INSERT INTO `tw_auth_extend` VALUES ('1', '4', '1');
INSERT INTO `tw_auth_extend` VALUES ('1', '37', '1');

-- ----------------------------
-- Table structure for tw_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_group`;
CREATE TABLE `tw_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` text NOT NULL COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for tw_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_group_access`;
CREATE TABLE `tw_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- ----------------------------
-- Table structure for tw_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tw_auth_rule`;
CREATE TABLE `tw_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1923 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


-- ----------------------------
-- Table structure for tw_btc
-- ----------------------------
DROP TABLE IF EXISTS `tw_btc`;
CREATE TABLE `tw_btc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for tw_btc_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_btc_log`;
CREATE TABLE `tw_btc_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL COMMENT '账号',
  `userid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1order_buy2order_sell',
  `plusminus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0减1加',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '数量',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的用户id',
  `ctype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1可用2冻结',
  `action` smallint(4) NOT NULL DEFAULT '0',
  `addip` varchar(50) DEFAULT NULL,
  `showname` varchar(20) NOT NULL DEFAULT '比特币',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `plusminus` (`plusminus`) USING BTREE,
  KEY `operator` (`operator`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for tw_coin
-- ----------------------------
DROP TABLE IF EXISTS `tw_coin`;
CREATE TABLE `tw_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `fee_bili` varchar(50) NOT NULL DEFAULT '',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) unsigned NOT NULL DEFAULT '0',
  `fee_meitian` varchar(200) NOT NULL DEFAULT '' COMMENT '每天限制',
  `dj_zj` varchar(200) NOT NULL DEFAULT '',
  `dj_dk` varchar(200) NOT NULL DEFAULT '',
  `dj_yh` varchar(200) NOT NULL DEFAULT '',
  `dj_mm` varchar(200) NOT NULL DEFAULT '',
  `dj_zj_mytx` varchar(200) DEFAULT NULL COMMENT '提币钱包服务器ip',
  `dj_dk_mytx` varchar(200) DEFAULT NULL COMMENT '提币钱包服务器端口',
  `dj_yh_mytx` varchar(200) DEFAULT NULL COMMENT '提币钱包服务器用户名',
  `dj_mm_mytx` varchar(200) DEFAULT NULL COMMENT '提币钱包服务器密码',
  `zr_zs` varchar(50) NOT NULL DEFAULT '',
  `zr_jz` varchar(50) NOT NULL DEFAULT '',
  `zr_dz` varchar(50) NOT NULL DEFAULT '',
  `zr_sm` varchar(50) NOT NULL DEFAULT '',
  `zc_sm` varchar(50) NOT NULL DEFAULT '',
  `zc_fee` decimal(12,5) NOT NULL DEFAULT '0.00000',
  `zc_user` varchar(50) NOT NULL DEFAULT '',
  `zc_min` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `zc_max` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `zc_jz` varchar(50) NOT NULL DEFAULT '',
  `zc_zd` varchar(50) NOT NULL DEFAULT '',
  `js_yw` varchar(50) NOT NULL DEFAULT '',
  `js_sm` text,
  `js_qb` varchar(50) NOT NULL DEFAULT '',
  `js_ym` varchar(50) NOT NULL DEFAULT '',
  `js_gw` varchar(50) NOT NULL DEFAULT '',
  `js_lt` varchar(50) NOT NULL DEFAULT '',
  `js_wk` varchar(50) NOT NULL DEFAULT '',
  `cs_yf` varchar(50) NOT NULL DEFAULT '',
  `cs_sf` varchar(50) NOT NULL DEFAULT '',
  `cs_fb` varchar(50) NOT NULL DEFAULT '',
  `cs_qk` varchar(50) NOT NULL DEFAULT '',
  `cs_zl` varchar(50) NOT NULL DEFAULT '',
  `cs_cl` varchar(50) NOT NULL DEFAULT '',
  `cs_zm` varchar(50) NOT NULL DEFAULT '',
  `cs_nd` varchar(50) NOT NULL DEFAULT '',
  `cs_jl` varchar(50) NOT NULL DEFAULT '',
  `cs_ts` varchar(50) NOT NULL DEFAULT '',
  `cs_bz` varchar(50) NOT NULL DEFAULT '',
  `tp_zs` varchar(50) NOT NULL DEFAULT '',
  `tp_js` varchar(50) NOT NULL DEFAULT '',
  `tp_yy` varchar(50) NOT NULL DEFAULT '',
  `tp_qj` varchar(50) NOT NULL DEFAULT '',
  `sh_zd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `zc_fee_bili` decimal(10,2) DEFAULT '0.00',
  `price` decimal(13,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='币种配置表';

-- ----------------------------
-- Table structure for tw_coin_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_coin_json`;
CREATE TABLE `tw_coin_json` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ----------------------------
-- Table structure for tw_config
-- ----------------------------
DROP TABLE IF EXISTS `tw_config`;
CREATE TABLE `tw_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `footer_logo` varchar(200) NOT NULL DEFAULT '' COMMENT ' ',
  `huafei_zidong` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `kefu` varchar(200) NOT NULL DEFAULT '',
  `huafei_openid` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `huafei_appkey` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `index_lejimum` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `login_verify` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `fee_meitian` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `top_name` varchar(200) NOT NULL DEFAULT '' COMMENT '设置',
  `web_name` varchar(200) NOT NULL DEFAULT '',
  `web_title` varchar(200) NOT NULL DEFAULT '',
  `web_logo` varchar(200) NOT NULL DEFAULT '',
  `web_llogo_small` varchar(200) NOT NULL DEFAULT '',
  `web_keywords` varchar(200) DEFAULT NULL,
  `web_description` varchar(200) DEFAULT NULL,
  `web_close` varchar(255) DEFAULT NULL,
  `web_close_cause` varchar(255) DEFAULT NULL,
  `web_icp` varchar(255) DEFAULT NULL,
  `web_cnzz` varchar(255) DEFAULT NULL,
  `web_ren` varchar(255) DEFAULT NULL,
  `web_reg` text,
  `market_mr` varchar(255) DEFAULT NULL,
  `xnb_mr` varchar(255) DEFAULT NULL,
  `rmb_mr` varchar(255) DEFAULT NULL,
  `web_waring` varchar(255) DEFAULT NULL,
  `moble_type` varchar(255) DEFAULT NULL,
  `moble_url` varchar(255) DEFAULT NULL,
  `moble_user` varchar(255) DEFAULT NULL,
  `moble_pwd` varchar(255) DEFAULT NULL,
  `contact_moble` varchar(255) DEFAULT NULL,
  `user_bank` text,
  `user_text_truename` text,
  `user_text_moble` text,
  `user_text_alipay` text,
  `user_text_bank` text,
  `user_text_log` text,
  `user_text_password` text,
  `user_text_paypassword` text,
  `mytx_min` text,
  `mytx_max` text,
  `mytx_bei` text,
  `mytx_coin` text,
  `mytx_fee_min` float(12,2) DEFAULT '0.00' COMMENT '提现手续费最低',
  `mytx_fee` text,
  `trade_min` text,
  `trade_max` text,
  `trade_limit` text,
  `trade_text_log` text,
  `issue_ci` text,
  `issue_jian` text,
  `issue_min` text,
  `issue_max` text,
  `money_min` text,
  `money_max` text,
  `money_bei` text,
  `money_text_index` text,
  `money_text_log` text,
  `money_text_type` text,
  `invit_type` text,
  `invit_fee1` text,
  `invit_fee2` text,
  `invit_fee3` text,
  `invit_text_txt` text,
  `invit_text_log` text,
  `index_notice_1` text,
  `index_notice_11` text,
  `index_notice_2` text,
  `index_notice_22` text,
  `index_notice_3` text,
  `index_notice_33` text,
  `index_notice_4` text,
  `index_notice_44` text,
  `text_footer` text,
  `shop_text_index` text,
  `shop_text_log` text,
  `shop_text_addr` text,
  `shop_text_view` text,
  `huafei_text_index` int(11) DEFAULT '0',
  `huafei_text_log` text,
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `shop_coin` varchar(200) NOT NULL DEFAULT '' COMMENT '计算方式',
  `shop_logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商城LOGO',
  `shop_login` varchar(200) NOT NULL DEFAULT '' COMMENT '是否要登陆',
  `index_html` varchar(50) NOT NULL DEFAULT '',
  `trade_hangqing` varchar(50) NOT NULL DEFAULT '',
  `trade_moshi` varchar(50) NOT NULL DEFAULT '',
  `mytx_day_max` decimal(13,0) NOT NULL DEFAULT '0',
  `en_web_reg` text,
  `tui_jy_jl` decimal(13,4) DEFAULT NULL,
  `before_cpc` bigint(20) NOT NULL DEFAULT '0',
  `fee_bili` decimal(4,2) NOT NULL COMMENT '手续费(%)',
  `least_btc` decimal(4,2) NOT NULL COMMENT '至少多少比特币才能显示',
  `sfk_time` int(11) NOT NULL DEFAULT '0' COMMENT '在线出售比特币的付款时间',
  `ethaddress` varchar(100) DEFAULT NULL,
  `ethpassword` varchar(50) DEFAULT NULL,
  `usdtzcaddr` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置表';

-- ----------------------------
-- Table structure for tw_daohang
-- ----------------------------
DROP TABLE IF EXISTS `tw_daohang`;
CREATE TABLE `tw_daohang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT 'url',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=gbk ROW_FORMAT=DYNAMIC;


-- ----------------------------
-- Table structure for tw_eth
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth`;
CREATE TABLE `tw_eth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(30,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth
-- ----------------------------
INSERT INTO `tw_eth` VALUES ('1', '人民币', 'CNY', '2936.13046026', '1529907601');
INSERT INTO `tw_eth` VALUES ('2', '美元', 'USD', '451.01900000', '1529907601');
INSERT INTO `tw_eth` VALUES ('3', '澳元', 'AUD', '607.48863196', '1529907601');
INSERT INTO `tw_eth` VALUES ('4', '日元', 'JPY', '49382.09290231', '1529907601');
INSERT INTO `tw_eth` VALUES ('5', '韩币', 'KRW', '503502.03848335', '1529907601');
INSERT INTO `tw_eth` VALUES ('6', '加元', 'CAD', '599.46276576', '1529907601');
INSERT INTO `tw_eth` VALUES ('7', '法郎', 'CHF', '445.35409590', '1529907601');
INSERT INTO `tw_eth` VALUES ('8', '卢比', 'INR', '30707.67659574', '1529907601');
INSERT INTO `tw_eth` VALUES ('9', '卢布', 'RUB', '28472.52296329', '1529907601');
INSERT INTO `tw_eth` VALUES ('10', '欧元', 'EUR', '387.05439129', '1529907601');
INSERT INTO `tw_eth` VALUES ('11', '英镑', 'GBP', '340.09395548', '1529907601');
INSERT INTO `tw_eth` VALUES ('12', '港币', 'HKD', '3539.18045136', '1529907601');
INSERT INTO `tw_eth` VALUES ('13', '巴西雷亚尔', 'BRL', '1707.37699643', '1529907601');
INSERT INTO `tw_eth` VALUES ('14', '印尼盾', 'IDR', '6347874.18508550', '1529907601');
INSERT INTO `tw_eth` VALUES ('15', '比索', 'MXN', '9064.42872952', '1529907601');
INSERT INTO `tw_eth` VALUES ('16', '台币', 'TWD', '13723.43142800', '1529907601');
INSERT INTO `tw_eth` VALUES ('17', '令吉', 'MYR', '1811.39049989', '1529907601');
INSERT INTO `tw_eth` VALUES ('18', '新币 ', 'SGD', '615.14315544', '1529907601');

-- ----------------------------
-- Table structure for tw_eth_hash
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth_hash`;
CREATE TABLE `tw_eth_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ethhash` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `addtime` int(10) NOT NULL,
  `isdeal` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ethhash` (`ethhash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth_hash
-- ----------------------------

-- ----------------------------
-- Table structure for tw_eth_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth_log`;
CREATE TABLE `tw_eth_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL COMMENT '账号',
  `userid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1order_buy2order_sell',
  `plusminus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0减1加',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '数量',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的用户id',
  `ctype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1可用2冻结',
  `action` smallint(4) NOT NULL DEFAULT '0',
  `addip` varchar(50) DEFAULT NULL,
  `showname` varchar(20) NOT NULL DEFAULT '比特币',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `plusminus` (`plusminus`) USING BTREE,
  KEY `operator` (`operator`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth_log
-- ----------------------------

-- ----------------------------
-- Table structure for tw_eth_transfer
-- ----------------------------
DROP TABLE IF EXISTS `tw_eth_transfer`;
CREATE TABLE `tw_eth_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zc_addr` varchar(100) NOT NULL,
  `zr_addr` varchar(100) NOT NULL,
  `zc_amount` decimal(20,8) NOT NULL,
  `addtime` int(10) NOT NULL,
  `zchash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_eth_transfer
-- ----------------------------
INSERT INTO `tw_eth_transfer` VALUES ('1', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '0x76d71eb19d44102088e8642a6c56493f6e29266d', '0.00100000', '1529715619', '0x1daee8f090a8311d19954d5aab556bfdcf38ce25c7ae8fc9076a1cb8f118bd4b');

-- ----------------------------
-- Table structure for tw_ethapi
-- ----------------------------
DROP TABLE IF EXISTS `tw_ethapi`;
CREATE TABLE `tw_ethapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `ischecked` tinyint(1) NOT NULL DEFAULT '0',
  `coinname` varchar(30) NOT NULL DEFAULT '',
  `blockNumber` int(11) NOT NULL DEFAULT '0',
  `timeStamp` int(10) NOT NULL DEFAULT '0',
  `hash` varchar(200) NOT NULL DEFAULT '',
  `nonce` int(11) NOT NULL DEFAULT '0',
  `blockHash` varchar(200) NOT NULL DEFAULT '',
  `transactionIndex` int(11) NOT NULL DEFAULT '0',
  `from` varchar(200) NOT NULL DEFAULT '',
  `to` varchar(200) NOT NULL DEFAULT '',
  `value` varchar(50) NOT NULL DEFAULT '0',
  `isError` tinyint(1) NOT NULL DEFAULT '0',
  `txreceipt_status` tinyint(1) NOT NULL DEFAULT '0',
  `contractAddress` varchar(200) DEFAULT NULL,
  `confirmations` int(11) NOT NULL DEFAULT '0',
  `tokenSymbol` varchar(20) DEFAULT NULL,
  `tokenDecimal` int(11) DEFAULT NULL,
  `islost` tinyint(1) NOT NULL DEFAULT '0',
  `isdone` tinyint(1) NOT NULL DEFAULT '0',
  `endtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE,
  KEY `ischecked` (`ischecked`) USING BTREE,
  KEY `coinname` (`coinname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
- ----------------------------
-- Table structure for tw_ethzr
-- ----------------------------
DROP TABLE IF EXISTS `tw_ethzr`;
CREATE TABLE `tw_ethzr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fromaddress` varchar(255) NOT NULL DEFAULT '',
  `toaddress` varchar(255) NOT NULL DEFAULT '',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `addtime` int(10) NOT NULL,
  `orderno` bigint(20) NOT NULL DEFAULT '0',
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  `otherid` bigint(20) NOT NULL DEFAULT '0',
  `transaction_hash` varchar(255) NOT NULL,
  `block_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `thash` (`toaddress`,`transaction_hash`,`fromaddress`) USING BTREE,
  KEY `finished` (`finished`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=712354 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for tw_feedback
-- ----------------------------
DROP TABLE IF EXISTS `tw_feedback`;
CREATE TABLE `tw_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` int(10) NOT NULL,
  `endtime` int(10) DEFAULT NULL,
  `subject` varchar(50) NOT NULL,
  `attachone` varchar(200) DEFAULT NULL,
  `attachtwo` varchar(200) DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `txid` varchar(100) DEFAULT '',
  `freshtime` int(10) DEFAULT '0',
  `userstatus` tinyint(1) DEFAULT '0',
  `adminstatus` tinyint(1) DEFAULT '0',
  `recordno` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for tw_feedback_reply
-- ----------------------------
DROP TABLE IF EXISTS `tw_feedback_reply`;
CREATE TABLE `tw_feedback_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `addtime` int(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_feedback_reply
-- ----------------------------

-- ----------------------------
-- Table structure for tw_finance
-- ----------------------------
DROP TABLE IF EXISTS `tw_finance`;
CREATE TABLE `tw_finance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `coinname` varchar(50) NOT NULL COMMENT '币种',
  `num_a` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前正常',
  `num_b` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前冻结',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '之前总计',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '操作数量',
  `type` varchar(50) NOT NULL COMMENT '操作类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `nameid` int(11) NOT NULL DEFAULT '0' COMMENT '操作详细',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '操作备注',
  `mum_a` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余正常',
  `mum_b` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余冻结',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '剩余总计',
  `move` varchar(50) NOT NULL DEFAULT '' COMMENT '附加',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='财务记录表'


-- ----------------------------
-- Records of tw_finance
-- ----------------------------

-- ----------------------------
-- Table structure for tw_finance_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_finance_log`;
CREATE TABLE `tw_finance_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `adminname` varchar(50) DEFAULT '' COMMENT '管理员用户名',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `plusminus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0减少1增加',
  `amount` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '金额，8位小数',
  `description` varchar(100) DEFAULT '' COMMENT '备注',
  `optype` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '动作类型',
  `cointype` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '资金类型',
  `old_amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '原始数据',
  `new_amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '修改后的数据',
  `userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作者id',
  `addip` varchar(100) NOT NULL DEFAULT '0.0.0.0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0后台1前台pc端2前台手机端',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (62000000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (63000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (64000000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (65000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (66000000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (67000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (68000000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (69000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (70000000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (71000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (72000000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (73000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (74000000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (75000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (76000000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (77000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (78000000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (79000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (80000000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (81000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (82000000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (83000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (84000000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (85000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (86000000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (87000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (88000000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (89000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (90000000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (91000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (92000000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (93000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (94000000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (95000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (96000000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (97000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (98000000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (99000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (100000000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (101000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (102000000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (103000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (104000000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (105000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (106000000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (107000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (108000000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (109000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (110000000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (111000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (112000000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (113000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (114000000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (115000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (116000000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (117000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (118000000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (119000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (120000000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (121000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;


-- ----------------------------
-- Table structure for tw_footer
-- ----------------------------
DROP TABLE IF EXISTS `tw_footer`;
CREATE TABLE `tw_footer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_footer
-- ----------------------------
INSERT INTO `tw_footer` VALUES ('1', '1', '关于我们', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('2', '1', '联系我们', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('3', '1', '资质证明', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('4', '1', '用户协议', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('5', '1', '法律声明', '/Article/index/type/aboutus.html', '', '1', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('6', '1', '1', '/', 'footer_1.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('7', '1', '1', 'http://www.szfw.org/', 'footer_2.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('8', '1', '1', 'http://www.miibeian.gov.cn/', 'footer_3.png', '2', '', '1', '111', '0', '1');
INSERT INTO `tw_footer` VALUES ('9', '1', '1', 'http://www.cyberpolice.cn/', 'footer_4.png', '2', '', '1', '111', '0', '1');

-- ----------------------------
-- Table structure for tw_gic
-- ----------------------------
DROP TABLE IF EXISTS `tw_gic`;
CREATE TABLE `tw_gic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_gic
-- ----------------------------
INSERT INTO `tw_gic` VALUES ('1', '人民币', 'CNY', '40080.00781199', '1529907601');
INSERT INTO `tw_gic` VALUES ('2', '美元', 'USD', '6156.69000000', '1529907601');
INSERT INTO `tw_gic` VALUES ('3', '澳元', 'AUD', '8292.59784061', '1529907601');
INSERT INTO `tw_gic` VALUES ('4', '日元', 'JPY', '674096.29649906', '1529907601');
INSERT INTO `tw_gic` VALUES ('5', '韩币', 'KRW', '6873116.13326720', '1529907601');
INSERT INTO `tw_gic` VALUES ('6', '加元', 'CAD', '8183.03977288', '1529907601');
INSERT INTO `tw_gic` VALUES ('7', '法郎', 'CHF', '6079.36053401', '1529907601');
INSERT INTO `tw_gic` VALUES ('8', '卢比', 'INR', '419178.89361702', '1529907601');
INSERT INTO `tw_gic` VALUES ('9', '卢布', 'RUB', '388667.65569269', '1529907601');
INSERT INTO `tw_gic` VALUES ('10', '欧元', 'EUR', '5283.53328871', '1529907601');
INSERT INTO `tw_gic` VALUES ('11', '英镑', 'GBP', '4642.49411836', '1529907601');
INSERT INTO `tw_gic` VALUES ('12', '港币', 'HKD', '48312.01544305', '1529907601');
INSERT INTO `tw_gic` VALUES ('13', '巴西雷亚尔', 'BRL', '23306.75842958', '1529907601');
INSERT INTO `tw_gic` VALUES ('14', '印尼盾', 'IDR', '86652432.63936600', '1529907601');
INSERT INTO `tw_gic` VALUES ('15', '比索', 'MXN', '123669.05737911', '1529907601');
INSERT INTO `tw_gic` VALUES ('16', '台币', 'TWD', '187233.40205433', '1529907601');
INSERT INTO `tw_gic` VALUES ('17', '令吉', 'MYR', '24713.41133030', '1529907601');
INSERT INTO `tw_gic` VALUES ('18', '新币 ', 'SGD', '8392.60547543', '1529907601');

-- ----------------------------
-- Table structure for tw_gic_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_gic_log`;
CREATE TABLE `tw_gic_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL COMMENT '账号',
  `userid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1order_buy2order_sell',
  `plusminus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0减1加',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '数量',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的用户id',
  `ctype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1可用2冻结',
  `action` smallint(4) NOT NULL DEFAULT '0',
  `addip` varchar(50) DEFAULT NULL,
  `showname` varchar(20) NOT NULL DEFAULT '比特币',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `plusminus` (`plusminus`) USING BTREE,
  KEY `operator` (`operator`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_gic_log
-- ----------------------------
INSERT INTO `tw_gic_log` VALUES ('1', '18954215320', '77', '1530760115', '1', '0', '0.02446087', '买家下单减可用BTC，手续费0', '76', '1', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_gic_log` VALUES ('2', '18954215320', '77', '1530760115', '1', '1', '0.02446087', '买家下单加冻结BTC，手续费0', '76', '2', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_gic_log` VALUES ('3', '18954215320', '77', '1530760130', '1', '0', '0.02446087', '买家下单减可用BTC，手续费0', '76', '1', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_gic_log` VALUES ('4', '18954215320', '77', '1530760130', '1', '1', '0.02446087', '买家下单加冻结BTC，手续费0', '76', '2', '1', '192.168.1.136', '比特币');

-- ----------------------------
-- Table structure for tw_good_order
-- ----------------------------
DROP TABLE IF EXISTS `tw_good_order`;
CREATE TABLE `tw_good_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单',
  `goodid` int(11) NOT NULL COMMENT '商品的id',
  `uid` int(11) NOT NULL COMMENT '购买者的id',
  `uname` varchar(255) NOT NULL COMMENT '用户名',
  `nums` int(11) NOT NULL COMMENT '购买数量',
  `prices` decimal(13,2) NOT NULL COMMENT '购买价格',
  `tel` int(11) NOT NULL COMMENT '电话',
  `weixin` varchar(255) NOT NULL COMMENT '微信',
  `qq` varchar(55) NOT NULL COMMENT 'qq',
  `ctime` int(11) NOT NULL COMMENT '购买时间',
  `status` int(6) NOT NULL DEFAULT '0' COMMENT '状态',
  `address` text NOT NULL COMMENT '收货地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_good_order
-- ----------------------------

-- ----------------------------
-- Table structure for tw_goods
-- ----------------------------
DROP TABLE IF EXISTS `tw_goods`;
CREATE TABLE `tw_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品表',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `goods_img` varchar(255) NOT NULL COMMENT '商品图片',
  `goods_kc` int(11) NOT NULL COMMENT '商品库存',
  `goods_price` decimal(8,2) NOT NULL COMMENT '商品价格',
  `goods_content` text NOT NULL COMMENT '商品介绍',
  `goods_adtime` int(11) NOT NULL COMMENT '添加时间',
  `goods_status` int(2) NOT NULL DEFAULT '1' COMMENT '1正常0下架2删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_goods
-- ----------------------------
INSERT INTO `tw_goods` VALUES ('3', '比特购', '/Upload/goods/20180409/s_5acad4859de54.png', '20', '0.00', '', '1523241679', '1');
INSERT INTO `tw_goods` VALUES ('4', '商品2', '/Upload/goods/20180409/s_5acadea0af01c.png', '80', '0.20', '', '1523244704', '1');
INSERT INTO `tw_goods` VALUES ('5', '产品3', '/Upload/goods/20180409/s_5acae0a3f2cbc.png', '20', '0.80', '', '1523245219', '1');
INSERT INTO `tw_goods` VALUES ('6', '产品4', '/Upload/goods/20180409/s_5acae0bc297e2.png', '50', '2.00', '', '1524067200', '1');
INSERT INTO `tw_goods` VALUES ('7', '产品5', '/Upload/goods/20180409/s_5acae0eb54204.jpg', '121', '1.00', '', '1523245290', '1');
INSERT INTO `tw_goods` VALUES ('8', '产品6', '/Upload/goods/20180409/s_5acae1140053e.png', '1', '12.00', '', '1523245331', '1');
INSERT INTO `tw_goods` VALUES ('9', '221', '/Upload/goods/20180409/s_5acae128c4dce.png', '23', '23.00', '', '1523245352', '1');
INSERT INTO `tw_goods` VALUES ('10', '32', '/Upload/goods/20180409/s_5acae13513987.png', '23', '23.00', '', '1523245364', '1');
INSERT INTO `tw_goods` VALUES ('11', 'Samsonite/新秀丽', '/Upload/goods/20180409/s_5acaf803d0efc.png', '321', '23.00', '<p><span style=\"font-weight: 700; color: rgb(153, 153, 153);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p><p><strong><span style=\"font-weight: 700; color: rgb(153, 153, 153);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上市时间:&nbsp;2016年夏季</span></strong><span style=\"font-weight: 700; color: rgb(153, 153, 153);\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 大小:&nbsp;中&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;容纳电脑尺寸:&nbsp;13英寸</span></p><p><span style=\"font-weight: 700; color: rgb(153, 153, 153);\">&nbsp; &nbsp; &nbsp; &nbsp;<br/></span></p><ul style=\"list-style-type: none;\" class=\" list-paddingleft-2\"><li><p>是否有背部缓冲棉:&nbsp;是&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;适用对象:&nbsp;青年&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 销售渠道类型:&nbsp;商场同款(线上线下都销售)</p></li><li><p>防水程度:&nbsp;防泼水&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 性别:&nbsp;女</p></li><li><p>质地:&nbsp;涤纶</p></li><li><p>提拎部件类型:&nbsp;硬把</p></li><li><p>闭合方式:&nbsp;拉链</p></li><li><p>内部结构:&nbsp;网袋&nbsp;拉链暗袋&nbsp;手机袋&nbsp;证件袋&nbsp;电脑插袋</p></li><li><p>图案:&nbsp;纯色</p></li><li><p>颜色分类:&nbsp;海军蓝&nbsp;浅灰色&nbsp;黑色</p></li><li><p>有无夹层:&nbsp;有</p></li><li><p>箱包硬度:&nbsp;软</p></li><li><p>是否可折叠:&nbsp;否</p></li><li><p>成色:&nbsp;全新</p></li><li><p>适用场景:&nbsp;休闲</p></li><li><p>品牌:&nbsp;Samsonite/新秀丽</p></li><li><p>货号:&nbsp;BP2002</p></li><li><p>肩带样式:&nbsp;双根</p></li><li><p>里料材质:&nbsp;织物</p></li><li><p>箱包尺寸:&nbsp;280*405*120</p></li></ul><p><span style=\"text-decoration:line-through;\"><strong style=\"margin: 0px; padding: 0px;\"></strong></span></p><p><span style=\"margin: 0px; padding: 0px;\"></span></p><table width=\"790\" style=\"width: 768px;\"><tbody style=\"margin: 0px; padding: 0px;\"><tr style=\"margin: 0px; padding: 0px;\" class=\"firstRow\"><td colspan=\"2\" style=\"margin: 0px; padding: 0px; word-break: break-all;\"><p><img src=\"/ueditor/php/upload/image/20180409/1523250710303890.jpg\" width=\"790\" height=\"540\" alt=\"\" class=\"jhi-img img-ks-lazyload\"/></p><p><br/></p></td></tr><tr style=\"margin: 0px; padding: 0px;\"><td style=\"margin: 0px; padding: 0px;\"><img src=\"/ueditor/php/upload/image/20180409/1523250711385736.jpg\" width=\"396\" height=\"432\" alt=\"\" class=\"jhi-img img-ks-lazyload\"/></td><td style=\"margin: 0px; padding: 0px;\"><img src=\"/ueditor/php/upload/image/20180409/1523250711100393.jpg\" width=\"394\" height=\"432\" alt=\"\" class=\"jhi-img img-ks-lazyload\"/></td></tr><tr style=\"margin: 0px; padding: 0px;\"><td colspan=\"2\" style=\"margin: 0px; padding: 0px;\"><img src=\"/ueditor/php/upload/image/20180409/1523250712118354.jpg\" width=\"790\" height=\"278\" alt=\"\" class=\"jhi-img img-ks-lazyload\"/></td></tr></tbody></table><p><a href=\"https://detail.tmall.com/item.htm?id=556441589031&scene=taobao_shop\" class=\"job abs imghover an8N8-uAJm\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; display: block; float: left; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; overflow: hidden; background: url(\"></a></p><p><a href=\"https://detail.tmall.com/item.htm?id=556441589031&scene=taobao_shop\" class=\"job abs imghover an8N8-uAJm\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; display: block; float: left; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; overflow: hidden; background: url(\"><span class=\"trans1s june-box-fadein\" style=\"margin: 0px; padding: 0px; transition: 1s ease-in; opacity: 0; display: block; width: 396px; height: 432px; background: url(\">&nbsp;</span></a></p><p><a href=\"https://detail.tmall.com/item.htm?id=533116400117&scene=taobao_shop\" class=\"job abs imghover an8N8-DzYi\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; display: block; float: left; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; overflow: hidden; background: url(\"></a></p><p><a href=\"https://detail.tmall.com/item.htm?id=533116400117&scene=taobao_shop\" class=\"job abs imghover an8N8-DzYi\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; display: block; float: left; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; overflow: hidden; background: url(\"><span class=\"trans1s june-box-fadein\" style=\"margin: 0px; padding: 0px; transition: 1s ease-in; opacity: 0; display: block; width: 394px; height: 432px; background: url(\">&nbsp;</span></a></p><p><a class=\"jdb abs an8N8-fXMF\" href=\"https://detail.tmall.com/item.htm?id=566735841799&scene=taobao_shop\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; background-image: url(\"></a><a class=\"jdb abs an8N8-wn4B\" href=\"https://detail.tmall.com/item.htm?id=546477778614&scene=taobao_shop\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; background-image: url(\"></a><a class=\"jdb abs an8N8-0R1D\" href=\"https://detail.tmall.com/item.htm?id=554574862261&scene=taobao_shop\" target=\"_blank\" style=\"margin: 0px; padding: 0px; text-decoration-line: none; color: rgb(41, 83, 166); outline: 0px; position: absolute; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px; white-space: normal; background-image: url(\"></a></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p>', '1523245375', '1');
INSERT INTO `tw_goods` VALUES ('12', '产品', '/Upload/goods/20180410/s_5acc1f554e93e.jpg', '21', '21.00', '<p>12</p>', '1523326804', '1');

-- ----------------------------
-- Table structure for tw_hor
-- ----------------------------
DROP TABLE IF EXISTS `tw_hor`;
CREATE TABLE `tw_hor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(30,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_hor
-- ----------------------------
INSERT INTO `tw_hor` VALUES ('1', '人民币', 'CNY', '1.00000000', '1519811229');

-- ----------------------------
-- Table structure for tw_hor_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_hor_log`;
CREATE TABLE `tw_hor_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL COMMENT '账号',
  `userid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1order_buy2order_sell',
  `plusminus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0减1加',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '数量',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的用户id',
  `ctype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1可用2冻结',
  `action` smallint(4) NOT NULL DEFAULT '0',
  `addip` varchar(50) DEFAULT NULL,
  `showname` varchar(20) NOT NULL DEFAULT '比特币',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`) USING BTREE,
  KEY `plusminus` (`plusminus`) USING BTREE,
  KEY `operator` (`operator`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_hor_log
-- ----------------------------
INSERT INTO `tw_hor_log` VALUES ('1', '17561917361', '83', '1529985383', '1', '0', '49.50495049', '买家下单减可用HOR，手续费0', '76', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('2', '17561917361', '83', '1529985383', '1', '1', '49.50495049', '买家下单加冻结HOR，手续费0', '76', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('3', '17561917361', '83', '1529985485', '1', '0', '51.48514851', '买家下单减可用HOR，手续费0', '76', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('4', '17561917361', '83', '1529985485', '1', '1', '51.48514851', '买家下单加冻结HOR，手续费0', '76', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('5', '17561917361', '83', '1529998211', '1', '0', '51.48514851', '卖家释放币减冻结HOR', '83', '2', '5', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('6', '15764251917', '76', '1529998211', '1', '1', '51.48514851', '卖家释放币加可用HOR', '83', '1', '5', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('15', '17561917361', '83', '1530072098', '1', '0', '49.50495049', '买家下单减可用HOR，手续费0', '84', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('16', '17561917361', '83', '1530072098', '1', '1', '49.50495049', '买家下单加冻结HOR，手续费0', '84', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('17', '17561917361', '83', '1530077496', '1', '0', '54.45544554', '买家下单减可用HOR，手续费0', '84', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('18', '17561917361', '83', '1530077496', '1', '1', '54.45544554', '买家下单加冻结HOR，手续费0', '84', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('19', '17561917361', '83', '1530077501', '1', '0', '54.45544554', '买家下单减可用HOR，手续费0', '84', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('20', '17561917361', '83', '1530077501', '1', '1', '54.45544554', '买家下单加冻结HOR，手续费0', '84', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('21', '17561917361', '83', '1530077581', '1', '0', '54.45544554', '买家下单减可用HOR，手续费0', '84', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_hor_log` VALUES ('22', '17561917361', '83', '1530077581', '1', '1', '54.45544554', '买家下单加冻结HOR，手续费0', '84', '2', '1', '127.0.0.1', '比特币');

-- ----------------------------
-- Table structure for tw_horzr
-- ----------------------------
DROP TABLE IF EXISTS `tw_horzr`;
CREATE TABLE `tw_horzr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fromaddress` varchar(255) NOT NULL DEFAULT '',
  `toaddress` varchar(255) NOT NULL DEFAULT '',
  `amount` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `addtime` int(10) NOT NULL,
  `orderno` bigint(20) NOT NULL DEFAULT '0',
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  `otherid` bigint(20) NOT NULL DEFAULT '0',
  `transaction_hash` varchar(255) NOT NULL,
  `block_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `thash` (`toaddress`,`transaction_hash`,`fromaddress`) USING BTREE,
  KEY `finished` (`finished`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_horzr
-- ----------------------------
INSERT INTO `tw_horzr` VALUES ('1', '0x5c8955d413bf73f9a7af6e58aee36abc154a219d', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '5.00000000', '1529716035', '1', '1', '1', '0xcc07a29d24bbfd3f6c6e4b367c7368079d7afcca03255bc525fd8b8c9e86fefb', '0xf601f9f7b4fe947fcf3e31a5a17704ca911e0ba08a5738dfb32188434361d95e');
INSERT INTO `tw_horzr` VALUES ('2', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '0x43e590e56023ccb8754b93904a4ea066c6641793', '0.49900000', '1529722621', '2', '1', '2', '0x0771055aeefebc04539c767ef70083a8f53ce7ae3df850cf4312144717025b6e', '0x19abd9329c893f28bf72fe4e55321575e60905bf388f6cbf7d7244f26a00f836');
INSERT INTO `tw_horzr` VALUES ('3', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '0x76d71eb19d44102088e8642a6c56493f6e29266d', '0.85000000', '1529722621', '3', '1', '3', '0x8fa9918da48e4e4879e5f5a418b566dcdfbc812d01d3263563fe1809faedf058', '0x19abd9329c893f28bf72fe4e55321575e60905bf388f6cbf7d7244f26a00f836');

-- ----------------------------
-- Table structure for tw_invit
-- ----------------------------
DROP TABLE IF EXISTS `tw_invit`;
CREATE TABLE `tw_invit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `invit` int(11) unsigned NOT NULL,
  `name` tinyint(4) NOT NULL,
  `type` int(11) NOT NULL,
  `num` decimal(20,8) unsigned NOT NULL,
  `mum` decimal(20,8) unsigned NOT NULL,
  `fee` decimal(20,8) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL,
  `buysell` tinyint(1) NOT NULL DEFAULT '2',
  `orderno` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `invit` (`invit`),
  KEY `name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `buysell` (`buysell`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推广奖励表';

-- ----------------------------
-- Records of tw_invit
-- ----------------------------

-- ----------------------------
-- Table structure for tw_link
-- ----------------------------
DROP TABLE IF EXISTS `tw_link`;
CREATE TABLE `tw_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `img` varchar(100) NOT NULL DEFAULT '',
  `mytx` varchar(100) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `look_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='常用银行地址';

-- ----------------------------
-- Records of tw_link
-- ----------------------------
INSERT INTO `tw_link` VALUES ('27', '巴比特', '巴比特', 'http://www.8btc.com/', '', '', '', '0', '1492790400', '1492790400', '1', '1');
INSERT INTO `tw_link` VALUES ('42', '云币', '云币', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/5.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('43', 'wan', 'wan', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/4.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('44', '网录科技', '网录科技', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/3.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('45', '新链加速器', '新链加速器', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/2.jpg', '', '', '0', '1492652293', '1492652293', '1', '0');
INSERT INTO `tw_link` VALUES ('46', '云财经', '云财经', 'http://www.yuncaijing.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('47', '金投网', '金投网', 'http://finance.cngold.org/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('48', '互动百科', '互动百科', 'http://www.baike.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('49', '比特币挖矿', '比特币挖矿', 'http://www.cybtc.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('50', '中国纸黄金', '中国纸黄金', 'http://www.zhijinwang.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('51', '炎黄财经视频', '炎黄财经视频', 'http://www.mytv365.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('52', '币看比特币', '币看比特币', 'https://bitkan.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('53', 'BTCBOX', 'BTCBOX', 'https://btcbox.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('54', '比特范', '比特范', 'http://news.btcfans.com/', '', '', '', '0', '1503331200', '1503331200', '1', '1');
INSERT INTO `tw_link` VALUES ('55', '链行', '链行', 'https://www.lhang.com/#/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('56', '玩币族', '玩币族', 'http://www.wanbizu.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('57', 'bitbank', 'bitbank', 'https://www.bitbank.com/', '', '', '', '0', '1503417600', '1503417600', '1', '1');
INSERT INTO `tw_link` VALUES ('58', '铅笔', '铅笔', 'http://chainb.com/', '', '', '', '0', '1503244800', '1503244800', '1', '1');
INSERT INTO `tw_link` VALUES ('59', '彩云比特', '彩云比特', 'http://www.cybtc.com/', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('60', '小黑屋', '小黑屋', 'http://www.cybtc.com/forum.php?mod=misc&action=showdarkroom', '', '', '', '0', '1503504000', '1503504000', '1', '1');
INSERT INTO `tw_link` VALUES ('61', '比西西商城', '比西西商城', 'http://shop.bitxixi.com/', '', '', '', '0', '1503331200', '1503331200', '1', '1');
INSERT INTO `tw_link` VALUES ('62', 'BITKAN', 'BITKAN', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/6.jpg', '', '', '0', '1503504000', '1503504000', '1', '0');
INSERT INTO `tw_link` VALUES ('63', '趣块链', '趣块链', '', 'http://eryuemei.oss-cn-shenzhen.aliyuncs.com/upload/1.jpg', '', '', '0', '1503504000', '1503504000', '1', '0');

-- ----------------------------
-- Table structure for tw_location
-- ----------------------------
DROP TABLE IF EXISTS `tw_location`;
CREATE TABLE `tw_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_location
-- ----------------------------
INSERT INTO `tw_location` VALUES ('1', '中国', 'CN');
INSERT INTO `tw_location` VALUES ('2', '美国', 'US');
INSERT INTO `tw_location` VALUES ('3', '澳大利亚', 'AU');
INSERT INTO `tw_location` VALUES ('4', '日本', 'JP');
INSERT INTO `tw_location` VALUES ('5', '韩国', 'KR');
INSERT INTO `tw_location` VALUES ('6', '加拿大', 'CA');
INSERT INTO `tw_location` VALUES ('7', '法国', 'FR');
INSERT INTO `tw_location` VALUES ('8', '印度', 'IN');
INSERT INTO `tw_location` VALUES ('9', '俄罗斯', 'RU');
INSERT INTO `tw_location` VALUES ('10', '德国', 'DE');
INSERT INTO `tw_location` VALUES ('11', '英国', 'GB');
INSERT INTO `tw_location` VALUES ('12', '香港', 'HK');
INSERT INTO `tw_location` VALUES ('13', '巴西', 'BR');
INSERT INTO `tw_location` VALUES ('14', '印尼', 'ID');
INSERT INTO `tw_location` VALUES ('15', '菲律宾', 'PH');

