<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    //'filter'=>$searchModel,
    'afterAjaxUpdate'=>'function(id, data){ wrb(id); }',//refer to tasks.js, id is the above gridview id
    'columns'=>array(
        array(
           'name' =>'order_no',
           'value'=>'$data->order_no',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
         ),
        array(
           'name' =>'create_time',
           'value'=>'$data->formatDateTime($data->create_time,true)',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%'),
         ),
        array(
            'name' =>'remarks',//use remarks as proxy for item name search
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Item Name'),
            'value' => '$data->getItemColumnData()',
        ),
        array(
            'name' =>'grand_total',
            'value'=>'$data->formatCurrency($data->grand_total)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
            'type'=>'html',
            'filter'=>false,
        ),     
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getOrderButtons(array(
                'view'=>true,
                'pay'=>'$data->payable()',
            )),
            'template'=>'{view} {pay}',
            'htmlOptions'=>array('style'=>'text-align:center;width:5%'),
        ),
    ),
));
