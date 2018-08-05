<div class="recent-item rounded">
    <div class="image">
        <?php echo CHtml::image($data->product_image,$data->displayLanguageValue('name',user()->getLocale()),array('width'=>60,'height'=>60)); ?>
    </div>
    <div class="text">
        <?php echo CHtml::link(CHtml::encode(Helper::rightTrim($data->displayLanguageValue('name',user()->getLocale()), 50)), $data->getProductUrl(), array('target'=>'_blank')); ?>
    </div>
    <div class="text">
        <strong style="color:red;"><?php echo $data->formatCurrency($data->unit_price,$data->currency); ?></strong>
        <span style="font-size:0.8em;">
            <?php echo Helper::prettyDate($data->create_time); ?> 
        </span>
    </div>
</div>