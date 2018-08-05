<div class="list-box" style="position: relative">
    
    <div style="font-size: 1.1em;padding-bottom: 10px;">
        <?php echo $data->shop->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'max-width:50px;max-height:30px;vertical-align:middle;padding-right:5px;')).
                   CHtml::link(CHtml::encode(Helper::rightTrim($data->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY),isset($trimLength)?$trimLength:150)), $this->module->runControllerMethod('news/customer','getNewsViewUrl',$data)); ?>
    </div>

    <div class="summary">
        <?php echo Sii::t('sii','News released {datetime}',array('{datetime}'=>strtolower(Helper::prettyDate($data->create_time)))); ?>
    </div>
        
</div>

