<div class="order-items">
    <?php
        $this->renderPartial('_order_header',array('order'=>$model));
        $this->renderPartial('_order_shop',array('order'=>$model));
        $this->renderPartial('_order_footer',array('order'=>$model));
    ?>
</div>