<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemCheckoutAction
 *
 * @author kwlok
 */
class ItemCheckoutAction extends CartBaseAction 
{
    /**
     * Checkout item at cart form 
     * @param integer $key the ID of session object to delete
     */
    public function run() 
    {
        if(isset($_GET['k']) && isset($_GET['v'])) {
            $key = urldecode($_GET['k']);
            $value = urldecode($_GET['v'])=='true'?true:false;

            $this->getController()->cart->checkoutItem($key,$value);
             
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'status'=>'success',
                'subtotal'=>$this->getShippingSubtotal($key),
                'total'=>$this->getShopTotal($key),
                'tax'=>$this->getShopTaxes($key),//this method has to be called after shopTotal
                'count'=>$this->getCartTotalCount(),
            ));
            Yii::app()->end();      
        }

        throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));
    }  
}
