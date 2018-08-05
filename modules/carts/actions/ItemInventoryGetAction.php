<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemInventoryGetAction
 *
 * @author kwlok
 */
class ItemInventoryGetAction extends CAction 
{
    /**
     * Get inventory informaion based on product id and product option
     * 
     * @param integer $v The view mode; Default page or modal page
     * @param integer $pid The id of product model
     * @param integer $opts The product options 
     */
    public function run() 
    {
        if (isset($_GET['v']) && isset($_GET['pid']) && isset($_GET['opts'])) {
            $options = explode(',', urldecode($_GET['opts']));
            array_pop($options);//removing the last entry ','
            
            header('Content-type: application/json');
            $available = Yii::app()->serviceManager->getInventoryManager()->getAvailableByProductOptions(base64_decode($_GET['pid']), $options);
            echo CJSON::encode(array(
                'inventory'=>$available,
                'inventory_html'=>$_GET['v']=='modal'?Inventory::getDisplayText($available):$available,
                'buy_button_text'=>$available==0?Sii::t('sii','Sold Out'):Sii::t('sii','Add To Cart'),
            ));                        
            Yii::app()->end();      
         }
         throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));    
    }
}