<div class="form customer-address-form">

    <div class="form-row">
        <div class="column">
            <?php echo $form->labelEx($model,'first_name',array('class'=>'form-label')); ?>
            <?php echo $form->textField($model,'first_name',array('class'=>'form-input','size'=>60,'maxlength'=>50,'placeholder'=>$model->getAttributeLabel('first_name'))); ?>
            <?php echo $form->error($model,'first_name'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model,'last_name',array('class'=>'form-label')); ?>
            <?php echo $form->textField($model,'last_name',array('class'=>'form-input','size'=>60,'maxlength'=>50,'placeholder'=>$model->getAttributeLabel('last_name'))); ?>
            <?php echo $form->error($model,'last_name'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <?php if (isset($showAlias)):?>
    <div class="form-row">
        <?php echo $form->label($model,'alias_name',array('class'=>'form-label')); ?>
        <?php echo $form->textField($model,'alias_name',array('class'=>'form-input','size'=>50,'maxlength'=>100,'placeholder'=>$model->getAttributeLabel('alias_name'))); ?>
        <?php echo $form->error($model,'alias_name'); ?>
    </div>
    <?php endif;?>
    
    <div class="form-row">
        <?php echo $form->labelEx($model,'mobile',array('class'=>'form-label')); ?>
        <?php echo $form->textField($model,'mobile',array('class'=>'form-input','size'=>50,'maxlength'=>20,'placeholder'=>$model->getAttributeLabel('mobile'))); ?>
        <?php echo $form->error($model,'mobile'); ?>
    </div>

    <div class="form-row">
        <?php echo $form->labelEx($model->address,'address1',array('class'=>'form-label')); ?>
        <?php echo $form->textField($model->address,'address1',array('class'=>'form-input','size'=>50,'maxlength'=>100,'placeholder'=>$model->getAttributeLabel('address'))); ?>
    </div>

    <div class="form-row">
        <?php echo $form->textField($model->address,'address2',array('class'=>'form-input','size'=>50,'maxlength'=>100,'placeholder'=>$model->getAttributeLabel('address2'))); ?>
        <?php echo $form->error($model->address,'address1'); ?>
        <?php echo $form->error($model->address,'address2'); ?>
    </div>

    <div class="form-row">
        <div class="column">
            <?php echo $form->labelEx($model->address,'postcode',array('class'=>'form-label')); ?>
            <?php echo $form->textField($model->address,'postcode',array('class'=>'form-input','size'=>25,'maxlength'=>20,'placeholder'=>$model->getAttributeLabel('postcode'))); ?>
            <?php echo $form->error($model->address,'postcode'); ?>
        </div>
        <div class="column">
        <?php echo $form->labelEx($model->address,'city',array('class'=>'form-label')); ?>
            <?php echo $form->textField($model->address,'city',array('class'=>'form-input','size'=>30,'maxlength'=>40,'placeholder'=>$model->getAttributeLabel('city'))); ?>                
            <?php echo $form->error($model->address,'city'); ?>
        </div>
    </div>

    <div class="form-row">
        <div class="column">
            <?php echo $form->labelEx($model->address,'country',array('class'=>'form-label','style'=>'margin-top:8px')); ?>
            <?php echo $form->dropDownList($model->address,'country',
                                            SLocale::getCountries(),
                                            array('class'=>'chzn-select-country form-input',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Country'),
                                                  'style'=>'')); 
            ?>
            <?php echo $form->error($model->address,'country'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model->address,'state',array('class'=>'form-label','style'=>'margin-top:8px')); ?>
            <?php echo $form->dropDownList($model->address,'state',
                                            SLocale::getStates($model->address->country),
                                            array('class'=>'chzn-select-state form-input',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select State'),
                                                  'style'=>'')); 
            ?>
            <?php echo $form->error($model->address,'state'); ?>
        </div>
    </div>
</div>
<?php $this->widget('SStateDropdown',[
    'useChosen'=>false,
    'stateGetActionUrl'=>url('account/profile/stateget'),
    'countryFieldId'=>'CustomerAddressForm_country',
    'stateFieldId'=>'CustomerAddressForm_state',
]);