<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();$(\'#Customer_gender_chzn .chzn-search\').hide();$(\'#Customer_locale_chzn .chzn-search\').hide();',CClientScript::POS_END);?>
<?php //cs()->registerScript('chosen2','$(\'.chzn-select-country\').chosen();$(\'#AccountAddress_country_chzn .chzn-search\').hide();',CClientScript::POS_END);?>
<div class="form" >
    
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'account_profile_form',
            'action'=>url('account/profile/update'),
        )); 
    ?>

    <?php //echo $form->errorSummary($model->profile); ?>
    <?php //if (isset($model->address))
          //    echo $form->errorSummary($model->address); 
    ?>

    <div class="row">
        <div class="column">
            <?php echo $form->labelEx($model,'first_name'); ?>
            <?php echo $form->textField($model,'first_name',array('size'=>35,'maxlength'=>50)); ?>
            <?php echo $form->error($model,'first_name'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model,'last_name'); ?>
            <?php echo $form->textField($model,'last_name',array('size'=>35,'maxlength'=>50)); ?>
            <?php echo $form->error($model,'last_name'); ?>
        </div>
    </div>

    <div class="row" style="clear:left;padding-top: 5px">
        <div class="column">
            <?php echo $form->labelEx($model,'alias_name'); ?>
            <?php echo $form->textField($model,'alias_name',array('size'=>35,'maxlength'=>50)); ?>
            <?php echo $form->error($model,'alias_name'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model,'birthday'); ?>
            <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'Customer[birthday]',
                    'value'=>$model->birthday,
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'showOn'=>'both',
                        'changeMonth'=>true,
                        'changeYear'=>true,
                        'yearRange'=>'-100:+0',
                        'dateFormat'=> 'yy-mm-dd',
                        'gotoCurrent'=>true,
                        'buttonImage'=> $this->getImage('datepicker',false),
                        'buttonImageOnly'=> true,
                    ),
                    'htmlOptions'=>array(
                        'style'=>'margin-right:5px;vertical-align:middle;width:185px;',

                    ),
                ));
            ?>    
            <?php echo $form->error($model,'birthday'); ?> 
        </div>        
    </div>
    
    <div class="row" style="clear:left;padding-top: 5px">
        <div class="column">
            <?php echo $form->labelEx($model,'gender',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($model,'gender', 
                                            array('M' => Sii::t('sii','Male'),
                                                  'F' => Sii::t('sii','Female')),
                                            array('class'=>'chzn-select',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Gender'),
                                                  'style'=>'width:150px;')); 
            ?>
            <?php echo $form->error($model,'gender'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model,'locale',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($model,'locale', 
                                            SLocale::getLanguages(),
                                            array('class'=>'chzn-select',
                                                      'prompt'=>'',
                                                      'data-placeholder'=>Sii::t('sii','Select Langague'),
                                                      'style'=>'width:210px;')); 
            ?>
            <?php echo $form->error($model,'locale'); ?>
        </div>
    </div>
    
    <div class="row" style="clear:left;padding-top: 15px">
        <?php echo $form->label($addressForm,'mobile'); ?>
        <?php echo $form->textField($addressForm,'mobile',array('size'=>70,'maxlength'=>20)); ?>
        <?php echo $form->error($addressForm,'mobile'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($addressForm,'address1'); ?>
        <div class="column">
            <?php echo $form->textField($addressForm,'address1',array('size'=>70,'maxlength'=>100)); ?>
            <?php echo $form->error($addressForm,'address1'); ?>
        </div>
        <?php echo $form->textField($addressForm,'address2',array('size'=>70,'maxlength'=>100)); ?>
        <?php echo $form->error($addressForm,'address2'); ?>
    </div>
    
    <div class="row">
        <div class="column">
            <?php echo $form->label($addressForm,'postcode'); ?>
            <?php echo $form->textField($addressForm,'postcode',array('size'=>30,'maxlength'=>20)); ?>
        </div>
        <div class="column">
            <?php echo $form->label($addressForm,'city'); ?>
            <?php echo $form->textField($addressForm,'city',array('size'=>30,'maxlength'=>40)); ?>

        </div>
        <div class="clear">
            <?php echo $form->error($addressForm,'postcode'); ?>
            <?php echo $form->error($addressForm,'city'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row">
        <div class="column">
            <?php echo $form->label($addressForm,'country',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($addressForm,'country',
                                            SLocale::getCountries(),
                                            array('class'=>'chzn-select-country',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Country'),
                                                  'style'=>'width:190px;')); 
            ?>
        </div>
        <div class="column">
            <?php echo $form->label($addressForm,'state',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($addressForm,'state',
                                            SLocale::getStates($addressForm->country),
                                            array('class'=>'chzn-select-state',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select State'),
                                                  'style'=>'width:200px;')); 
            ?>
        </div>
        <div class="clear">
            <?php echo $form->error($addressForm,'state'); ?>
            <?php echo $form->error($addressForm,'country'); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row" style="margin-top:20px;">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'profileButton',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Save'),
                    'value'=>'profilebtn',
                    'onclick'=>'js:function(){submitform(\'account_profile_form\');}',
                ));
         ?>
    </div> 

    <?php $this->endWidget(); ?>
    
</div><!-- form div -->
<?php $this->widget('SStateDropdown',array(
    'stateGetActionUrl'=>url('account/profile/stateget'),
    'countryFieldId'=>'CustomerAddressForm_country',
    'stateFieldId'=>'CustomerAddressForm_state',
));