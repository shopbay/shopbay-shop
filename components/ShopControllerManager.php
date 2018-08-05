<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("wcm.controllers.WcmLayoutTrait");
/**
 * Description of ShopControllerManager
 * Manage controller stuff, like layouts, views
 * 
 * @author kwlok
 */
class ShopControllerManager extends SControllerManager
{
    use WcmLayoutTrait;
    /**
     * @var string the page title suffix filter class
     */
    public $pageTitleSuffixFilter = 'shop.components.filters.ShopPageTitleSuffixFilter';
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        if (user()!=null && user()->onShopScope()){
            user()->refreshShop();//reload session shop
            //Note: shop website layout is set at child class ShopControllerManager
        }
        
        $this->authenticatedLayout = 'shop.views.layouts.authenticated';

        //Track google analytics either at host or shop level
        if ($_SERVER['HTTP_HOST']==Yii::app()->urlManager->hostDomain)
            $this->insertTrackingCode();//for website tracking on host domain (www)
        else {
            $shopTrackingId = null;//@todo set shop goolge analytics tracking id here
            $this->insertTrackingCode($shopTrackingId);
        }          
        
        /**
         * @todo Include shop specific Drift or other live chat widget as plugin
         * @todo There should also include shop specific own google analytics plugin (configured by Merchant)
         * @todo Include shop specific facebook oauth plugin
         * @todo Include shop specific Braintree payment plugin
         */
    }
    /**
     * Google analytics is loaded only when it is enabled
     * 
     * @todo Does this also track authenticated layout? Previous implementation does not track 'login user' analytics
     */
    public function insertTrackingCode($trackingId=null)
    {
        $this->htmlBodyBegin = Yii::app()->googleAnalytics->renderGTag($trackingId);
    }
}
