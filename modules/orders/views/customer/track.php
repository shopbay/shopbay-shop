<?php 
$this->registerTaskScript();
$this->registerChosen();
$this->breadcrumbs = [];
$this->menu = [];

$message = $this->renderPartial('application.modules.carts.views.management._complete',[
                        'orderNo'=>$model->order_no,
                        'orderUrl'=>Order::getAccessUrl($model->order_no,true,$model->shop->domain),
                        'paymentMethod'=>$model->paymentMethodModel,
                        'status'=>$model->status,
                    ],true);
$workflowItems = $model->searchItems();
foreach ($workflowItems->data as $item){
    if ($item->fulfillable()){
        $message .= Sii::t('sii','Item "{name}" {status}.',['{name}'=>$item->displayLanguageValue('name',user()->getLocale()),'{status}'=>Process::getHtmlDisplayText($item->status)]).' ';
        $message .= $item->getWorkflowDescription().'<br>';
    }
}
    
$this->appendOrderHeader([
    'shop' => $model->shop,
    'orderNo'=> $model->order_no,
    'email'=> $model->buyerEmail,
    'message' => $message,
    'actionButton' => $this->widget('zii.widgets.jui.CJuiButton',[
                            'id'=>'orderbutton',
                            'name'=>'orderButton',
                            'buttonType'=>'button',
                            'caption'=>Sii::t('sii','Pay Now'),//todo get caption based on model status
                            'onclick'=>'js:function(){_w('.$model->id.',"Order","Pay");}',//refer to tasks.js
                        ],true),
    'actionButtonVisible' => $model->actionable(user()->currentRole,$model->account_id),
]);

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

$this->smodalWidget();

//put script here if any
$script = <<<EOJS
EOJS;
Helper::registerJs($script);
