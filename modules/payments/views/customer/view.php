<?php
$this->breadcrumbs=array(
	Sii::t('sii','Account')=>url('account/profile'),
	Sii::t('sii','Payments')=>url('payments'),
	Sii::t('sii','View'),
);

$this->menu=array();

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
            'name'=> $model->displayName().' '.$model->payment_no,
            'superscript'=>null,
            'subscript'=>$model->formatDatetime($model->create_time,true),
        ),
    'body'=>$this->renderPartial('_view_body',array('model'=>$model),true),
));
