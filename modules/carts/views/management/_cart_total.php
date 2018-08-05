<?php
$this->widget($this->getModule()->getClass('listview'), 
    array(
        'dataProvider'=> $this->getCartTotalDataProvider($shop),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
    ));