<?php
$this->render('shop.views.layouts.index',[
    'page'=>ShopPage::HTML,
    'content'=>$this->renderPartial('common.modules.accounts.views.management._resetpassword_form',['model'=>$model],true),
    'cssClass'=>'reset-password-page',
]);
