<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of PromocodeSetAction
 *
 * @author kwlok
 */
class PromocodeSetAction extends CartBaseAction 
{
    /**
     * Check promotional code and return any discount totals 
     */
    public function run() 
    {
        if(isset($_GET['s']) && isset($_GET['c'])) {
            //unset session promocode first
            //only one promocode at one time for each cart checkout
            $this->getController()->cart->setPromocode($_GET['s'],null);
            
            if (empty($_GET['c'])){
                $status = 'skip';
                $message = '';
            }
            else if ($this->getController()->cart->getShop($_GET['s'])->hasPromocode($_GET['c'])){
                //store promocode into session and let CartBase do its calculation
                $this->getController()->cart->setPromocode($_GET['s'],$_GET['c']);
                //get campaign info
                $campaign = $this->getController()->cart->getShop($_GET['s'])->getPromocodeCampaign($_GET['c']);
                $discountData = new CampaignPromocodeDiscountData();
                $discountData->createCampaignData($campaign);
                $status = 'success';
                $message = CHtml::tag('span',array('class'=>'found'),'<i class="fa fa-check"></i>');
            }
            else {
                $this->getController()->cart->setPromocode($_GET['s'],null);//unset session promocode
                $status = 'failure';
                $message = CHtml::tag('div',array('class'=>'error'),Sii::t('sii','Promotional code not found. Please check again.'));
            }
            
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'status'=>$status,
                'message'=>$message,
                'total'=>$this->getShopTotalByShop($_GET['s']),
                'tax'=>$this->getShopTaxesByShop($_GET['s']),//this method has to be called after shopTotal
            ));
            
            Yii::app()->end();      
        }
        throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));
    }  
}
