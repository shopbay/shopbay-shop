<?php
$this->widget($this->getModule()->getClass('listview'), 
    array(
        'id'=>'cart_subtotal',
        'dataProvider'=> $this->getCartSubTotalDataProvider($shop,$shipping),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
    ));