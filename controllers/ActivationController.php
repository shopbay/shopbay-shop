<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.customers.models.CustomerAccount');
Yii::import('shop.controllers.ShopControllerTrait');
/**
 * Description of ActivationController
 *
 * @author kwlok
 */
class ActivationController extends AuthenticatedController
{
    use ShopControllerTrait {
        init as traitInit;
    }    
    /**
     * Init controller
     */
    public function init()
    {
        $this->traitInit();
        $this->module = Yii::app()->getModule('customers');
    }
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }        
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'rights - presignup', 
        ];
    }
    /**
     * Activate a account; 
     * User need to verify both shop/email and password to complete activation
     * 
     * Activation url account/activate?token=base64_encode[activate_str]
     * @see CustomerAccount::getActivationUrl()
     */
    public function actionIndex()
    {
        logTrace(__METHOD__.' Activating account...');
        
        if(isset($_REQUEST['token'])) {
            
            logInfo(__METHOD__.' Activation token='.$_REQUEST['token']);
            
            try {
                
                $this->module->serviceManager->activate(user()->getShop(),$_REQUEST['token']);
                user()->setFlash('welcome',[
                    'message'=>Sii::t('sii','Welcome! Your account is activated successfully.'),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Account Activation'),
                ]);
                $this->redirect(url('/welcome'));//on purpose to change url in browser
                Yii::app()->end();
                
            } catch (CException $e) {
                logError(__METHOD__.' error='.$e->getMessage().' code='.$e->getCode().' >> '.$e->getTraceAsString(),[],false);
                $this->redirect(url('/login?__iu='.base64_encode($e->getCode())));//on purpose to show error
                Yii::app()->end();
            }
        }
        throwError403(Sii::t('sii','Unauthorized Access'));  
    }
    
    /**
     * @todo Action presignup to be done...
     */
}
