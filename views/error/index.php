<?php
$this->render('shop.views.layouts.index',[
    'page'=>ShopPage::HTML,
    'content'=>$this->renderPartial('common.views.error.error',['code'=>$code,'messages'=>$messages],true),//$code,$messages is passed in from SErrorController:sendResponse()
    'cssClass'=>'error-page',
]);

