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
/**
 * [4] It merges further with message translation from payment plugins messages sources
 */
$pluginsSource = [];//empty
foreach (Yii::app()->getModule('payments')->plugins as $plugin) {
    $pluginsSource = array_merge($pluginsSource,require(Yii::app()->getModule('payments')->pluginPath.'/'.$plugin['name'].'/messages/zh_cn/sii.php'));
}

return array_merge($appMessages,$moduleMessages,$pluginsSource,[
    //put local translation here e.g. 'translate'=>'翻译',
]);
