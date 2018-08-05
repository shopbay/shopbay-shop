<?php
$this->widget($this->module->getClass('listview'), 
    array(
        'id'=>'order_subtotal',
        'dataProvider'=> $this->getOrderSubTotalDataProvider($order,$shipping),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
    ));
