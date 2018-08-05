<?php

$this->breadcrumbs=[
	Sii::t('sii','Account')=>url('account/profile'),
	Sii::t('sii','Payments'),
];
$this->menu=[];
    
$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['menu'  => $this->menu],
    ['flash' => $this->modelType],
    ['description' => Sii::t('sii','This lists all your payment records.')],
    ['sidebars' => $this->getProfileSidebar()],
    $config));
