<?php if (isset($action) && ($action=='SelectPaymentMethod' || $action=='Confirm'))
          $this->renderView('carts.confirmaddress');
      if (isset($action) && $action=='Confirm')
          $this->renderView('carts.confirmpayment');
?>
<?php
      if (isset($checkout) && !$checkout && $this->cart->getShop($shop)->hasPromocodes())
          $this->renderView('carts.cartpromocode',array('shop'=>$shop,'promocode'=>$this->cart->getPromocode($shop)));

?>
<?php $this->widget('common.widgets.SListView', [
        'dataProvider'=> $this->getCartTotalDataProvider($shop),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.keyvalue'),
    ]);
?>
<div class="buttons">
    <?php echo $buttons; ?>
    <?php if (isset($checkout) && !$checkout)
              echo $this->getPaypalCheckoutButton($this->cart->getShop($shop)); 
    ?>
</div>
