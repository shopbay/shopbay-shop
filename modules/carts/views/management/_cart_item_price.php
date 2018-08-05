<?php 
$this->widget($this->getModule()->getClass('listview'), 
    array(
        'dataProvider'=> $this->getCartItemPriceInfoDataProvider($data),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
        'emptyText'=>'',
        'htmlOptions'=>array('class'=>'price-details'),
    ));