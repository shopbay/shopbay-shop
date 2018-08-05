<?php
    echo CHtml::form(url('tasks/item/receive'),'post',array('id'=>'item-form'));
    echo CHtml::activeHiddenField($model,'id');
?>
    <input id="receive-button" name="receive-button" type="submit" value="<?php echo Sii::t('sii','Confirm Received');?>" class="ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
<?php 
    echo CHtml::endForm();
