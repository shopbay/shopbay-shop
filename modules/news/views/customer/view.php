<?php
$this->breadcrumbs=array(
    Sii::t('sii','News')=>url('news'),
    $model->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY),
);

$this->menu=array();

$this->widget('common.widgets.spage.SPage',array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
            'name'=> $model->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY),
            'image'=> $model->shop->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'max-width:120px;max-height:80px;')),
            'superscript'=> $model->shop->displayLanguageValue('name',user()->getLocale()),
            'subscript'=> $model->formatDateTime($model->create_time,true),
        ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
));