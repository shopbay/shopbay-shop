<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=> 'shopping_cart',
    'flash' => ['cart','cart2'],
    'heading'=> [
        'name'=>Sii::t('sii','Shopping Cart').(count($this->cart->getShops())>1?' <span id="cart_shop_count">(<span id="cart_shop_count_value">'.count($this->cart->getShops()).'</span>)</span>':''),
    ],
    'linebreak'=>false,
    'loader'=>false,
    'sections'=>$this->getCartSectionsData(user()->getLocale()),
]);

$this->widget('common.widgets.spage.SPage',[
    'id'=> 'recommendation',
    'heading'=> false,
    'layout'=>false,
    'linebreak'=>false,
    'loader'=>false,
    'body'=>$this->renderView('itemrecommend',[],true),
]);

$this->smodalWidget();

if (YII_DEBUG){
    foreach ($this->cart->getItems() as $key => $item){
        echo dump($item->getAttributes());
    }
}  

