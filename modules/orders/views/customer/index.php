<?php $this->getModule()->registerProcessCssFile();?>
<?php $this->getModule()->registerTaskScript(true);?>
<?php
$this->breadcrumbs=[
    Sii::t('sii','Orders'),
];

//Moved to page filter quick menu
//$this->menu=[
//    ['id'=>'item','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items')],
//    ['id'=>'order','title'=>Sii::t('sii','View Orders'),'subscript'=>Sii::t('sii','orders'), 'url'=>url('orders'),'linkOptions'=>['class'=>'active']],
//];
    
$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['flash' => $this->modelType],
    //['menu'  => $this->menu],
    ['sidebars'=>$this->getPageFilterSidebarData()],
    $config)
);
