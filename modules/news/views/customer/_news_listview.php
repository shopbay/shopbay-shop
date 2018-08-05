<?php $this->widget('common.widgets.SDetailView', array(
        'data'=>$data,
        'htmlOptions'=>array('class'=>'list-box float-image'),
        'attributes'=>array(
            array(
                'type'=>'raw',
                'template'=>'<div class="image">{value}</div>',
                'value'=>$data->shop->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'max-width:120px;max-height:80px;')),
            ),
            array(
                'type'=>'raw',
                'template'=>'{value}',
                'value'=>$this->widget('common.widgets.SDetailView', array(
                    'data'=>$data,
                    'htmlOptions'=>array('class'=>'data'),
                    'attributes'=>array(
                        array(
                            'type'=>'raw',
                            'template'=>'<div class="heading-element">{value}</div>',
                            'value'=>CHtml::link(CHtml::encode($data->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY)), $this->getNewsViewUrl($data)),
                        ),
                        array(
                            'type'=>'raw',
                            'template'=>'<div class="element">{value}</div>',
                            'value'=>'<strong>'.Sii::t('sii','Release Date').'</strong>'.
                                     CHtml::encode($data->formatDatetime($data->create_time,true)),
                        ),         
                    ),
                ),true),
            ),        
        ),
    )); 