<?php
$content = $this->widget('common.widgets.spage.SPage',[
    'heading'=> [
        'name'=> isset($activated)?Sii::t('sii','Your account is already activated.'):Sii::t('sii','Thanks for registering with us.'),
        'image'=>'<i class="fa fa-check-circle fa-fw" style="color:limegreen;font-size:2em;"></i>',
    ],
    'flash'=>'complete',
    'layout'=>false,
    'linebreak'=>false,
    'loader'=>false,
    'body'=>isset($activated)?'':$this->renderPartial('_complete_body',['email'=>$email],true),
],true);

if (isset($modal)){
    echo $content;
}
else {
    $this->render('shop.views.layouts.index',[
        'page'=>ShopPage::HTML,
        'content'=>$content,
    ]);
}