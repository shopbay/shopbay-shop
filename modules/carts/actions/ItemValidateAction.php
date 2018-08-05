<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemValidateAction
 *
 * @author kwlok
 */
class ItemValidateAction extends CartBaseAction 
{
    /**
     * Checkout item at cart form 
     * @param integer $key the ID of session object to delete
     */
    public function run() 
    {
         if(isset($_GET['f']) && isset($_GET['v']))
         {
            //need to urlencode back as key are stored in urlencoded form
            //php auto urldecode when processing
            $field = explode('_', urldecode($_GET['f']));
            $key = $field[0];
            $attr = $field[1];
            //get quantity
            $value = urldecode($_GET['v']);
            $item = $this->getController()->cart->itemAt($key);
            if ($item->hasCampaignBgaG()){
                $affinityItem = $this->getController()->cart->getAffinityItem($key);
                $affinityItem->quantity = $item->getCampaignModel()->get_y_qty * $value;
            }
            header('Content-type: application/json');
            $error = array();
            switch ($attr) {
                case 'quantity':
                    $error = ItemValidator::validateItemQuantity($value);
                    if (count($error)==0) {
                        $item->quantity = $value;
                        $this->getController()->cart->update($item,$item->quantity);
                        echo CJSON::encode(array(
                            'status'=>'success',
                            'item_key'=>$item->getKey(),
                            'item_subtotal'=>$item->getProductModel()->formatCurrency($item->getTotalPrice()),
                            'item_affinity_subtotal'=>isset($affinityItem)?$affinityItem->getProductModel()->formatCurrency($affinityItem->getTotalPrice()):0,
                            'item_affinity_quantity'=>isset($affinityItem)?$affinityItem->quantity:0,
                            'subtotal'=>$this->getShippingSubtotal($key),
                            'total'=>$this->getShopTotal($key),
                            'tax'=>$this->getShopTaxes($key),//this method has to be called after shopTotal
                            'form'=>$this->getController()->getCartName($item->getShop()),
                            'item_count'=>$this->getController()->cart->getCount($item->getShop()),//get all remaining items count of the shop
                            'count'=>$this->getCartTotalCount(),
                        ));
                    }
                    else {
                        $item->quantity = 1;
                        user()->setFlash('cart',array(
                            'message'=>$error,
                            'type'=>'error',
                            'title'=>null,
                        ));
                        echo CJSON::encode(array(
                            'status'=>'failure',
                            'flash'=>$this->getController()->sflashWidget('cart',true),
                        ));
                    }
                    break;
                default:
                    break;
            }

            user()->setCartCount($this->getController()->cart->getCount());
            
            Yii::app()->end();      
         }

         throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));
    }  
    
}