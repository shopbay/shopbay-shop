<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shop.controllers.ShopControllerTrait');
/**
 * Description of LayoutController
 * A proxy controller to for shop page layout rendering (with theme supported)
 *
 * @author kwlok
 */
class LayoutController extends SController
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
    }
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }    

    public function actionIndex() 
    {
        //do nothing
    }    
}
