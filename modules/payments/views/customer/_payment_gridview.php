<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
           'name'=>'payment_no',
           'htmlOptions'=>array('style'=>'text-align:center;'),
           'type'=>'html',                        
         ),
        array(
           'name'=>'create_time',
           'value'=>'$data->formatDatetime($data->create_time,true)',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%'),
           'type'=>'html',                        
         ),
        array(
           'name'=>'type',
           'value'=>'$data->getTypeDesc()',
           'htmlOptions'=>array('style'=>'text-align:center;'),
           'type'=>'html',                        
         ),
        array(
            'class'=>'CLinkColumn',
            'header'=>Sii::t('sii','Reference No'),
            'labelExpression'=>'$data->reference_no',
            'urlExpression'=>'url(\'order/view/\'.$data->reference_no)',
            'htmlOptions'=>array('style'=>'text-align:center;width:12%'),
        ),                            
        array(
           'name'=>'method',
           'value'=>'$data->getPaymentMethodName(user()->getLocale())',
           'htmlOptions'=>array('style'=>'text-align:center;width:18%'),
           'type'=>'html',                        
        ),
        array(
           'name'=>'amount',
           'value'=>'$data->formatCurrency($data->amount,$data->currency)',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',                        
        ),
        //array(
        //   'name'=>'trace_no',
        //   'value'=>'$data->getTraceNo()',
        //   'htmlOptions'=>array('style'=>'text-align:center;'),
        //   'type'=>'html',
        //),
        array(
            'class'=>'CButtonColumn',
            'buttons'=> array (
                'view' => array(
                    'label'=>'<i class="fa fa-info-circle" title="'.Sii::t('sii','More Information').'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'$data->viewUrl', 
                ),
            ),
            'template'=>'{view}',
            'htmlOptions'=>array('width'=>'8%'),
        ),            
    ),
));
