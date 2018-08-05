<?php
$this->breadcrumbs=array(
    Sii::t('sii','News'),
);

$this->menu=array();

$this->spageindexWidget(array_merge(array('breadcrumbs'=>$this->breadcrumbs),
                                    array('menu'  => $this->menu),
                                    array('flash' => $this->modelType),
                                    $config));