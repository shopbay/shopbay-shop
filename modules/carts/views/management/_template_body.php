<?php echo CHtml::form(request()->getHostInfo().'/cart/csrf','post',array('id'=>$this->getCartName($shop))); ?>

    <div class="cart-items">
        <?php echo $items; ?>
    </div>

    <div class="cart-summary">
        <?php echo $summary;?>
    </div>

    <div id="buttons">
        <?php echo $addonButtons; ?>
    </div>

<?php  echo CHtml::endForm();?> 