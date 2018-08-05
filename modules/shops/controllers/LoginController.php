<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.accounts.models.LoginForm');
Yii::import('common.modules.accounts.controllers.AuthenticateController');
/**
 * Description of LoginController
 * This controller handles shop buyers login form
 * Support both guest checkout and normal account login
 * 
 * @author kwlok
 */
class LoginController extends AuthenticateController 
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'rights - form', 
        ];
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'form'=>[
                'class'=>'common.modules.accounts.actions.LoginFormAction',
                'viewFile'=>'form',
            ],  
        ];
    }
    
    public function getGuestCheckoutUrl()
    {
        return request()->getQuery('returnUrl').'&checkout='.request()->getQuery('checkout');        
    }
}
