<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shop.controllers.ShopControllerTrait');
/**
 * Description of ErrorController
 *
 * @author kwlok
 */
class ErrorController extends SErrorController 
{
    use ShopControllerTrait {
        init as traitInit;
    }    
    
    public $errorView = 'shop.views.error.index';
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        $this->traitInit();
        //cannot force logout, else session will be gone. We need session shop to render header and footer
        //$this->forceLogout = array(403);
    }     
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }        
    
}