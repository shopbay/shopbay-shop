<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=array(
    Sii::t('sii','Account')=>url('account/profile'),
    Sii::t('sii','Questions')=>url('questions'),
    Sii::t('sii','Update'),
);
$this->menu=$this->getPageMenu($model);

$this->widget('common.widgets.spage.SPage', array(
    'id'=>$this->modelType,
    'breadcrumbs' => $this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => get_class($model),
    'heading' => array(
        'name'=> $model->title,
        'tag'=> $model->getStatusText(),
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));