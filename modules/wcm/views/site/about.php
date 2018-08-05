<div class="segment canvas about-us">
    <h1><?php echo Sii::t('sii','About {company}',array('{company}'=>param('ORG_NAME')));?></h1>
    <?php echo $this->renderMarkdown($about,['SITE_NAME']);?>
</div>
<!--<div class="segment line-break"></div>-->
<!--<div class="segment canvas values">
    <h1><?php //echo Sii::t('sii','Our Core Values');?></h1>
    <?php //echo $this->renderMarkdown($values);?>
</div>-->
<!--<div class="segment line-break"></div>-->
<!--<div class="segment canvas investors">
    <h1><?php //echo Sii::t('sii','Our Investors');?></h1>
    <?php //echo $this->renderMarkdown($investors,['SITE_NAME']);?>
</div>
<div class="segment line-break"></div>-->