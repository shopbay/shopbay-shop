<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns' => array(
        array(
            'name'=>'create_time',
            'value'=>'$data->formatDateTime($data->create_time,true)',
            'htmlOptions'=>array('style'=>'text-align:center;width:12%'),
        ),
        array(
            'name' =>'order_no',
            'value' => '$data->order_no',
            'htmlOptions'=>array('style'=>'text-align:center;width:9%'),
        ),
        array(
            'name' =>'name',
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Item Name'),
            'value' => '$data->getItemColumnData(\'customer\',user()->getLocale())',
        ),
        array(
            'name'=>'unit_price',
            'value'=>'$data->formatCurrency($data->unit_price,$data->currency)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
        ),
        array(
            'name' =>'quantity',
            'value' => '$data->quantity',
            'htmlOptions'=>array('style'=>'text-align:center;width:6%'),
            'type'=>'html',
        ),
        array(
            'name'=>'total_price',
            'value'=>'$data->formatCurrency($data->grandTotal,$data->currency)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
        ),
//        array(
//            'name' =>Sii::t('sii','Weight'),
//            'value' => '$data->total_weight',
//        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
 //           'filter'=>true,
        ),
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getItemButtons(array(
                'view'=>true,
                'review'=>'$data->reviewable()',
                'receive'=>'$data->receivable()',
                'tracking'=>'$data->fulfillable()',
            ),true),//customer mode
            'template'=>'{view} {tracking} {review} {receive}',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
    ),
));