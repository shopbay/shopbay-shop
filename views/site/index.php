<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=>'index_page',
    'heading'=> [
        'name'=> param('ORG_NAME').' - '.Sii::t('sii','The Open Source Ecommerce Platform'),
    ],
    'linebreak'=>false,
    'body'=> $this->renderPartial('_readme',[
                    'learnMoreUrl'=>Yii::app()->urlManager->createMerchantUrl('features'),
                    'openSourceUrl'=>Config::getSystemSetting('repo_source_link'),
                ],true),
]);