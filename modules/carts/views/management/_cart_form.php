<?php echo CHtml::form(request()->getHostInfo().'/cart/csrf','post',['id'=>$this->getCartName($shop)]); ?>

    <div id="step" class="grid-view" style="display:none"></div>

    <div class="cart-items">
        <?php $this->renderView('carts.cart',[
                'checkout'=>false,
                'shop'=>$shop,
                'queryParams'=>isset($queryParams)?$queryParams:[],
            ]); 
        ?>
    </div>
    
    <div class="cart-summary">
        <?php $this->renderView('carts.cartsummary',[
                'checkout'=>false,
                'shop'=>$shop,
                'buttons'=>$this->renderView('carts.buttons',[
                    'buttons'=>$this->getCheckoutButtons(isset($action)?$action:$this->getAction()->getId(),[
                        'shopModel'=>$this->cart->getShop($shop),
                        'queryParams'=>isset($queryParams)?$queryParams:[],
                    ]),
                ],true),
            ]);
        ?>
    </div>
    
    <div id="buttons">

        <?php echo $this->renderView('carts.buttons',[
                    'buttons'=>$this->getAddOnButtons(isset($action)?$action:$this->getAction()->getId(),[
                        'shopModel'=>$this->cart->getShop($shop),
                        'queryParams'=>isset($queryParams)?$queryParams:[],
                    ]),
                ]); 
        ?>
    </div>

<?php  echo CHtml::endForm();?>     