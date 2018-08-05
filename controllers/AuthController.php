<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.accounts.controllers.AuthenticateController');
Yii::import('common.modules.accounts.models.LoginForm');
Yii::import('shop.controllers.ShopControllerTrait');
/**
 * Description of AuthController
 *
 * @author kwlok
 */
class AuthController extends AuthenticateController
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
        $this->rightsFilterActionsExclude = array_merge(['guest'],$this->rightsFilterActionsExclude);
        $this->module = Yii::app()->getModule('accounts');
        //Comment off below to use non-api login
        //$this->module->apiLoginRoute = null;
        if (isset($this->queryParams['returnUrl']))
            unset($this->queryParams['returnUrl']);//remove returnUrl as this is the referral of reaching this controller
    }
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }        
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'guest'=>[
                'class'=>'common.modules.accounts.actions.LoginFormAction',
                'viewFile'=>'guest',
            ],  
        ]);
    }
    /**
     * Login action
     * @see AuthenticateController::actionLogin()
     */    
    /**
     * Logout action
     * @see AuthenticateController::actionLogout()
     */    
    /**
     * The guest checkout url
     * @return type
     */
    public function getGuestCheckoutUrl()
    {
        return request()->getQuery('returnUrl').'&'.http_build_query($this->queryParams);//forward all query params, incuding guest 'checkout' token
    }

}
