<?php 
echo $data->getProductImageThumbnail(Image::VERSION_SMALL,array('style'=>'vertical-align:top'));

$this->widget($this->module->getClass('listview'), 
    array(
        'dataProvider'=> $this->getOrderItemInfoDataProvider($data),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
        'htmlOptions'=>array('class'=>'item-details'),
    ));
