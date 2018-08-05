<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of PromotionGetAction
 * Note: this is used by storefront.js viewpromo() function.
 * 
 * @author kwlok
 */
class PromotionGetAction extends CAction 
{
    /**
     * Return item promo info.
     * @param integer $c the ID of the cart item campaign
     */
    public function run() 
    {
        if (isset($_GET['c'])) {
            $campaign = CampaignBga::model()->findByPk(base64_decode($_GET['c']));
            header('Content-type: application/json');
            echo CJSON::encode(['url'=>$campaign->getUrl(request()->isSecureConnection)]);                        
            Yii::app()->end();      
         }
         throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));    
    }
}