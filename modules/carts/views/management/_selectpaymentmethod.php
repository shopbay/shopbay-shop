<?php $this->renderView('paymentform',['model'=>$form,'url'=>$this->getOwner()->appendQueryParams('/cart/management/buttonget/mid/{id}')]);?>

<div class="line-break" style="margin-top: 20px;"></div>
 
<?php 
$this->renderPartial('_cart',['checkout'=>true,'shop'=>$shop]);

