<?php
$this->widget('common.widgets.SListView', [
    'dataProvider'=> $this->getCartShippingDataProvider($checkout,$shop),
    'template'=>'{items}',
    'itemView'=>$this->module->getView('carts.cartshipping'),
    'viewData'=>['checkout'=>$checkout,'shop'=>$shop,'queryParams'=>isset($queryParams)?$queryParams:[]],
    'htmlOptions'=>['class'=>'cart-shop'],
]);
