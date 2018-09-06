###第一次提交
---------------------------
* otc系统

* 添加上传图片，在upload/下新增一个skqrcode文件
* 新建表

    ```
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
    ```
    
* 数据表添加新字段,新环境下先执行下面
    * alter table tw_order_buy add id_num int(10) not null default 0 comment "识别号";
    * alter table tw_order_sell add id_num int(10) not null default 0 comment "识别号";
    * alter table tw_order_sell add skaccount varchar(50) not null default '' comment "收款账号 逗号分开来";
    * alter table tw_config add day_withdraw decimal(10,2) not null default '0.00' comment "当日提现最大限额";
    * alter table tw_config add single_withdraw decimal(10,2) not null default '0.00' comment "单笔提现最大限额审核";
    
* php.ini 中的max_execution_time 的值修改为180秒 ：为了跑脚本不失败。
Home/Controller/QueueController.class.php 为脚本