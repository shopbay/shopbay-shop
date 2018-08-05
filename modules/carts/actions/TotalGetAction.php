<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of TotalGet
 *
 * @author kwlok
 */
class TotalGetAction extends CartBaseAction 
{
    /**
     * Deletes a particular cart item.
     * @param integer $id the ID of the cart item to be deleted
     */
    public function run() 
    {
        if(isset($_GET['scope']) && isset($_GET['k'])) {

            $keys = explode(',', urldecode($_GET['k']));
            array_pop($keys);//removing the last entry ','
                
            logTrace('count keys '.count($keys));
            
            if ($_GET['scope']=='all')
                $this->getController()->cart->checkoutItems($keys,true);
            if ($_GET['scope']=='none') 
                $this->getController()->cart->checkoutItems($keys,false);
             
            $subtotal = new CList();
            foreach ($keys as $key) {
                $subtotal->add($this->getShippingSubtotal($key));
            }
            
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'status'=>'success',
                'subtotals'=>$subtotal->toArray(),
                'total'=>$this->getShopTotal($keys[0]),//take first key as representative (since all are same shop)
                'tax'=>$this->getShopTaxes($keys[0]),//this method has to be called after shopTotal
            ));
            Yii::app()->end();      
        
        }
        throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));
    }  
}