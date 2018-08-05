<?php 
echo $data->getProductModel()->getImageThumbnail(Image::VERSION_SMALL,['style'=>'vertical-align:top']);

$this->widget('common.widgets.SListView',[
    'dataProvider'=> $this->getCartItemInfoDataProvider($data,$checkout,$queryParams,true,true),//show quantity, show subtotal
    'template'=>'{items}',
    'itemView'=>'common.modules.carts.views.base._key_value',
    'htmlOptions'=>['class'=>'item-details'],
]);