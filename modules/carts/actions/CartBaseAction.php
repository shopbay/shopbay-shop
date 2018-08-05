<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.carts.components.CartData");
/**
 * Description of CartBaseAction
 *
 * @author kwlok
 */
class CartBaseAction extends CAction 
{
    protected function getShopTaxesByShop($shop)
    {
        $taxes = new CMap();
        //extract formatted tax amount
        foreach ($this->getController()->cart->getTaxes($shop) as $taxId => $taxData) {
            $d = $this->getController()->module->serviceManager->getTaxManager()->parseTaxData($taxData);
            $taxes->add($shop.'_'.$taxId,$d->amount_text);//formatted tax amount
        }
        return $taxes->toArray();
    }
    protected function getShopTaxes($key)
    {
        $components = CartData::parseItemKey($key);
        return $this->getShopTaxesByShop($components['shop']);
    }
    protected function getShippingSubtotal($key)
    {
        $components = CartData::parseItemKey($key);
        return array($components['shipping']=>$this->getController()->cart->getCheckoutSubTotalByShipping($components['shipping'],Helper::FORMAT));
    }
    protected function getShopTotalByShop($shop)
    {
        return array($shop=>$this->getController()->cart->getCheckoutTotal($shop,Helper::FORMAT));
    }    
    protected function getShopTotal($key)
    {
        $components = CartData::parseItemKey($key);
        return $this->getShopTotalByShop($components['shop']);
    }
    protected function getShop($key)
    {
        $components = CartData::parseItemKey($key);
        return $components['shop'];
    }
    protected function getShipping($key)
    {
        $components = CartData::parseItemKey($key);
        return $components['shipping'];
    }
    protected function getCampaign($key)
    {
        $components = CartData::parseItemKey($key);
        if (isset($components['campaign']))
            return $components['campaign'];
        return null;
    }
    protected function getSKU($key)
    {
        $components = CartData::parseItemKey($key);
        return $components['sku'];
    }    
    protected function getCartTotalCount()
    {
        $count = $this->getController()->cart->getCount();//get all items count
        user()->setCartCount($count);
        return $count;
    }      
    protected function getCartShopCount()
    {
        return count($this->getController()->cart->getShops());//get all shops count
    }      
}