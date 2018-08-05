<?php 
$this->module->registerProcessCssFile();
$this->module->registerTaskScript();
$this->module->registerChosen();
$this->module->registerSUploadScript();

$this->breadcrumbs = [
    Sii::t('sii','Items'),
];

//Moved to page filter quick menu
//$this->menu = [
//    array('id'=>'item','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items'),'linkOptions'=>['class'=>'active']),
//    array('id'=>'order','title'=>Sii::t('sii','View Orders'),'subscript'=>Sii::t('sii','orders'), 'url'=>url('orders')),
//];

$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['flash' => $this->id],
    //['menu'  => $this->menu],
    ['sidebars'=>$this->getPageFilterSidebarData()],
    $config)
);                                  