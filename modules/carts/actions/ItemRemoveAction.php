<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemRemoveAction
 *
 * @author kwlok
 */
class ItemRemoveAction extends CartBaseAction 
{
    /**
     * Deletes a particular cart item.
     * @param integer $id the ID of the cart item to be deleted
     */
    public function run() 
    {
         if(isset($_GET['k']))  {
            $keys = explode(',', urldecode($_GET['k']));
            array_pop($keys);//removing the last entry ','

            if (!empty($keys)) {
                //multiple keys
                foreach ($keys as $key)
                    $this->getController()->cart->removeItem($key);
                $key = $keys[0];//take first key as representative (since all are same shop)
            }
            else {
                //single key
                $key = urldecode($_GET['k']);
                $this->getController()->cart->removeItem($key);
                logTrace(__METHOD__.' key '.$key.' removed');
            }
            
            $count = $this->getCartTotalCount();
            $shop = $this->getShop($key);
            header('Content-type: application/json');
            if ($this->getController()->cart->getCount()==0){
                user()->setCartCount(0);
                user()->setShopCartCount($shop,0);
                echo CJSON::encode(array(
                    'status'=>'full_empty', 
                    'emptyCart'=>$this->getController()->renderView('carts.empty',null,true),
                ));
            }
            else if ($this->getController()->cart->getCount($shop)==0){
                user()->setShopCartCount($shop,0);
                user()->setCartCount($this->getController()->cart->getCount());//reset cart count since shop cart count is changed
                echo CJSON::encode(array(
                    'status'=>'shop_empty', 
                    'emptyCart'=>$this->getController()->renderView('carts.empty',null,true),
                    'form'=>$this->getController()->getCartName($shop),
                    'shop_count'=>$this->getCartShopCount()-1,//minus out current shop which has no more items in cart
                    'total_count'=>$count,
                ));
            }
            else {
                $shopCartCount = $this->getController()->cart->getCount($shop);
                user()->setShopCartCount($shop,$shopCartCount);
                user()->setCartCount($this->getController()->cart->getCount());//reset cart count since shop cart count is changed
                echo CJSON::encode(array(
                    'status'=>'half_empty', 
                    'form'=>$this->getController()->getCartName($shop),
                    'cart'=>$this->getController()->renderView('carts.cartshop',array('shop'=>$shop,'checkout'=>false),true),
                    'total'=>$this->getShopTotalByShop($shop),
                    'item_count'=>$shopCartCount,//get all items count of the shop
                    'shop_count'=>$this->getCartShopCount(),
                    'total_count'=>$count,
                    'itemkey'=>$key,
                ));
            }
            
            Yii::app()->end();      
         }

         throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));
    }  
}