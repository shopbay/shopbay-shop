<?php
$this->breadcrumbs=[
    Sii::t('sii','Account')=>url('account/profile'),
    Sii::t('sii','Change Email'),
];

$this->menu=[
    ['id'=>'account','title'=>Sii::t('sii','Manage Account'),'subscript'=>Sii::t('sii','account'), 'url'=>url('account/profile')],
    ['id'=>'password','title'=>Sii::t('sii','Change Password'),'subscript'=>Sii::t('sii','password'), 'url'=>url('account/password'),'visible'=>user()->isAuthorizedActivated],
    ['id'=>'email','title'=>Sii::t('sii','Change Email'),'subscript'=>Sii::t('sii','email'), 'url'=>url('account/email'),'linkOptions'=>['class'=>'active']],
    ['id'=>'notify','title'=>Sii::t('sii','Manage Notifications'),'subscript'=>Sii::t('sii','notification'), 'url'=>url('account/notifications'),'visible'=>user()->isAuthorizedActivated],
];


$this->widget('common.widgets.spage.SPage',[
    'id'=>'account_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => $this->loadWizards(get_class($form),user()),
    'heading'=> [
        'name'=> user()->getEmail(),
        'superscript'=>null,
        'subscript'=>null,
    ],
    'body'=>$this->renderPartial('common.modules.accounts.views.management._email_form',['model'=>$form],true),
    'sidebars'=>$this->getProfileSidebar(),
]);
