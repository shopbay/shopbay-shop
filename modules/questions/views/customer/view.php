<?php
$this->breadcrumbs=[
    Sii::t('sii','Account')=>url('account/profile'),
    Sii::t('sii','Questions')=>url('questions'),
    Sii::t('sii','View'),
];

//@todo Shop level question submission is disabled; Pending solution #279: [Product] Shop enquiry messages and FAQ management is ready.
$this->menu=[
    //['id'=>'ask','title'=>Sii::t('sii','Ask Question'),'subscript'=>Sii::t('sii','ask'), 'url'=>$this->getQuestionAskUrl()],
];
$this->widget('common.widgets.spage.SPage',[
    'id'=>'question_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> [
        'name'=>'<i class="fa fa-quote-left"></i>'.Helper::purify($model->question).'<i class="fa fa-quote-right"></i>',
        'image'=>$model->getReferenceImage(Image::VERSION_XSMALL),
        'superscript'=>$model->getReferenceName(user()->getLocale()),
        'subscript'=>$model->formatDateTime($model->question_time,true).Helper::htmlColorText($model->getTypeLabel()),
    ],
    'body'=>$this->renderPartial('_view_body', ['model'=>$model],true),
]);    
