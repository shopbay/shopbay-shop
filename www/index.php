<?php
defined('APP_CONFIG') or define('APP_CONFIG',dirname(__FILE__).'/../config.json');
require_once(dirname(__FILE__).'/../../shopbay-kernel/config/globals.php');
//load customized Yii class (enable both Yii 1.x and Yii 2.x)
require(dirname(__FILE__).'/../../shopbay-kernel/components/Yii.php');
$config=dirname(__FILE__).'/../config/main.php';
Yii::createWebApplication($config)->run();