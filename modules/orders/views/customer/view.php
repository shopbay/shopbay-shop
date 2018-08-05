<?php 
$this->getModule()->registerTaskScript(true);

$this->breadcrumbs=array(
        Sii::t('sii','Orders')=>url('orders'),
        $model->order_no
);

$this->menu=array(
      array('id'=>$model->getWorkflowAction(),'title'=>SButtonColumn::getButtonTitle($model->getWorkflowAction()),
            'subscript'=>SButtonColumn::getButtonSubscript($model->getWorkflowAction()), 'visible'=>$model->actionable(user()->currentRole,user()->getId()), 
            'linkOptions'=>array(
                'onclick'=>'qwo('.$model->id.',\''.$model->getWorkflowAction().'\')',
                'class'=>'workflow-button'
        )),
);

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => get_class($model),
    'heading'=> array(
        'name'=> $model->order_no,
        'tag'=> $model->getStatusText(),
        'superscript'=>null,
        'subscript'=>$model->formatDatetime($model->create_time,true),
    ),
    'body'=>$this->renderPartial('_view_body',array('model'=>$model),true),
    'sectionLinebreak'=>true,
    'sections'=>$this->getSectionsData($model),
    'csrfToken' => true, //needed by tasks.js
));