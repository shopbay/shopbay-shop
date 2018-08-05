<?php $this->getModule()->registerChosen();?>
<?php
$this->widget('common.widgets.spage.SPage',array(
    'id'  => 'cart_template',
    'flash'  => isset($flash)?$flash:null,
    'heading'=> $this->isLastStep($this->action->id)?null:$step,
    'linebreak'=>false,
    'loader'=>false,
    'body'=>$this->renderPartial('_template_body',array('shop'=>$shop,'items'=>$items,'addonButtons'=>$addonButtons,'summary'=>$summary),true),
));

$this->smodalWidget();