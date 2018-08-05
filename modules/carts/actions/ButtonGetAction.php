<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ButtonGetAction
 *
 * @author kwlok
 */
class ButtonGetAction extends CAction 
{
    /**
     * Deletes a particular cart item.
     * @param integer $id the ID of the cart item to be deleted
     */
    public function run() 
    {
        if (isset($_GET['mid'])){
            $buttonName = $this->getController()->getButtonName('action-'.$this->getController()->cart->getCheckoutShop());
            $paymentMethod = PaymentMethod::model()->findByPk($_GET['mid']);
            header('Content-type: application/json');
            if ($paymentMethod!=null){
                $params = [
                    'buttonName'=>$buttonName,
                    'methodId'=>$_GET['mid'],
                    'paymentMethod'=>$paymentMethod->method,
                    'shopId'=>$paymentMethod->shop_id,
                    'formId'=>$this->getController()->getCartName($paymentMethod->shop_id),
                    'amount'=>$this->getController()->cart->getCheckoutGrandTotal($paymentMethod->shop_id),
                ];
                $paymentForm = PaymentMethod::getFormInstance($paymentMethod->method);
                $buttonAction = $paymentForm->onMethodSelected($params);
                if ($buttonAction['buttonClick']=='SelectPaymentMethod'){//default proceed action
                    $defaultActionUrl = $this->controller->appendQueryParams('/cart/management/SelectPaymentMethod');
                    $buttonAction['buttonClick'] = new CJavaScriptExpression('proceed("'.$defaultActionUrl.'");');
                }
                echo CJSON::encode($buttonAction);
            }
            else {
                echo CJSON::encode([
                    'buttonName'=>$buttonName,
                    'buttonMethod'=>PaymentMethod::UNDEFINED,
                    'buttonDisable'=>true,
                    'buttonClick'=> new CJavaScriptExpression('alert("'.Sii::t('sii','Payment method not supported').'");'),
                    'callback'=> false,
                ]);
            }
            Yii::app()->end();

        }
        throwError404();        
    }  
    
}