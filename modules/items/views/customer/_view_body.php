<?php $this->getModule()->registerGridViewCssFile();?>
<?php if ($model->reviewable()): ?>
<table>
     <tbody>
        <tr>
            <td colspan="2" style="width:75%;padding-left:20px;vertical-align: top">
                <h1><?php echo Sii::t('sii','Post a review');?></h1>
                <?php $this->renderView('comments.commentupdate',array('model'=>$model->getComment(url('tasks/item/review'))));?>
            </td>
        </tr>
     </tbody>   
</table>      
<div class="line-break"></div> 
<?php endif; ?>

<?php if ($model->itemReviewed()): ?>
<table width="95%">
     <tbody>
        <tr>
            <td colspan="2" style="width:75%;padding-left:20px;vertical-align: top">
                <h1><?php echo Sii::t('sii','My Review')?></h1>
                <?php $this->renderView('comment',array('model'=>$model->getReview()));?>
            </td>
        </tr>
     </tbody>   

</table>      
<div class="line-break"></div> 
<?php endif; ?>

<?php 

    if ($model->itemRefunded())
        $this->renderPartial('common.modules.orders.views.merchant._summary_refund',['model'=>$model]);

    $this->widget('common.widgets.SDetailView', array(
        'data'=>$model,
        'columns'=>array(
            array(
                'image-column'=>array(
                    'image'=>$this->simagezoomerWidget(array('imageOwner'=>$model->product),true),
                    //Below simageviewerWidget works in Yii 1.1.15 due to fancybox lib compatibility with jquery 1.11
                    //'image'=>$this->simageviewerWidget(array(
                    //'imageModel'=>$model->product,
                    //'imageName'=>$model->existsProduct?$model->product->getLanguageValue('name',user()->getLocale()):Sii::t('sii','Image'),
                    //'imageUrl'=>$model->existsProduct?null:$model->productImageUrl,
                    //'imageVersion'=>Image::VERSION_XMEDIUM)
                    //,true),
                    //------
//                    'width'=>$model->product->getImagesCount()>1?'0.38':'0.35',
                    'cssClass'=>$model->getProductImagesCount()>1?SPageLayout::WIDTH_38PERCENT:SPageLayout::WIDTH_35PERCENT,
                ),
            ),
            array(
                array('name'=>'shop_id','type'=>'raw','value'=>CHtml::link($model->shop->displayLanguageValue('name',user()->getLocale()),$model->shop->url,['target'=>'_blank']),'visible'=>!user()->onShopScope()),
                array('name'=>'order_no','type'=>'raw','value'=>
                    CHtml::link($model->order_no,$model->byGuestCustomer()?$model->order->getGuestAccessUrl($model->shop->domain):$model->order->viewUrl).
                    CHtml::tag('span',['class'=>'tag'],Helper::htmlColorText($model->order->getStatusText()))
                ),
                array('name'=>'shipping_order_no'),
                array('name'=>'product_sku'),
                array('name'=>'shipping_id','value'=>$model->shipping->displayLanguageValue('name',user()->getLocale())),
                array('name'=>'weight','value'=>$model->formatWeight($model->weight),'visible'=>isset($model->weight)),
                array('name'=>'tracking_no','value'=>$model->tracking_no,'visible'=>isset($model->tracking_no)),
                array('name'=>'tracking_url','value'=>$model->tracking_url,'visible'=>isset($model->tracking_url)),
                'key-value-element'=>$model->getOptions(user()->getLocale()),
            ),
            array(
                array('name'=>'unit_price',
                      'type'=>'raw',
                      'cssClass'=>'unit-price',
                      'value'=>$model->formatCurrency($model->getPrice(),$model->currency).
                               ($model->isCampaignItem()?'<span class="usual-price">'.$model->formatCurrency($model->getCampaignUsualPrice(),$model->currency).'</span>':'')
                ),
                array('name'=>'quantity'),
                array('name'=>'option_fee','value'=>$model->formatCurrency($model->option_fee,$model->currency)),
                array('name'=>'shipping_surcharge','value'=>$model->formatCurrency($model->shipping_surcharge,$model->currency)),
                array('label'=>Sii::t('sii','Item Total'),'value'=>$model->formatCurrency($model->total_price,$model->currency)),
                array('label'=>Sii::t('sii','Discount'),'type'=>'raw','value'=>$model->formatCurrency($model->orderDiscount,$model->currency).$model->getOrderDiscountTag(user()->getLocale()),'visible'=>$model->hasOrderDiscount),
                array('label'=>Sii::t('sii','Tax'),'value'=>$model->formatCurrency($model->taxPrice,$model->currency)),
                array('name'=>'total_price','value'=>$model->formatCurrency($model->grandTotal,$model->currency)),
            ),
        ),
    ));


$this->spagesectionWidget($this->getModule()->runControllerMethod('items/customer','getSectionsData',$model));
