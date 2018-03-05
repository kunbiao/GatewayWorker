<?php
//加载GatewayClient。关于GatewayClient参见本页面底部介绍
require_once 'Gateway.php';
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '127.0.0.1:1238';

// 假设用户已经登录，用户uid和群组id在session中
// client_id与uid绑定
Gateway::bindUid('7f0000010b5400000001', 234);
Gateway::sendToUid(234, 'abcdasdasdasdasdas');
