<?php $this->module->registerChosen();?>
<?php if ($model->actionable(user()->currentRole,user()->getId()))
        $this->module->registerTaskScript();
?>
<?php 
$workflowAction = $model->getWorkflowAction(user()->currentRole);
$this->menu=array(
      array('id'=>$workflowAction,'title'=>SButtonColumn::getButtonTitle($workflowAction),
            'subscript'=>SButtonColumn::getButtonSubscript($workflowAction), 'visible'=>$model->receivable(), 
            'linkOptions'=>array(
                'onclick'=>'qwi('.$model->id.',\''.$workflowAction.'\')',
                'class'=>'workflow-button'
        )),
);

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>array(
                    Sii::t('sii','Items')=>url('items'),
                    Sii::t('sii','View'),
                ),
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
            'name'=> $model->displayLanguageValue('name',user()->getLocale()),
            'image'=> $model->brand==null?'':$model->brand->getImageThumbnail(Image::VERSION_XSMALL),
            'tag'=> $model->getStatusText(),
            'superscript'=>$model->isCampaignItem()?$model->getCampaignColorTag(user()->getLocale()):'',
            'subscript'=>null,
        ),
    'body'=>$this->renderView('items.itemviewbody',array('model'=>$model),true),
    'csrfToken' => true, //needed by tasks.js
));
