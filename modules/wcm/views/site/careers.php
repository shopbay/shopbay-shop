<div class="segment canvas">
    <h1><?php echo Sii::t('sii','Careers at {company}',array('{company}'=>param('ORG_NAME')));?></h1>
    <?php echo $this->renderMarkdown('careers',['SITE_NAME']);?>
</div>