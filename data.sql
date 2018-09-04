
-- 用户收款账号
CREATE TABLE `tw_user_skaccount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pay_method_id` int(11) NOT NULL DEFAULT '0' COMMENT '支付方式ID',

  `name` varchar(50) NOT NULL COMMENT '姓名',
  `account` varchar(100) DEFAULT NULL COMMENT '账号',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '二维码',
  `bank` varchar(100) DEFAULT NULL COMMENT '开户行',
  `desc` varchar(400) DEFAULT NULL COMMENT '详细',

  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
  `addon` varchar(255) DEFAULT NULL COMMENT '附件',
  `ordertype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1表示order_buy2表示order_sell3表示临时订单',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未读1已读',
  `advid` bigint(20) NOT NULL DEFAULT '0' COMMENT '广告ID',
  `advtype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1表示adv_buy2表示adv_sell',
  `isfinished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fromuid` (`fromuid`) USING BTREE,
  KEY `touid` (`touid`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `type` (`ordertype`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;



/*
Navicat MySQL Data Transfer

Source Server         : 局域网
Source Server Version : 50556
Source Host           : 192.168.1.2:3306
Source Database       : otc

Target Server Type    : MYSQL
Target Server Version : 50556
File Encoding         : 65001

Date: 2018-07-17 09:28:30
*/

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
  `margin` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '溢价',
  `min_limit` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '限额（最小）',
  `max_limit` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '限额（最大）',
  `pay_method` varchar(50) NOT NULL COMMENT '支付方式 逗号分开来',
  `message` varchar(500) NOT NULL COMMENT '留言',
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

-- ----------------------------
-- Records of tw_ad_buy
-- ----------------------------
INSERT INTO `tw_ad_buy` VALUES ('1', 'bubvg1526001504', '69', '1', '1', '3.00', '100.00', '10000.00', '1,2,3,4,', '交易条款', '30', '0', '0', '1,1,1,1,1,1,1', '1526001504', '1', null, '0', '10', '0');
INSERT INTO `tw_ad_buy` VALUES ('2', 'cqyvt1529749565', '82', '1', '1', '5.00', '50.00', '100.00', '4,', '无', '10', '0', '0', '1,1,1,1,1,1,1', '1529749565', '1', null, '0', '14', '0');
INSERT INTO `tw_ad_buy` VALUES ('3', 'vkfix1529996918', '83', '1', '1', '1.00', '50.00', '100.00', '1,2,3,4,', '1', '30', '0', '0', '1,1,1,1,1,1,1', '1529996918', '1', null, '0', '10', '0');

-- ----------------------------
-- Table structure for tw_ad_sell
-- ----------------------------
DROP TABLE IF EXISTS `tw_ad_sell`;
CREATE TABLE `tw_ad_sell` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ad_no` varchar(15) NOT NULL COMMENT '广告唯一编号',
  `userid` int(11) NOT NULL COMMENT '发布用户id',
  `location` int(10) NOT NULL COMMENT '地区',
  `currency` int(10) NOT NULL COMMENT '货币',
  `margin` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '溢价',
  `min_price` decimal(12,2) DEFAULT '0.00',
  `min_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `max_limit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pay_method` varchar(50) NOT NULL COMMENT '支付方式',
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
-- Records of tw_ad_sell
-- ----------------------------
INSERT INTO `tw_ad_sell` VALUES ('1', 'yffew1525946726', '68', '1', '1', '5.00', '6.00', '100.00', '1000.00', '1,2,3,4,', '交易条款', '0', '0', '1,1,1,1,1,1,1', '1525946726', '1', null, '1', '10', '0', '这是收款账号：asdasdasdasdasdas');
INSERT INTO `tw_ad_sell` VALUES ('2', 'xtuuv1529752051', '69', '1', '1', '4.00', '6.50', '100.00', '10000.00', '1,2,3,4,', '交易条款', '0', '0', '1,1,1,1,1,1,1', '1529752051', '1', null, '1', '10', '0', '收款账号是112312123213123123121212');
INSERT INTO `tw_ad_sell` VALUES ('3', 'tjfsh1529898766', '82', '1', '1', '1.00', '5.00', '50.00', '50.00', '1,2,3,4,', '2222', '0', '0', '1,1,1,1,1,1,1', '1529898766', '1', null, '1', '10', '0', '1111');
INSERT INTO `tw_ad_sell` VALUES ('4', 'uwpmf1529901394', '82', '1', '1', '1.00', '0.00', '500.00', '600.00', '1,2,3,4,', '12321312312', '0', '0', '1,1,1,1,1,1,1', '1529901394', '1', null, '1', '11', '0', '1321');
INSERT INTO `tw_ad_sell` VALUES ('5', 'aplmm1529901841', '82', '1', '1', '1.00', '0.00', '700.00', '800.00', '1,2,3,4,', '123123', '0', '0', '1,1,1,1,1,1,1', '1529901841', '1', null, '1', '11', '0', '123123');
INSERT INTO `tw_ad_sell` VALUES ('6', 'iyizl1529985060', '83', '1', '1', '1.00', '1.00', '50.00', '500.00', '1,2,', '21', '0', '0', '1,1,1,1,1,1,1', '1529985060', '1', null, '1', '14', '0', '12121');
INSERT INTO `tw_ad_sell` VALUES ('7', 'sdojr1530238214', '76', '1', '1', '11.00', '111.00', '111.00', '1111.00', '1,2,3,4,', '1', '0', '0', '1,1,1,1,1,1,1', '1530238214', '2', null, '1', '11', '0', '111');
INSERT INTO `tw_ad_sell` VALUES ('8', 'kropp1530499895', '76', '1', '1', '11.00', '11.00', '111.00', '1111.00', '1,2,3,4,', '1', '0', '0', '1,1,1,1,1,1,1', '1530499895', '1', null, '1', '14', '0', '11');
INSERT INTO `tw_ad_sell` VALUES ('9', 'tmfts1530500453', '77', '1', '1', '2.00', '4000.00', '1000.00', '100000.00', '2,', '1234567@qq.com', '0', '0', '1,1,1,1,1,1,1', '1530500453', '1', null, '1', '11', '0', '1234567@qq.com');

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
-- Records of tw_adver
-- ----------------------------
INSERT INTO `tw_adver` VALUES ('3', 'banner', '', 'http://copro.oss-cn-hongkong.aliyuncs.com/pic/banner2.jpg', '', '0', '1512662400', '1512662400', '1', '0');
INSERT INTO `tw_adver` VALUES ('6', 'banner', '', 'http://copro.oss-cn-hongkong.aliyuncs.com/pic/banner.jpg', '', '0', '1525363200', '1525363200', '1', '0');
INSERT INTO `tw_adver` VALUES ('7', 'm-banner', '', 'http://copro.oss-cn-hongkong.aliyuncs.com/pic/m_banner2.jpg', '', '0', '1525363200', '1525363200', '1', '1');

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
-- Records of tw_area_code
-- ----------------------------
INSERT INTO `tw_area_code` VALUES ('1', '中国', 'China', 'CN', '86', '中国,China');
INSERT INTO `tw_area_code` VALUES ('2', '阿富汗', 'Afghanistan', 'AF', '93', '阿富汗,Afghanistan');
INSERT INTO `tw_area_code` VALUES ('3', '阿尔巴尼亚', 'Albania', 'AL', '355', '阿尔巴尼亚,Albania');
INSERT INTO `tw_area_code` VALUES ('4', '阿尔及利亚', 'Algeria', 'DZ', '213', '阿尔及利亚,Algeria');
INSERT INTO `tw_area_code` VALUES ('5', '美属萨摩亚群岛', 'American Samoa', 'AS', '684', '美属萨摩亚群岛,American Samoa');
INSERT INTO `tw_area_code` VALUES ('6', '安道尔共和国', 'Andorra', 'AD', '376', '安道尔共和国,Andorra');
INSERT INTO `tw_area_code` VALUES ('7', '安哥拉', 'Angola', 'AO', '244', '安哥拉,Angola');
INSERT INTO `tw_area_code` VALUES ('8', '安圭拉岛', 'Anguilla', 'AI', '1264', '安圭拉岛,Anguilla');
INSERT INTO `tw_area_code` VALUES ('9', '安提瓜和巴布达	', 'Antigua and Barbuda', 'AG', '1268', '安提瓜和巴布达,Antigua and Barbuda');
INSERT INTO `tw_area_code` VALUES ('10', '阿根廷', 'Argentina', 'AR', '54', '阿根廷,Argentina');
INSERT INTO `tw_area_code` VALUES ('11', '亚美尼亚', 'Armenia', 'AM', '374', '亚美尼亚,Armenia');
INSERT INTO `tw_area_code` VALUES ('12', '阿鲁巴', 'Aruba', 'AW', '297', '阿鲁巴,Aruba');
INSERT INTO `tw_area_code` VALUES ('13', '澳大利亚', 'Australia', 'AU', '61', '澳大利亚,Australia');
INSERT INTO `tw_area_code` VALUES ('14', '奥地利', 'Austria', 'AT', '43', '奥地利,Austria');
INSERT INTO `tw_area_code` VALUES ('15', '阿塞拜疆共和国', 'Azerbaijan', 'AZ', '994', '阿塞拜疆共和国,Azerbaijan');
INSERT INTO `tw_area_code` VALUES ('16', '巴哈马', 'Bahamas', 'BS', '1242', '巴哈马,Bahamas');
INSERT INTO `tw_area_code` VALUES ('17', '巴林', 'Bahrain', 'BH', '973', '巴林,Bahrain');
INSERT INTO `tw_area_code` VALUES ('18', '孟加拉共和国', 'Bangladesh', 'BD', '880', '孟加拉共和国,Bangladesh');
INSERT INTO `tw_area_code` VALUES ('19', '巴巴多斯', 'Barbados', 'BB', '1246', '巴巴多斯,Barbados');
INSERT INTO `tw_area_code` VALUES ('20', '白俄罗斯', 'Belarus', 'BY', '375', '白俄罗斯,Belarus');
INSERT INTO `tw_area_code` VALUES ('21', '比利时', 'Belgium', 'BE', '32', '比利时,Belgium');
INSERT INTO `tw_area_code` VALUES ('22', '伯利兹', 'Belize', 'BZ', '501', '伯利兹,Belize');
INSERT INTO `tw_area_code` VALUES ('23', '贝宁共和国', 'Benin', 'BJ', '229', '贝宁共和国,Benin');
INSERT INTO `tw_area_code` VALUES ('24', '百慕大群岛', 'Bermuda', 'BM', '1441', '百慕大群岛,Bermuda');
INSERT INTO `tw_area_code` VALUES ('25', '不丹', 'Bhutan', 'BT', '975', '不丹,Bhutan');
INSERT INTO `tw_area_code` VALUES ('26', '玻利维亚', 'Bolivia', 'BO', '591', '玻利维亚,Bolivia');
INSERT INTO `tw_area_code` VALUES ('27', '博内尔岛', 'Bonaire', 'BON', '5997', '博内尔岛,Bonaire');
INSERT INTO `tw_area_code` VALUES ('28', '波斯尼亚和黑塞哥维那', 'Bosnia and Herzegovina', 'BA', '387', '波斯尼亚和黑塞哥维那,Bosnia and Herzegovina');
INSERT INTO `tw_area_code` VALUES ('29', '博茨瓦纳', 'Botswana', 'BW', '267', '博茨瓦纳,Botswana');
INSERT INTO `tw_area_code` VALUES ('30', '巴西', 'Brazil', 'BR', '55', '巴西,Brazil');
INSERT INTO `tw_area_code` VALUES ('31', '英属印度洋领地', 'British Indian Ocean Territory', 'IO', '246', '英属印度洋领地,British Indian Ocean Territory');
INSERT INTO `tw_area_code` VALUES ('32', '文莱', 'Brunei', 'BN', '673', '文莱,Brunei');
INSERT INTO `tw_area_code` VALUES ('33', '保加利亚', 'Bulgaria', 'BG', '359', '保加利亚,Bulgaria');
INSERT INTO `tw_area_code` VALUES ('34', '布基纳法索', 'Burkina Faso', 'BF', '226', '布基纳法索,Burkina Faso');
INSERT INTO `tw_area_code` VALUES ('35', '布隆迪', 'Burundi', 'BI', '257', '布隆迪,Burundi');
INSERT INTO `tw_area_code` VALUES ('36', '柬埔寨', 'Cambodia', 'KH', '855', '柬埔寨,Cambodia');
INSERT INTO `tw_area_code` VALUES ('37', '喀麦隆', 'Cameroon', 'CM', '237', '喀麦隆,Cameroon');
INSERT INTO `tw_area_code` VALUES ('38', '加拿大', 'Canada', 'CA', '1', '加拿大,Canada');
INSERT INTO `tw_area_code` VALUES ('39', '佛得角', 'Cape Verde', 'CV', '238', '佛得角,Cape Verde');
INSERT INTO `tw_area_code` VALUES ('40', '开曼群岛', 'Cayman Islands', 'KY', '1345', '开曼群岛,Cayman Islands');
INSERT INTO `tw_area_code` VALUES ('41', '中非共和国	', 'Central African Republic', 'CF', '236', '中非共和国,Central African Republic');
INSERT INTO `tw_area_code` VALUES ('42', '乍得', 'Chad', 'TD', '235', '乍得,Chad');
INSERT INTO `tw_area_code` VALUES ('43', '智利', 'Chile', 'CL', '56', '智利,Chile');
INSERT INTO `tw_area_code` VALUES ('44', '哥伦比亚', 'Colombia', 'CO', '57', '哥伦比亚,Colombia');
INSERT INTO `tw_area_code` VALUES ('45', '科摩罗伊斯兰联邦共和国', 'Comoros', 'CMR', '269', '科摩罗伊斯兰联邦共和国,Comoros');
INSERT INTO `tw_area_code` VALUES ('46', '刚果', 'Congo', 'CG', '242', '刚果,Congo');
INSERT INTO `tw_area_code` VALUES ('47', '库克群岛', 'Cook Islands', 'CK', '682', '库克群岛,Cook Islands');
INSERT INTO `tw_area_code` VALUES ('48', '哥斯达黎加', 'Costa Rica', 'CR', '506', '哥斯达黎加,Costa Rica');
INSERT INTO `tw_area_code` VALUES ('49', '克罗地亚', 'Croatia', 'HR', '385', '克罗地亚,Croatia');
INSERT INTO `tw_area_code` VALUES ('50', '古巴', 'Cuba', 'CU', '53', '古巴,Cuba');
INSERT INTO `tw_area_code` VALUES ('51', '库拉索', 'Curaçao', 'AN', '5999', '库拉索,Curaçao');
INSERT INTO `tw_area_code` VALUES ('52', '塞浦路斯', 'Cyprus', 'CY', '357', '塞浦路斯,Cyprus');
INSERT INTO `tw_area_code` VALUES ('53', '捷克共和国', 'Czech Republic', 'CZ', '420', '捷克共和国,Czech Republic');
INSERT INTO `tw_area_code` VALUES ('54', '科特迪瓦', 'Coate d\'Ivoire', 'KT', '225', '科特迪瓦,Coate d\'Ivoire');
INSERT INTO `tw_area_code` VALUES ('55', '丹麦', 'Denmark', 'DK', '45', '丹麦,Denmark');
INSERT INTO `tw_area_code` VALUES ('56', '吉布提', 'Djibouti', 'DJ', '253', '吉布提,Djibouti');
INSERT INTO `tw_area_code` VALUES ('57', '多米尼克', 'Dominica', 'DM', '1767', '多米尼克,Dominica');
INSERT INTO `tw_area_code` VALUES ('58', '多米尼加', 'Dominican Republic', 'DO', '1', '多米尼加,Dominican Republic');
INSERT INTO `tw_area_code` VALUES ('59', '厄瓜多尔', 'Ecuador', 'EC', '593', '厄瓜多尔,Ecuador');
INSERT INTO `tw_area_code` VALUES ('60', '埃及', 'Egypt', 'EG', '20', '埃及,Egypt');
INSERT INTO `tw_area_code` VALUES ('61', '萨尔瓦多', 'El Salvador', 'SV', '503', '萨尔瓦多,El Salvador');
INSERT INTO `tw_area_code` VALUES ('62', '赤道几内亚', 'Equatorial Guinea', 'GQ', '240', '赤道几内亚,Equatorial Guinea');
INSERT INTO `tw_area_code` VALUES ('63', '厄立特里亚', 'Eritrea', 'ER', '291', '厄立特里亚,Eritrea');
INSERT INTO `tw_area_code` VALUES ('64', '爱沙尼亚', 'Estonia', 'EE', '372', '爱沙尼亚,Estonia');
INSERT INTO `tw_area_code` VALUES ('65', '埃塞俄比亚', 'Ethiopia', 'ET', '251', '埃塞俄比亚,Ethiopia');
INSERT INTO `tw_area_code` VALUES ('66', '福克兰群岛', 'Falkland Islands', 'FK', '500', '福克兰群岛,Falkland Islands');
INSERT INTO `tw_area_code` VALUES ('67', '法罗群岛', 'Faroe Islands', 'FO', '298', '法罗群岛,Faroe Islands');
INSERT INTO `tw_area_code` VALUES ('68', '斐济', 'Fiji', 'FJ', '679', '斐济,Fiji');
INSERT INTO `tw_area_code` VALUES ('69', '芬兰', 'Finland', 'FI', '358', '芬兰,Finland');
INSERT INTO `tw_area_code` VALUES ('70', '法国', 'France', 'FR', '33', '法国,France');
INSERT INTO `tw_area_code` VALUES ('71', '法属圭亚那', 'French Guiana', 'FG', '594', '法属圭亚那,French Guiana');
INSERT INTO `tw_area_code` VALUES ('72', '法属波利尼西亚', 'French Polynesia', 'PF', '689', '法属波利尼西亚,French Polynesia');
INSERT INTO `tw_area_code` VALUES ('73', '加蓬', 'Gabon', 'GA', '241', '加蓬,Gabon');
INSERT INTO `tw_area_code` VALUES ('74', '冈比亚', 'Gambia', 'GM', '220', '冈比亚,Gambia');
INSERT INTO `tw_area_code` VALUES ('75', '格鲁吉亚', 'Georgia', 'GE', '995', '格鲁吉亚,Georgia');
INSERT INTO `tw_area_code` VALUES ('76', '德国', 'Germany', 'DE', '49', '德国,Germany');
INSERT INTO `tw_area_code` VALUES ('77', '加纳', 'Ghana', 'GH', '233', '加纳,Ghana');
INSERT INTO `tw_area_code` VALUES ('78', '直布罗陀', 'Gibraltar', 'GI', '350', '直布罗陀,Gibraltar');
INSERT INTO `tw_area_code` VALUES ('79', '希腊', 'Greece', 'GR', '30', '希腊,Greece');
INSERT INTO `tw_area_code` VALUES ('80', '格陵兰岛', 'Greenland', 'GL', '299', '格陵兰岛,Greenland');
INSERT INTO `tw_area_code` VALUES ('81', '格林纳达', 'Grenada', 'GD', '1473', '格林纳达,Grenada');
INSERT INTO `tw_area_code` VALUES ('82', '瓜德罗普岛', 'Guadeloupe', 'GP', '590', '瓜德罗普岛,Guadeloupe');
INSERT INTO `tw_area_code` VALUES ('83', '关岛', 'Guam', 'GU', '1671', '关岛,Guam');
INSERT INTO `tw_area_code` VALUES ('84', '危地马拉', 'Guatemala', 'GT', '502', '危地马拉,Guatemala');
INSERT INTO `tw_area_code` VALUES ('85', '巴布亚新几内亚', 'Guinea', 'PG', '224', '巴布亚新几内亚,Guinea');
INSERT INTO `tw_area_code` VALUES ('86', '几内亚比绍共和国', 'Guinea-Bissau', 'GW', '245', '几内亚比绍共和国,Guinea-Bissau');
INSERT INTO `tw_area_code` VALUES ('87', '圭亚那', 'Guyana', 'GY', '592', '圭亚那,Guyana');
INSERT INTO `tw_area_code` VALUES ('88', '海地', 'Haiti', 'HT', '509', '海地,Haiti');
INSERT INTO `tw_area_code` VALUES ('89', '洪都拉斯', 'Honduras', 'HN', '504', '洪都拉斯,Honduras');
INSERT INTO `tw_area_code` VALUES ('90', '香港', 'Hong Kong', 'HK', '852', '香港,Hong Kong');
INSERT INTO `tw_area_code` VALUES ('91', '匈牙利', 'Hungary', 'HU', '36', '匈牙利,Hungary');
INSERT INTO `tw_area_code` VALUES ('92', '冰岛', 'Iceland', 'IS', '354', '冰岛,Iceland');
INSERT INTO `tw_area_code` VALUES ('93', '印度', 'India', 'IN', '91', '印度,India');
INSERT INTO `tw_area_code` VALUES ('94', '印度尼西亚', 'Indonesia', 'ID', '62', '印度尼西亚,Indonesia');
INSERT INTO `tw_area_code` VALUES ('95', '伊朗', 'Iran', 'IR', '98', '伊朗,Iran');
INSERT INTO `tw_area_code` VALUES ('96', '伊拉克', 'Iraq', 'IQ', '964', '伊拉克,Iraq');
INSERT INTO `tw_area_code` VALUES ('97', '爱尔兰', 'Ireland', 'IE', '353', '爱尔兰,Ireland');
INSERT INTO `tw_area_code` VALUES ('98', '以色列', 'Israel', 'IL', '972', '以色列,Israel');
INSERT INTO `tw_area_code` VALUES ('99', '意大利', 'Italy', 'IT', '39', '意大利,Italy');
INSERT INTO `tw_area_code` VALUES ('100', '牙买加', 'Jamaica', 'JM', '1876', '牙买加,Jamaica');
INSERT INTO `tw_area_code` VALUES ('101', '日本', 'Japan', 'JP', '81', '日本,Japan');
INSERT INTO `tw_area_code` VALUES ('102', '约旦', 'Jordan', 'JO', '962', '约旦,Jordan');
INSERT INTO `tw_area_code` VALUES ('103', '哈萨克斯坦', 'Kazakhstan', 'KZ', '7', '哈萨克斯坦,Kazakhstan');
INSERT INTO `tw_area_code` VALUES ('104', '肯尼亚', 'Kenya', 'KE', '254', '肯尼亚,Kenya');
INSERT INTO `tw_area_code` VALUES ('105', '基里巴斯', 'Kiribati', 'KI', '686', '基里巴斯,Kiribati');
INSERT INTO `tw_area_code` VALUES ('106', '科威特', 'Kuwait', 'KW', '965', '科威特,Kuwait');
INSERT INTO `tw_area_code` VALUES ('107', '吉尔吉斯', 'Kyrgyzstan', 'KG', '996', '吉尔吉斯,Kyrgyzstan');
INSERT INTO `tw_area_code` VALUES ('108', '老挝', 'Laos', 'LA', '856', '老挝,Laos');
INSERT INTO `tw_area_code` VALUES ('109', '拉脱维亚', 'Latvia', 'LV', '371', '拉脱维亚,Latvia');
INSERT INTO `tw_area_code` VALUES ('110', '黎巴嫩', 'Lebanon', 'LB', '961', '黎巴嫩,Lebanon');
INSERT INTO `tw_area_code` VALUES ('111', '莱索托', 'Lesotho', 'LS', '266', '莱索托,Lesotho');
INSERT INTO `tw_area_code` VALUES ('112', '利比里亚', 'Liberia', 'LR', '231', '利比里亚,Liberia');
INSERT INTO `tw_area_code` VALUES ('113', '利比亚', 'Libya', 'LY', '218', '利比亚,Libya');
INSERT INTO `tw_area_code` VALUES ('114', '列支敦士登', 'Liechtenstein', 'LI', '423', '列支敦士登,Liechtenstein');
INSERT INTO `tw_area_code` VALUES ('115', '立陶宛', 'Lithuania', 'LT', '370', '立陶宛,Lithuania');
INSERT INTO `tw_area_code` VALUES ('116', '卢森堡', 'Luxembourg', 'LU', '352', '卢森堡,Luxembourg');
INSERT INTO `tw_area_code` VALUES ('117', '澳门', 'Macao', 'MO', '853', '澳门,Macao');
INSERT INTO `tw_area_code` VALUES ('118', '马其顿共和国', 'Macedonia', 'MK', '389', '马其顿共和国,Macedonia');
INSERT INTO `tw_area_code` VALUES ('119', '马达加斯加', 'Madagascar', 'MG', '261', '马达加斯加,Madagascar');
INSERT INTO `tw_area_code` VALUES ('120', '马拉维', 'Malawi', 'MW', '265', '马拉维,Malawi');
INSERT INTO `tw_area_code` VALUES ('121', '马来西亚', 'Malaysia', 'MY', '60', '马来西亚,Malaysia');
INSERT INTO `tw_area_code` VALUES ('122', '马尔代夫', 'Maldives', 'MV', '960', '马尔代夫,Maldives');
INSERT INTO `tw_area_code` VALUES ('123', '马里', 'Mali', 'ML', '223', '马里,Mali');
INSERT INTO `tw_area_code` VALUES ('124', '马耳他', 'Malta', 'MT', '356', '马耳他,Malta');
INSERT INTO `tw_area_code` VALUES ('125', '马提尼克岛', 'Martinique', 'MQ', '596', '马提尼克岛,Martinique');
INSERT INTO `tw_area_code` VALUES ('126', '毛里塔尼亚', 'Mauritania', 'MR', '222', '毛里塔尼亚,Mauritania');
INSERT INTO `tw_area_code` VALUES ('127', '毛里求斯', 'Mauritius', 'MU', '230', '毛里求斯,Mauritius');
INSERT INTO `tw_area_code` VALUES ('128', '马约特岛', 'Mayotte', 'YT', '269', '马约特岛,Mayotte');
INSERT INTO `tw_area_code` VALUES ('129', '墨西哥', 'Mexico', 'MX', '52', '墨西哥,Mexico');
INSERT INTO `tw_area_code` VALUES ('130', '密克罗尼西亚联邦', 'Micronesia', 'FM', '691', '密克罗尼西亚联邦,Micronesia');
INSERT INTO `tw_area_code` VALUES ('131', '摩尔多瓦', 'Moldova', 'MD', '373', '摩尔多瓦,Moldova');
INSERT INTO `tw_area_code` VALUES ('132', '摩纳哥', 'Monaco', 'MC', '377', '摩纳哥,Monaco');
INSERT INTO `tw_area_code` VALUES ('133', '蒙古', 'Mongolia', 'MN', '976', '蒙古,Mongolia');
INSERT INTO `tw_area_code` VALUES ('134', '黑山共和国', 'Montenegro', 'MNE', '382', '黑山共和国,Montenegro');
INSERT INTO `tw_area_code` VALUES ('135', '蒙特塞拉特岛', 'Montserrat', 'MS', '1664', '蒙特塞拉特岛,Montserrat');
INSERT INTO `tw_area_code` VALUES ('136', '摩洛哥', 'Morocco', 'MA', '212', '摩洛哥,Morocco');
INSERT INTO `tw_area_code` VALUES ('137', '莫桑比克', 'Mozambique', 'MZ', '258', '莫桑比克,Mozambique');
INSERT INTO `tw_area_code` VALUES ('138', '缅甸', 'Myanmar', 'MM', '95', '缅甸,Myanmar');
INSERT INTO `tw_area_code` VALUES ('139', '纳米比亚', 'Namibia	', 'NA', '264', '纳米比亚,Namibia');
INSERT INTO `tw_area_code` VALUES ('140', '尼泊尔', 'Nepal', 'NP', '977', '尼泊尔,Nepal');
INSERT INTO `tw_area_code` VALUES ('141', '荷兰', 'Netherlands', 'NL', '31', '荷兰,Netherlands');
INSERT INTO `tw_area_code` VALUES ('142', '荷属安德烈斯群岛	', 'Netherlands Antilles', 'AN', '599', '荷属安德烈斯群岛,Netherlands Antilles');
INSERT INTO `tw_area_code` VALUES ('143', '新喀里多尼亚', 'New Caledonia', 'NC', '687', '新喀里多尼亚,New Caledonia');
INSERT INTO `tw_area_code` VALUES ('144', '新西兰', 'New Zealand', 'NZ', '64', '新西兰,New Zealand');
INSERT INTO `tw_area_code` VALUES ('145', '尼加拉瓜', 'Nicaragua', 'NI', '505', '尼加拉瓜,Nicaragua');
INSERT INTO `tw_area_code` VALUES ('146', '尼日尔', 'Niger', 'NE', '227', '尼日尔,Niger');
INSERT INTO `tw_area_code` VALUES ('147', '尼日利亚', 'Nigeria', 'NG', '234', '尼日利亚,Nigeria');
INSERT INTO `tw_area_code` VALUES ('148', '朝鲜', 'North Korea', 'KP', '850', '朝鲜,North Korea');
INSERT INTO `tw_area_code` VALUES ('149', '北马里亚纳群岛', 'Northern Mariana Islands', 'MP', '1', '北马里亚纳群岛,Northern Mariana Islands');
INSERT INTO `tw_area_code` VALUES ('150', '挪威', 'Norway', 'NO', '47', '挪威,Norway');
INSERT INTO `tw_area_code` VALUES ('151', '阿曼', 'Oman', 'OM', '968', '阿曼,Oman');
INSERT INTO `tw_area_code` VALUES ('152', '巴基斯坦', 'Pakistan', 'PK', '92', '巴基斯坦,Pakistan');
INSERT INTO `tw_area_code` VALUES ('153', '帕劳', 'Palau', 'PW', '680', '帕劳,Palau');
INSERT INTO `tw_area_code` VALUES ('154', '巴勒斯坦', 'Palestine', 'PS', '970', '巴勒斯坦,Palestine');
INSERT INTO `tw_area_code` VALUES ('155', '巴拿马', 'Panama', 'PA', '507', '巴拿马,Panama');
INSERT INTO `tw_area_code` VALUES ('156', '巴布亚新几内亚', 'Papua New Guinea', 'PG', '675', '巴布亚新几内亚,Papua New Guinea');
INSERT INTO `tw_area_code` VALUES ('157', '巴拉圭', 'Paraguay', 'PY', '595', '巴拉圭,Paraguay');
INSERT INTO `tw_area_code` VALUES ('158', '秘鲁', 'Peru', 'PE', '51', '秘鲁,Peru');
INSERT INTO `tw_area_code` VALUES ('159', '菲律宾', 'Philippines', 'PH', '63', '菲律宾,Philippines');
INSERT INTO `tw_area_code` VALUES ('160', '波兰', 'Poland', 'PL', '48', '波兰,Poland');
INSERT INTO `tw_area_code` VALUES ('161', '葡萄牙', 'Portugal', 'PT', '351', '葡萄牙,Portugal');
INSERT INTO `tw_area_code` VALUES ('162', '波多黎各', 'Puerto Rico', 'PR', '1787', '波多黎各,Puerto Rico');
INSERT INTO `tw_area_code` VALUES ('163', '卡塔尔', 'Qatar', 'QA', '974', '卡塔尔,Qatar');
INSERT INTO `tw_area_code` VALUES ('164', '留尼旺岛', 'Reunion', 'RE', '262', '留尼旺岛,Reunion');
INSERT INTO `tw_area_code` VALUES ('165', '罗马尼亚', 'Romania', 'RO', '40', '罗马尼亚,Romania');
INSERT INTO `tw_area_code` VALUES ('166', '俄罗斯', 'Russia', 'RU', '7', '俄罗斯,Russia');
INSERT INTO `tw_area_code` VALUES ('167', '卢旺达', 'Rwanda', 'RW', '250', '卢旺达,Rwanda');
INSERT INTO `tw_area_code` VALUES ('168', '圣赫勒拿岛', 'Saint Helena', 'SH', '290', '圣赫勒拿岛,Saint Helena');
INSERT INTO `tw_area_code` VALUES ('169', '圣基茨和尼维斯', 'Saint Kitts and Nevis', 'KN', '1869', '圣基茨和尼维斯,Saint Kitts and Nevis');
INSERT INTO `tw_area_code` VALUES ('170', '圣卢西亚', 'Saint Lucia', 'LC', '1758', '圣卢西亚,Saint Lucia');
INSERT INTO `tw_area_code` VALUES ('171', '圣马丁（荷兰一部分）', 'Saint Maarten (Dutch part)', 'SX', '1721', '圣马丁（荷兰一部分）,Saint Maarten (Dutch part)');
INSERT INTO `tw_area_code` VALUES ('172', '圣皮埃尔和密克隆', 'Saint Pierre And Miquelon', 'PM', '508', '圣皮埃尔和密克隆,Saint Pierre And Miquelon');
INSERT INTO `tw_area_code` VALUES ('173', '萨摩亚群岛', 'Samoa', 'WS', '685', '萨摩亚群岛,Samoa');
INSERT INTO `tw_area_code` VALUES ('174', '圣马力诺', 'San Marino', 'SM', '378', '圣马力诺,San Marino');
INSERT INTO `tw_area_code` VALUES ('175', '圣多美和普林西比', 'Sao Tome And Principe', 'ST', '239', '圣多美和普林西比,Sao Tome And Principe');
INSERT INTO `tw_area_code` VALUES ('176', '沙特阿拉伯', 'Saudi Arabia', 'SA', '966', '沙特阿拉伯,Saudi Arabia');
INSERT INTO `tw_area_code` VALUES ('177', '塞内加尔', 'Senegal', 'SN', '221', '塞内加尔,Senegal');
INSERT INTO `tw_area_code` VALUES ('178', '塞尔维亚共和国', 'Serbia', 'SRB', '381', '塞尔维亚共和国,Serbia');
INSERT INTO `tw_area_code` VALUES ('179', '塞舌尔', 'Seychelles', 'SC', '248', '塞舌尔,Seychelles');
INSERT INTO `tw_area_code` VALUES ('180', '塞拉利昂', 'Sierra Leone', 'SL', '232', '塞拉利昂,Sierra Leone');
INSERT INTO `tw_area_code` VALUES ('181', '新加坡', 'Singapore', 'SG', '65', '新加坡,Singapore');
INSERT INTO `tw_area_code` VALUES ('182', '斯洛伐克', 'Slovakia', 'SK', '421', '斯洛伐克,Slovakia');
INSERT INTO `tw_area_code` VALUES ('183', '斯洛文尼亚', 'Slovenia', 'SI', '386', '斯洛文尼亚,Slovenia');
INSERT INTO `tw_area_code` VALUES ('184', '索马里', 'Somalia', 'SO', '252', '索马里,Somalia');
INSERT INTO `tw_area_code` VALUES ('185', '南非', 'South Africa', 'ZA', '27', '南非,South Africa');
INSERT INTO `tw_area_code` VALUES ('186', '韩国', 'South Korea', 'KR', '82', '韩国,South Korea');
INSERT INTO `tw_area_code` VALUES ('187', '西班牙', 'Spain', 'ES', '34', '西班牙,Spain');
INSERT INTO `tw_area_code` VALUES ('188', '斯里兰卡', 'Sri Lanka', 'LK', '94', '斯里兰卡,Sri Lanka');
INSERT INTO `tw_area_code` VALUES ('189', '苏丹', 'Sudan', 'SD', '249', '苏丹,Sudan');
INSERT INTO `tw_area_code` VALUES ('190', '苏里南', 'Suriname', 'SR', '597', '苏里南,Suriname');
INSERT INTO `tw_area_code` VALUES ('191', '斯威士兰', 'Swaziland', 'SZ', '268', '斯威士兰,Swaziland');
INSERT INTO `tw_area_code` VALUES ('192', '瑞典', 'Sweden', 'SE', '46', '瑞典,Sweden');
INSERT INTO `tw_area_code` VALUES ('193', '瑞士', 'Switzerland', 'CH', '41', '瑞士,Switzerland');
INSERT INTO `tw_area_code` VALUES ('194', '叙利亚', 'Syria', 'SY', '963', '叙利亚,Syria');
INSERT INTO `tw_area_code` VALUES ('195', '台湾', 'Taiwan', 'TW', '886', '台湾,Taiwan');
INSERT INTO `tw_area_code` VALUES ('196', '塔吉克斯坦', 'Tajikistan', 'TJ', '992', '塔吉克斯坦,Tajikistan');
INSERT INTO `tw_area_code` VALUES ('197', '坦桑尼亚', 'Tanzania', 'TZ', '255', '坦桑尼亚,Tanzania');
INSERT INTO `tw_area_code` VALUES ('198', '泰国', 'Thailand', 'TH', '66', '泰国,Thailand');
INSERT INTO `tw_area_code` VALUES ('199', '刚果民主共和国', 'The Democratic Republic Of Congo', 'CD', '243', '刚果民主共和国,The Democratic Republic Of Congo');
INSERT INTO `tw_area_code` VALUES ('200', '东帝汶', 'Timor-Leste', 'TL', '1670', '东帝汶,Timor-Leste');
INSERT INTO `tw_area_code` VALUES ('201', '多哥', 'Togo', 'TG', '228', '多哥,Togo');
INSERT INTO `tw_area_code` VALUES ('202', '托克劳群岛', 'Tokelau', 'TK', '690', '托克劳群岛,Tokelau');
INSERT INTO `tw_area_code` VALUES ('203', '汤加', 'Tonga', 'TO', '676', '汤加,Tonga');
INSERT INTO `tw_area_code` VALUES ('204', '特立尼达和多巴哥', 'Trinidad and Tobago', 'TT', '1868', '特立尼达和多巴哥,Trinidad and Tobago');
INSERT INTO `tw_area_code` VALUES ('205', '突尼斯', 'Tunisia', 'TN', '216', '突尼斯,Tunisia');
INSERT INTO `tw_area_code` VALUES ('206', '土耳其', 'Turkey', 'TR', '90', '土耳其,Turkey');
INSERT INTO `tw_area_code` VALUES ('207', '土库曼斯坦', 'Turkmenistan', 'TM', '993', '土库曼斯坦,Turkmenistan');
INSERT INTO `tw_area_code` VALUES ('208', '特克斯和凯科斯群岛', 'Turks and Caicos Islands', 'TC', '1649', '特克斯和凯科斯群岛,Turks and Caicos Islands');
INSERT INTO `tw_area_code` VALUES ('209', '图瓦卢', 'Tuvalu', 'TV', '688', '图瓦卢,Tuvalu');
INSERT INTO `tw_area_code` VALUES ('210', '乌干达', 'Uganda', 'UG', '256', '乌干达,Uganda');
INSERT INTO `tw_area_code` VALUES ('211', '乌克兰', 'Ukraine', 'UA', '380', '乌克兰,Ukraine');
INSERT INTO `tw_area_code` VALUES ('212', '阿拉伯联合酋长国', 'United Arab Emirates', 'AE', '971', '阿拉伯联合酋长国,United Arab Emirates');
INSERT INTO `tw_area_code` VALUES ('213', '英国', 'United Kingdom', 'UK', '44', '英国,United Kingdom');
INSERT INTO `tw_area_code` VALUES ('214', '美国', 'United States', 'US', '1', '美国,United States');
INSERT INTO `tw_area_code` VALUES ('215', '乌拉圭', 'Uruguay', 'UY', '598', '乌拉圭,Uruguay');
INSERT INTO `tw_area_code` VALUES ('216', '乌兹别克斯坦', 'Uzbekistan', 'UZ', '998', '乌兹别克斯坦,Uzbekistan');
INSERT INTO `tw_area_code` VALUES ('217', '瓦努阿图', 'Vanuatu', 'VU', '678', '瓦努阿图,Vanuatu');
INSERT INTO `tw_area_code` VALUES ('218', '梵蒂冈', 'Vatican', 'VA', '379', '梵蒂冈,Vatican');
INSERT INTO `tw_area_code` VALUES ('219', '委内瑞拉', 'Venezuela', 'VE', '58', '委内瑞拉,Venezuela');
INSERT INTO `tw_area_code` VALUES ('220', '越南', 'Vietnam', 'VN', '84', '越南,Vietnam');
INSERT INTO `tw_area_code` VALUES ('221', '维尔京群岛 英国', 'Virgin Islands, British', 'VG', '0', '维尔京群岛 英国,Virgin Islands');
INSERT INTO `tw_area_code` VALUES ('222', '维尔京群岛 美国', 'Virgin Islands, U.S.', 'VI', '1340', '维尔京群岛 美国,Virgin Islands');
INSERT INTO `tw_area_code` VALUES ('223', '沃利斯和富图纳群岛', 'Wallis And Futuna', 'WF', '681', '沃利斯和富图纳群岛,');
INSERT INTO `tw_area_code` VALUES ('224', '也门', 'Yemen', 'YE', '967', '也门,Yemen');
INSERT INTO `tw_area_code` VALUES ('225', '赞比亚', 'Zambia', 'ZM', '260', '赞比亚,Zambia');
INSERT INTO `tw_area_code` VALUES ('226', '津巴布韦', 'Zimbabwe', 'ZW', '263', '津巴布韦,Zimbabwe');
INSERT INTO `tw_area_code` VALUES ('227', '其他', 'Other', 'QT', '0', '其他');

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
-- Records of tw_article
-- ----------------------------
INSERT INTO `tw_article` VALUES ('1', '最新公告测试一', '<p>最新公告测试一</p>', '2', 'notice', '0', '1', '1', '1', '1512538973', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('2', '最新公告测试二', '<p>最新公告测试二最新公告测试二最新公告测试二最新公告测试二最新公告测试二最新公告测试二最新公告测试二最新公告测试二最新公告测试二</p>', '2', 'notice', '0', '1', '1', '2', '1512539007', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('3', '常见问题测试一常见问题测试一常见问题测试一', '<p>常见问题测试一常见问题测试一常见问题测试一常见问题测试一常见问题测试一常见问题测试一<br/></p>', '2', 'newuser', '0', '1', '1', '1', '1512539659', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('4', '常见问题测试二', '<p>常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二常见问题测试二</p>', '2', 'newuser', '0', '1', '1', '2', '1512539686', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('5', '我们的介绍', '<p>我们的介绍</p>', '2', 'gongsi', '0', '1', '1', '1', '1512540077', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('6', '我们的服务', '<p>我们的服务<br/></p>', '2', 'gongsi', '0', '1', '1', '2', '1512540098', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('7', '我们的优势', '<p>我们的优势</p>', '2', 'gongsi', '0', '1', '1', '3', '1512540150', '1512489600', '1', '');
INSERT INTO `tw_article` VALUES ('10', '用户注册协议', '<p>用户注册协议</p>', '2', 'gongsi', '0', '1', '1', '1', '1512546711', '1512489600', '1', '');

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
-- Records of tw_article_type
-- ----------------------------
INSERT INTO `tw_article_type` VALUES ('30', 'notice', '最新公告', '', '1', '0', '', '<p>系统内测公告</p>', '1', '1494950400', '1494950400', '1');
INSERT INTO `tw_article_type` VALUES ('35', 'newuser', '常见问题', '', '1', '1', '', '', '1', '1506787200', '1506787200', '1');
INSERT INTO `tw_article_type` VALUES ('37', 'gongsi', '关于我们', '', '0', '1', '', '', '2', '1508860800', '1508860800', '1');
INSERT INTO `tw_article_type` VALUES ('38', 'falv', '法律法规', '', '0', '1', '', '', '3', '1508909371', '1508909373', '1');
INSERT INTO `tw_article_type` VALUES ('39', 'zhengce', '国家政策', '', '0', '1', 'falv', '', '1', '1512489600', '1512489600', '1');
INSERT INTO `tw_article_type` VALUES ('40', 'law', '法律条文', '', '1', '1', 'falv', '', '2', '1512489600', '1512489600', '1');

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
-- Records of tw_auth_group
-- ----------------------------
INSERT INTO `tw_auth_group` VALUES ('1', 'admin', '1', '资讯管理员', '拥有网站文章资讯相关权限', '-1', '424,426,431,441,446,453,461,475,501,502,503,508,509,510,515,516');
INSERT INTO `tw_auth_group` VALUES ('2', 'admin', '1', '财务管理组', '拥有网站资金相关的权限', '-1', '431');
INSERT INTO `tw_auth_group` VALUES ('4', 'admin', '1', '资讯管理员', '拥有网站文章资讯相关权限11', '-1', '');
INSERT INTO `tw_auth_group` VALUES ('11', 'admin', '1', 'superadmin', '超级管理员', '1', '');
INSERT INTO `tw_auth_group` VALUES ('12', 'admin', '1', 'caiwu', '财务管理', '1', '1714,1715,1717,1718,1719,1721,1722,1723,1725,1727,1729,1731,1732,1733,1737,1738,1740,1743,1746,1747,1748,1749,1750,1751,1752,1753,1754,1755,1756,1757,1758,1759,1760,1761,1764,1765,1766,1767,1768,1769,1770,1771,1772,1775,1776,1777,1778,1779,1780,1783,1784,1785,1786,1787,1788,1789,1792,1793,1800,1802,1806,1808,1809,1810,1811,1814,1816,1817,1819,1820,1821,1822,1838,1839,1843,1845,1846,1861,1862,1863,1864,1865,1866,1867,1868,1869,1873,1874,1876,1877,1878,1879,1880,1881,1882,1883,1884,1885,1886,1887,1888,1889,1890,1891,1892,1893,1894,1895,1896,1897,1898,1899,1900,1903,1904,1905,1906,1908,1909,1910,1911,1912,1913,1914,1915,1916,1917,1918,1919');
INSERT INTO `tw_auth_group` VALUES ('14', 'admin', '1', 'neirong', '内容管理', '1', '');

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
-- Records of tw_auth_group_access
-- ----------------------------
INSERT INTO `tw_auth_group_access` VALUES ('3', '2');
INSERT INTO `tw_auth_group_access` VALUES ('1', '11');
INSERT INTO `tw_auth_group_access` VALUES ('2', '12');
INSERT INTO `tw_auth_group_access` VALUES ('4', '12');
INSERT INTO `tw_auth_group_access` VALUES ('5', '14');

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
-- Records of tw_auth_rule
-- ----------------------------
INSERT INTO `tw_auth_rule` VALUES ('425', 'admin', '1', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('427', 'admin', '1', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('428', 'admin', '1', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('429', 'admin', '1', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('430', 'admin', '1', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('432', 'admin', '2', 'Admin/Article/mydocument', '内容', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('437', 'admin', '1', 'Admin/Trade/config', '交易配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('455', 'admin', '1', 'Admin/Issue/config', '认购配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('457', 'admin', '1', 'Admin/Index/database/type/export', '数据备份', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('461', 'admin', '1', 'Admin/Article/chat', '聊天列表', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('464', 'admin', '1', 'Admin/Index/database/type/import', '数据还原', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('471', 'admin', '1', 'Admin/Mytx/config', '提现配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('472', 'admin', '2', 'Admin/Mytx/index', '提现', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('477', 'admin', '1', 'Admin/User/myzr', '转入虚拟币', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('479', 'admin', '1', 'Admin/User/myzc', '转出虚拟币', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('482', 'admin', '2', 'Admin/ExtA/index', '扩展', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('488', 'admin', '1', 'Admin/Auth_manager/createGroup', '新增用户组', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('499', 'admin', '1', 'Admin/ExtA/index', '扩展管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('509', 'admin', '1', 'Admin/Article/adver_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('510', 'admin', '1', 'Admin/Article/adver_status', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('513', 'admin', '1', 'Admin/Issue/index_edit', '认购编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('514', 'admin', '1', 'Admin/Issue/index_status', '认购修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('515', 'admin', '1', 'Admin/Article/chat_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('516', 'admin', '1', 'Admin/Article/chat_status', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('517', 'admin', '1', 'Admin/User/coin_edit', 'coin修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('519', 'admin', '1', 'Admin/Mycz/type_status', '状态修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('520', 'admin', '1', 'Admin/Issue/log_status', '认购状态', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('521', 'admin', '1', 'Admin/Issue/log_jiedong', '认购解冻', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('522', 'admin', '1', 'Admin/Tools/database/type/export', '数据备份', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('525', 'admin', '1', 'Admin/Config/coin_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('526', 'admin', '1', 'Admin/Config/coin_add', '编辑币种', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('527', 'admin', '1', 'Admin/Config/coin_status', '状态修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('528', 'admin', '1', 'Admin/Config/market_edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('530', 'admin', '1', 'Admin/Tools/database/type/import', '数据还原', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('541', 'admin', '2', 'Admin/Trade/config', '交易', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('569', 'admin', '1', 'Admin/ADVERstatus', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('570', 'admin', '1', 'Admin/Tradelog/index', '交易记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('585', 'admin', '1', 'Admin/Config/mycz', '充值配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('590', 'admin', '1', 'Admin/Mycztype/index', '充值类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('600', 'admin', '1', 'Admin/Usergoods/index', '用户联系地址', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1379', 'admin', '1', 'Admin/Bazaar/index', '集市管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1405', 'admin', '1', 'Admin/Bazaar/config', '集市配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1425', 'admin', '1', 'Admin/Bazaar/log', '集市记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1451', 'admin', '1', 'Admin/Bazaar/invit', '集市推广', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1519', 'admin', '2', 'Admin/Finance/index', '财务', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1575', 'admin', '1', 'Admin/Shop/index', '商品管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1576', 'admin', '1', 'Admin/Issue/index', '认购管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1577', 'admin', '1', 'Admin/Issue/log', '认购记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1578', 'admin', '1', 'Admin/Huafei/index', '充值记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1579', 'admin', '1', 'Admin/Huafei/config', '充值配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1580', 'admin', '1', 'Admin/Vote/index', '投票记录', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1581', 'admin', '1', 'Admin/Vote/type', '投票类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1582', 'admin', '1', 'Admin/Money/index', '理财管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1599', 'admin', '1', 'Admin/Config/moble', '短信配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1606', 'admin', '1', 'Admin/Shop/config', '商城配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1607', 'admin', '1', 'Admin/Money/log', '理财日志', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1623', 'admin', '1', 'Admin/Shop/type', '商品类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1625', 'admin', '1', 'Admin/Huafei/type', '充值金额', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1626', 'admin', '1', 'Admin/Money/fee', '理财明细', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1701', 'admin', '1', 'Admin/AuthManager/createGroup', '新增用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1702', 'admin', '1', 'Admin/AuthManager/editgroup', '编辑用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1703', 'admin', '1', 'Admin/AuthManager/writeGroup', '更新用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1704', 'admin', '1', 'Admin/AuthManager/changeStatus', '改变状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1705', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1706', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1707', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1708', 'admin', '1', 'Admin/AuthManager/tree', '成员列表授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1709', 'admin', '1', 'Admin/AuthManager/group', '用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1710', 'admin', '1', 'Admin/AuthManager/addToGroup', '添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1711', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '用户组移除', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1712', 'admin', '1', 'Admin/AuthManager/addToCategory', '分类添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1713', 'admin', '1', 'Admin/AuthManager/addToModel', '模型添加到用户组', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1714', 'admin', '1', 'Admin/Trade/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1715', 'admin', '1', 'Admin/Trade/chexiao', '撤销挂单', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1716', 'admin', '1', 'Admin/Shop/images', '图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1717', 'admin', '1', 'Admin/Login/index', '用户登录', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1718', 'admin', '1', 'Admin/Login/loginout', '用户退出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1719', 'admin', '1', 'Admin/User/setpwd', '修改管理员密码', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1721', 'admin', '2', 'Admin/Index/index', '系统', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1722', 'admin', '2', 'Admin/Article/index', '内容', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1723', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1724', 'admin', '2', 'Admin/Finance/mycz', '财务', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1725', 'admin', '2', 'Admin/Trade/index', '交易', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1726', 'admin', '2', 'Admin/Game/index', '应用', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1727', 'admin', '2', 'Admin/Config/index', '设置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1728', 'admin', '2', 'Admin/Operate/index', '运营', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1729', 'admin', '2', 'Admin/Tools/index', '工具', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1730', 'admin', '2', 'Admin/Cloud/index', '扩展', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1731', 'admin', '1', 'Admin/Index/index', '系统概览', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1732', 'admin', '1', 'Admin/Article/index', '文章管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1733', 'admin', '1', 'Admin/Article/edit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1734', 'admin', '1', 'Admin/Text/index', '提示文字', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1735', 'admin', '1', 'Admin/Text/edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1736', 'admin', '1', 'Admin/Text/status', '修改', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1737', 'admin', '1', 'Admin/User/index', '用户管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1738', 'admin', '1', 'Admin/User/config', '用户配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1739', 'admin', '1', 'Admin/Finance/index', '财务明细', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1740', 'admin', '1', 'Admin/Finance/myczTypeEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1742', 'admin', '1', 'Admin/Finance/config', '配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1743', 'admin', '1', 'Admin/Tools/index', '清理缓存', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1744', 'admin', '1', 'Admin/Finance/type', '类型', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1745', 'admin', '1', 'Admin/Finance/type_status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1746', 'admin', '1', 'Admin/User/edit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1747', 'admin', '1', 'Admin/User/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1748', 'admin', '1', 'Admin/User/adminEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1749', 'admin', '1', 'Admin/User/adminStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1750', 'admin', '1', 'Admin/User/authEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1751', 'admin', '1', 'Admin/User/authStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1752', 'admin', '1', 'Admin/User/authStart', '重新初始化权限', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1753', 'admin', '1', 'Admin/User/logEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1754', 'admin', '1', 'Admin/User/logStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1755', 'admin', '1', 'Admin/User/qianbaoEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1756', 'admin', '1', 'Admin/Trade/index', '广告管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1757', 'admin', '1', 'Admin/User/qianbaoStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1758', 'admin', '1', 'Admin/User/bankEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1759', 'admin', '1', 'Admin/User/bankStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1760', 'admin', '1', 'Admin/User/coinEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1761', 'admin', '1', 'Admin/User/coinLog', '财产统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1762', 'admin', '1', 'Admin/User/goodsEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1763', 'admin', '1', 'Admin/User/goodsStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1764', 'admin', '1', 'Admin/Article/typeEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1765', 'admin', '1', 'Admin/Article/youqingEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1766', 'admin', '1', 'Admin/Config/index', '基本配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1767', 'admin', '1', 'Admin/Article/adverEdit', '编辑添加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1768', 'admin', '1', 'Admin/User/authAccess', '访问授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1769', 'admin', '1', 'Admin/User/authAccessUp', '访问授权修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1770', 'admin', '1', 'Admin/User/authUser', '成员授权', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1771', 'admin', '1', 'Admin/User/authUserAdd', '成员授权增加', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1772', 'admin', '1', 'Admin/User/authUserRemove', '成员授权解除', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1775', 'admin', '1', 'AdminUser/detail', '后台用户详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1776', 'admin', '1', 'AdminUser/status', '后台用户状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1777', 'admin', '1', 'AdminUser/add', '后台用户新增', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1778', 'admin', '1', 'AdminUser/edit', '后台用户编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1779', 'admin', '1', 'Admin/Articletype/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1780', 'admin', '1', 'Admin/Article/images', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1781', 'admin', '1', 'Admin/Adver/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1782', 'admin', '1', 'Admin/Adver/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1783', 'admin', '1', 'Admin/Article/type', '文章类型', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1784', 'admin', '1', 'Admin/User/index_edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1785', 'admin', '1', 'Admin/User/index_status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1786', 'admin', '1', 'Admin/Finance/mycz', '人民币充值', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1787', 'admin', '1', 'Admin/Finance/myczTypeStatus', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1788', 'admin', '1', 'Admin/Finance/myczTypeImage', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1789', 'admin', '1', 'Admin/Finance/mytxStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1792', 'admin', '1', 'Admin/User/admin', '管理员管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1793', 'admin', '1', 'Admin/Trade/log', '成交记录', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1796', 'admin', '1', 'Admin/Invit/config', '推广配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1798', 'admin', '1', 'Admin/Link/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1799', 'admin', '1', 'Admin/Link/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1800', 'admin', '1', 'Admin/Index/coin', '币种统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1801', 'admin', '1', 'Admin/Cloud/update', '自动升级', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1802', 'admin', '1', 'Admin/Config/mobile', '短信配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1804', 'admin', '1', 'Admin/Chat/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1805', 'admin', '1', 'Admin/Chat/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1806', 'admin', '1', 'Admin/Article/adver', '广告管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1807', 'admin', '1', 'Admin/Trade/chat', '交易聊天', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1808', 'admin', '1', 'Admin/Finance/myczType', '人民币充值方式', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1809', 'admin', '1', 'Admin/Usercoin/edit', '财产修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1810', 'admin', '1', 'Admin/Finance/mytxExcel', '导出选中', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1811', 'admin', '1', 'Admin/User/auth', '权限列表', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1812', 'admin', '1', 'Admin/Mycz/status', '修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1813', 'admin', '1', 'Admin/Mycztype/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1814', 'admin', '1', 'Admin/Config/contact', '客服配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1816', 'admin', '1', 'Admin/Tools/queue', '服务器队列', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1817', 'admin', '1', 'Admin/Tools/qianbao', '钱包检查', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1818', 'admin', '1', 'Admin/Cloud/game', '应用管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1819', 'admin', '1', 'Admin/Article/youqing', '友情链接', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1820', 'admin', '1', 'Admin/User/log', '登录日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1821', 'admin', '1', 'Admin/Finance/mytx', '人民币提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1822', 'admin', '1', 'Admin/Finance/mytxChuli', '正在处理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1823', 'admin', '1', 'Admin/Config/bank', '银行配置', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1824', 'admin', '1', 'Admin/Config/bank_edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1825', 'admin', '1', 'Admin/Coin/edit', '编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1826', 'admin', '1', 'Admin/Coin/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1828', 'admin', '1', 'Admin/Config/market_add', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1838', 'admin', '1', 'Admin/Config/coin', '币种配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1839', 'admin', '1', 'Admin/User/detail', '用户详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1843', 'admin', '1', 'Admin/User/qianbao', '用户钱包', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1845', 'admin', '1', 'Admin/Finance/mytxConfig', '人民币提现配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1846', 'admin', '1', 'Admin/Finance/mytxChexiao', '撤销提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1847', 'admin', '1', 'Admin/Mytx/status', '状态修改', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1848', 'admin', '1', 'Admin/Mytx/excel', '取消', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1849', 'admin', '1', 'Admin/Mytx/exportExcel', '导入excel', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1850', 'admin', '1', 'Admin/Menu/index', '菜单管理', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1851', 'admin', '1', 'Admin/Menu/sort', '排序', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1852', 'admin', '1', 'Admin/Menu/add', '添加', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1853', 'admin', '1', 'Admin/Menu/edit', '编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1854', 'admin', '1', 'Admin/Cloud/kefu', '客服代码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1855', 'admin', '1', 'Admin/Menu/del', '删除', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1856', 'admin', '1', 'Admin/Cloud/kefuUp', '使用', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1857', 'admin', '1', 'Admin/Menu/toogleHide', '是否隐藏', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1858', 'admin', '1', 'Admin/Menu/toogleDev', '是否开发', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1859', 'admin', '1', 'Admin/Menu/importFile', '导入文件', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1860', 'admin', '1', 'Admin/Menu/import', '导入', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1861', 'admin', '1', 'Admin/Config/text', '提示文字', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1862', 'admin', '1', 'Admin/User/bank', '提现地址', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1863', 'admin', '1', 'Admin/Trade/invit', '交易推荐', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1864', 'admin', '1', 'Admin/Finance/myzr', '虚拟币转入', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1865', 'admin', '1', 'Admin/Finance/mytxQueren', '确认提现', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1866', 'admin', '1', 'Admin/Finance/myzcQueren', '确认转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1867', 'admin', '1', 'Admin/Config/qita', '其他配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1868', 'admin', '1', 'Admin/User/coin', '用户财产', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1869', 'admin', '1', 'Admin/Finance/myzc', '虚拟币转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1870', 'admin', '1', 'Admin/Verify/code', '图形验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1871', 'admin', '1', 'Admin/Verify/mobile', '手机验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1872', 'admin', '1', 'Admin/Verify/email', '邮件验证码', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1873', 'admin', '1', 'Admin/Config/daohang', '导航配置', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1874', 'admin', '1', 'Admin/User/myzc_qr', '确认转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1875', 'admin', '1', 'Admin/User/amountlog', '资金变更日志', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1876', 'admin', '1', 'Admin/Article/status', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1877', 'admin', '1', 'Admin/Finance/myczStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1878', 'admin', '1', 'Admin/Finance/myczQueren', '确认到账', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1879', 'admin', '1', 'Admin/Article/typeStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1880', 'admin', '1', 'Admin/Article/youqingStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1881', 'admin', '1', 'Admin/Article/adverStatus', '修改状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1882', 'admin', '1', 'Admin/Article/adverImage', '上传图片', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1883', 'admin', '1', 'Admin/User/feedback', '用户反馈', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1884', 'admin', '1', 'Admin/Finance/myczExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1885', 'admin', '1', 'Admin/Tools/recoverzc', '恢复自动转出队列', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1886', 'admin', '1', 'Admin/Tools/chkzdzc', '查看自动转出队列状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1887', 'admin', '1', 'Admin/Finance/myzcBatch', '批量转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1888', 'admin', '1', 'Admin/Finance/myzcBatchLog', '批量转出错误日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1889', 'admin', '1', 'Admin/Trade/tradeExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1890', 'admin', '1', 'Admin/Trade/tradelogExcel', '导出excel', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1891', 'admin', '1', 'Admin/Finance/ethtransfer', '以太坊转账', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1892', 'admin', '1', 'Admin/User/nameauth', '实名审核', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1893', 'admin', '1', 'Admin/Trade/orderinfo', '订单详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1894', 'admin', '2', 'Admin/Finance/myzr', '财务', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1895', 'admin', '1', 'Admin/User/invittree', '推荐关系', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1896', 'admin', '1', 'Admin/Trade/orderlist', '订单管理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1897', 'admin', '1', 'Admin/Finance/tradePrize', '代理奖励', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1898', 'admin', '1', 'Admin/Finance/amountlog', '资金日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1899', 'admin', '1', 'Admin/Finance/incentive', '聊天记录', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1900', 'admin', '1', 'Admin/User/region', '用户地区', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1901', 'admin', '2', 'Admin/User/nameauthdetail', '查看详情', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1903', 'admin', '1', 'Admin/Config/daohangEdit', '添加导航', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1904', 'admin', '1', 'Admin/Config/coinEdit', '编辑币种', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1905', 'admin', '1', 'Admin/Finance/myzcSd', '人工转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1906', 'admin', '1', 'Admin/Finance/myzcCancel', '取消转出', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1907', 'admin', '1', 'Admin/Config/Status', '币状态编辑', '-1', '');
INSERT INTO `tw_auth_rule` VALUES ('1908', 'admin', '1', 'Admin/Config/coinStatus', '币状态编辑', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1909', 'admin', '1', 'Admin/Trade/chuli_ajax', '改订单状态', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1910', 'admin', '1', 'Admin/Trade/admindone', '处理问题单', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1911', 'admin', '1', 'Admin/Tools/delcache', '缓存清理', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1912', 'admin', '1', 'Admin/Config/contactEdit', '编辑客服', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1913', 'admin', '1', 'Admin/Config/daohangStatus', '编辑导航', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1914', 'admin', '1', 'Admin/Trade/adinfo', '广告详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1915', 'admin', '1', 'Admin/Trade/addel', '删除广告', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1916', 'admin', '1', 'Admin/User/financelog', '财产日志', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1917', 'admin', '1', 'Admin/User/nameauthdetail', '实名详情', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1918', 'admin', '1', 'Admin/Trade/analysis', '交易统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1919', 'admin', '1', 'Admin/Index/coinSet', '初始化币种统计', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1920', 'admin', '1', 'Admin/Goods/index', '商品', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1921', 'admin', '1', 'Admin/Goods/order', '订单', '1', '');
INSERT INTO `tw_auth_rule` VALUES ('1922', 'admin', '2', 'Admin/Goods/index', '比特购', '1', '');

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
-- Records of tw_btc
-- ----------------------------
INSERT INTO `tw_btc` VALUES ('1', '人民币', 'CNY', '40080.00781199', '1529907601');
INSERT INTO `tw_btc` VALUES ('2', '美元', 'USD', '6156.69000000', '1529907601');
INSERT INTO `tw_btc` VALUES ('3', '澳元', 'AUD', '8292.59784061', '1529907601');
INSERT INTO `tw_btc` VALUES ('4', '日元', 'JPY', '674096.29649906', '1529907601');
INSERT INTO `tw_btc` VALUES ('5', '韩币', 'KRW', '6873116.13326720', '1529907601');
INSERT INTO `tw_btc` VALUES ('6', '加元', 'CAD', '8183.03977288', '1529907601');
INSERT INTO `tw_btc` VALUES ('7', '法郎', 'CHF', '6079.36053401', '1529907601');
INSERT INTO `tw_btc` VALUES ('8', '卢比', 'INR', '419178.89361702', '1529907601');
INSERT INTO `tw_btc` VALUES ('9', '卢布', 'RUB', '388667.65569269', '1529907601');
INSERT INTO `tw_btc` VALUES ('10', '欧元', 'EUR', '5283.53328871', '1529907601');
INSERT INTO `tw_btc` VALUES ('11', '英镑', 'GBP', '4642.49411836', '1529907601');
INSERT INTO `tw_btc` VALUES ('12', '港币', 'HKD', '48312.01544305', '1529907601');
INSERT INTO `tw_btc` VALUES ('13', '巴西雷亚尔', 'BRL', '23306.75842958', '1529907601');
INSERT INTO `tw_btc` VALUES ('14', '印尼盾', 'IDR', '86652432.63936600', '1529907601');
INSERT INTO `tw_btc` VALUES ('15', '比索', 'MXN', '123669.05737911', '1529907601');
INSERT INTO `tw_btc` VALUES ('16', '台币', 'TWD', '187233.40205433', '1529907601');
INSERT INTO `tw_btc` VALUES ('17', '令吉', 'MYR', '24713.41133030', '1529907601');
INSERT INTO `tw_btc` VALUES ('18', '新币 ', 'SGD', '8392.60547543', '1529907601');

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
-- Records of tw_btc_log
-- ----------------------------
INSERT INTO `tw_btc_log` VALUES ('1', '18954215320', '77', '1530760115', '1', '0', '0.02446087', '买家下单减可用BTC，手续费0', '76', '1', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_btc_log` VALUES ('2', '18954215320', '77', '1530760115', '1', '1', '0.02446087', '买家下单加冻结BTC，手续费0', '76', '2', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_btc_log` VALUES ('3', '18954215320', '77', '1530760130', '1', '0', '0.02446087', '买家下单减可用BTC，手续费0', '76', '1', '1', '192.168.1.136', '比特币');
INSERT INTO `tw_btc_log` VALUES ('4', '18954215320', '77', '1530760130', '1', '1', '0.02446087', '买家下单加冻结BTC，手续费0', '76', '2', '1', '192.168.1.136', '比特币');

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
-- Records of tw_coin
-- ----------------------------
INSERT INTO `tw_coin` VALUES ('10', 'usdt', 'qbb', '泰达币', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/pic/usdt.png', '0', '0', '0', '0', '1', '0', '47.75.149.37', '8332', 'abc', '123', '47.75.149.37', '8332', 'abc', '123', '0', '1', '6', '', '', '20.00000', '', '0.00100', '10000.00000', '1', '0.0001', 'tether', null, '', '', '', '0', '', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', 'btc', '0', '0.00', '0.00');
INSERT INTO `tw_coin` VALUES ('11', 'btc', 'qbb', '比特币', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/pic/bitcoin.png', '0', '0', '0', '0', '1', '', '47.75.185.122', '8332', 'abc', '123', '47.75.185.122', '8332', 'abc', '123', '0', '1', '6', '', '', '0.00010', '', '0.00100', '1000.00000', '1', '0.00001', 'Bitcoin', null, '', '', '', '', '', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', 'btc', '0', '0.01', '0.00');
INSERT INTO `tw_coin` VALUES ('12', 'eth', 'qbb', '以太坊', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/pic/eth.png', '0', '0', '0', '0', '1', '', '', '', '', '', '', '', '', '', '0', '1', '6', '', '', '0.00010', '', '0.00100', '10000.00000', '1', '0.00001', 'Ether', null, '', '', '', '', '', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', 'eth', '0', '0.01', '0.00');
INSERT INTO `tw_coin` VALUES ('14', 'hor', 'qbb', '时光链', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/pic/hor.png', '0', '0', '0', '0', '1', '', '', '', '', '', '', '', '', '', '0', '1', '6', '', '', '0.50000', '', '0.10000', '1000000.00000', '1', '0.0001', 'Hours Chain', null, '', '', '', '', '', '', '', '', '', '0', '', '', '', '0xd9dAC7b72472376b60b6aee9cfa2498ccCdCB2A7', '0', '', '', '', '', 'erc20', '0', '0.10', '0.00');

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
-- Records of tw_coin_json
-- ----------------------------
INSERT INTO `tw_coin_json` VALUES ('48', 'btc', '[8151.81599193,0,\"0.00000000\",null]', '', '0', '1510761599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('49', 'btc', '[8151.81599193,0,null,null]', '', '0', '1510847999', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('50', 'btc', '[8151.81599193,0,null,null]', '', '0', '1510934399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('52', 'eth', '[10141.04918466,0,\"0.00000000\",null]', '', '0', '1510761599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('53', 'eth', '[10141.04918466,0,null,null]', '', '0', '1510847999', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('54', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511020799', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('55', 'eth', '[10141.04918466,0,null,null]', '', '0', '1510934399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('56', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511107199', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('57', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511193599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('58', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511279999', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('59', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511366399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('60', 'eth', '[10141.04918466,0,null,null]', '', '0', '1511020799', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('61', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511452799', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('62', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511539199', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('63', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511625599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('64', 'btc', '[8151.81599193,0,null,null]', '', '0', '1511711999', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('65', 'eth', '[10141.04918466,0,null,null]', '', '0', '1511107199', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('66', 'btc', '[8151.81599193,0,null,null]', '', '0', '1512748799', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('67', 'btc', '[8310.66112744,0,null,null]', '', '0', '1515254399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('68', 'btc', '[8311.86109456,0,null,null]', '', '0', '1523289599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('69', 'btc', '[9.8298,0,null,null]', '', '0', '1523375999', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('70', 'btc', '[9.8298,0,null,null]', '', '0', '1523462399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('71', 'btc', '[8.8298,0,null,null]', '', '0', '1523548799', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('72', 'btc', '[8.8298,0,null,null]', '', '0', '1523635199', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('73', 'btc', '[8.8298,0,null,null]', '', '0', '1523721599', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('74', 'btc', '[8.8298,0,null,null]', '', '0', '1529942399', '0', '0');
INSERT INTO `tw_coin_json` VALUES ('75', 'btc', '[308.52969,0,null,null]', '', '0', '1531756799', '0', '0');

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
  `contact_weibo` text,
  `contact_tqq` text,
  `contact_qq` text,
  `contact_qqun` text,
  `contact_weixin` text,
  `contact_weixin_img` text,
  `contact_email` text,
  `contact_alipay` text,
  `contact_alipay_img` text,
  `contact_bank` text,
  `user_truename` text,
  `user_moble` text,
  `user_alipay` text,
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
-- Records of tw_config
-- ----------------------------
INSERT INTO `tw_config` VALUES ('1', '58f881562ea7b.png', '1', 'c', '', '', '0', '1', '', '', '点点OTC', '点点OTC', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/pic/logo.png', '58f881536618f.png', '点点OTC，BTC场外交易，OTC', '点点OTC，BTC场外交易，OTC', '1', '', '', '', '100', '', 'hyjf_cny', 'btc', 'becc', '', '1', '', '', '', '18253251582', 'info@dotstar.io', '1888888888', '888888', '', '', '56f98e6d70135.jpg', '83839140@qq.com', '83839140@qq.com', '56f98e6d7245d.jpg', '中国银行|中国农业银行', '2', '2', '2', '2', '&lt;span&gt;&lt;span&gt;会员您好,务必正确填写好自己的真实姓名和真实身份证号码.&lt;/span&gt;&lt;/span&gt;', '&lt;span&gt;会员您好,务必用自己的手机号码进行手机认证,认证以后可以用来接收验证码.&lt;/span&gt;', '&lt;span&gt;会员您好,务必正确填写支付宝 &amp;nbsp;真实姓名（与实名认证姓名相同）和支付宝账号,后期提现唯一依据.&lt;/span&gt;', '&lt;span&gt;会员您好,&lt;/span&gt;&lt;span&gt;&lt;span&gt;务必正确填写银行卡信息 提现唯一依据.&lt;/span&gt;&lt;span&gt;&lt;/span&gt;&lt;/span&gt;', '&lt;span&gt;自己以往操作和登录及登录地点的相关记录.&lt;/span&gt;', '&lt;span&gt;会员您好,修改登录密码以后请不要忘记.若不记得旧登录密码,请点击--&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;忘记密码&lt;/span&gt;', '&lt;span&gt;会员您好,修改交易密码以后请不要忘记.若不记得旧交易密码,请点击--&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;忘记密码&lt;/span&gt;', '100', '100000', '1', 'becc', '2.00', '0.5', '1', '10000000', '10', '&lt;span&gt;&lt;span&gt;你委托买入或者卖出成功交易后的记录.&lt;/span&gt;&lt;/span&gt;', '5', '24', '1', '100000', '100', '100000', '100', '理财首页', '理财记录', '理财类型', '1', '5', '3', '2', '安全便捷', '&lt;span&gt;&lt;span&gt;查看自己推广的好友,请点击&lt;/span&gt;&lt;span style=&quot;color:#EE33EE;&quot;&gt;“+”&lt;/span&gt;&lt;span&gt;,同时正确引导好友实名认证以及买卖,赚取推广收益和交易手续费.&lt;/span&gt;&lt;/span&gt;', '系统可靠', '银行级用户数据加密、动态身份验证多级风险识别控制，保障交易安全', '系统可靠', '账户多层加密，分布式服务器离线存储，即时隔离备份数据，确保安全', '快捷方便', '充值即时、提现迅速，每秒万单的高性能交易引擎，保证一切快捷方便', '服务专业', '热忱的客服工作人员和24小时的技术团队随时为您的账户安全保驾护航', '&lt;p&gt;\r\n	&lt;a href=&quot;/Article/index/type/aboutus.html&quot; target=&quot;_blank&quot;&gt;/Article/index/type/aboutus.html&lt;/a&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&amp;lt;a href=&quot;&lt;a href=&quot;/Article/index/type/aboutus.html&quot; target=&quot;_blank&quot;&gt;/Article/index/type/aboutus.html&lt;/a&gt;&quot;&amp;gt;关于我们&amp;lt;/a&amp;gt;\r\n&lt;/p&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;联系我们&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;资质证明&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;用户协议&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n|&lt;br /&gt;\r\n&amp;lt;a href=&quot;/Article/index/type/aboutus.html&quot;&amp;gt;法律声明&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;p style=&quot;margin-top: 5px;text-align: center;&quot;&amp;gt;&lt;br /&gt;\r\nCopyright &amp;copy; 2016&lt;br /&gt;\r\n&amp;lt;a href=&quot;/&quot;&amp;gt;{$C[\'web_name\']}交易平台 &amp;lt;/a&amp;gt;&lt;br /&gt;\r\nAll Rights Reserved.&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.miibeian.gov.cn/&quot;&amp;gt;{$C[\'web_icp\']}&amp;lt;/a&amp;gt;{$C[\'web_cnzz\']|htmlspecialchars_decode}&lt;br /&gt;\r\n&lt;br /&gt;\r\n&amp;lt;/p&amp;gt;&lt;br /&gt;\r\n&amp;lt;p class=&quot;clear1&quot; id=&quot;ut646&quot; style=&quot;margin-top: 10px;text-align: center;&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://webscan.360.cn/index/checkwebsite/url/www.movesay.com&quot; target=&quot;_blank&quot;&amp;gt;&amp;lt;img border=&quot;0&quot; width=&quot;83&quot; height=&quot;31&quot; src=&quot;http://img.webscan.360.cn/status/pai/hash/a272bae5f02b1df25be2c1d9d0b251f7&quot;/&amp;gt;&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.szfw.org/&quot; target=&quot;_blank&quot; id=&quot;ut118&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_2.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.miibeian.gov.cn/&quot; target=&quot;_blank&quot; id=&quot;ut119&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_3.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;a href=&quot;http://www.cyberpolice.cn/&quot; target=&quot;_blank&quot; id=&quot;ut120&quot; class=&quot;margin10&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;img src=&quot;__UPLOAD__/footer/footer_4.png&quot;&amp;gt;&lt;br /&gt;\r\n&amp;lt;/a&amp;gt;&lt;br /&gt;\r\n&amp;lt;/p&amp;gt;&lt;br /&gt;', '', '', '', '', '12', '', '1467383018', '0', '', '/Upload/shop/574d7b987b55f.png', '1', 'a', '1', '0', '100000', '', '0.0000', '0', '0.50', '0.00', '3', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', 'sl242016', 'mjbHzsLo8ZC6orSjcY9uuTdwc4VAUztJFM');

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
-- Records of tw_daohang
-- ----------------------------
INSERT INTO `tw_daohang` VALUES ('1', 'finance', '钱包', '/Finance/index', '3', '0', '0', '1');
INSERT INTO `tw_daohang` VALUES ('4', 'article', '帮助中心', '/Article/index', '5', '0', '0', '1');
INSERT INTO `tw_daohang` VALUES ('9', 'trade', '场外交易', '/Trade/index', '1', '1512548016', '0', '1');
INSERT INTO `tw_daohang` VALUES ('10', 'newad', '发布广告', '/Newad/index', '2', '1512548058', '0', '1');

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

-- ----------------------------
-- Records of tw_ethapi
-- ----------------------------
INSERT INTO `tw_ethapi` VALUES ('1', '68', '1', 'eth', '5837258', '1529715640', '0x1daee8f090a8311d19954d5aab556bfdcf38ce25c7ae8fc9076a1cb8f118bd4b', '175', '0x320f69c381037c1bb481bbd7437edfdb17ed4a131bfa5406aeaadc384f715954', '65', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '0x76d71eb19d44102088e8642a6c56493f6e29266d', '1000000000000000', '0', '1', null, '616', null, null, '0', '0', null);
INSERT INTO `tw_ethapi` VALUES ('2', '68', '1', 'hor', '5837707', '1529722590', '0x8fa9918da48e4e4879e5f5a418b566dcdfbc812d01d3263563fe1809faedf058', '180', '0x19abd9329c893f28bf72fe4e55321575e60905bf388f6cbf7d7244f26a00f836', '0', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', '0x76d71eb19d44102088e8642a6c56493f6e29266d', '850000000000000000', '0', '0', '0xd9dac7b72472376b60b6aee9cfa2498cccdcb2a7', '685', 'HOR', '18', '0', '0', null);

-- ----------------------------
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
-- Records of tw_ethzr
-- ----------------------------
INSERT INTO `tw_ethzr` VALUES ('1', '0xea674fdde714fd979de3edf0f56aa9716b898ec8', '0x9077358c42086ff98fc1f5d8e0886c76aa4e7a40', '0.05002000', '1529716681', '1', '1', '509095', '0xc7b72932183d50290c088cfbb17a7b3c4a74edd755af4c8aca4e4e1eaf3217aa', '0x0baeb51665852963a1d0fd3fcf7fa44c6eb5446efa2ecf802b79a6a6058c1393');

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
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

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
-- Records of tw_finance_log
-- ----------------------------
INSERT INTO `tw_finance_log` VALUES ('1', '18253251582', 'admin', '1525946847', '1', '1000.00000000', '', '3', '10', '0.00000000', '1000.00000000', '68', '1', '161.202.48.247', '0');
INSERT INTO `tw_finance_log` VALUES ('2', '17554227203', 'admin', '1525946853', '1', '10000.00000000', '', '3', '10', '0.00000000', '10000.00000000', '69', '1', '161.202.48.247', '0');
INSERT INTO `tw_finance_log` VALUES ('3', '18610550287', 'admin', '1526263378', '1', '100.00000000', '', '3', '10', '0.00000000', '100.00000000', '73', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('4', '18610550287', '18610550287', '1526263719', '0', '10.00000000', '', '6', '10', '100.00000000', '90.00000000', '73', '73', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('5', '18610550287', '18610550287', '1526265248', '0', '5.00000000', '', '6', '10', '90.00000000', '85.00000000', '73', '73', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('6', '18610550287', '18610550287', '1526265670', '0', '1.00000000', '', '6', '10', '85.00000000', '84.00000000', '73', '73', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('7', '18610550287', 'admin', '1526266977', '1', '1.00000000', '', '27', '10', '85.00000000', '86.00000000', '73', '1', '127.0.0.1', '0');
INSERT INTO `tw_finance_log` VALUES ('8', '18610550287', '18610550287', '1526267010', '0', '5.00000000', '', '6', '10', '85.00000000', '80.00000000', '73', '73', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('9', '18610550287', '18610550287', '1526283036', '0', '1.00000000', '', '6', '10', '80.00000000', '79.00000000', '73', '73', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('10', '18253251582', 'admin', '1529634988', '1', '10.00000000', '', '3', '11', '0.00000000', '10.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('11', '18253251582', 'admin', '1529634988', '1', '50.00000000', '', '3', '12', '0.00000000', '50.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('12', '18253251582', 'admin', '1529634988', '1', '1000.00000000', '', '3', '14', '0.00000000', '1000.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('13', '18253251582', 'admin', '1529635001', '0', '85.00749626', '', '3', '10', '885.00749626', '800.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('14', '18253251582', '18253251582', '1529635746', '0', '10.00000000', '', '6', '10', '800.00000000', '790.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('15', 'admin', '18253251582', '1529635746', '1', '10.00000000', '', '7', '10', '0.00000000', '10.00000000', '1', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('18', '18253251582', '18253251582', '1529635989', '0', '21.00000000', '', '6', '10', '790.00000000', '769.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('19', 'admin', '18253251582', '1529635989', '1', '1.00000000', '', '7', '10', '10.00000000', '11.00000000', '1', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('20', '18253251582', '18253251582', '1529637417', '0', '30.00000000', '', '6', '10', '769.00000000', '739.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('21', '18253251582', '18253251582', '1529637594', '0', '1.00000000', '', '6', '11', '10.00000000', '9.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('22', '17554227203', '18253251582', '1529637594', '1', '1.00000000', '', '7', '11', '0.00000000', '1.00000000', '69', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('23', '18253251582', '18253251582', '1529637712', '0', '1.00000000', '', '6', '11', '9.00000000', '8.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('24', '17554227203', '18253251582', '1529637712', '1', '0.99980000', '', '7', '11', '1.00000000', '1.99980000', '69', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('25', '18253251582', '18253251582', '1529638211', '0', '1.00000000', '', '6', '11', '8.00000000', '7.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('26', '18253251582', '18253251582', '1529638374', '0', '1.00000000', '', '6', '12', '50.00000000', '49.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('27', '17554227203', '18253251582', '1529638374', '1', '1.00000000', '', '7', '12', '0.00000000', '1.00000000', '69', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('28', '18253251582', '18253251582', '1529638464', '0', '1.00000000', '', '6', '12', '49.00000000', '48.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('29', '17554227203', '18253251582', '1529638464', '1', '0.99980000', '', '7', '12', '1.00000000', '1.99980000', '69', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('30', '17554227203', '17554227203', '1529638537', '0', '1.00000000', '', '6', '12', '1.99980000', '0.99980000', '69', '69', '161.202.48.247', '1');
INSERT INTO `tw_finance_log` VALUES ('31', '18253251582', '18253251582', '1529638611', '0', '1.00000000', '', '6', '14', '1000.00000000', '999.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('32', '18253251582', '18253251582', '1529639008', '0', '1.00000000', '', '6', '14', '999.00000000', '998.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('33', '18253251582', '18253251582', '1529639209', '0', '1.00000000', '', '6', '14', '998.00000000', '997.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('34', '18253251582', 'admin', '1529648039', '1', '1.00000000', '', '27', '11', '8.00000000', '9.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('35', '18253251582', '18253251582', '1529655781', '0', '30.00000000', '', '6', '10', '739.00000000', '709.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('36', '18253251582', '18253251582', '1529655940', '0', '25.00000000', '', '6', '10', '709.00000000', '684.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('37', '18253251582', '18253251582', '1529656036', '0', '0.10000000', '', '6', '11', '8.00000000', '7.90000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('38', '18253251582', '18253251582', '1529656117', '0', '0.00200000', '', '6', '12', '48.00000000', '47.99800000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('39', '18253251582', '18253251582', '1529656160', '0', '1.00000000', '', '6', '14', '997.00000000', '996.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('40', '18253251582', '18253251582', '1529656218', '0', '1.00000000', '', '6', '14', '996.00000000', '995.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('41', '18253251582', '18253251582', '1529661633', '0', '94.00000000', '', '6', '10', '684.00000000', '590.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('42', '18253251582', '18253251582', '1529669668', '0', '90.00000000', '', '6', '10', '590.00000000', '500.00000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('43', '18253251582', 'admin', '1529670675', '1', '94.00000000', '', '27', '10', '594.00000000', '688.00000000', '68', '1', '60.209.33.43', '0');
INSERT INTO `tw_finance_log` VALUES ('44', '18253251582', '18253251582', '1529717207', '0', '0.00100000', '', '6', '12', '47.99900000', '47.99800000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('45', '18253251582', '18253251582', '1529724042', '0', '0.03000000', '', '6', '11', '7.90000000', '7.87000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('46', '18253251582', '18253251582', '1529724068', '0', '0.04000000', '', '6', '11', '7.87000000', '7.83000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('47', '18253251582', '18253251582', '1529752825', '0', '1.00000000', '', '6', '11', '7.83000000', '6.83000000', '68', '68', '60.209.33.43', '1');
INSERT INTO `tw_finance_log` VALUES ('48', '18954215320', 'admin', '1530501106', '1', '100.00000000', '', '3', '10', '0.00000000', '100.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('49', '18954215320', 'admin', '1530501106', '1', '100.00000000', '', '3', '11', '0.00000000', '100.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('50', '18954215320', 'admin', '1530501106', '1', '100.00000000', '', '3', '12', '0.00000000', '100.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('51', '18954215320', 'admin', '1530501106', '1', '100.00000000', '', '3', '14', '0.00000000', '100.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('52', '18954215320', 'admin', '1530501161', '1', '105.00000000', '', '3', '10', '0.00000000', '105.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('53', '18954215320', 'admin', '1530501264', '1', '100.00000000', '', '3', '10', '5.00000000', '105.00000000', '77', '1', '192.168.1.216', '0');
INSERT INTO `tw_finance_log` VALUES ('54', '18253251582', '18253251582', '1531123261', '0', '0.10000000', '', '6', '11', '6.83000000', '6.73000000', '68', '68', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('55', '17554227203', '17554227203', '1531123638', '0', '0.10000000', '', '6', '11', '1.99980000', '1.89980000', '69', '69', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('56', '18253251582', '17554227203', '1531123639', '1', '0.09989000', '', '7', '11', '6.73000000', '6.82989000', '68', '69', '127.0.0.1', '1');
INSERT INTO `tw_finance_log` VALUES ('57', '18253251582', '18253251582', '1531124316', '0', '0.20000000', '', '6', '11', '6.82989000', '6.62989000', '68', '68', '127.0.0.1', '1');

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

-- ----------------------------
-- Table structure for tw_market
-- ----------------------------
DROP TABLE IF EXISTS `tw_market`;
CREATE TABLE `tw_market` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `round` varchar(255) NOT NULL DEFAULT '',
  `fee_buy` varchar(255) NOT NULL DEFAULT '',
  `fee_sell` varchar(255) NOT NULL DEFAULT '',
  `buy_min` varchar(255) NOT NULL DEFAULT '',
  `buy_max` varchar(255) NOT NULL DEFAULT '',
  `sell_min` varchar(255) NOT NULL DEFAULT '',
  `sell_max` varchar(255) NOT NULL DEFAULT '',
  `trade_min` varchar(255) NOT NULL DEFAULT '',
  `trade_max` varchar(255) NOT NULL DEFAULT '',
  `invit_buy` varchar(50) NOT NULL DEFAULT '',
  `invit_sell` varchar(50) NOT NULL DEFAULT '',
  `invit_1` varchar(50) NOT NULL DEFAULT '',
  `invit_2` varchar(50) NOT NULL DEFAULT '',
  `invit_3` varchar(50) NOT NULL DEFAULT '',
  `zhang` varchar(255) NOT NULL DEFAULT '',
  `die` varchar(255) NOT NULL DEFAULT '',
  `hou_price` varchar(255) NOT NULL DEFAULT '',
  `tendency` text,
  `trade` int(11) unsigned NOT NULL DEFAULT '0',
  `new_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `buy_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sell_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `min_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `max_price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `volume` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `change` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `api_min` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `api_max` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) DEFAULT NULL COMMENT '开盘时间（小时）',
  `stop_time` int(11) DEFAULT NULL COMMENT '闭盘时间（小时）',
  `start_minute` int(11) DEFAULT NULL COMMENT '开盘时间（分钟）',
  `stop_minute` int(11) DEFAULT NULL COMMENT '毕盘时间（分钟）',
  `agree6` int(11) DEFAULT NULL COMMENT '周六是否可以交易0是不可交易1是可交易',
  `agree7` int(11) DEFAULT NULL COMMENT '周天是否可以交易0是不可交易1是可交易',
  `trade_num_min` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='行情配置表';

-- ----------------------------
-- Records of tw_market
-- ----------------------------

-- ----------------------------
-- Table structure for tw_market_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_market_json`;
CREATE TABLE `tw_market_json` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_market_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_menu
-- ----------------------------
DROP TABLE IF EXISTS `tw_menu`;
CREATE TABLE `tw_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `ico_name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=497 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tw_menu
-- ----------------------------
INSERT INTO `tw_menu` VALUES ('1', '系统', '0', '1', 'Index/index', '0', '', '', '0', 'home');
INSERT INTO `tw_menu` VALUES ('2', '内容', '0', '1', 'Article/index', '0', '', '', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('3', '用户', '0', '1', 'User/index', '0', '', '', '0', 'user');
INSERT INTO `tw_menu` VALUES ('4', '财务', '0', '1', 'Finance/myzr', '0', '', '', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('5', '交易', '0', '1', 'Trade/index', '0', '', '', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('7', '设置', '0', '1', 'Config/index', '0', '', '', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('9', '工具', '0', '1', 'Tools/index', '0', '', '', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('11', '系统概览', '1', '1', 'Index/index', '0', '', '系统', '0', 'home');
INSERT INTO `tw_menu` VALUES ('13', '文章管理', '2', '1', 'Article/index', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('14', '编辑添加', '13', '1', 'Article/edit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('15', '修改状态', '13', '100', 'Article/status', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('16', '上传图片', '13', '2', 'Article/images', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('18', '编辑', '17', '2', 'Adver/edit', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('19', '修改', '17', '2', 'Adver/status', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('21', '编辑', '20', '3', 'Chat/edit', '1', '', '聊天管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('22', '修改', '20', '3', 'Chat/status', '1', '', '聊天管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('26', '用户管理', '3', '1', 'User/index', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('32', '确认转出', '26', '8', 'User/myzc_qr', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('33', '用户配置', '3', '1', 'User/config', '1', '', '前台用户管理', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('34', '编辑', '33', '2', 'User/index_edit', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('35', '修改', '33', '2', 'User/index_status', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('37', '财产修改', '26', '3', 'Usercoin/edit', '1', '', '用户管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('39', '新增用户组', '38', '0', 'AuthManager/createGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('40', '编辑用户组', '38', '0', 'AuthManager/editgroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('41', '更新用户组', '38', '0', 'AuthManager/writeGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('42', '改变状态', '38', '0', 'AuthManager/changeStatus', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('43', '访问授权', '38', '0', 'AuthManager/access', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('44', '分类授权', '38', '0', 'AuthManager/category', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('45', '成员授权', '38', '0', 'AuthManager/user', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('46', '成员列表授权', '38', '0', 'AuthManager/tree', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('47', '用户组', '38', '0', 'AuthManager/group', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('48', '添加到用户组', '38', '0', 'AuthManager/addToGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('49', '用户组移除', '38', '0', 'AuthManager/removeFromGroup', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('50', '分类添加到用户组', '38', '0', 'AuthManager/addToCategory', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('51', '模型添加到用户组', '38', '0', 'AuthManager/addToModel', '1', '', '权限管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('53', '配置', '52', '1', 'Finance/config', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('55', '批量转出错误日志', '4', '10', 'Finance/myzcBatchLog', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('56', '状态修改', '52', '1', 'Finance/type_status', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('60', '修改', '57', '3', 'Mycz/status', '1', '', '充值管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('61', '状态修改', '57', '3', 'Mycztype/status', '1', '', '充值管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('64', '状态修改', '62', '5', 'Mytx/status', '1', '', '提现管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('65', '取消', '62', '5', 'Mytx/excel', '1', '', '提现管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('68', '广告管理', '5', '1', 'Trade/index', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('69', '成交记录', '5', '2', 'Trade/log', '1', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('70', '修改状态', '68', '0', 'Trade/status', '1', '', '交易管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('71', '撤销挂单', '68', '0', 'Trade/chexiao', '1', '', '交易管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('79', '基本配置', '7', '1', 'Config/index', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('81', '客服配置', '7', '3', 'Config/contact', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('83', '编辑', '82', '4', 'Config/bank_edit', '1', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('85', '编辑', '84', '4', 'Coin/edit', '0', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('87', '状态修改', '84', '4', 'Coin/status', '1', '', '网站配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('91', '状态修改', '88', '4', 'Config/market_add', '1', '', '', '0', '0');
INSERT INTO `tw_menu` VALUES ('95', '其他配置', '7', '6', 'Config/qita', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('115', '图片', '111', '0', 'Shop/images', '0', '', '云购商城', '0', '0');
INSERT INTO `tw_menu` VALUES ('127', '用户登录', '3', '0', 'Login/index', '1', '', '用户配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('128', '用户退出', '3', '0', 'Login/loginout', '1', '', '用户配置', '0', '0');
INSERT INTO `tw_menu` VALUES ('129', '修改管理员密码', '3', '0', 'User/setpwd', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('131', '用户详情', '3', '4', 'User/detail', '1', '', '前台用户管理', '0', 'time');
INSERT INTO `tw_menu` VALUES ('132', '后台用户详情', '3', '1', 'AdminUser/detail', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('133', '后台用户状态', '3', '1', 'AdminUser/status', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('134', '后台用户新增', '3', '1', 'AdminUser/add', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('135', '后台用户编辑', '3', '1', 'AdminUser/edit', '1', '', '后台用户管理', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('138', '编辑', '2', '1', 'Articletype/edit', '1', '', '内容管理', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('140', '编辑', '139', '2', 'Link/edit', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('141', '修改', '139', '2', 'Link/status', '1', '', '内容管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('155', '服务器队列', '9', '3', 'Tools/queue', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('156', '钱包检查', '9', '3', 'Tools/qianbao', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('157', '币种统计', '1', '2', 'Index/coin', '0', '', '系统', '0', 'home');
INSERT INTO `tw_menu` VALUES ('163', '提示文字', '7', '5', 'Config/text', '1', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('278', '文章类型', '2', '2', 'Article/type', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('279', '广告管理', '2', '3', 'Article/adver', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('280', '友情链接', '2', '4', 'Article/youqing', '0', '', '内容', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('282', '登录日志', '3', '4', 'User/log', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('283', '用户钱包', '3', '5', 'User/qianbao', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('284', '提现地址', '3', '6', 'User/bank', '1', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('285', '用户财产', '3', '7', 'User/coin', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('289', '交易推荐', '5', '6', 'Trade/invit', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('291', '人民币充值', '4', '2', 'Finance/mycz', '1', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('292', '人民币充值方式', '4', '3', 'Finance/myczType', '1', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('293', '人民币提现', '4', '4', 'Finance/mytx', '1', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('294', '人民币提现配置', '4', '5', 'Finance/mytxConfig', '1', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('295', '虚拟币转入', '4', '6', 'Finance/myzr', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('296', '虚拟币转出', '4', '7', 'Finance/myzc', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('297', '修改状态', '291', '100', 'Finance/myczStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('298', '确认到账', '291', '100', 'Finance/myczQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('299', '编辑添加', '292', '1', 'Finance/myczTypeEdit', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('300', '状态修改', '292', '2', 'Finance/myczTypeStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('301', '上传图片', '292', '2', 'Finance/myczTypeImage', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('302', '修改状态', '293', '2', 'Finance/mytxStatus', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('303', '导出选中', '293', '3', 'Finance/mytxExcel', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('304', '正在处理', '293', '4', 'Finance/mytxChuli', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('305', '撤销提现', '293', '5', 'Finance/mytxChexiao', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('306', '确认提现', '293', '6', 'Finance/mytxQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('307', '确认转出', '296', '6', 'Finance/myzcQueren', '1', '', '财务', '0', 'home');
INSERT INTO `tw_menu` VALUES ('309', '清理缓存', '9', '1', 'Tools/index', '0', '', '工具', '0', 'wrench');
INSERT INTO `tw_menu` VALUES ('312', '管理员管理', '3', '2', 'User/admin', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('313', '权限列表', '3', '3', 'User/auth', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('314', '编辑添加', '26', '1', 'User/edit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('315', '修改状态', '26', '1', 'User/status', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('316', '编辑添加', '312', '1', 'User/adminEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('317', '修改状态', '312', '1', 'User/adminStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('318', '编辑添加', '313', '1', 'User/authEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('319', '修改状态', '313', '1', 'User/authStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('320', '重新初始化权限', '313', '1', 'User/authStart', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('321', '编辑添加', '282', '1', 'User/logEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('322', '修改状态', '282', '1', 'User/logStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('323', '编辑添加', '283', '1', 'User/qianbaoEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('324', '修改状态', '283', '1', 'User/qianbaoStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('325', '编辑添加', '284', '1', 'User/bankEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('326', '修改状态', '284', '1', 'User/bankStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('327', '编辑添加', '285', '1', 'User/coinEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('328', '财产统计', '285', '1', 'User/coinLog', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('329', '编辑添加', '286', '1', 'User/goodsEdit', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('330', '修改状态', '286', '1', 'User/goodsStatus', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('331', '编辑添加', '278', '1', 'Article/typeEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('332', '修改状态', '278', '100', 'Article/typeStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('333', '编辑添加', '280', '1', 'Article/youqingEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('334', '修改状态', '280', '100', 'Article/youqingStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('335', '编辑添加', '279', '1', 'Article/adverEdit', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('336', '修改状态', '279', '100', 'Article/adverStatus', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('337', '上传图片', '279', '100', 'Article/adverImage', '1', '', '内容', '0', 'home');
INSERT INTO `tw_menu` VALUES ('377', '访问授权', '313', '1', 'User/authAccess', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('378', '访问授权修改', '313', '1', 'User/authAccessUp', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('379', '成员授权', '313', '1', 'User/authUser', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('380', '成员授权增加', '313', '1', 'User/authUserAdd', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('381', '成员授权解除', '313', '1', 'User/authUserRemove', '1', '', '用户', '0', 'home');
INSERT INTO `tw_menu` VALUES ('382', '币种配置', '7', '4', 'Config/coin', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('388', '导航配置', '7', '7', 'Config/daohang', '0', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('444', '短信配置', '7', '2', 'Config/mobile', '1', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('446', '资金日志', '4', '9', 'Finance/amountlog', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('447', '用户反馈', '3', '10', 'User/feedback', '1', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('448', '实名审核', '3', '11', 'User/nameauth', '0', '审核用户实名认证信息', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('449', '以太坊转账', '4', '12', 'Finance/ethtransfer', '0', '以太坊转账', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('450', '导出excel', '291', '0', 'Finance/myczExcel', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('453', '恢复自动转出队列', '296', '0', 'Tools/recoverzc', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('454', '查看自动转出队列状态', '296', '0', 'Tools/chkzdzc', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('455', '批量转出', '296', '0', 'Finance/myzcBatch', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('456', '批量转出错误日志', '296', '0', 'Finance/myzcBatchLog', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('457', '导出excel', '68', '0', 'Trade/tradeExcel', '1', '', '交易', '1', '0');
INSERT INTO `tw_menu` VALUES ('458', '导出excel', '69', '0', 'Trade/tradelogExcel', '1', '', '财务', '1', '0');
INSERT INTO `tw_menu` VALUES ('460', '代理奖励', '4', '8', 'Finance/tradePrize', '1', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('461', '聊天记录', '4', '9', 'Finance/incentive', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('462', '推荐关系', '3', '2', 'User/invittree', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('463', '用户地区', '3', '10', 'User/region', '1', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('464', '订单管理', '5', '2', 'Trade/orderlist', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('465', '订单详情', '464', '0', 'Trade/orderinfo', '0', '', '交易管理', '0', '0');
INSERT INTO `tw_menu` VALUES ('466', '编辑币种', '382', '1', 'Config/coinEdit', '1', '', '设置', '0', 'cog');
INSERT INTO `tw_menu` VALUES ('467', '人工转出', '296', '1', 'Finance/myzcSd', '1', '', '财务', '0', '0');
INSERT INTO `tw_menu` VALUES ('468', '取消转出', '296', '1', 'Finance/myzcCancel', '1', '', '财务', '0', '0');
INSERT INTO `tw_menu` VALUES ('469', '币状态编辑', '382', '1', 'Config/coinStatus', '1', '', '设置', '0', '0');
INSERT INTO `tw_menu` VALUES ('470', '改订单状态', '464', '1', 'Trade/chuli_ajax', '1', '', '交易', '0', '0');
INSERT INTO `tw_menu` VALUES ('480', '处理问题单', '464', '1', 'Trade/admindone', '1', '', '交易', '0', '0');
INSERT INTO `tw_menu` VALUES ('481', '编辑客服', '81', '1', 'Config/contactEdit', '1', '', '设置', '0', '0');
INSERT INTO `tw_menu` VALUES ('482', '缓存清理', '309', '1', 'Tools/delcache', '1', '', '工具', '0', '0');
INSERT INTO `tw_menu` VALUES ('483', '编辑导航', '388', '1', 'Config/daohangStatus', '1', '', '设置', '0', '0');
INSERT INTO `tw_menu` VALUES ('484', '添加导航', '388', '1', 'Config/daohangEdit', '1', '', '设置', '0', '0');
INSERT INTO `tw_menu` VALUES ('485', '广告详情', '68', '1', 'Trade/adinfo', '1', '', '交易', '0', '0');
INSERT INTO `tw_menu` VALUES ('486', '删除广告', '68', '1', 'Trade/addel', '1', '', '交易', '0', '0');
INSERT INTO `tw_menu` VALUES ('487', '财产日志', '3', '9', 'User/financelog', '0', '', '用户', '0', 'user');
INSERT INTO `tw_menu` VALUES ('488', '实名详情', '448', '1', 'User/nameauthdetail', '1', '', '用户', '0', '0');
INSERT INTO `tw_menu` VALUES ('489', '交易统计', '5', '4', 'Trade/analysis', '0', '', '交易', '0', 'stats');
INSERT INTO `tw_menu` VALUES ('490', '初始化币种统计', '1', '1', 'Index/coinSet', '1', '', '系统', '0', '0');
INSERT INTO `tw_menu` VALUES ('491', '提币手续费统计', '4', '7', 'Finance/mytxfee', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('492', '商品', '491', '0', 'Goods/index', '1', '', '比特购', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('493', '订单', '491', '1', 'Goods/order', '1', '', '比特购', '0', 'list-alt');
INSERT INTO `tw_menu` VALUES ('494', '以太坊转账记录', '4', '10', 'Finance/ethtrans', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('495', '漏单处理', '4', '99', 'Finance/deallost', '0', '', '财务', '0', 'th-list');
INSERT INTO `tw_menu` VALUES ('496', '以太坊批量转帐', '4', '11', 'Finance/ethbatch', '0', '', '财务', '0', 'th-list');

-- ----------------------------
-- Table structure for tw_mycz
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz`;
CREATE TABLE `tw_mycz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `num` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `mum` decimal(11,2) unsigned NOT NULL DEFAULT '0.00',
  `type` varchar(50) NOT NULL,
  `tradeno` varchar(50) NOT NULL DEFAULT '',
  `remark` varchar(250) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `alipay_truename` varchar(20) DEFAULT NULL,
  `alipay_account` varchar(35) DEFAULT NULL,
  `ewmname` varchar(50) NOT NULL DEFAULT '',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bank` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值记录表';

-- ----------------------------
-- Records of tw_mycz
-- ----------------------------

-- ----------------------------
-- Table structure for tw_mycz_invit
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz_invit`;
CREATE TABLE `tw_mycz_invit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid` int(11) unsigned NOT NULL COMMENT '用户id',
  `invitid` int(11) unsigned NOT NULL COMMENT '推荐人id',
  `num` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '操作金额',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '赠送金额',
  `coinname` varchar(50) NOT NULL DEFAULT '' COMMENT '赠送币种',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '到账金额',
  `remark` varchar(250) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值赠送';

-- ----------------------------
-- Records of tw_mycz_invit
-- ----------------------------

-- ----------------------------
-- Table structure for tw_mycz_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_mycz_type`;
CREATE TABLE `tw_mycz_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `max` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `min` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `kaihu` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `truename` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `img` varchar(50) NOT NULL DEFAULT '',
  `extra` varchar(50) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='充值类型';

-- ----------------------------
-- Records of tw_mycz_type
-- ----------------------------
INSERT INTO `tw_mycz_type` VALUES ('1', '10000000', '100', '', '', 'alipay', '支付宝转账', '', '15099566764', '', '', '', '需要在联系方式里面设置支付宝账号', '0', '0', '0', '1', '0.00');
INSERT INTO `tw_mycz_type` VALUES ('2', '10000000', '100', '中国银行', '', 'bank', '银行卡转帐', '', '6216608300003225303', '', '', '', '需要在联系方式里面设置银行卡号', '0', '0', '0', '1', '0.00');
INSERT INTO `tw_mycz_type` VALUES ('4', '1000', '100', '', '', 'weixin', '微信转账支付', '', '', '', '', '', '需要在联系方式里面设置微信账号', '0', '0', '0', '0', '5.00');

-- ----------------------------
-- Table structure for tw_mytx
-- ----------------------------
DROP TABLE IF EXISTS `tw_mytx`;
CREATE TABLE `tw_mytx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `mum` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `truename` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `bank` varchar(250) NOT NULL DEFAULT '',
  `bankprov` varchar(50) NOT NULL DEFAULT '',
  `bankcity` varchar(50) NOT NULL DEFAULT '',
  `bankaddr` varchar(50) NOT NULL DEFAULT '',
  `bankcard` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现记录表';

-- ----------------------------
-- Records of tw_mytx
-- ----------------------------

-- ----------------------------
-- Table structure for tw_myzc
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzc`;
CREATE TABLE `tw_myzc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `txid` varchar(200) NOT NULL DEFAULT '',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `to_user` int(2) NOT NULL DEFAULT '0' COMMENT '会员转币',
  `cointype` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_myzc
-- ----------------------------
INSERT INTO `tw_myzc` VALUES ('1', '68', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', 'usdt', '9a2bbdcd0a7964dec502810e234783e7466874dbb775b39636a63345e542ba0f', '30.00000000', '20.00000000', '10.00000000', '0', '1529655781', '1529670665', '1', '1', '4');
INSERT INTO `tw_myzc` VALUES ('2', '68', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', 'usdt', '6fa8516a53589aa7ecf8dcec0e6dc03f11ba401a13ac53c365bc320be62d8210', '25.00000000', '20.00000000', '5.00000000', '0', '1529655940', '1529661470', '1', '1', '4');
INSERT INTO `tw_myzc` VALUES ('3', '68', '17A16QmavnUfCW11DAApiJxp7ARnxN5pGX', 'btc', '', '0.10000000', '0.00001123', '0.09989000', '0', '1529656036', '0', '0', '0', '1');
INSERT INTO `tw_myzc` VALUES ('4', '68', '0x43e590e56023ccb8754b93904a4ea066c6641793', 'eth', '0x3fa2c41b75745e77c03ba20618efd60fa75f17eb54f0bcc786a767aa6f22e75d', '0.00200000', '0.00010020', '0.00189980', '0', '1529656117', '1529717140', '1', '1', '2');
INSERT INTO `tw_myzc` VALUES ('5', '68', '0x43e590e56023ccb8754b93904a4ea066c6641793', 'hor', '0x9d46c8a1b57322efa601a78e472027f61be39748364f9d463a3ff02b76588fe8', '1.00000000', '0.00000000', '1.00000000', '0', '1529656160', '1529722325', '1', '1', '3');
INSERT INTO `tw_myzc` VALUES ('6', '68', '0x43e590e56023ccb8754b93904a4ea066c6641793', 'hor', '0x0771055aeefebc04539c767ef70083a8f53ce7ae3df850cf4312144717025b6e', '1.00000000', '0.50100000', '0.49900000', '0', '1529656218', '1529722326', '1', '1', '3');
INSERT INTO `tw_myzc` VALUES ('7', '68', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', 'usdt', '', '94.00000000', '20.00000000', '74.00000000', '0', '1529661633', '1529670675', '2', '0', '4');
INSERT INTO `tw_myzc` VALUES ('8', '68', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', 'usdt', '9f717a51a3cebc03a73652df0ed5111059b3f26b96287253818e303a2c4585e5', '90.00000000', '20.00000000', '70.00000000', '0', '1529669668', '1529670665', '1', '1', '4');
INSERT INTO `tw_myzc` VALUES ('9', '68', '0x43e590e56023ccb8754b93904a4ea066c6641793', 'eth', '0x370503c31ebda07b6c96b4c5752ee86e7fb60da1195daad9f5de2f3f5f5267ed', '0.00100000', '0.00010010', '0.00089990', '0', '1529717207', '1529717234', '1', '1', '2');
INSERT INTO `tw_myzc` VALUES ('10', '68', 'mu9CpoVjz8FhjuFwErG7yiYpyDRo8C1jt5', 'btc', '332462fd6a62ff155b73bc5f8c0a983ddddb7e7a5638134a414e2a249a3eb8f8', '0.03000000', '0.00010300', '0.02989700', '0', '1529724042', '1529724257', '1', '1', '1');
INSERT INTO `tw_myzc` VALUES ('11', '68', 'mu9CpoVjz8FhjuFwErG7yiYpyDRo8C1jt5', 'btc', '594594c1b61701f87c3c1dbbaee59673436230c4cc3e0b431dcab40e527ded11', '0.04000000', '0.00010400', '0.03989600', '0', '1529724068', '1529724261', '1', '1', '1');
INSERT INTO `tw_myzc` VALUES ('12', '68', '17A16QmavnUfCW11DAApiJxp7ARnxN5pGX', 'btc', '', '1.00000000', '0.00001000', '0.99980000', '0', '1529752825', '0', '0', '0', '1');
INSERT INTO `tw_myzc` VALUES ('13', '68', '17A16QmavnUfCW11DAApiJxp7ARnxN5pGX', 'btc', '', '0.10000000', '0.00011000', '0.09989000', '0', '1531123261', '0', '0', '0', '1');
INSERT INTO `tw_myzc` VALUES ('14', '69', '15fRwf9ePtanUjKZqsNUssed6c4XsGgyQH', 'btc', '8f327b6a5894a346c78bdfcfa34c63e9', '0.10000000', '0.00011000', '0.09989000', '0', '1531123638', '0', '1', '0', '1');
INSERT INTO `tw_myzc` VALUES ('15', '68', '17A16QmavnUfCW11DAApiJxp7ARnxN5pGX', 'btc', '', '0.20000000', '0.00012000', '0.19988000', '0', '1531124316', '1531124346', '1', '0', '1');

-- ----------------------------
-- Table structure for tw_myzc_fee
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzc_fee`;
CREATE TABLE `tw_myzc_fee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `coinname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `txid` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tw_myzc_fee
-- ----------------------------
INSERT INTO `tw_myzc_fee` VALUES ('1', '69', '14', 'btc', 'f0e0d4572a9524d2d4f8eba59ccaf9f0', '1', '0.00011000', '0.10000000', '0.09989000', '0', '1531123638', '0', '1');
INSERT INTO `tw_myzc_fee` VALUES ('2', '68', '15', 'btc', '', '2', '0.00012000', '0.20000000', '0.19988000', '0', '1531124346', '0', '1');

-- ----------------------------
-- Table structure for tw_myzr
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzr`;
CREATE TABLE `tw_myzr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(200) NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `txid` varchar(200) NOT NULL DEFAULT '',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  `from_user` int(2) NOT NULL DEFAULT '0' COMMENT '会员转币',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_myzr
-- ----------------------------
INSERT INTO `tw_myzr` VALUES ('1', '1', 'mrHEcNvqgsmphpqkJH1SgANzr26FFH2pHF', 'usdt', '47be492d934b304501f91d7d26f3cc06', '10.00000000', '10.00000000', '0.00000000', '0', '1529635746', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('3', '1', 'mrHEcNvqgsmphpqkJH1SgANzr26FFH2pHF', 'usdt', '673509dac5236c21672b13e926758ec7', '21.00000000', '1.00000000', '20.00000000', '0', '1529635989', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('4', '69', '15fRwf9ePtanUjKZqsNUssed6c4XsGgyQH', 'btc', 'e7e9ff860498e3395129371486569d57', '1.00000000', '1.00000000', '0.00000000', '0', '1529637594', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('5', '69', '15fRwf9ePtanUjKZqsNUssed6c4XsGgyQH', 'btc', '9c27d24244f2e7381d9467785959e49e', '1.00000000', '0.99980000', '0.00020000', '0', '1529637712', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('6', '69', '0x76d71eb19d44102088e8642a6c56493f6e29266d', 'eth', '1b0160678cf856f4a3b41d3b76f44737', '1.00000000', '1.00000000', '0.00000000', '0', '1529638374', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('7', '69', '0x76d71eb19d44102088e8642a6c56493f6e29266d', 'eth', 'fd3bf2f0a19dfdb5a2739690e097e551', '1.00000000', '0.99980000', '0.00020000', '0', '1529638464', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('8', '68', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', 'eth', '0x1daee8f090a8311d19954d5aab556bfdcf38ce25c7ae8fc9076a1cb8f118bd4b', '0.00100000', '0.00100000', '0.00000000', '0', '1529716921', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('9', '68', '0x6ba83bab1dbdcc52f40ffe9918f8bc9e80ff6668', 'hor', '0x8fa9918da48e4e4879e5f5a418b566dcdfbc812d01d3263563fe1809faedf058', '0.85000000', '0.85000000', '0.00000000', '0', '1529722681', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('10', '68', 'msEir6PPBaT6DCR9YVcGYUPd4VwTi7Uuae', 'btc', '94fe6297b1f1cf0f741ce23965d6b6804d4258f5caab7422ea46f7f0caffb195', '0.01000000', '0.01000000', '0.00000000', '0', '1529725921', '0', '-4', '0');
INSERT INTO `tw_myzr` VALUES ('11', '68', 'msEir6PPBaT6DCR9YVcGYUPd4VwTi7Uuae', 'btc', '0bb9acc7dda0e73948fe6e27bd9e742c628401c123f4b4bad466e24ddff66e45', '0.10000000', '0.10000000', '0.00000000', '0', '1529725921', '0', '-4', '0');
INSERT INTO `tw_myzr` VALUES ('12', '80', '0x0fd081e3bb178dc45c0cb23202069dda57064258', 'eth', '0x21312ecbd8101e63376d14b5a2d372e71e359ec2cec642a96bf06f67fc02ed8f', '2.00000000', '2.00000000', '0.00000000', '0', '1529907601', '0', '1', '0');
INSERT INTO `tw_myzr` VALUES ('14', '80', '1A4rCdWhuvx6ZL1oB6G65M6UDsbA3zEcpU', 'usdt', '79d72ff301eb76a0a8dabf0ded7f4fc17cdc4599b76a93df39a161d8f8a3faee', '185.00000000', '185.00000000', '0.00000000', '0', '1529908082', '0', '-5', '0');
INSERT INTO `tw_myzr` VALUES ('15', '68', '', 'btc', '8f327b6a5894a346c78bdfcfa34c63e9', '0.10000000', '0.09989000', '0.00011000', '0', '1531123638', '0', '1', '0');

-- ----------------------------
-- Table structure for tw_myzr_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_myzr_json`;
CREATE TABLE `tw_myzr_json` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `coinname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_myzr_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_order_buy
-- ----------------------------
DROP TABLE IF EXISTS `tw_order_buy`;
CREATE TABLE `tw_order_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buy_id` int(10) NOT NULL COMMENT '买家的id',
  `buy_bid` int(11) NOT NULL DEFAULT '0' COMMENT '发布购买单子的id',
  `sell_id` int(10) NOT NULL COMMENT '卖家id',
  `sell_sid` int(10) NOT NULL DEFAULT '0' COMMENT '卖家发布买的id',
  `deal_amount` decimal(13,2) NOT NULL COMMENT '交易金额',
  `deal_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '交易价格',
  `deal_ctype` int(2) NOT NULL DEFAULT '0' COMMENT '交易货币的种类',
  `deal_num` decimal(20,8) NOT NULL COMMENT '交易数量',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `dktime` int(11) NOT NULL DEFAULT '0' COMMENT '打款时间',
  `ltime` int(10) NOT NULL DEFAULT '30' COMMENT '限时多长时间付款',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0代表已经拍下1代付款2待收货3代评价4已经完成5取消交易6处于申诉状态 需要管理员处理',
  `desc` varchar(100) DEFAULT NULL COMMENT '交易操作描述',
  `finished_time` int(10) DEFAULT NULL,
  `order_no` varchar(55) NOT NULL COMMENT '订单编号',
  `cancle_op` int(1) NOT NULL DEFAULT '0' COMMENT '0默手动取消交易1为此交易已关闭，因为未及时将付款标记为完成。如果您已付款，请要求卖家重新打开交易。',
  `buy_pj` int(1) DEFAULT '0' COMMENT '来自买家的评价',
  `sell_pj` int(1) DEFAULT '0' COMMENT '来自卖家的评价',
  `su_type` int(1) NOT NULL DEFAULT '0' COMMENT '1我已付款，但卖家没有放行我比特币2卖家未遵守交易广告条款',
  `su_reason` text,
  `sutp` varchar(255) DEFAULT NULL COMMENT '上传路径',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `deal_coin` int(10) NOT NULL DEFAULT '0',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `lmessage` varchar(255) DEFAULT NULL,
  `adminhandle` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  KEY `buy_id` (`buy_id`) USING BTREE,
  KEY `sell_id` (`sell_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_order_buy
-- ----------------------------
INSERT INTO `tw_order_buy` VALUES ('1', '69', '0', '68', '1', '100.00', '6.67', '1', '14.99250374', '1526001528', '1526001532', '3', '4', null, '1526001549', '152600152869682641', '0', '1', '1', '0', null, null, '1', '10', '0.00000000', '这是我的要求', '0');
INSERT INTO `tw_order_buy` VALUES ('2', '78', '0', '68', '1', '668.00', '6.68', '1', '100.00000000', '1529194281', '0', '3', '5', null, null, '152919428178683302', '1', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('3', '69', '0', '68', '1', '100.00', '6.84', '1', '14.61988304', '1529752090', '1529752095', '3', '5', null, null, '152975209069689866', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '这是我的要求', '0');
INSERT INTO `tw_order_buy` VALUES ('4', '69', '0', '68', '1', '200.00', '6.83', '1', '29.28257686', '1529752251', '0', '3', '5', null, null, '152975225169684004', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('5', '69', '0', '68', '1', '200.00', '6.83', '1', '29.28257686', '1529752290', '1529752294', '3', '3', null, '1529889112', '152975229069683304', '0', '0', '1', '2', '12312312312', '/Upload/lanch/sutp/20180623/s_5b2e2af1433ce.jpg', '1', '10', '0.00000000', '这是我的要求', '0');
INSERT INTO `tw_order_buy` VALUES ('6', '69', '0', '68', '1', '300.00', '6.83', '1', '43.92386530', '1529752321', '1529752325', '3', '4', null, '1529752508', '152975232169682020', '0', '1', '1', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('7', '76', '0', '83', '6', '50.00', '1.01', '1', '49.50495049', '1529985383', '1529985411', '3', '1', null, null, '152998538376838648', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '1');
INSERT INTO `tw_order_buy` VALUES ('8', '76', '0', '83', '6', '52.00', '1.01', '1', '51.48514851', '1529985485', '1529996610', '3', '3', null, '1529998211', '152998548576835123', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('9', '84', '0', '83', '6', '50.00', '1.01', '1', '49.50495049', '1530072098', '0', '3', '0', null, null, '153007209884835776', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('10', '84', '0', '83', '6', '55.00', '1.01', '1', '54.45544554', '1530077496', '0', '3', '0', null, null, '153007749684831612', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('11', '84', '0', '83', '6', '55.00', '1.01', '1', '54.45544554', '1530077501', '0', '3', '0', null, null, '153007750184834394', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('12', '84', '0', '83', '6', '55.00', '1.01', '1', '54.45544554', '1530077581', '0', '3', '0', null, null, '153007758184833822', '0', '0', '0', '0', null, null, '1', '14', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('13', '84', '0', '69', '2', '100.00', '6.77', '1', '14.77104874', '1530077642', '0', '3', '0', null, null, '153007764284692267', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('14', '76', '0', '69', '2', '100.00', '6.77', '1', '14.77104874', '1530238156', '1530242249', '3', '1', null, null, '153023815676696634', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('15', '77', '0', '69', '2', '1100.00', '6.77', '1', '162.48153618', '1530500799', '0', '3', '0', null, null, '153050079977691195', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('16', '77', '0', '69', '2', '1100.00', '6.77', '1', '162.48153618', '1530500806', '0', '3', '0', null, null, '153050080677694552', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('17', '77', '0', '69', '2', '1100.00', '6.77', '1', '162.48153618', '1530500812', '0', '3', '0', null, null, '153050081277697026', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('18', '77', '0', '69', '2', '1100.00', '6.77', '1', '162.48153618', '1530500901', '0', '3', '0', null, null, '153050090177696713', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('19', '77', '0', '68', '1', '100.00', '6.83', '1', '14.64128843', '1530759945', '0', '3', '0', null, null, '153075994577688347', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('20', '77', '0', '68', '1', '100.00', '6.83', '1', '14.64128843', '1530759969', '0', '3', '0', null, null, '153075996977682256', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('21', '77', '0', '68', '1', '100.00', '6.83', '1', '14.64128843', '1530759998', '0', '3', '0', null, null, '153075999877685371', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('22', '77', '0', '68', '1', '100.00', '6.83', '1', '14.64128843', '1530760002', '0', '3', '0', null, null, '153076000277682192', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('23', '76', '0', '77', '9', '1000.00', '40881.61', '1', '0.02446087', '1530760115', '0', '3', '0', null, null, '153076011576778821', '0', '0', '0', '0', null, null, '1', '11', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('24', '76', '0', '77', '9', '1000.00', '40881.61', '1', '0.02446087', '1530760130', '0', '3', '0', null, null, '153076013076771588', '0', '0', '0', '0', null, null, '1', '11', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('25', '77', '0', '68', '1', '100.00', '6.83', '1', '14.64128843', '1530760206', '0', '3', '0', null, null, '153076020677682022', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_buy` VALUES ('26', '77', '0', '69', '2', '200.00', '6.77', '1', '29.54209748', '1530760238', '0', '3', '0', null, null, '153076023877698662', '0', '0', '0', '0', null, null, '1', '10', '0.00000000', '', '0');

-- ----------------------------
-- Table structure for tw_order_sell
-- ----------------------------
DROP TABLE IF EXISTS `tw_order_sell`;
CREATE TABLE `tw_order_sell` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `buy_id` int(10) NOT NULL COMMENT '买家的id',
  `buy_bid` int(11) NOT NULL DEFAULT '0' COMMENT '发布购买单子的id',
  `sell_id` int(10) NOT NULL COMMENT '卖家id',
  `sell_sid` int(10) NOT NULL DEFAULT '0' COMMENT '买家发布买的id',
  `deal_amount` decimal(20,2) NOT NULL COMMENT '交易金额',
  `deal_price` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '交易价格',
  `deal_ctype` int(2) NOT NULL DEFAULT '0' COMMENT '交易货币种类',
  `deal_num` decimal(20,8) NOT NULL COMMENT '交易数量',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `dktime` int(11) NOT NULL DEFAULT '0' COMMENT '打款时间',
  `ltime` int(10) NOT NULL DEFAULT '60' COMMENT '限时多长时间付款',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0代表已经拍下1代付款2待发货3代评价4已经完成5取消交易6处于申诉的状态管理员需要审核',
  `desc` varchar(100) DEFAULT NULL COMMENT '交易操作描述',
  `finished_time` int(10) DEFAULT NULL,
  `order_no` varchar(55) NOT NULL COMMENT '订单编号',
  `buy_pj` int(1) DEFAULT '0' COMMENT '买家给出的评价1好评2中评3差评',
  `sell_pj` int(1) DEFAULT '0' COMMENT '卖家给买家的评价',
  `su_type` int(1) NOT NULL DEFAULT '0' COMMENT '1 2',
  `su_reason` text,
  `cancle_op` int(2) NOT NULL DEFAULT '0' COMMENT '取消的原因',
  `sutp` varchar(155) DEFAULT NULL COMMENT '上传路径',
  `type` tinyint(1) NOT NULL DEFAULT '2',
  `deal_coin` int(10) NOT NULL DEFAULT '0',
  `fee` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `lmessage` varchar(255) DEFAULT NULL,
  `adminhandle` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  KEY `buy_id` (`buy_id`) USING BTREE,
  KEY `sell_id` (`sell_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_order_sell
-- ----------------------------
INSERT INTO `tw_order_sell` VALUES ('1', '69', '1', '68', '0', '100.00', '6.70', '1', '14.92537313', '1529994251', '1529994349', '30', '1', null, null, '152999425168693279', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '留个言', '0');
INSERT INTO `tw_order_sell` VALUES ('2', '83', '3', '76', '0', '50.00', '6.57', '1', '7.61035007', '1529997029', '1530071970', '30', '1', null, null, '152999702976838767', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '212', '0');
INSERT INTO `tw_order_sell` VALUES ('15', '83', '3', '76', '0', '52.56', '6.57', '1', '8.00000000', '1530081277', '0', '30', '0', null, null, '153008127776837455', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_sell` VALUES ('16', '83', '3', '76', '0', '52.56', '6.57', '1', '8.00000000', '1530081511', '0', '30', '0', null, null, '153008151176835808', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '23', '0');
INSERT INTO `tw_order_sell` VALUES ('17', '69', '1', '76', '0', '100.00', '6.70', '1', '14.92537313', '1530238139', '0', '30', '0', null, null, '153023813976697797', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_sell` VALUES ('18', '69', '1', '76', '0', '100.00', '6.70', '1', '14.92537313', '1530238144', '0', '30', '0', null, null, '153023814476698443', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_sell` VALUES ('19', '69', '1', '77', '0', '670.00', '6.70', '1', '100.00000000', '1530501125', '0', '30', '0', null, null, '153050112577692291', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_sell` VALUES ('20', '69', '1', '77', '0', '670.00', '6.70', '1', '100.00000000', '1530501173', '0', '30', '0', null, null, '153050117377697547', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');
INSERT INTO `tw_order_sell` VALUES ('21', '69', '1', '77', '0', '670.00', '6.70', '1', '100.00000000', '1530501292', '0', '30', '0', null, null, '153050129277692808', '0', '0', '0', null, '0', null, '2', '10', '0.00000000', '', '0');

-- ----------------------------
-- Table structure for tw_order_temp
-- ----------------------------
DROP TABLE IF EXISTS `tw_order_temp`;
CREATE TABLE `tw_order_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ordertype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1是买2是卖',
  `buy_id` int(11) NOT NULL DEFAULT '0',
  `buy_bid` int(11) NOT NULL DEFAULT '0',
  `sell_id` int(11) NOT NULL DEFAULT '0',
  `sell_sid` int(11) NOT NULL DEFAULT '0',
  `ctime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ordertype` (`ordertype`) USING BTREE,
  KEY `buy_id` (`buy_id`) USING BTREE,
  KEY `sell_id` (`sell_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_order_temp
-- ----------------------------
INSERT INTO `tw_order_temp` VALUES ('2', '1', '68', '0', '69', '2', '1529753367');

-- ----------------------------
-- Table structure for tw_pay_method
-- ----------------------------
DROP TABLE IF EXISTS `tw_pay_method`;
CREATE TABLE `tw_pay_method` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_pay_method
-- ----------------------------
INSERT INTO `tw_pay_method` VALUES ('1', '现金存款');
INSERT INTO `tw_pay_method` VALUES ('2', '银行转账');
INSERT INTO `tw_pay_method` VALUES ('3', '支付宝');
INSERT INTO `tw_pay_method` VALUES ('4', '微信支付');

-- ----------------------------
-- Table structure for tw_prompt
-- ----------------------------
DROP TABLE IF EXISTS `tw_prompt`;
CREATE TABLE `tw_prompt` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `mytx` varchar(200) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `sort` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `status` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_prompt
-- ----------------------------

-- ----------------------------
-- Table structure for tw_reg_prize
-- ----------------------------
DROP TABLE IF EXISTS `tw_reg_prize`;
CREATE TABLE `tw_reg_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `addtime` int(11) NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `childuid` int(11) NOT NULL,
  `childuname` varchar(55) NOT NULL,
  `coinid` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_reg_prize
-- ----------------------------

-- ----------------------------
-- Table structure for tw_region
-- ----------------------------
DROP TABLE IF EXISTS `tw_region`;
CREATE TABLE `tw_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rgname` varchar(100) NOT NULL,
  `addtime` int(10) NOT NULL,
  `dluser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_region
-- ----------------------------

-- ----------------------------
-- Table structure for tw_text
-- ----------------------------
DROP TABLE IF EXISTS `tw_text`;
CREATE TABLE `tw_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_text
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade`;
CREATE TABLE `tw_trade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `market` varchar(50) NOT NULL,
  `price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `deal` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `type` tinyint(2) unsigned NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `market` (`market`,`type`,`status`),
  KEY `num` (`num`,`deal`),
  KEY `status` (`status`),
  KEY `market_2` (`market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易下单表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_trade
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_json
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_json`;
CREATE TABLE `tw_trade_json` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market` varchar(100) NOT NULL,
  `data` varchar(500) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `market` (`market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易图表表';

-- ----------------------------
-- Records of tw_trade_json
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_json_copy
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_json_copy`;
CREATE TABLE `tw_trade_json_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `market` varchar(100) NOT NULL,
  `data` varchar(500) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `market` (`market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易图表表';

-- ----------------------------
-- Records of tw_trade_json_copy
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_log`;
CREATE TABLE `tw_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `peerid` int(11) unsigned NOT NULL,
  `market` varchar(50) NOT NULL,
  `price` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `num` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `mum` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee_buy` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `fee_sell` decimal(20,8) unsigned NOT NULL DEFAULT '0.00000000',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `peerid` (`peerid`),
  KEY `main` (`market`,`status`,`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (1500000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (2000000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (2500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (3000000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (3500000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (4000000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (4500000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (5000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN (5500000) ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN (6000000) ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN (6500000) ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN (7000000) ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN (7500000) ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN (8000000) ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (8500000) ENGINE = InnoDB,
 PARTITION p18 VALUES LESS THAN (9000000) ENGINE = InnoDB,
 PARTITION p19 VALUES LESS THAN (9500000) ENGINE = InnoDB,
 PARTITION p20 VALUES LESS THAN (10000000) ENGINE = InnoDB,
 PARTITION p21 VALUES LESS THAN (10500000) ENGINE = InnoDB,
 PARTITION p22 VALUES LESS THAN (11000000) ENGINE = InnoDB,
 PARTITION p23 VALUES LESS THAN (11500000) ENGINE = InnoDB,
 PARTITION p24 VALUES LESS THAN (12000000) ENGINE = InnoDB,
 PARTITION p25 VALUES LESS THAN (12500000) ENGINE = InnoDB,
 PARTITION p26 VALUES LESS THAN (13000000) ENGINE = InnoDB,
 PARTITION p27 VALUES LESS THAN (13500000) ENGINE = InnoDB,
 PARTITION p28 VALUES LESS THAN (14000000) ENGINE = InnoDB,
 PARTITION p29 VALUES LESS THAN (14500000) ENGINE = InnoDB,
 PARTITION p30 VALUES LESS THAN (15000000) ENGINE = InnoDB,
 PARTITION p31 VALUES LESS THAN (15500000) ENGINE = InnoDB,
 PARTITION p32 VALUES LESS THAN (16000000) ENGINE = InnoDB,
 PARTITION p33 VALUES LESS THAN (16500000) ENGINE = InnoDB,
 PARTITION p34 VALUES LESS THAN (17000000) ENGINE = InnoDB,
 PARTITION p35 VALUES LESS THAN (17500000) ENGINE = InnoDB,
 PARTITION p36 VALUES LESS THAN (18000000) ENGINE = InnoDB,
 PARTITION p37 VALUES LESS THAN (18500000) ENGINE = InnoDB,
 PARTITION p38 VALUES LESS THAN (19000000) ENGINE = InnoDB,
 PARTITION p39 VALUES LESS THAN (19500000) ENGINE = InnoDB,
 PARTITION p40 VALUES LESS THAN (20000000) ENGINE = InnoDB,
 PARTITION p41 VALUES LESS THAN (20500000) ENGINE = InnoDB,
 PARTITION p42 VALUES LESS THAN (21000000) ENGINE = InnoDB,
 PARTITION p43 VALUES LESS THAN (21500000) ENGINE = InnoDB,
 PARTITION p44 VALUES LESS THAN (22000000) ENGINE = InnoDB,
 PARTITION p45 VALUES LESS THAN (22500000) ENGINE = InnoDB,
 PARTITION p46 VALUES LESS THAN (23000000) ENGINE = InnoDB,
 PARTITION p47 VALUES LESS THAN (23500000) ENGINE = InnoDB,
 PARTITION p48 VALUES LESS THAN (24000000) ENGINE = InnoDB,
 PARTITION p49 VALUES LESS THAN (24500000) ENGINE = InnoDB,
 PARTITION p50 VALUES LESS THAN (25000000) ENGINE = InnoDB,
 PARTITION p51 VALUES LESS THAN (25500000) ENGINE = InnoDB,
 PARTITION p52 VALUES LESS THAN (26000000) ENGINE = InnoDB,
 PARTITION p53 VALUES LESS THAN (26500000) ENGINE = InnoDB,
 PARTITION p54 VALUES LESS THAN (27000000) ENGINE = InnoDB,
 PARTITION p55 VALUES LESS THAN (27500000) ENGINE = InnoDB,
 PARTITION p56 VALUES LESS THAN (28000000) ENGINE = InnoDB,
 PARTITION p57 VALUES LESS THAN (28500000) ENGINE = InnoDB,
 PARTITION p58 VALUES LESS THAN (29000000) ENGINE = InnoDB,
 PARTITION p59 VALUES LESS THAN (30000000) ENGINE = InnoDB,
 PARTITION p60 VALUES LESS THAN (30500000) ENGINE = InnoDB,
 PARTITION p61 VALUES LESS THAN (31000000) ENGINE = InnoDB,
 PARTITION p62 VALUES LESS THAN (31500000) ENGINE = InnoDB,
 PARTITION p63 VALUES LESS THAN (32000000) ENGINE = InnoDB,
 PARTITION p64 VALUES LESS THAN (32500000) ENGINE = InnoDB,
 PARTITION p65 VALUES LESS THAN (33000000) ENGINE = InnoDB,
 PARTITION p66 VALUES LESS THAN (33500000) ENGINE = InnoDB,
 PARTITION p67 VALUES LESS THAN (34000000) ENGINE = InnoDB,
 PARTITION p68 VALUES LESS THAN (34500000) ENGINE = InnoDB,
 PARTITION p69 VALUES LESS THAN (35000000) ENGINE = InnoDB,
 PARTITION p70 VALUES LESS THAN (35500000) ENGINE = InnoDB,
 PARTITION p71 VALUES LESS THAN (36000000) ENGINE = InnoDB,
 PARTITION p72 VALUES LESS THAN (36500000) ENGINE = InnoDB,
 PARTITION p73 VALUES LESS THAN (37000000) ENGINE = InnoDB,
 PARTITION p74 VALUES LESS THAN (37500000) ENGINE = InnoDB,
 PARTITION p75 VALUES LESS THAN (38000000) ENGINE = InnoDB,
 PARTITION p76 VALUES LESS THAN (38500000) ENGINE = InnoDB,
 PARTITION p77 VALUES LESS THAN (39000000) ENGINE = InnoDB,
 PARTITION p78 VALUES LESS THAN (39500000) ENGINE = InnoDB,
 PARTITION p79 VALUES LESS THAN (40000000) ENGINE = InnoDB,
 PARTITION p80 VALUES LESS THAN (40500000) ENGINE = InnoDB,
 PARTITION p81 VALUES LESS THAN (41000000) ENGINE = InnoDB,
 PARTITION p82 VALUES LESS THAN (41500000) ENGINE = InnoDB,
 PARTITION p83 VALUES LESS THAN (42000000) ENGINE = InnoDB,
 PARTITION p84 VALUES LESS THAN (42500000) ENGINE = InnoDB,
 PARTITION p85 VALUES LESS THAN (43000000) ENGINE = InnoDB,
 PARTITION p86 VALUES LESS THAN (43500000) ENGINE = InnoDB,
 PARTITION p87 VALUES LESS THAN (44000000) ENGINE = InnoDB,
 PARTITION p88 VALUES LESS THAN (44500000) ENGINE = InnoDB,
 PARTITION p89 VALUES LESS THAN (45000000) ENGINE = InnoDB,
 PARTITION p90 VALUES LESS THAN (45500000) ENGINE = InnoDB,
 PARTITION p91 VALUES LESS THAN (46000000) ENGINE = InnoDB,
 PARTITION p92 VALUES LESS THAN (46500000) ENGINE = InnoDB,
 PARTITION p93 VALUES LESS THAN (47000000) ENGINE = InnoDB,
 PARTITION p94 VALUES LESS THAN (47500000) ENGINE = InnoDB,
 PARTITION p95 VALUES LESS THAN (48000000) ENGINE = InnoDB,
 PARTITION p96 VALUES LESS THAN (48500000) ENGINE = InnoDB,
 PARTITION p97 VALUES LESS THAN (49000000) ENGINE = InnoDB,
 PARTITION p98 VALUES LESS THAN (49500000) ENGINE = InnoDB,
 PARTITION p99 VALUES LESS THAN (50000000) ENGINE = InnoDB,
 PARTITION p100 VALUES LESS THAN (50500000) ENGINE = InnoDB,
 PARTITION p101 VALUES LESS THAN (51000000) ENGINE = InnoDB,
 PARTITION p102 VALUES LESS THAN (51500000) ENGINE = InnoDB,
 PARTITION p103 VALUES LESS THAN (52000000) ENGINE = InnoDB,
 PARTITION p104 VALUES LESS THAN (52500000) ENGINE = InnoDB,
 PARTITION p105 VALUES LESS THAN (53000000) ENGINE = InnoDB,
 PARTITION p106 VALUES LESS THAN (53500000) ENGINE = InnoDB,
 PARTITION p107 VALUES LESS THAN (54000000) ENGINE = InnoDB,
 PARTITION p108 VALUES LESS THAN (54500000) ENGINE = InnoDB,
 PARTITION p109 VALUES LESS THAN (55000000) ENGINE = InnoDB,
 PARTITION p110 VALUES LESS THAN (55500000) ENGINE = InnoDB,
 PARTITION p111 VALUES LESS THAN (56000000) ENGINE = InnoDB,
 PARTITION p112 VALUES LESS THAN (56500000) ENGINE = InnoDB,
 PARTITION p113 VALUES LESS THAN (57000000) ENGINE = InnoDB,
 PARTITION p114 VALUES LESS THAN (57500000) ENGINE = InnoDB,
 PARTITION p115 VALUES LESS THAN (58000000) ENGINE = InnoDB,
 PARTITION p116 VALUES LESS THAN (58500000) ENGINE = InnoDB,
 PARTITION p117 VALUES LESS THAN (59000000) ENGINE = InnoDB,
 PARTITION p118 VALUES LESS THAN (59500000) ENGINE = InnoDB,
 PARTITION p119 VALUES LESS THAN (60000000) ENGINE = InnoDB,
 PARTITION p120 VALUES LESS THAN (60500000) ENGINE = InnoDB,
 PARTITION p121 VALUES LESS THAN (61000000) ENGINE = InnoDB,
 PARTITION p122 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_trade_log
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_prize
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_prize`;
CREATE TABLE `tw_trade_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpc` decimal(20,6) NOT NULL,
  `trade_sum` decimal(20,1) NOT NULL DEFAULT '0.0',
  `addtime` int(10) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_trade_prize
-- ----------------------------

-- ----------------------------
-- Table structure for tw_trade_sum
-- ----------------------------
DROP TABLE IF EXISTS `tw_trade_sum`;
CREATE TABLE `tw_trade_sum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` int(10) NOT NULL,
  `amount` decimal(20,1) NOT NULL DEFAULT '0.0',
  `prizesum` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `history_prize` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_trade_sum
-- ----------------------------

-- ----------------------------
-- Table structure for tw_usdt
-- ----------------------------
DROP TABLE IF EXISTS `tw_usdt`;
CREATE TABLE `tw_usdt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_usdt
-- ----------------------------
INSERT INTO `tw_usdt` VALUES ('1', '人民币', 'CNY', '6.50615194', '1529907601');
INSERT INTO `tw_usdt` VALUES ('2', '美元', 'USD', '0.99941000', '1529907601');
INSERT INTO `tw_usdt` VALUES ('3', '澳元', 'AUD', '1.34613002', '1529907601');
INSERT INTO `tw_usdt` VALUES ('4', '日元', 'JPY', '109.42545096', '1529907601');
INSERT INTO `tw_usdt` VALUES ('5', '韩币', 'KRW', '1115.70681563', '1529907601');
INSERT INTO `tw_usdt` VALUES ('6', '加元', 'CAD', '1.32834555', '1529907601');
INSERT INTO `tw_usdt` VALUES ('7', '法郎', 'CHF', '0.98685718', '1529907601');
INSERT INTO `tw_usdt` VALUES ('8', '卢比', 'INR', '68.04493617', '1529907601');
INSERT INTO `tw_usdt` VALUES ('9', '卢布', 'RUB', '63.09207411', '1529907601');
INSERT INTO `tw_usdt` VALUES ('10', '欧元', 'EUR', '0.85767125', '1529907601');
INSERT INTO `tw_usdt` VALUES ('11', '英镑', 'GBP', '0.75361193', '1529907601');
INSERT INTO `tw_usdt` VALUES ('12', '港币', 'HKD', '7.84244640', '1529907601');
INSERT INTO `tw_usdt` VALUES ('13', '巴西雷亚尔', 'BRL', '3.78336532', '1529907601');
INSERT INTO `tw_usdt` VALUES ('14', '印尼盾', 'IDR', '14069.40706879', '1529907601');
INSERT INTO `tw_usdt` VALUES ('15', '比索', 'MXN', '20.07710399', '1529907601');
INSERT INTO `tw_usdt` VALUES ('16', '台币', 'TWD', '30.39648367', '1529907601');
INSERT INTO `tw_usdt` VALUES ('17', '令吉', 'MYR', '4.01210893', '1529907601');
INSERT INTO `tw_usdt` VALUES ('18', '新币 ', 'SGD', '1.36250099', '1529907601');

-- ----------------------------
-- Table structure for tw_usdt_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_usdt_log`;
CREATE TABLE `tw_usdt_log` (
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_usdt_log
-- ----------------------------
INSERT INTO `tw_usdt_log` VALUES ('1', '18253251582', '68', '1526001528', '1', '0', '14.99250374', '买家下单减可用USDT，手续费0', '69', '1', '1', '39.89.46.62', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('2', '18253251582', '68', '1526001528', '1', '1', '14.99250374', '买家下单加冻结USDT，手续费0', '69', '2', '1', '39.89.46.62', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('3', '18253251582', '68', '1526001549', '1', '0', '14.99250374', '卖家释放币减冻结USDT', '68', '2', '5', '119.81.249.135', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('4', '17554227203', '69', '1526001549', '1', '1', '14.99250374', '卖家释放币加可用USDT', '68', '1', '5', '119.81.249.135', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('5', '18253251582', '68', '1529194281', '1', '0', '100.00000000', '买家下单减可用USDT，手续费0', '78', '1', '1', '180.97.200.28', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('6', '18253251582', '68', '1529194281', '1', '1', '100.00000000', '买家下单加冻结USDT，手续费0', '78', '2', '1', '180.97.200.28', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('7', '18253251582', '68', '1529718575', '1', '0', '100.00000000', '买家超时不付款订单取消卖家减冻结USDT', '0', '2', '6', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('8', '18253251582', '68', '1529718575', '1', '1', '100.00000000', '买家超时不付款订单取消卖家加可用USDT', '0', '1', '6', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('9', '18253251582', '68', '1529752090', '1', '0', '14.61988304', '买家下单减可用USDT，手续费0', '69', '1', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('10', '18253251582', '68', '1529752090', '1', '1', '14.61988304', '买家下单加冻结USDT，手续费0', '69', '2', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('11', '18253251582', '68', '1529752098', '1', '0', '14.61988304', '买家取消订单减冻结USDT', '69', '2', '3', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('12', '18253251582', '68', '1529752098', '1', '1', '14.61988304', '买家取消订单加可用USDT', '69', '1', '3', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('13', '18253251582', '68', '1529752251', '1', '0', '29.28257686', '买家下单减可用USDT，手续费0', '69', '1', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('14', '18253251582', '68', '1529752251', '1', '1', '29.28257686', '买家下单加冻结USDT，手续费0', '69', '2', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('15', '18253251582', '68', '1529752269', '1', '0', '29.28257686', '买家取消订单减冻结USDT', '69', '2', '3', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('16', '18253251582', '68', '1529752269', '1', '1', '29.28257686', '买家取消订单加可用USDT', '69', '1', '3', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('17', '18253251582', '68', '1529752290', '1', '0', '29.28257686', '买家下单减可用USDT，手续费0', '69', '1', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('18', '18253251582', '68', '1529752290', '1', '1', '29.28257686', '买家下单加冻结USDT，手续费0', '69', '2', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('19', '18253251582', '68', '1529752321', '1', '0', '43.92386530', '买家下单减可用USDT，手续费0', '69', '1', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('20', '18253251582', '68', '1529752321', '1', '1', '43.92386530', '买家下单加冻结USDT，手续费0', '69', '2', '1', '161.202.48.247', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('21', '18253251582', '68', '1529752508', '1', '0', '43.92386530', '卖家释放币减冻结USDT', '68', '2', '5', '60.209.33.43', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('22', '17554227203', '69', '1529752508', '1', '1', '43.92386530', '卖家释放币加可用USDT', '68', '1', '5', '60.209.33.43', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('23', '18253251582', '68', '1529889112', '1', '0', '29.28257686', '卖家释放币减冻结USDT', '1', '2', '7', '60.209.33.43', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('24', '17554227203', '69', '1529889112', '1', '1', '29.28257686', '卖家释放币加可用USDT', '1', '1', '7', '60.209.33.43', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('25', '18253251582', '68', '1529994251', '2', '0', '14.92537313', '卖家下单减可用USDT', '68', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('26', '18253251582', '68', '1529994251', '2', '1', '14.92537313', '卖家下单加冻结USDT', '68', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('27', '15764251917', '76', '1529997029', '2', '0', '7.61035007', '卖家下单减可用USDT', '76', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('28', '15764251917', '76', '1529997029', '2', '1', '7.61035007', '卖家下单加冻结USDT', '76', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('45', '17554227203', '69', '1530077642', '1', '0', '14.77104874', '买家下单减可用USDT，手续费0', '84', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('46', '17554227203', '69', '1530077642', '1', '1', '14.77104874', '买家下单加冻结USDT，手续费0', '84', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('55', '15764251917', '76', '1530081277', '2', '0', '8.00000000', '卖家下单减可用USDT', '76', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('56', '15764251917', '76', '1530081277', '2', '1', '8.00000000', '卖家下单加冻结USDT', '76', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('57', '15764251917', '76', '1530081511', '2', '0', '8.00000000', '卖家下单减可用USDT', '76', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('58', '15764251917', '76', '1530081511', '2', '1', '8.00000000', '卖家下单加冻结USDT', '76', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('59', '15764251917', '76', '1530238139', '2', '0', '14.92537313', '卖家下单减可用USDT', '76', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('60', '15764251917', '76', '1530238139', '2', '1', '14.92537313', '卖家下单加冻结USDT', '76', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('61', '15764251917', '76', '1530238144', '2', '0', '14.92537313', '卖家下单减可用USDT', '76', '1', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('62', '15764251917', '76', '1530238144', '2', '1', '14.92537313', '卖家下单加冻结USDT', '76', '2', '2', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('63', '17554227203', '69', '1530238156', '1', '0', '14.77104874', '买家下单减可用USDT，手续费0', '76', '1', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('64', '17554227203', '69', '1530238156', '1', '1', '14.77104874', '买家下单加冻结USDT，手续费0', '76', '2', '1', '127.0.0.1', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('65', '17554227203', '69', '1530500799', '1', '0', '162.48153618', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('66', '17554227203', '69', '1530500799', '1', '1', '162.48153618', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('67', '17554227203', '69', '1530500806', '1', '0', '162.48153618', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('68', '17554227203', '69', '1530500806', '1', '1', '162.48153618', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('69', '17554227203', '69', '1530500812', '1', '0', '162.48153618', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('70', '17554227203', '69', '1530500812', '1', '1', '162.48153618', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('71', '17554227203', '69', '1530500901', '1', '0', '162.48153618', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('72', '17554227203', '69', '1530500901', '1', '1', '162.48153618', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('73', '18954215320', '77', '1530501125', '2', '0', '100.00000000', '卖家下单减可用USDT', '77', '1', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('74', '18954215320', '77', '1530501125', '2', '1', '100.00000000', '卖家下单加冻结USDT', '77', '2', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('75', '18954215320', '77', '1530501173', '2', '0', '100.00000000', '卖家下单减可用USDT', '77', '1', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('76', '18954215320', '77', '1530501173', '2', '1', '100.00000000', '卖家下单加冻结USDT', '77', '2', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('77', '18954215320', '77', '1530501292', '2', '0', '100.00000000', '卖家下单减可用USDT', '77', '1', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('78', '18954215320', '77', '1530501292', '2', '1', '100.00000000', '卖家下单加冻结USDT', '77', '2', '2', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('79', '18253251582', '68', '1530759945', '1', '0', '14.64128843', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('80', '18253251582', '68', '1530759945', '1', '1', '14.64128843', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('81', '18253251582', '68', '1530759969', '1', '0', '14.64128843', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('82', '18253251582', '68', '1530759969', '1', '1', '14.64128843', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('83', '18253251582', '68', '1530759998', '1', '0', '14.64128843', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('84', '18253251582', '68', '1530759998', '1', '1', '14.64128843', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('85', '18253251582', '68', '1530760002', '1', '0', '14.64128843', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('86', '18253251582', '68', '1530760002', '1', '1', '14.64128843', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('87', '18253251582', '68', '1530760206', '1', '0', '14.64128843', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('88', '18253251582', '68', '1530760206', '1', '1', '14.64128843', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('89', '17554227203', '69', '1530760238', '1', '0', '29.54209748', '买家下单减可用USDT，手续费0', '77', '1', '1', '192.168.1.216', '比特币');
INSERT INTO `tw_usdt_log` VALUES ('90', '17554227203', '69', '1530760238', '1', '1', '29.54209748', '买家下单加冻结USDT，手续费0', '77', '2', '1', '192.168.1.216', '比特币');

-- ----------------------------
-- Table structure for tw_user
-- ----------------------------
DROP TABLE IF EXISTS `tw_user`;
CREATE TABLE `tw_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `gjcode` int(11) NOT NULL DEFAULT '86' COMMENT '国际区号',
  `mobile` varchar(50) DEFAULT NULL,
  `enname` varchar(50) DEFAULT NULL COMMENT '英文用户名',
  `headimg` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `goodnum` int(11) NOT NULL DEFAULT '0' COMMENT '好评的次数',
  `goodcomm` int(11) NOT NULL DEFAULT '0' COMMENT '好评度',
  `mobiletime` int(11) unsigned NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL,
  `jianjie` varchar(255) DEFAULT NULL COMMENT '个人简介',
  `history` decimal(20,8) NOT NULL DEFAULT '0.00000000' COMMENT '历史成交',
  `tpwdsetting` varchar(32) NOT NULL DEFAULT '',
  `sftime_sum` int(11) NOT NULL DEFAULT '0' COMMENT '总的释放时间',
  `first_trade_time` int(11) DEFAULT '0' COMMENT '第一次交易的时间',
  `transact` int(11) NOT NULL DEFAULT '0' COMMENT '交易次数',
  `paypassword` varchar(50) NOT NULL DEFAULT '',
  `invit_1` varchar(50) NOT NULL DEFAULT '',
  `invit_2` varchar(50) NOT NULL DEFAULT '',
  `invit_3` varchar(50) NOT NULL DEFAULT '',
  `truename` varchar(32) DEFAULT '',
  `idcard` varchar(32) DEFAULT '',
  `logins` int(11) unsigned NOT NULL DEFAULT '0',
  `ga` varchar(50) DEFAULT NULL,
  `addip` varchar(50) NOT NULL DEFAULT '',
  `addr` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `alipay` varchar(50) DEFAULT NULL COMMENT '支付宝',
  `invit` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `mibao_question` varchar(200) DEFAULT NULL COMMENT '密保',
  `mibao_answer` varchar(200) DEFAULT NULL COMMENT '密保答案',
  `zhengjian` varchar(20) DEFAULT NULL,
  `idcard_zheng` varchar(200) DEFAULT NULL,
  `idcard_fan` varchar(200) DEFAULT NULL,
  `findpwd_mibao` tinyint(1) DEFAULT '0',
  `findpaypwd_mibao` tinyint(1) DEFAULT '0',
  `is_agree` tinyint(1) DEFAULT '0' COMMENT '0是未进行同意操作（默认） 1是已经同意',
  `freason` varchar(255) DEFAULT NULL,
  `idcard_shouchi` varchar(200) DEFAULT NULL,
  `ethpassword` varchar(50) DEFAULT NULL,
  `etcpassword` varchar(50) DEFAULT NULL,
  `pwd_err` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登陆密码错误次数',
  `buy_sum` decimal(20,5) DEFAULT '0.00000',
  `sell_sum` decimal(20,5) DEFAULT '0.00000',
  `trade_sum` decimal(20,5) DEFAULT '0.00000',
  `cpcprize_sum` int(11) DEFAULT '0',
  `noid` bigint(20) unsigned DEFAULT NULL,
  `region` int(10) DEFAULT '0',
  `xinren` mediumtext COMMENT '被信任的用户id用,隔开',
  `pingbi` mediumtext COMMENT '被屏蔽的用户id用,隔开',
  `ixinren` mediumtext COMMENT '我信任的id',
  `ipingbi` mediumtext COMMENT '我屏蔽的id',
  `trade_id` mediumtext COMMENT '完成交易后对方的id(可重复)',
  `logintime` int(11) DEFAULT NULL COMMENT '登陆时间',
  `loginstatus` int(11) DEFAULT NULL COMMENT '登录状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of tw_user
-- ----------------------------
INSERT INTO `tw_user` VALUES ('1', 'admin', '86', '13210095960', 'adadczsa', '', '6', '100', '1495880575', '821f3157e1a3456bfe1a000a1adf0862', '', '0.00000000', '1', '158908', '1511768661', '6', '821f3157e1a3456bfe1a000a1adf0862', '0', '0', '0', 'gao', '371524199306015816', '109', '', '123.151.42.48', '未分配或者内网IP', '0', '1495880575', '0', '1', '88888888@qq.com', '', 'UJRFSIPAY', '', '你的出生日期', 'hunaosdf0318432', 'sfz', '', '', '0', '0', '2', '21', '', null, null, '0', '0.00000', '0.00000', '0.00000', '0', '1', '0', ',1,2,3,4,5,1359,32,27', ',1359', ',14,7,35', null, ',1051,2,1051,3,1051,7,7,20,35,27,35', '1529543311', '1');
INSERT INTO `tw_user` VALUES ('68', '18253251582', '86', '18253251582', 'test001', null, '2', '100', '1525946652', '821f3157e1a3456bfe1a000a1adf0862', '来来来', '0.00000000', '1', '2283', '1526001549', '3', 'c293048f9e4415de9d3c28705d5c4646', '0', '0', '0', '马健', '211481198401154411', '16', '', '161.202.48.247', '瑞士', '0', '1525946652', '0', '1', '', null, 'AKDWYHIZS', null, null, null, 'sfz', '/Upload/lanch/idcard/20180709/s_5b42fa7826bfc.jpg', '/Upload/lanch/idcard/20180709/s_5b42fa7a189db.jpg', '0', '0', '1', null, '/Upload/lanch/idcard/20180709/s_5b42fa7d2cec4.jpg', 'eth6810963917', null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, '', '', ',69,69,69', '1531124779', '1');
INSERT INTO `tw_user` VALUES ('69', '17554227203', '86', '17554227203', 'test002', null, '3', '100', '1525946778', '821f3157e1a3456bfe1a000a1adf0862', '', '0.00000000', '1', '0', '1526001549', '3', 'c293048f9e4415de9d3c28705d5c4646', '68', '0', '0', '彭镇', '152801198703025310', '8', null, '39.89.46.62', '山东省联通', '0', '1525946778', '0', '1', '', null, 'LIYRVZPFD', null, null, null, 'sfz', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af4ef139f2ae.jpg', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af4ef19865ec.jpg', '0', '0', '1', null, 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af4ef1ff3208.jpg', 'eth6920060609', null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', '', '', null, null, ',68,68,68', '1531124888', '1');
INSERT INTO `tw_user` VALUES ('70', '15966800028', '86', '15966800028', 'wsk0028', null, '0', '0', '1525955890', '6345ba4338c9acdda3b6a6d333ecd2a3', null, '0.00000000', '1', '0', '0', '0', '2b792dabb4328a140caef066322c49ff', '0', '0', '0', '王树奎', '232622196610132119', '7', 'NPXX2RGW77RLFPLL|1|1', '123.235.198.25', '山东省青岛市联通', '0', '1525955890', '0', '0', '158254590@qq.com', null, 'TFVYZSCPR', null, null, null, 'sfz', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180513/s_5af7a1c0cd4d7.jpg', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180513/s_5af7a1c160674.jpg', '0', '0', '0', null, 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180513/s_5af7a1c287380.jpg', null, null, '5', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526192659', '0');
INSERT INTO `tw_user` VALUES ('71', '15969837875', '86', '15969837875', 'wzh7875', null, '0', '0', '1526007665', 'b67fa1f74641710c248e0a4dfb4ccb71', null, '0.00000000', '1', '0', '0', '0', '38cfd7e6e6e8f27aa57452567afa0b76', '0', '0', '0', '王振华', '622224198812045016', '2', 'LJVEXXFQP5E4IB4R|1|1', '223.80.234.118', '山东省青岛市移动', '0', '1526007665', '0', '1', '15969837875@163.com', null, 'BGPVIYQNW', null, null, null, 'sfz', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af509fed5580.jpg', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af509ff7b2a1.jpg', '0', '0', '0', null, 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180511/s_5af50a011e65a.jpg', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526194161', '1');
INSERT INTO `tw_user` VALUES ('72', '18222924979', '86', '18222924979', 'xiaojian', null, '0', '0', '1526027289', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '0', '0', '0', '', '0', '0', '0', '', '', '0', null, '60.209.241.96', '山东省青岛市联通', '0', '1526027289', '0', '1', null, null, 'KCDZSTWFB', null, null, null, null, null, null, '0', '0', '0', null, null, null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526028149', '1');
INSERT INTO `tw_user` VALUES ('73', '18610550287', '86', '18610550287', 'xiaowu', null, '0', '0', '1526027345', '821f3157e1a3456bfe1a000a1adf0862', '', '0.00000000', '1', '0', '0', '0', 'c293048f9e4415de9d3c28705d5c4646', '0', '0', '0', '杨大伟', '520201661010163', '1', null, '39.89.46.62', '山东省联通', '0', '1526027345', '0', '1', '1923191641@qq.com', null, 'NYKZVHBSP', null, null, null, 'sfz', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180514/s_5af8ee07cd9ab.jpg', 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180514/s_5af8ee07d56ad.jpg', '0', '0', '1', null, 'http://copro.oss-cn-hongkong.aliyuncs.com/idcard/20180514/s_5af8ee07d9146.jpg', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526283036', '1');
INSERT INTO `tw_user` VALUES ('74', '13512838751', '86', '13512838751', 'longtianyu8', null, '0', '0', '1526111276', 'e14b2601d577529654fa67698f49653c', null, '0.00000000', '1', '0', '0', '0', '9cf662c39b9832ffa303b597c714af55', '0', '0', '0', '', '', '0', 'WHYQG7BQBJPS53ME|1|1', '60.11.212.223', '黑龙江省鹤岗市联通', '0', '1526111276', '0', '1', 'longtianyu8@163.com', null, 'DAPJQXFEG', null, null, null, null, null, null, '0', '0', '0', null, null, null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526111802', '1');
INSERT INTO `tw_user` VALUES ('75', 'shoubi', '86', 'shoubi', 'shoubi', null, '0', '0', '1526266428', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '0', '0', '0', '', '0', '0', '0', '', '', '0', null, '127.0.0.1', '未分配或者内网IP', '0', '1526266428', '0', '1', null, null, 'DMSRQZVXT', null, null, null, null, null, null, '0', '0', '0', null, null, null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1526266585', '1');
INSERT INTO `tw_user` VALUES ('76', '15764251917', '86', '15764251917', 'gaoadfc', null, '0', '0', '1527476173', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '0', '1529998211', '1', '821f3157e1a3456bfe1a000a1adf0862', '0', '0', '0', '李伟', '371524199306015817', '19', null, '127.0.0.1', '未分配或者内网IP', '0', '1527476174', '0', '1', null, null, 'XHIFPMGSQ', null, null, null, 'sfz', 'http://tbzhe.oss-ap-southeast-1.aliyuncs.com/idcard/20180628/s_5b344305ee44f.jpg', 'http://tbzhe.oss-ap-southeast-1.aliyuncs.com/idcard/20180628/s_5b344305f038f.jpg', '0', '0', '0', null, 'http://tbzhe.oss-ap-southeast-1.aliyuncs.com/idcard/20180628/s_5b344305f1717.png', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, ',83', null, ',83', '1531467351', '1');
INSERT INTO `tw_user` VALUES ('77', '18954215320', '86', '18954215320', 'wangyujie', null, '0', '0', '1529029261', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '0', '0', '0', 'dff33bebd2404125cd813c90a69f2c84', '0', '0', '0', '王玉', '152801198703025315', '4', null, '60.209.8.157', '山东省青岛市联通', '0', '1529029261', '0', '1', null, null, 'NAJPXRFVQ', null, null, null, 'sfz', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180702/s_5b3995e6404e9.jpg', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180702/s_5b3995e640cb9.jpg', '0', '0', '1', null, 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180702/s_5b3995e641489.jpg', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1530760238', '1');
INSERT INTO `tw_user` VALUES ('78', '13964260078', '86', '13964260078', 'xinjie', null, '0', '0', '1529052288', 'a5b70fb66f9bda8f84dd554e4ed17086', null, '0.00000000', '1', '0', '0', '0', '', '0', '0', '0', '', '', '19', null, '60.209.8.157', '山东省青岛市联通', '0', '1529052288', '0', '1', null, null, 'NUSDFWLIV', null, null, null, null, null, null, '0', '0', '0', null, null, 'eth7899562850', null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1529902745', '1');
INSERT INTO `tw_user` VALUES ('80', '18504315301', '86', '18504315301', 'gaoyao1992', null, '0', '0', '1529291028', 'ca3910caef01f7087a0102b30635c76b', null, '0.00000000', '1', '0', '0', '0', 'ffbde2c137da95f6c3a1626661588069', '0', '0', '0', '', '', '1', null, '101.190.13.144', '澳大利亚', '0', '1529291028', '0', '1', null, null, 'GQLEUVWHF', null, null, null, null, null, null, '0', '0', '0', null, null, 'eth8010237288', null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1529907959', '1');
INSERT INTO `tw_user` VALUES ('81', '18815572632', '86', '18815572632', 'test00001', null, '0', '0', '1529737759', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '0', '0', '0', '7cbb3252ba6b7e9c422fac5334d22054', '68', '0', '0', '胡海洋', '433026196612172414', '2', null, '60.209.33.43', '山东省青岛市联通', '0', '1529737759', '0', '1', null, null, 'ETBWYQCVS', null, null, null, 'sfz', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26ad731a.jpg', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26b2f9ba.jpg', '0', '0', '1', null, 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26c2144d.jpg', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1529753963', '0');
INSERT INTO `tw_user` VALUES ('82', '15727303293', '86', '15727303293', 'vipdooo', null, '0', '0', '1529749341', 'a7625428fa3dc634bb11100604db99ce', null, '0.00000000', '1', '0', '0', '0', '9ad3698c02ef76ff4ef28868aab1358a', '0', '0', '0', '', '', '6', null, '220.235.135.13', '澳大利亚', '0', '1529749341', '0', '1', null, null, 'NYKLQGHXS', null, null, null, null, null, null, '0', '0', '0', null, null, 'eth8246843213', null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1529908001', '1');
INSERT INTO `tw_user` VALUES ('83', '17561917361', '86', '17561917361', 'gaoadfc1', null, '0', '0', '1529984994', '821f3157e1a3456bfe1a000a1adf0862', null, '0.00000000', '1', '26', '1529998211', '1', '821f3157e1a3456bfe1a000a1adf0862', '0', '0', '0', '胡海洋', '433026196612172414', '3', null, '127.0.0.1', '未分配或者内网IP', '0', '1529984994', '0', '1', null, null, 'URFIBWHJZ', null, null, null, 'sfz', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26ad731a.jpg', 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26ad731a.jpg', '0', '0', '1', null, 'http://tdcw.oss-cn-hongkong.aliyuncs.com/idcard/20180623/s_5b2df26ad731a.jpg', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', ',76', null, null, null, ',76', '1530079264', '0');
INSERT INTO `tw_user` VALUES ('84', '15866838358', '86', '15866838358', 'a603574937', null, '0', '0', '1530071557', '821f3157e1a3456bfe1a000a1adf0862', '', '0.00000000', '1', '0', '0', '0', 'c293048f9e4415de9d3c28705d5c4646', '1', '0', '0', '陈冠希', '130133831013213', '7', null, '127.0.0.1', '未分配或者内网IP', '0', '1530071557', '0', '1', '', null, 'IWFCNUBMA', null, null, null, 'sfz', '', '', '0', '0', '2', '111', '', null, null, '0', '0.00000', '0.00000', '0.00000', '0', null, '0', null, null, null, null, null, '1531123354', '0');

-- ----------------------------
-- Table structure for tw_user_bank
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_bank`;
CREATE TABLE `tw_user_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `bank` varchar(200) NOT NULL DEFAULT '',
  `bankprov` varchar(200) NOT NULL DEFAULT '',
  `bankcity` varchar(200) NOT NULL DEFAULT '',
  `bankaddr` varchar(200) NOT NULL DEFAULT '',
  `bankcard` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_user_bank
-- ----------------------------

-- ----------------------------
-- Table structure for tw_user_bank_type
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_bank_type`;
CREATE TABLE `tw_user_bank_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `img` varchar(200) NOT NULL DEFAULT '',
  `mytx` varchar(200) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='常用银行地址';

-- ----------------------------
-- Records of tw_user_bank_type
-- ----------------------------
INSERT INTO `tw_user_bank_type` VALUES ('4', 'boc', '中国银行', 'http://www.boc.cn/', 'img_56937003683ce.jpg', '', '', '0', '1452503043', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('5', 'abc', '农业银行', 'http://www.abchina.com/cn/', 'img_569370458b18d.jpg', '', '', '0', '1452503109', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('6', 'bccb', '北京银行', 'http://www.bankofbeijing.com.cn/', 'img_569370588dcdc.jpg', '', '', '0', '1452503128', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('8', 'ccb', '建设银行', 'http://www.ccb.com/', 'img_5693709bbd20f.jpg', '', '', '0', '1452503195', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('9', 'ceb', '光大银行', 'http://www.bankofbeijing.com.cn/', 'img_569370b207cc8.jpg', '', '', '0', '1452503218', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('10', 'cib', '兴业银行', 'http://www.cib.com.cn/cn/index.html', 'img_569370d29bf59.jpg', '', '', '0', '1452503250', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('11', 'citic', '中信银行', 'http://www.ecitic.com/', 'img_569370fb7a1b3.jpg', '', '', '0', '1452503291', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('12', 'cmb', '招商银行', 'http://www.cmbchina.com/', 'img_5693710a9ac9c.jpg', '', '', '0', '1452503306', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('13', 'cmbc', '民生银行', 'http://www.cmbchina.com/', 'img_5693711f97a9d.jpg', '', '', '0', '1452503327', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('14', 'comm', '交通银行', 'http://www.bankcomm.com/BankCommSite/default.shtml', 'img_5693713076351.jpg', '', '', '0', '1452503344', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('16', 'gdb', '广发银行', 'http://www.cgbchina.com.cn/', 'img_56937154bebc5.jpg', '', '', '0', '1452503380', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('17', 'icbc', '工商银行', 'http://www.icbc.com.cn/icbc/', 'img_56937162db7f5.jpg', '', '', '0', '1452503394', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('19', 'psbc', '邮政银行', 'http://www.psbc.com/portal/zh_CN/index.html', 'img_5693717eefaa3.jpg', '', '', '0', '1452503422', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('20', 'spdb', '浦发银行', 'http://www.spdb.com.cn/chpage/c1/', 'img_5693718f1d70e.jpg', '', '', '0', '1452503439', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('21', 'szpab', '平安银行', 'http://bank.pingan.com/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '1');
INSERT INTO `tw_user_bank_type` VALUES ('22', 'alipay', '支付宝', 'https://www.alipay.com/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '0');
INSERT INTO `tw_user_bank_type` VALUES ('23', 'tenpay', '财付通', 'https://www.tenpay.com/v3/', '56c2e4c9aff85.jpg', '', '', '0', '1455613129', '0', '0');

-- ----------------------------
-- Table structure for tw_user_coin
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_coin`;
CREATE TABLE `tw_user_coin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `usdt` decimal(20,8) unsigned NOT NULL,
  `usdtd` decimal(20,8) unsigned NOT NULL,
  `usdtb` varchar(200) NOT NULL,
  `btc` decimal(20,8) unsigned NOT NULL,
  `btcd` decimal(20,8) unsigned NOT NULL,
  `btcb` varchar(200) NOT NULL,
  `eth` decimal(20,8) unsigned NOT NULL,
  `ethd` decimal(20,8) unsigned NOT NULL,
  `ethb` varchar(200) NOT NULL,
  `hor` decimal(20,8) unsigned NOT NULL,
  `hord` decimal(20,8) unsigned NOT NULL,
  `horb` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COMMENT='用户币种表';

-- ----------------------------
-- Records of tw_user_coin
-- ----------------------------
INSERT INTO `tw_user_coin` VALUES ('1', '1', '11.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('69', '68', '532.66174256', '88.13181528', '1Dxap6p9kFxxocAKsJVXimm95pS41aeJQj', '6.62989000', '0.00000000', '15fRwf9ePtanUjKZqsNUssed6c4XsGgyQH', '47.99800000', '0.00000000', '0x76d71eb19d44102088e8642a6c56493f6e29266d', '995.85000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('70', '69', '9379.18860622', '709.01033968', '1CRAvQcU5L42hRrPD7NdS5gf62qd7twfMu', '1.89980000', '0.00000000', '', '0.99980000', '0.00000000', '0x80ae819ed600f8f2d6c6abcff2c20d4a845e5875', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('71', '70', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('72', '71', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('73', '72', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('74', '73', '79.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('75', '74', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('76', '75', '3000.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('77', '76', '29946.53890367', '53.46109633', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '51.48514851', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('78', '77', '5.00000000', '300.00000000', '', '99.95107826', '0.04892174', '', '100.00000000', '0.00000000', '', '100.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('79', '78', '0.00000000', '0.00000000', '1K9J6r2NCwdbJ6aCqU4GcgL3oYHWdfVNWu', '0.00000000', '0.00000000', '1GbQPncmb711ooMhn8zyKHEYcjhsDvtMd3', '0.00000000', '0.00000000', '0x3fe11283a0e7a294c0fb820693a85d0778c00d59', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('81', '80', '0.00000000', '0.00000000', '1A4rCdWhuvx6ZL1oB6G65M6UDsbA3zEcpU', '0.00000000', '0.00000000', '17WHJMz9U9xy1zrBQ6QR15XK28ceCs2xcN', '2.00000000', '0.00000000', '0xace98bc626cc16e34652ab6d126bd80127c0caa5', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('82', '81', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '17oZb4sDCFcrsCdsPcEc3Yth7MCDuRGriJ', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('83', '82', '0.00000000', '0.00000000', '1GXJ4dTT9brfqArRkMW2hGTCBebztGbSLA', '0.00000000', '0.00000000', '1D7UBTM4kZJyjnVUQk18W5puGVGvBizH12', '0.00000000', '0.00000000', '0x0092038ebbb82e09bb6108e1029bd4be7824287b', '0.00000000', '0.00000000', '');
INSERT INTO `tw_user_coin` VALUES ('84', '83', '200.00000000', '0.00000000', '', '200.00000000', '0.00000000', '', '200.00000000', '0.00000000', '', '2000.00000000', '262.37623760', '');
INSERT INTO `tw_user_coin` VALUES ('85', '84', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '', '0.00000000', '0.00000000', '');

-- ----------------------------
-- Table structure for tw_user_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_log`;
CREATE TABLE `tw_user_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(20) NOT NULL DEFAULT '',
  `addr` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `session_key` varchar(100) DEFAULT NULL,
  `state` tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `state` (`state`) USING BTREE,
  KEY `session_key` (`session_key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COMMENT='用户记录表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (100000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (200000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (300000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (400000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (600000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (700000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (800000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (900000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_user_log
-- ----------------------------
INSERT INTO `tw_user_log` VALUES ('71', '78', 'login', 'PC端，Safari/602.1登录', '180.97.200.34', '江苏省苏州市电信', '0', '1529822352', '1529833733', '1', 'lcv7kvnqs578gie6bj3qbt0u80', '0');
INSERT INTO `tw_user_log` VALUES ('72', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '180.97.200.34', '江苏省苏州市电信', '0', '1529833732', '1529840123', '1', 'psm7apf996dscr3cipsjbhre46', '0');
INSERT INTO `tw_user_log` VALUES ('73', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529889083', '1529889258', '1', 'v6hd9n6vk2vgjuh8u23a223o31', '0');
INSERT INTO `tw_user_log` VALUES ('74', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529889722', '1529895698', '1', 'v6hd9n6vk2vgjuh8u23a223o31', '0');
INSERT INTO `tw_user_log` VALUES ('75', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '119.81.249.135', '香港SoftLayer数据中心', '0', '1529895698', '1529896261', '1', 'pf6dm780aoauavojq5cl1gsjq5', '0');
INSERT INTO `tw_user_log` VALUES ('76', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '119.81.249.135', '香港SoftLayer数据中心', '0', '1529896261', '1529994108', '1', 'pf6dm780aoauavojq5cl1gsjq5', '0');
INSERT INTO `tw_user_log` VALUES ('77', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529897667', '1529900474', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('78', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.33.43', '山东省青岛市联通', '0', '1529899159', '1529899159', '1', 'psm7apf996dscr3cipsjbhre46', '1');
INSERT INTO `tw_user_log` VALUES ('79', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529900492', '1529900496', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('80', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529900527', '1529901002', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('81', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529901072', '1529901517', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('82', '82', 'login', '注册完成后自动登录', '101.190.13.144', '澳大利亚', '0', '1529901517', '1529901572', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('83', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529901682', '1529905157', '1', '15snm08eo3eaoigbvj607vknl1', '0');
INSERT INTO `tw_user_log` VALUES ('84', '80', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.167登录', '101.190.13.144', '澳大利亚', '0', '1529906973', '1529906973', '1', 'pcot4qmm32642c4c05993m9in7', '1');
INSERT INTO `tw_user_log` VALUES ('85', '82', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529907067', '1529907067', '1', '15snm08eo3eaoigbvj607vknl1', '1');
INSERT INTO `tw_user_log` VALUES ('86', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1529983427', '1530060084', '1', '2mfqihrtmu5olkaaijusalu5u1', '0');
INSERT INTO `tw_user_log` VALUES ('87', '83', 'login', '注册完成后自动登录', '127.0.0.1', '未分配或者内网IP', '0', '1529984994', '1529996888', '1', '7buje4qct3kvuhitsmslj9kpr3', '0');
INSERT INTO `tw_user_log` VALUES ('88', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1529994107', '1530519231', '1', '1af5i1fuhvquf44um56a2rlq62', '0');
INSERT INTO `tw_user_log` VALUES ('89', '69', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '127.0.0.1', '未分配或者内网IP', '0', '1529994331', '1531123414', '1', 'mq80adg2mcvvndgnv940eg8ku4', '0');
INSERT INTO `tw_user_log` VALUES ('90', '83', 'login', 'PC端Windows NT 6.1，Chrome/53.0.2785.104登录', '127.0.0.1', '未分配或者内网IP', '0', '1529996888', '1530071615', '1', 'vc9vqg3ake0d7gds1eq993gnl2', '0');
INSERT INTO `tw_user_log` VALUES ('91', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530060083', '1530077827', '1', 'l2kb510sttib9s8c34iep410l4', '0');
INSERT INTO `tw_user_log` VALUES ('92', '84', 'login', '注册完成后自动登录', '127.0.0.1', '未分配或者内网IP', '0', '1530071557', '1530072149', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('93', '83', 'login', 'PC端Windows NT 6.1，Chrome/53.0.2785.104登录', '127.0.0.1', '未分配或者内网IP', '0', '1530071615', '1530079185', '1', 'h40dts7snmb3mkq2r6uhdv19k2', '0');
INSERT INTO `tw_user_log` VALUES ('94', '84', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530077363', '1530077645', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('95', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530077826', '1530077801', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('96', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530077850', '1530078116', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('97', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530078116', '1530078145', '1', 'l2kb510sttib9s8c34iep410l4', '0');
INSERT INTO `tw_user_log` VALUES ('98', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530078146', '1530079237', '1', 'l2kb510sttib9s8c34iep410l4', '0');
INSERT INTO `tw_user_log` VALUES ('99', '84', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530078448', '1530080763', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('100', '76', 'login', 'PC端Windows NT 6.1，Chrome/53.0.2785.104登录', '127.0.0.1', '未分配或者内网IP', '0', '1530079237', '1530079240', '1', 'h40dts7snmb3mkq2r6uhdv19k2', '0');
INSERT INTO `tw_user_log` VALUES ('101', '83', 'login', 'PC端Windows NT 6.1，Chrome/53.0.2785.104登录', '127.0.0.1', '未分配或者内网IP', '0', '1530079260', '1530082710', '1', 'h40dts7snmb3mkq2r6uhdv19k2', '0');
INSERT INTO `tw_user_log` VALUES ('102', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530080782', '1530080804', '1', 'op92a7trcf9deivmohg1ue2e95', '0');
INSERT INTO `tw_user_log` VALUES ('103', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530080804', '1530080833', '1', 'l2kb510sttib9s8c34iep410l4', '0');
INSERT INTO `tw_user_log` VALUES ('104', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530080827', '1530093719', '1', 'l2kb510sttib9s8c34iep410l4', '0');
INSERT INTO `tw_user_log` VALUES ('105', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530093718', '1530146259', '1', 'v63ppu7o2r5o91th36rg9v35r1', '0');
INSERT INTO `tw_user_log` VALUES ('106', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1530146259', '1530165178', '1', 'lpvn8bd2qr67fmbp21t3tj7vv0', '0');
INSERT INTO `tw_user_log` VALUES ('107', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530234919', '1530234929', '1', 'bb09q44am1fjrmd9cbud0fs947', '0');
INSERT INTO `tw_user_log` VALUES ('108', '84', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530234942', '1530235565', '1', 'bb09q44am1fjrmd9cbud0fs947', '0');
INSERT INTO `tw_user_log` VALUES ('109', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530235587', '1530499845', '1', 'bb09q44am1fjrmd9cbud0fs947', '0');
INSERT INTO `tw_user_log` VALUES ('110', '84', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530499029', '1530499664', '1', 'gk9ikjlhnkg6412ivafoisaal6', '0');
INSERT INTO `tw_user_log` VALUES ('111', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530499845', '1530501367', '1', 'gk9ikjlhnkg6412ivafoisaal6', '0');
INSERT INTO `tw_user_log` VALUES ('112', '77', 'login', 'PC端Windows NT 10.0，Chrome/64.0.3282.140登录', '192.168.1.216', '局域网对方和您在同一内部网', '0', '1530500374', '1530501393', '1', 'uf2kj65m122i3oujvk9j937d32', '0');
INSERT INTO `tw_user_log` VALUES ('113', '77', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530501393', '1530501396', '1', 'gk9ikjlhnkg6412ivafoisaal6', '0');
INSERT INTO `tw_user_log` VALUES ('114', '77', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530501419', '1530759928', '1', 'gk9ikjlhnkg6412ivafoisaal6', '0');
INSERT INTO `tw_user_log` VALUES ('115', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1530519230', '1530760285', '1', 'rohmrtcasus1j673ph3i08ru34', '0');
INSERT INTO `tw_user_log` VALUES ('116', '77', 'login', 'PC端Windows NT 10.0，Chrome/64.0.3282.140登录', '192.168.1.216', '局域网对方和您在同一内部网', '0', '1530759928', '1530759928', '1', 'obb56g1kp1c3phg3tjqb8epm01', '1');
INSERT INTO `tw_user_log` VALUES ('117', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '192.168.1.136', '局域网对方和您在同一内部网', '0', '1530760078', '1530773136', '1', 'i86a2cej37vdpelb07ce546nc6', '0');
INSERT INTO `tw_user_log` VALUES ('118', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '192.168.1.119', '局域网对方和您在同一内部网', '0', '1530760285', '1530760318', '1', 'isvv8toggo99osba7mcu4kivi7', '0');
INSERT INTO `tw_user_log` VALUES ('119', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '192.168.1.119', '局域网对方和您在同一内部网', '0', '1530760317', '1531097933', '1', '0j9qnnh5nbmp4lrfsp12ictc75', '0');
INSERT INTO `tw_user_log` VALUES ('120', '84', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1530865434', '1531116916', '1', 'vb1vsvlonan7r0o4tsg3r26k12', '0');
INSERT INTO `tw_user_log` VALUES ('121', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '127.0.0.1', '未分配或者内网IP', '0', '1531097933', '1535772113', '1', 'm2cnql6s226n8m6t261e37mmk5', '0');
INSERT INTO `tw_user_log` VALUES ('122', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '127.0.0.1', '未分配或者内网IP', '0', '1535772135', '1536895358', '1', 'm2cnql6s226n8m6t261e37mmk5', '0');
INSERT INTO `tw_user_log` VALUES ('123', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '127.0.0.1', '未分配或者内网IP', '0', '1536895370', '1545276206', '1', 'm2cnql6s226n8m6t261e37mmk5', '0');
INSERT INTO `tw_user_log` VALUES ('124', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '127.0.0.1', '未分配或者内网IP', '0', '1545276216', '1531106774', '1', 'm2cnql6s226n8m6t261e37mmk5', '0');
INSERT INTO `tw_user_log` VALUES ('125', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.99登录', '127.0.0.1', '未分配或者内网IP', '0', '1531106773', '1531106773', '1', 'h3qe63mvps33jfdjr6hcjmfvr3', '1');
INSERT INTO `tw_user_log` VALUES ('126', '84', 'login', 'PC端，Chrome/59.0.3071.92登录', '192.168.1.80', '局域网对方和您在同一内部网', '0', '1531116915', '1531123318', '1', 'a7rkapoitf518tbh90ls13oea6', '0');
INSERT INTO `tw_user_log` VALUES ('127', '84', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '127.0.0.1', '未分配或者内网IP', '0', '1531123318', '1531123359', '1', '0gtkjaa9rujuflri617499isg0', '0');
INSERT INTO `tw_user_log` VALUES ('128', '69', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '127.0.0.1', '未分配或者内网IP', '0', '1531123414', '1531123414', '1', '0gtkjaa9rujuflri617499isg0', '1');
INSERT INTO `tw_user_log` VALUES ('129', '76', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3315.4登录', '127.0.0.1', '未分配或者内网IP', '0', '1531446412', '1531446412', '1', '446dts3en0kmd1jesofh6af9d5', '1');

-- ----------------------------
-- Table structure for tw_user_log_copy
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_log_copy`;
CREATE TABLE `tw_user_log_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(20) NOT NULL DEFAULT '',
  `addr` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `session_key` varchar(100) DEFAULT NULL,
  `state` tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `state` (`state`) USING BTREE,
  KEY `session_key` (`session_key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COMMENT='用户记录表'
/*!50100 PARTITION BY RANGE (id)
(PARTITION p1 VALUES LESS THAN (100000) ENGINE = InnoDB,
 PARTITION p2 VALUES LESS THAN (200000) ENGINE = InnoDB,
 PARTITION p3 VALUES LESS THAN (300000) ENGINE = InnoDB,
 PARTITION p4 VALUES LESS THAN (400000) ENGINE = InnoDB,
 PARTITION p5 VALUES LESS THAN (500000) ENGINE = InnoDB,
 PARTITION p6 VALUES LESS THAN (600000) ENGINE = InnoDB,
 PARTITION p7 VALUES LESS THAN (700000) ENGINE = InnoDB,
 PARTITION p8 VALUES LESS THAN (800000) ENGINE = InnoDB,
 PARTITION p9 VALUES LESS THAN (900000) ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN (1000000) ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN MAXVALUE ENGINE = InnoDB) */;

-- ----------------------------
-- Records of tw_user_log_copy
-- ----------------------------
INSERT INTO `tw_user_log_copy` VALUES ('1', '68', 'login', '注册完成后自动登录', '161.202.48.247', '瑞士', '0', '1525946652', '1526001172', '1', 'f46ifpb50obsi5s2tvs3dib5o2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('2', '69', 'login', '注册完成后自动登录', '39.89.46.62', '山东省联通', '0', '1525946778', '1526001376', '1', '2aki6hn8ve9qi4sk62rgb7b5g2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('3', '70', 'login', '注册完成后自动登录', '123.235.198.25', '山东省青岛市联通', '0', '1525955890', '1526117954', '1', 'jrd10qk1l1b44ga1p5iqsdoni0', '0');
INSERT INTO `tw_user_log_copy` VALUES ('4', '68', 'login', 'PC端Windows NT 6.1，Chrome/66.0.3359.139登录', '119.81.249.135', '香港SoftLayer数据中心', '0', '1526001171', '1529574662', '1', 'tfk5tpd0er11ieior5t3k66nk2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('5', '69', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '39.89.46.62', '山东省联通', '0', '1526001375', '1529637552', '1', 'e8i4fpiu7aqv1j6m195sdejjb3', '0');
INSERT INTO `tw_user_log_copy` VALUES ('6', '71', 'login', '注册完成后自动登录', '223.80.234.118', '山东省青岛市移动', '0', '1526007666', '1526009560', '1', 'va4jvfre1psqbsg4fg3mqm6fh1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('7', '72', 'login', '注册完成后自动登录', '60.209.241.96', '山东省青岛市联通', '0', '1526027290', '1526027290', '1', '32k3t0ah8tgrcf26r4umr8e6v4', '1');
INSERT INTO `tw_user_log_copy` VALUES ('8', '73', 'login', '注册完成后自动登录', '39.89.46.62', '山东省联通', '0', '1526027345', '1526260220', '1', 'b0apoa7l2tpmhtc8kl0p1hh740', '0');
INSERT INTO `tw_user_log_copy` VALUES ('9', '74', 'login', '注册完成后自动登录', '60.11.212.223', '黑龙江省鹤岗市联通', '0', '1526111276', '1526111276', '1', '4pip7iv204riqudljempc5fe33', '1');
INSERT INTO `tw_user_log_copy` VALUES ('10', '71', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.87登录', '223.80.234.118', '山东省青岛市移动', '0', '1526112796', '1526193257', '1', 'bveg7icq1aeebobudtg4hrhjn5', '0');
INSERT INTO `tw_user_log_copy` VALUES ('11', '70', 'login', 'PC端Windows NT 10.0，Chrome/54.0.2840.99登录', '223.80.234.187', '山东省青岛市移动', '0', '1526117953', '1526173737', '1', '1sfc61g1t17u284vgis520fu32', '0');
INSERT INTO `tw_user_log_copy` VALUES ('12', '70', 'login', 'PC端Windows NT 10.0，Chrome/55.0.2883.87登录', '123.235.198.25', '山东省青岛市联通', '0', '1526173736', '1526177411', '1', 't3egj7c0tm6nhk66ovtmbasnn5', '0');
INSERT INTO `tw_user_log_copy` VALUES ('13', '70', 'login', 'PC端Windows NT 10.0，Chrome/54.0.2840.99登录', '223.80.234.187', '山东省青岛市移动', '0', '1526177410', '1526177893', '1', '1sfc61g1t17u284vgis520fu32', '0');
INSERT INTO `tw_user_log_copy` VALUES ('14', '70', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.87登录', '183.232.55.231', '广东省广州市移动', '0', '1526177892', '1526178025', '1', 'brnlms66p8gf3tb5rr36v10f01', '0');
INSERT INTO `tw_user_log_copy` VALUES ('15', '70', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.87登录', '183.232.55.231', '广东省广州市移动', '0', '1526178179', '1526191858', '1', 'brnlms66p8gf3tb5rr36v10f01', '0');
INSERT INTO `tw_user_log_copy` VALUES ('16', '70', 'login', 'PC端Windows NT 10.0，Chrome/54.0.2840.99登录', '223.80.234.187', '山东省青岛市移动', '0', '1526191857', '1526192644', '1', '1sfc61g1t17u284vgis520fu32', '0');
INSERT INTO `tw_user_log_copy` VALUES ('17', '70', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.87登录', '223.80.234.187', '山东省青岛市移动', '0', '1526192643', '1526193216', '1', 'brnlms66p8gf3tb5rr36v10f01', '0');
INSERT INTO `tw_user_log_copy` VALUES ('18', '71', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.87登录', '223.80.234.187', '山东省青岛市移动', '0', '1526193257', '1526193257', '1', 'brnlms66p8gf3tb5rr36v10f01', '1');
INSERT INTO `tw_user_log_copy` VALUES ('19', '73', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '127.0.0.1', '未分配或者内网IP', '0', '1526260220', '1526260220', '1', 'oa5bh9rf1roh0g093qml8hsk70', '1');
INSERT INTO `tw_user_log_copy` VALUES ('20', '1', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1526265369', '1526265600', '1', 'u944l298t0fni09lg35bpsh5r1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('21', '1', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '127.0.0.1', '未分配或者内网IP', '0', '1526265600', '1526265605', '1', 'nhlo58o7r40q5ff775j0imvr92', '0');
INSERT INTO `tw_user_log_copy` VALUES ('22', '1', 'login', 'PC端Windows NT 6.1，Chrome/55.0.2883.75登录', '127.0.0.1', '未分配或者内网IP', '0', '1526265617', '1526265628', '1', 'nhlo58o7r40q5ff775j0imvr92', '0');
INSERT INTO `tw_user_log_copy` VALUES ('23', '1', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1526265629', '1526265628', '1', 'u944l298t0fni09lg35bpsh5r1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('24', '1', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1526265698', '1526712393', '1', 'u944l298t0fni09lg35bpsh5r1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('25', '75', 'login', '注册完成后自动登录', '127.0.0.1', '未分配或者内网IP', '0', '1526266429', '1526266429', '1', 'nhlo58o7r40q5ff775j0imvr92', '1');
INSERT INTO `tw_user_log_copy` VALUES ('26', '1', 'login', 'PC端Windows NT 6.1，Chrome/65.0.3325.146登录', '127.0.0.1', '未分配或者内网IP', '0', '1526712392', '1526712403', '1', 'cu47ah710kb1l324o3voe2qpn2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('27', '1', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '127.0.0.1', '未分配或者内网IP', '0', '1527475673', '1527476106', '1', 'btd1ff2ucfagfqsc8ct41p5mm1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('28', '76', 'login', '注册完成后自动登录', '127.0.0.1', '未分配或者内网IP', '0', '1527476174', '1527476646', '1', 'btd1ff2ucfagfqsc8ct41p5mm1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('29', '1', 'login', '手机端，Safari/604.1登录', '127.0.0.1', '未分配或者内网IP', '0', '1528687037', '1528687039', '1', '4eqfgu2pit2hlorlp3gd99ljm2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('30', '1', 'login', '手机端，Safari/604.1登录', '127.0.0.1', '未分配或者内网IP', '0', '1528687051', '1528695370', '1', '4eqfgu2pit2hlorlp3gd99ljm2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('31', '77', 'login', '注册完成后自动登录', '60.209.8.157', '山东省青岛市联通', '0', '1529029261', '1529029261', '1', 'ku8q9o7ed7pgl3enqd7i07dfi1', '1');
INSERT INTO `tw_user_log_copy` VALUES ('32', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '60.209.8.157', '山东省青岛市联通', '0', '1529029808', '1529374130', '1', 'n7bi153sakdga4br8pmalhkj60', '0');
INSERT INTO `tw_user_log_copy` VALUES ('33', '78', 'login', '注册完成后自动登录', '60.209.8.157', '山东省青岛市联通', '0', '1529052289', '1529065483', '1', 'qqloce9ms51dmsmsgnk4v38dk1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('34', '79', 'login', '注册完成后自动登录', '220.235.135.13', '澳大利亚', '0', '1529055515', '1529060215', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('35', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '114.236.94.10', '江苏省盐城市电信', '0', '1529065442', '1529065483', '1', 'qqloce9ms51dmsmsgnk4v38dk1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('38', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '114.236.94.10', '江苏省盐城市电信', '0', '1529065466', '1529069836', '1', 'qqloce9ms51dmsmsgnk4v38dk1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('39', '78', 'login', 'PC端，Chrome/67.0.3396.87登录', '211.30.220.227', '澳大利亚悉尼', '0', '1529190584', '1529194138', '1', 't0h6p0152gh2mhufsrdgjr14s7', '0');
INSERT INTO `tw_user_log_copy` VALUES ('40', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '180.97.200.28', '江苏省苏州市电信', '0', '1529194137', '1529194138', '1', '08pig200t615qfao6v79gir1e1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('41', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '180.97.200.28', '江苏省苏州市电信', '0', '1529194152', '1529208254', '1', '08pig200t615qfao6v79gir1e1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('42', '80', 'login', '注册完成后自动登录', '101.190.13.144', '澳大利亚', '0', '1529291029', '1529291029', '1', 'pcot4qmm32642c4c05993m9in7', '1');
INSERT INTO `tw_user_log_copy` VALUES ('43', '79', 'login', 'PC端，Chrome/67.0.3396.79登录', '101.190.13.144', '澳大利亚', '0', '1529297768', '1529319630', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('44', '78', 'login', '手机端，Safari/604.1登录', '60.209.8.157', '山东省青岛市联通', '0', '1529373719', '1529374675', '1', 'fi61k712qkk2t6n3ol50ut39p7', '0');
INSERT INTO `tw_user_log_copy` VALUES ('45', '76', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '60.209.8.157', '山东省青岛市联通', '0', '1529374129', '1529374129', '1', 'sv65rljvc626oeto95crdopt63', '1');
INSERT INTO `tw_user_log_copy` VALUES ('46', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.8.157', '山东省青岛市联通', '0', '1529374674', '1529402547', '1', '9kncvq8dqqv3h7u8f07rb52gc1', '0');
INSERT INTO `tw_user_log_copy` VALUES ('47', '79', 'login', 'PC端，Chrome/67.0.3396.79登录', '220.235.135.13', '澳大利亚', '0', '1529400606', '1529400984', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('48', '79', 'login', 'PC端，Chrome/67.0.3396.79登录', '220.235.135.13', '澳大利亚', '0', '1529401014', '1529409749', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('49', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.8.157', '山东省青岛市联通', '0', '1529402546', '1529631785', '1', 'l1jfavrr00nh649mk4sfjvodt6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('50', '79', 'login', 'PC端，Firefox/60.0登录', '101.190.13.144', '澳大利亚', '0', '1529459601', '1529459601', '1', 'iibj0l71vjbqc4sl46rhsopnr0', '1');
INSERT INTO `tw_user_log_copy` VALUES ('51', '1', 'login', 'PC端Windows NT 6.1，Chrome/64.0.3282.140登录', '60.209.8.157', '山东省青岛市联通', '0', '1529543292', '1529543292', '1', 'abgkts1fr5rhbqpd8b94jb22s4', '1');
INSERT INTO `tw_user_log_copy` VALUES ('52', '68', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '119.81.249.135', '香港SoftLayer数据中心', '0', '1529574662', '1529632138', '1', '51fialusrdetq4v0vh1nqi7401', '0');
INSERT INTO `tw_user_log_copy` VALUES ('53', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.33.43', '山东省青岛市联通', '0', '1529631784', '1529642225', '1', 'psm7apf996dscr3cipsjbhre46', '0');
INSERT INTO `tw_user_log_copy` VALUES ('54', '68', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529632137', '1529753668', '1', 'p7hgv70keeeodgv7umhoan4126', '0');
INSERT INTO `tw_user_log_copy` VALUES ('55', '69', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '161.202.48.247', '瑞士', '0', '1529637552', '1529655556', '1', '9vlvki2gkcekossdla17fkdpi7', '0');
INSERT INTO `tw_user_log_copy` VALUES ('56', '78', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529642224', '1529653208', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('57', '78', 'login', 'PC端，Chrome/67.0.3396.87登录', '101.190.13.144', '澳大利亚', '0', '1529653206', '1529714732', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('58', '69', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '161.202.48.247', '瑞士', '0', '1529655555', '1529716892', '1', '9vlvki2gkcekossdla17fkdpi7', '0');
INSERT INTO `tw_user_log_copy` VALUES ('59', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.33.43', '山东省青岛市联通', '0', '1529714730', '1529747789', '1', 'psm7apf996dscr3cipsjbhre46', '0');
INSERT INTO `tw_user_log_copy` VALUES ('60', '69', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '161.202.48.247', '瑞士', '0', '1529716891', '1529751770', '1', 'dm9ck16ps0ancke4235p6f5bl2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('61', '81', 'login', '注册完成后自动登录', '60.209.33.43', '山东省青岛市联通', '0', '1529737759', '1529753858', '1', 'jt7qb2dms6m2ehon26rrnuhh15', '0');
INSERT INTO `tw_user_log_copy` VALUES ('62', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.33.43', '山东省青岛市联通', '0', '1529747798', '1529748863', '1', 'psm7apf996dscr3cipsjbhre46', '0');
INSERT INTO `tw_user_log_copy` VALUES ('63', '78', 'login', 'PC端，Chrome/67.0.3396.87登录', '220.235.135.13', '澳大利亚', '0', '1529748862', '1529748864', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('64', '78', 'login', 'PC端，Chrome/67.0.3396.87登录', '220.235.135.13', '澳大利亚', '0', '1529748905', '1529748951', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '0');
INSERT INTO `tw_user_log_copy` VALUES ('65', '82', 'login', '注册完成后自动登录', '220.235.135.13', '澳大利亚', '0', '1529749342', '1529749342', '1', 'csf2pbca7b34f8lv8uum8nfeb6', '1');
INSERT INTO `tw_user_log_copy` VALUES ('66', '78', 'login', 'PC端Windows NT 6.1，Chrome/49.0.2623.221登录', '60.209.33.43', '山东省青岛市联通', '0', '1529750045', '1529822354', '1', 'psm7apf996dscr3cipsjbhre46', '0');
INSERT INTO `tw_user_log_copy` VALUES ('67', '69', 'login', 'PC端Windows NT 6.1，Chrome/67.0.3396.87登录', '161.202.48.247', '瑞士', '0', '1529751770', '1529753842', '1', 'dm9ck16ps0ancke4235p6f5bl2', '0');
INSERT INTO `tw_user_log_copy` VALUES ('68', '69', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529753842', '1529753842', '1', 'p7hgv70keeeodgv7umhoan4126', '0');
INSERT INTO `tw_user_log_copy` VALUES ('69', '81', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529753858', '1529753861', '1', 'p7hgv70keeeodgv7umhoan4126', '0');
INSERT INTO `tw_user_log_copy` VALUES ('70', '81', 'login', 'PC端Windows NT 6.1，Firefox/49.0登录', '60.209.33.43', '山东省青岛市联通', '0', '1529753875', '1529753965', '1', 'p7hgv70keeeodgv7umhoan4126', '0');

-- ----------------------------
-- Table structure for tw_user_qianbao
-- ----------------------------
DROP TABLE IF EXISTS `tw_user_qianbao`;
CREATE TABLE `tw_user_qianbao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `coinname` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `addr` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `userid` (`userid`),
  KEY `coinname` (`coinname`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='用户钱包表';

-- ----------------------------
-- Records of tw_user_qianbao
-- ----------------------------
INSERT INTO `tw_user_qianbao` VALUES ('1', '73', 'usdt', '测试提币', 'mzv1R3RgDsFVhwiD6PmPEVEgLzKBMqBam2', '0', '1526263119', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('2', '73', 'usdt', 'jia', 'moXTHvpSCjVEtPUBoDDnCS2zWYbn8ADCd5', '0', '1526283007', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('3', '76', 'usdt', 'gao', 'dadada', '0', '1527476471', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('4', '68', 'usdt', 'usdt提币地址', 'mgrjAhQ917fkzuw2DrhBsZ8K5QPrMNXD5D', '0', '1529635179', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('5', '68', 'usdt', 'usdt测试二', 'mvns6T7U4kTqmTHVHJoh85KnZaaSXNa4nv', '0', '1529636040', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('6', '68', 'usdt', '测试usdt提币', 'mt1UMZWW4TBhM2bcNBuJUKdyFAPaQhX2gm', '0', '1529637386', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('7', '68', 'btc', '测试提比特币', '1JXYquUidP2KwGPZ4i3zFVSwaeqCbTwz6n', '0', '1529637568', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('8', '68', 'btc', '比特币提币二', 'mu9CpoVjz8FhjuFwErG7yiYpyDRo8C1jt5', '0', '1529637780', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('9', '68', 'btc', '比特币钱包地址', 'mkzSFf4fdPKZAznTbQLjHSZk3E24jpjPPo', '0', '1529637956', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('10', '68', 'btc', '比特币三', '17A16QmavnUfCW11DAApiJxp7ARnxN5pGX', '0', '1529638175', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('11', '68', 'eth', '大撒币', '0x80ae819ed600f8f2d6c6abcff2c20d4a845e5875', '0', '1529638298', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('12', '69', 'eth', '测试提币', '0x43e590e56023ccb8754b93904a4ea066c6641793', '0', '1529638512', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('13', '68', 'hor', 'hor提现', '0x43e590e56023ccb8754b93904a4ea066c6641793', '0', '1529638585', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('14', '68', 'hor', '测试格式', '0x61420b4933abd7bd7451090c0430085a09b8c76c', '0', '1529638979', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('15', '68', 'usdt', 'slusdt', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '0', '1529655668', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('16', '68', 'eth', 'sleth', '0x43e590e56023ccb8754b93904a4ea066c6641793', '0', '1529656085', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('19', '84', 'usdt', '1', '1', '0', '1530078462', '0', '1');
INSERT INTO `tw_user_qianbao` VALUES ('20', '69', 'btc', '测试提现', '15fRwf9ePtanUjKZqsNUssed6c4XsGgyQH', '0', '1531123599', '0', '1');

-- ----------------------------
-- Table structure for tw_zcbatch_error
-- ----------------------------
DROP TABLE IF EXISTS `tw_zcbatch_error`;
CREATE TABLE `tw_zcbatch_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zcid` int(11) NOT NULL,
  `addtime` int(10) NOT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_zcbatch_error
-- ----------------------------
INSERT INTO `tw_zcbatch_error` VALUES ('1', '7', '1529661642', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('2', '1', '1529661642', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('3', '7', '1529661994', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('4', '1', '1529661994', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('5', '7', '1529662050', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('6', '1', '1529662050', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('7', '7', '1529669555', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('8', '1', '1529669555', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('9', '8', '1529669688', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');
INSERT INTO `tw_zcbatch_error` VALUES ('10', '1', '1529669688', '钱包服务器转出币失败', 'muwJjWWpAjbHMQyf2zYz4QyGVVVe3rPDuo', '68');

-- ----------------------------
-- Table structure for tw_znc
-- ----------------------------
DROP TABLE IF EXISTS `tw_znc`;
CREATE TABLE `tw_znc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `price` decimal(30,8) NOT NULL DEFAULT '0.00000000' COMMENT '1个比特币价格,十分钟更新',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '价格更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tw_znc
-- ----------------------------
INSERT INTO `tw_znc` VALUES ('1', '人民币', 'CNY', '1.00000000', '1519811229');

-- ----------------------------
-- Table structure for tw_znc_log
-- ----------------------------
DROP TABLE IF EXISTS `tw_znc_log`;
CREATE TABLE `tw_znc_log` (
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

//添加新的字段
alter table tw_order_buy add id_num int(10) not null default 0 comment "识别号";
alter table tw_order_sell add id_num int(10) not null default 0 comment "识别号";
alter table tw_order_sell add skaccount varchar(50) not null default '' comment "收款账号 逗号分开来";

-- ----------------------------
-- Records of tw_znc_log
-- ----------------------------

-- ----------------------------
-- Procedure structure for delolddata
-- ----------------------------
DROP PROCEDURE IF EXISTS `delolddata`;
DELIMITER ;;
CREATE DEFINER=`txcsdbsw`@`%` PROCEDURE `delolddata`()
BEGIN
insert into tw_user_log_copy select * from tw_user_log where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
DELETE FROM tw_user_log WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
DELETE FROM `tw_ethzr`  WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(`addtime`))) >=3;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for e_del_user_log
-- ----------------------------
DROP PROCEDURE IF EXISTS `e_del_user_log`;
DELIMITER ;;
CREATE DEFINER=`z4z3l2lk`@`%` PROCEDURE `e_del_user_log`()
begin
	insert into tw_user_log_copy select * from tw_user_log where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
	DELETE FROM tw_user_log WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=2 and type = 1;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=3 and type = 3;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=5 and type = 5;
  insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=15 and type = 15;
	insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=30 and type = 30;
	insert into tw_trade_json_copy select * from tw_trade_json where (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
	DELETE FROM tw_trade_json WHERE (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(addtime)))>=60 and type = 60;
end
;;
DELIMITER ;

-- ----------------------------
-- Event structure for delolddata
-- ----------------------------
DROP EVENT IF EXISTS `delolddata`;
DELIMITER ;;
CREATE DEFINER=`txcsdbsw`@`%` EVENT `delolddata` ON SCHEDULE EVERY 1 DAY STARTS '2018-06-06 00:30:00' ON COMPLETION PRESERVE ENABLE DO begin

call delolddata();

end
;;
DELIMITER ;

-- ----------------------------
-- Event structure for e_del_user_log
-- ----------------------------
DROP EVENT IF EXISTS `e_del_user_log`;
DELIMITER ;;
CREATE DEFINER=`z4z3l2lk`@`%` EVENT `e_del_user_log` ON SCHEDULE EVERY 1 DAY STARTS '2017-09-06 05:00:00' ON COMPLETION PRESERVE ENABLE DO CALL e_del_user_log()
;;
DELIMITER ;
