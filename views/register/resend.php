<?php
$content = $this->widget('common.widgets.spage.SPage',[
    'id'=> 'register_page',
    'heading'=> [
        'name'=> Sii::t('sii','Activation Key Regenerated'),
    ],
    'layout'=>false,
    'linebreak'=>false,
    'loader'=>false,
    'body'=>$this->renderPartial('_resend_body',['email'=>$email],true),
],true);

$this->render('shop.views.layouts.index',[
    'page'=>ShopPage::HTML,
    'content'=>$content,
]);