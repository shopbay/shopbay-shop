<?php 
$this->widget($this->module->getClass('listview'), 
    array(
        'dataProvider'=> $this->getOrderItemPriceInfoDataProvider($data,user()->getLocale()),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
        'emptyText'=>'',
        'htmlOptions'=>array('class'=>'price-details'),
    ));

