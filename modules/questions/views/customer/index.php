<?php
$this->breadcrumbs=array(
	Sii::t('sii','Account')=>url('account/profile'),
 	Sii::t('sii','Questions'),
);
$this->menu=array(
    array('id'=>'ask','title'=>Sii::t('sii','Ask Question'),'subscript'=>Sii::t('sii','ask'), 'url'=>$this->getQuestionAskUrl(),'linkOptions'=>array('class'=>'primary-button')),
    array('id'=>'all','title'=>Sii::t('sii','All Questions'),'subscript'=>Sii::t('sii','all'), 'linkOptions'=>array('id'=>'all_items','class'=>$this->getPageMenuCssClass('all'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","all")')),
    array('id'=>'asked','title'=>Sii::t('sii','Asked Questions'),'subscript'=>Sii::t('sii','asked'), 'linkOptions'=>array('id'=>'asked_items','class'=>$this->getPageMenuCssClass('asked'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","asked")')),
    array('id'=>'answered','title'=>Sii::t('sii','Answered Questions'),'subscript'=>Sii::t('sii','answered'), 'linkOptions'=>array('id'=>'answered_items','class'=>$this->getPageMenuCssClass('answered'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","answered")')),
);

$this->spageindexWidget(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'=>$this->menu),
    array('flash' => $this->modelType),
    array('sidebars' => $this->getProfileSidebar()),
    $config));