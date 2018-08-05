<div class="upper_footer">
    <ul>
        <h3><?php echo Sii::t('sii','Ecommerce Platform');?></h3>
        <li><?php echo l(Sii::t('sii','Online Shop'), hostUrl('features'));?></li>
        <li><?php echo l(Sii::t('sii','Theme Store'), Yii::app()->urlManager->createMerchantUrl('themes'));?></li>
        <li><?php echo l(Sii::t('sii','Chatbot'), hostUrl('features/chatbots#all'));?></li>
    </ul>
    <ul>
        <h3><?php echo Sii::t('sii','Community');?></h3>
        <li><?php echo l(Sii::t('sii','Tutorials'), Yii::app()->urlManager->createMerchantUrl('community/tutorials'));?></li>
        <li><?php echo l(Sii::t('sii','Tutorial Series'), Yii::app()->urlManager->createMerchantUrl('community/tutorials/series'));?></li>
        <li><?php echo l(Sii::t('sii','Questions'), Yii::app()->urlManager->createMerchantUrl('community/questions'));?></li>
    </ul>
    <ul class="right-column">
        <h3><?php echo Sii::t('sii','Connect');?></h3>
        <?php $facebookLink = Config::getSystemSetting('facebook_link'); ?>
        <?php if (strlen($facebookLink)>10)://valid url has longer length ?>
            <li><?php echo l('<i class="fa fa-facebook-square" style="font-size:25px"></i>', $facebookLink,['target'=>'_blank']);?></li>
        <?php endif; ?>
        <?php $twitterLink = Config::getSystemSetting('twitter_link'); ?>
        <?php if (strlen($twitterLink)>10): ?>
            <li><?php echo l('<i class="fa fa-twitter-square" style="font-size:25px"></i>', $twitterLink,['target'=>'_blank']);?></li>
        <?php endif; ?>
        <?php $linkedinLink = Config::getSystemSetting('linkedin_link'); ?>
        <?php if (strlen($linkedinLink)>10): ?>
            <li><?php echo l('<i class="fa fa-linkedin-square" style="font-size:25px"></i>', $linkedinLink,['target'=>'_blank']);?></li>
        <?php endif; ?>
    </ul>
</div>
<div class="lower_footer">
    <ul class="copyright">
        <li><?php echo Sii::t('sii','Copyright &copy; 2015 - {year} {company}',['{year}'=>date('Y'),'{company}'=>CHtml::link(param('ORG_NAME'),hostUrl('site/about'))]);?></li>
<!--        <li><?php //echo l(Sii::t('sii','About us'), hostUrl('about'));?></li>-->
        <li><?php echo l(Sii::t('sii','Terms of Service'), hostUrl('site/terms'));?></li>
        <li><?php echo l(Sii::t('sii','Privacy Policy'), hostUrl('site/privacy'));?></li>
        <li><?php echo l(Sii::t('sii','Contact us'), hostUrl('site/contact'));?></li>
    </ul>
</div>