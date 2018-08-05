<?php
$this->registerScripts();
        
$body = CHtml::tag('div', ['style'=>'text-align:center'], Sii::t('sii','The link you requested may be broken or may have been removed.'));
$body .=  "<br><br>".CHtml::image($this->getImage('shop_icon.png'));//@todo to put Shopbay logo

$this->widget('common.widgets.spage.SPage',[
    'id'=>'generic_page_404',
    'heading'=> [
        'name'=> isset($message) ? $message : Sii::t('sii','Sorry, the page is not available.'),
    ],
    'linebreak'=>false,
    'body'=>$body,
]);