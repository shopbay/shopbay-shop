<?php
$this->renderView('carts.cartheader',['checkout'=>$checkout,'shop'=>$shop]);

$this->renderView('carts.cartshop',['checkout'=>$checkout,'shop'=>$shop,'queryParams'=>isset($queryParams)?$queryParams:[]]);

$this->renderView('carts.cartfooter',['checkout'=>$checkout,'shop'=>$shop,'confirm'=>isset($confirm)?$confirm:null]);
