<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
?>   
<?php 
//Use a dummy controller with ShopPageControllerTrait to render authenticated page using shop theme layout
Yii::import('shop.controllers.LayoutController');
$controller = Yii::app()->createController('layout/index');
//index 0 element is the controller
$controller[0]->renderPartial('shop.views.layouts.index',[
    'page'=>ShopPage::HTML,
    'content'=>$content,
    'cssClass'=>'embedded-page',
]);