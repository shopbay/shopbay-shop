<?php
/* @var $this CustomerController */
/* @var $data Item */
?>
<div class="list-box float-image">
    <span class="status">
        <?php echo Helper::htmlColorText($data->getStatusText(),false); ?>
    </span>
    <div class="image">
        <?php echo $data->getProductImageThumbnail(Image::VERSION_SMEDIUM,array('class'=>'img'));?>
    </div>
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>$data->quantity.
                             '<span style="font-size:0.7em;color:gray;padding:0px 5px;">x</span>'.
                             CHtml::link($data->displayLanguageValue('name',user()->getLocale()), $data->viewUrl)
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('order_no')).'</strong>'.
                             CHtml::link(CHtml::encode($data->order_no), $data->viewUrl).
                             '<strong>'.CHtml::encode($data->getAttributeLabel('total_price')).'</strong>'.
                             CHtml::encode($data->formatCurrency($data->grandTotal)),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>Helper::prettyDate($data->create_time),
                ),    
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>$data->getCampaignColorTag(user()->getLocale()),
                    'visible'=>$data->isCampaignItem(),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="button element">{value}</div>',
                    'value'=>TaskBaseController::getWorkflowButtons($data),
                    'visible'=>$data->actionable(user()->currentRole,user()->getId()),
                ),        
            ),
        )); 
    ?> 
</div>