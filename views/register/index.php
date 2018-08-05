<?php
$this->render('shop.views.layouts.index',[
    'page'=>ShopPage::REGISTER,
    'formModel'=>$form,
    'formView'=>'shop.views.register._form',
]);