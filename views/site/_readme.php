<p>
    <?php echo Sii::t('sii','{app} is built using popular LAMP stack and Yii framework and licensed under the Apache License Version 2.0.',['{app}'=>param('ORG_NAME')]);?>
</p>
<p>
    <?php echo Sii::t('sii','Web Agency or Ecommerce consultants use {app} as their ecommerce platform empowering merchants to run online business.',['{app}'=>param('ORG_NAME')]);?>
</p>
<p>
    <?php echo Sii::t('sii','Merchants or individuals use {app} to easily build online shops selling on digital channels such as web, mobile, social media and messaging apps.',['{app}'=>param('ORG_NAME')]);?>
</p>
<p>
    <?php echo Sii::t('sii','For developers, if you find our project interesting and meaningful, please join us make contribution and have fun together.');?>
</p>
<div style="margin-top: 40px;">
    <?php                
    $this->widget('zii.widgets.jui.CJuiButton',[
        'name'=>'button-name1',
        'buttonType'=>'button',
        'caption'=>Sii::t('sii','Learn more'),
        'value'=>'btn',
        'htmlOptions'=>['class'=>'form-input','target'=>'_blank','onclick'=>'window.open("'.$learnMoreUrl.'");'],
    ]);                 

    $this->widget('zii.widgets.jui.CJuiButton',[
        'name'=>'button-name2',
        'buttonType'=>'button',
        'caption'=>Sii::t('sii','View Github'),
        'value'=>'btn',
        'htmlOptions'=>['class'=>'form-input','target'=>'_blank','onclick'=>'window.open("'.$openSourceUrl.'");'],
    ]);   
    ?>
</div>
<div style="margin-top: 20px;">
    <?php
    $this->widget('zii.widgets.jui.CJuiButton',[
        'name'=>'demo-shop-button',
        'buttonType'=>'button',
        'caption'=>Sii::t('sii','View Demo Shop'),
        'value'=>'btn',
        'htmlOptions'=>['class'=>'form-input','style'=>'background: orangered','target'=>'_blank','onclick'=>'window.open("'.Config::getSystemSetting('shop_demo_link').'");'],
    ]); 
    $this->widget('zii.widgets.jui.CJuiButton',[
        'name'=>'demo-chatbot-button',
        'buttonType'=>'button',
        'caption'=>Sii::t('sii','View Demo Chatbot'),
        'value'=>'btn',
        'htmlOptions'=>['class'=>'form-input','style'=>'background: orangered','target'=>'_blank','onclick'=>'window.open("'.Config::getSystemSetting('chatbot_demo_link').'");'],
    ]); 
    ?>    
</div>