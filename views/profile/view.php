<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerChosen();?>
<?php
$this->breadcrumbs=[
    Sii::t('sii','Account')=>url('account/profile'),
    Sii::t('sii','Profile'),
];
$this->menu=[
    ['id'=>'account','title'=>Sii::t('sii','Manage Account'),'subscript'=>Sii::t('sii','account'), 'url'=>url('account/profile'),'linkOptions'=>['class'=>'active']],
    ['id'=>'password','title'=>Sii::t('sii','Change Password'),'subscript'=>Sii::t('sii','password'), 'url'=>url('account/password'),'visible'=>user()->isAuthorizedActivated],
    ['id'=>'email','title'=>Sii::t('sii','Change Email'),'subscript'=>Sii::t('sii','email'), 'url'=>url('account/email'),'visible'=>user()->isAuthorizedActivated],
    ['id'=>'notify','title'=>Sii::t('sii','Manage Notifications'),'subscript'=>Sii::t('sii','notification'), 'url'=>url('account/notifications'),'visible'=>user()->isAuthorizedActivated],
];

$this->widget('common.widgets.spage.SPage',[
    'id'=>'profile_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> [
        'name'=> $model->account->email,
        'superscript'=>null,
        'subscript'=>Sii::t('sii','Last update time {datetime}',['{datetime}'=>$model->formatDatetime($model->account->update_time,true)]),
    ],
    'body'=>$this->renderPartial('_view_body', ['model'=>$model,'addressForm'=>$this->getCustomerAddressForm($model)],true),
    'sidebars'=>$this->getProfileSidebar(),
]);