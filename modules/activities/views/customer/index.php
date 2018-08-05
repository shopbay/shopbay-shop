<?php
$this->breadcrumbs=[
    Sii::t('sii','Account')=>url('account/profile'),
    Sii::t('sii','Activities'),
];
    
$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['flash' => $this->id],
    ['sidebars' => $this->getProfileSidebar()],
    $config));