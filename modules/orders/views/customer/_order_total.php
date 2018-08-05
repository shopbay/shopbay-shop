<?php
$this->widget($this->module->getClass('listview'), 
    array(
        'dataProvider'=> $this->getOrderTotalDataProvider($order),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
    ));