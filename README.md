###第一次提交
---------------------------
* otc系统

* 测试phpstorm提交

* 添加上传图片，在upload/下新增一个skqrcode文件

* 数据表添加新字段,新环境下先执行下面两句
    * alter table tw_order_buy add id_num int(10) not null default 0 comment "识别号";
    * alter table tw_order_sell add id_num int(10) not null default 0 comment "识别号";