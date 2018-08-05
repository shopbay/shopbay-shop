<div class="image-form" >
    <?php $this->renderImageForm(
            $model,
            CHtml::label(Sii::t('sii','Change Avatar'),''),
            url('profile/'.$this->imageUploadAction));
    ?>    
</div>
<div class="data-form">
    <?php 
        $this->widget('common.widgets.SDetailView', [
            'data'=>$model,
            'columns'=>[
                [
                    ['name'=>'create_time','label'=>Sii::t('sii','Member since'),'value'=>$model->formatDatetime($model->account->create_time,false)],
                ],
            ],
        ]);
    ?>
    <?php echo $this->renderPartial('_form', ['model'=>$model,'addressForm'=>$addressForm]); ?>
</div>