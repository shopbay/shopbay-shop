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
    //customer items
    'This lists all the items that you have purchased.'=>'这里列出所有您已购买的商品。',
    'Unpaid items are items that you have not made payment. You can proceed payment at corresponding the purchase order.'=>'未付款商品正待您支付。您可在相关订单内付款。',
    'Paid items are items that pending merchant payment verification before shipping.'=>'已付款商品需等店家确认收到付款才能发货。',
    'These are items with confirmed by merchant and they should have started shipping them.'=>'已购买商品代表店家已确认并开始发货。',
    'These are items that already shipped by merchant. Please click "Accept" for acknowledgment when you have received your items.'=>'已发货商品代表店家已完成发货。当您收到商品时请点击“签收”。',
    'These are items that rejected by merchant due to unsuccessful payment verification. You can check the reason of rejection.'=>'已拒绝商品代表店家无法核对付款。您可查看拒绝的原因。',
    'These are items that either cancelled by merchant or yourselves. You can check the reason of cancellation.'=>'这类商品代表店家或您取消订单。您可查看取消的原因。',
    'Refunded items are items that merchant had cancelled and refunded you. If you have not received the refund, please contact merchant.'=>'已退款商品代表店家已将订单取消并已退款。若您仍未收到退款，请联系店家。',
    'Returned items are items that merchant had accepted your item return requests. When you return items, merchant will refund you accordingly.'=>'已退货商品代表店家已同意您的商品退还要求。当您将商品退还时，店家将会对退还商品退款。',
    'Received items are items that already acknowledged by you. We want to hear from you, please write us review to share your purchasing experience related to the item. If you find the items are not in good condition, you may request for items return and refund.'=>'已签收商品代表您已确认收取。我们欢迎您的可贵意见，愿您与我们分享您的商品购买体验。若您发现商品有瑕疵，您可提出退货退款要求。',
    'Pending items are items that currently being processed by merchant.'=>'处理中商品表示店家正在进行处理您的订单。',
]);
