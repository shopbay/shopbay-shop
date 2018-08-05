<?php
/**
 * Module Message translations (this file must be saved in UTF-8 encoding).
 * It merges messages from below sources in sequence:
 * [1] application level messages 
 * [2] common module level messages (inclusive of kernel common messages)
 * [3] local module level messages
 */
$appMessages = require(Yii::app()->basePath.DIRECTORY_SEPARATOR.'messages/zh_cn/sii.php');//already inclusive kernel messages
$moduleName = basename(dirname(__DIR__, 2));//move two levels up and grep module name
$moduleMessages = Sii::findMessages(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.KERNEL_NAME,$moduleName,'zh_cn');
return array_merge($appMessages,$moduleMessages,[
    'This lists all the orders that you have purchased.'=>'这里列出所有您已购买的订单。',
    'Unpaid orders are orders that require you to make payment to confirm.'=>'未付款订单正待您支付来确认订单。',
    'Paid orders are orders that pending merchant payment verification before shipping.'=>'已付款订单需等店家确认收到付款才能发货',
    'These are orders with confirmed by merchant and they should have started shipping your purchased items.'=>'已购买订单代表店家已确认并开始发货。',
    'These are orders that rejected by merchant due to unsuccessful payment verification. You can check the reason of rejection.'=>'已拒绝订单代表店家无法核对付款。您可查看拒绝的原因。',
    'These are orders that either cancelled by merchant or yourselves. You can check the reason of cancellation.'=>'这类订单代表店家抑或您已取消订单。您可查看取消的原因。',
    'These are orders that have been fully fulfilled and all their purchased items are shipped.'=>'已履行订单代表所有购买商品都已全部发货。',
    'These are orders that have been partially fulfilled and there are pending purchased items to be shipped or in other status.'=>'部分履行订单代表非全部购买商品都已发货, 尚有商品待发货或处其他状态。',
    'Refunded orders are orders that merchant had cancelled and refunded you. If you have not received the refund, please contact merchant.'=>'已退款订单代表店家已将订单取消并已退款。若您仍未收到退款，请联系店家。',
]);
