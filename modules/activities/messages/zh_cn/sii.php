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
    'This lists all your activity records.'=>'这里列出所有您的活动记录。',
    'This lists all your activity records related to questions.'=>'这里列出所有和问题有关的活动记录。',
    'This lists all your activity records related to comments.'=>'这里列出所有和评论有关的活动记录。',
    'This lists all your activity records related to likes.'=>'这里列出所有和喜欢有关的活动记录。',
]);
