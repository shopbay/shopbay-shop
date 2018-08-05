<?php
$this->render('shop.views.layouts.index',[
    'page'=>ShopPage::LOGIN,
    'formModel'=>$model,//$model is passed in from AuthenticateController:actionLogin()
    'formView'=>'shop.views.auth._form',
]);