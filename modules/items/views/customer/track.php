<?php 
$this->registerTaskScript();
$this->registerChosen();

$message = $this->renderPartial('_track_message',['model'=>$model],true);
if ($model->fulfillable())
    $message = $model->getWorkflowDescription().$message;

$this->appendOrderHeader([
    'shop' => $model->shop,
    'orderNo'=> $model->order_no,
    'message' => $message,
    'actionButton' => $this->widget('zii.widgets.jui.CJuiButton',[
                            'id'=>'itembutton',
                            'name'=>'itemButton',
                            'buttonType'=>'button',
                            'caption'=>Sii::t('sii','Proceed'),//todo get caption based on model status
                            'onclick'=>'js:function(){_w('.$model->id.',"Item","Receive");}',//refer to tasks.js
                        ],true),  
    'actionButtonVisible' => $model->actionable(user()->currentRole,$model->account_id),    
]);

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>[],
    'flash'  => get_class($model),
    'heading'=> array(
            'name'=> $model->displayLanguageValue('name',user()->getLocale()),
            'image'=> $model->brand==null?'':$model->brand->getImageThumbnail(Image::VERSION_XSMALL),
            'tag'=> $model->getStatusText(),
            'superscript'=>$model->isCampaignItem()?$model->getCampaignColorTag(user()->getLocale()):'',
            'subscript'=>null,
        ),
    'description'=>$model->getWorkflowDescription(),
    'body'=>$this->renderView('items.itemviewbody',array('model'=>$model),true),
    'csrfToken' => true, //needed by tasks.js
));
