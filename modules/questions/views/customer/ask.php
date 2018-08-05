<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Account')=>url('account/profile'),
	Sii::t('sii','Questions')=>url('questions'),
	Sii::t('sii','Ask'),
);
$this->menu=array();

$this->widget('common.widgets.spage.SPage', array(
    'id'=>$this->modelType,
    'breadcrumbs' => $this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => get_class($model),
    'heading' => array(
        'name'=> Sii::t('sii','Ask Question'),
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));