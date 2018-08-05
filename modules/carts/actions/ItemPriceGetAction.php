<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemPriceGet
 *
 * @author kwlok
 */
class ItemPriceGetAction extends CAction 
{
    /**
     * Return item price info.
     * @param integer $p the ID of the cart item product
     * @param integer $qty the ID of the cart item product quantity
     * @param integer $c the ID of the cart item campaign, if any
     */
    public function run() 
    {
        if (isset($_GET['p']) && isset($_GET['qty'])) {
            header('Content-type: application/json');
            $campaign = null;
            if (isset($_GET['c'])&&$_GET['c']!='undefined')
                $campaign = CampaignBga::model()->findbyPk(base64_decode($_GET['c']));
            
            $shopTheme = $this->findShopTheme();
            
            $xOffer = Yii::app()->serviceManager->getCampaignManager()->checkProductPrice(['model'=>base64_decode($_GET['p']),'xProduct'=>true],$_GET['qty'],$campaign);
            $xOfferHtml = $this->getController()->renderPartial($this->getController()->getThemeView('_product_price','storefront',$shopTheme),['data'=>$xOffer],true);
            if ($campaign!=null && $campaign->hasG()){
                $yOffer = Yii::app()->serviceManager->getCampaignManager()->checkProductPrice($campaign->y_product,$campaign->scaleQuantityYByX($_GET['qty']),$campaign);
                $yOfferHtml = $this->getController()->renderPartial($this->getController()->getThemeView('_product_price','storefront',$shopTheme),['data'=>$yOffer],true);
            }
            echo CJSON::encode([
                'grand_total'=>$campaign!=null?$campaign->formatCurrency($campaign->getOfferTotalPrice($_GET['qty'])):$xOffer['offer_price'],
                'x_offer_html'=>$xOfferHtml,
                'y_offer_html'=>$campaign!=null&&$campaign->hasG()?$yOfferHtml:'',
            ]);                        
            Yii::app()->end();      
         }
         throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));    
    }
    /**
     * Find shop theme to render corresponding theme view
     */
    protected function findShopTheme()
    {
        $product = Product::model()->findbyPk(base64_decode($_GET['p']));
        return $product->shop->getTheme();
    }
    
}