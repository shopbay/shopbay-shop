<?php
$this->breadcrumbs = [
    Sii::t('sii','Account'),
    Sii::t('sii','Notifications'),
];

$this->menu=[
    ['id'=>'account','title'=>Sii::t('sii','Manage Account'),'subscript'=>Sii::t('sii','account'), 'url'=>url('account/profile')],
    ['id'=>'password','title'=>Sii::t('sii','Change Password'),'subscript'=>Sii::t('sii','password'), 'url'=>url('account/password')],
    ['id'=>'email','title'=>Sii::t('sii','Change Email'),'subscript'=>Sii::t('sii','email'), 'url'=>url('account/email'),'visible'=>user()->isAuthorizedActivated],
    ['id'=>'notify','title'=>Sii::t('sii','Manage Notifications'),'subscript'=>Sii::t('sii','notification'), 'url'=>url('account/notifications'),'linkOptions'=>['class'=>'active'],'visible'=>user()->isAuthorizedActivated],
];

$this->widget('common.widgets.spage.SPage',[
    'id'=>'account_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => $this->id,
    'heading'=> [
        'name'=> Sii::t('sii','Notifications'),
    ],
    'description'=>Sii::t('sii','Subscribe to the notifications that you would like to receive'),
    'body'=>$this->renderPartial('common.modules.notifications.views.subscription._notifications',[],true),
    'sidebars'=>$this->getProfileSidebar(),
]);