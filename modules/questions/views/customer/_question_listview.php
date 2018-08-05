<div class="list-box float-image">
    <div class="image">
        <?php echo $data->getReferenceImage(); ?> 
    </div>
    <?php 
    
        $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="status">{value}</div>',
                    'value'=>Helper::htmlColorText($data->getStatusText(),false).Helper::htmlColorText($data->getTypeLabel(),false),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="data-element">{value}</div>',
                    'value'=>Sii::t('sii','To: ').CHtml::link(CHtml::encode($data->getReferenceName(user()->getLocale())), $data->getReference()->url),
                ),
            ),
        )); 
    
        $this->renderPartial('_quickview', array('data'=>$data));
    ?> 
</div>