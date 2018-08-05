<?php 
echo $data->getProductModel()->getImageThumbnail(Image::VERSION_SMALL,array('style'=>'vertical-align:top'));

$this->widget($this->getModule()->getClass('listview'), 
    array(
        'dataProvider'=> $this->getCartItemInfoDataProvider($data,$checkout,$queryParams),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
        'htmlOptions'=>array('class'=>'item-details'),
    ));