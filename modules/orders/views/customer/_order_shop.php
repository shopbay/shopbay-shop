<?php
$this->widget($this->getModule()->getClass('listview'), array(
        'dataProvider'=> $this->getOrderShippingDataProvider($order),
        'template'=>'{items}',
        'itemView'=>'_order_shipping',
        'viewData'=>array('order'=>$order),
        'htmlOptions'=>array('class'=>'order-shop'),
    ));
