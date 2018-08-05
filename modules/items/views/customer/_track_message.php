<div>
    <p>
        <?php echo Sii::t('sii','Your order {order_no} is {status}.',array('{status}'=>Process::getHtmlDisplayText($model->order->status),'{order_no}'=>CHtml::link($model->order->order_no,$model->order->getGuestAccessUrl($model->shop->domain),array('style'=>'color:red'))));?>
    </p>
    <p>
        <?php echo Sii::t('sii','Your item {name} is {status}.',array('{status}'=>Process::getHtmlDisplayText($model->status),'{name}'=>CHtml::link($model->displayLanguageValue('name',user()->getLocale()),$model->getGuestAccessUrl($model->shop->domain),array('style'=>'color:red'))));?>
    </p>
</div>