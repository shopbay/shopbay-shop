<div class="segment features top">
    <h1>
        <?php echo WcmFeature::title($page);?>
    </h1>
    <div class="features-summary">
        <ul>
            <?php 
                foreach (WcmFeature::items($page) as $item) {
                    echo CHtml::tag('li',[],'<i class="fa fa-check-circle fa-fw"></i>'.$item);
                } 
            ?>
        </ul>
    </div>   
</div>
<div class="segment features-set">
    <div class="feature-content">
        <?php 
            if ($page!='features_highlights'){
                $this->widget('zii.widgets.CBreadcrumbs', [
                    'htmlOptions'=>['id'=>'all','class'=>'breadcrumbs'],
                    'links'=>[Sii::t('sii','Features')=>url('/features'),WcmFeature::title($page)],
                    'homeLink'=>CHtml::link('<i class="fa fa-home"></i>', url('/')),
                    'separator'=>'<i class="fa fa-angle-right"></i>',
                ]);
            }
        ?>
        <?php echo $this->renderMarkdown($page,$this->getContentParams()); 
        ?>
    </div>
    <p class="clearfix"></p>
</div>
<?php
//Carousel setup for features_highlights
// Not displaying Carousel Indicators and Controls
// As Indicators seems not working, and control click UX is not so good (page jumping and url contains #admin_laptop_carousel
$this->renderCarouselWidget('$("#admin_laptop_carousel").carousel();$("#admin_mobile_carousel").carousel();');
