<?php if (isset($model->answer)):?>
<div class="answer rounded3" style="margin-left: 0px">
    <span class="character"><?php echo Sii::t('sii','A: ');?></span>
    <?php echo Helper::purify($model->answer); ?>
    <span class="time">
        <?php echo $model->formatDatetime($model->answer_time,true);?> 
    </span>
</div>
<?php else: ?>
<div style="padding:20px;">
    <i><?php echo Sii::t('sii','Pending answered by {shop_name}.',array('{shop_name}'=>$model->shop->displayLanguageValue('name',user()->getLocale())));?></i>
</div>
<?php endif;?>
