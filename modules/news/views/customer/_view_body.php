<?php if ($model->hasImage()):?>
<div class="news-image">
    <?php echo CHtml::image($model->imageOriginalUrl);?>
</div>
<?php endif;?>
<div class="content">
    <?php echo $model->displayLanguageValue('content',user()->getLocale(),Helper::PURIFY);?>
</div>   
