<?php   $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data '.(isset($showAvatar)?'content100':'content')),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="image">{value}</div>',
                    'value'=>$data->questioner->getAvatar(Image::VERSION_XSMALL),
                    'visible'=>isset($showAvatar),
                ),                
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element q">{value}</div>',
                    'value'=>$this->renderPartial($this->module->getView('questions.questiontemplate'),array(
                                    'title'=>Sii::t('sii','Q: '),
                                    'content'=>isset($htmlDecode)?Helper::addNofollow(CHtml::decode(Helper::purify($data->question))):CHtml::link(Helper::purify($data->question),$data->viewUrl),
                                    'datetime'=>$data->formatDatetime($data->question_time,true),
                                    'cssClass'=>'question',
                                 ),true),
                ),         
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element a">{value}</div>',
                    'value'=>$this->renderPartial($this->module->getView('questions.questiontemplate'),array(
                                    'title'=>Sii::t('sii','A: '),
                                    'content'=>isset($htmlDecode)?Helper::addNofollow(CHtml::decode(Helper::purify($data->answer))):Helper::purify($data->answer),
                                    'datetime'=>$data->formatDatetime($data->answer_time,true),
                                    'cssClass'=>'answer',
                                 ),true),
                    'visible'=>isset($data->answer),
                ),         
            ),
        )); 

