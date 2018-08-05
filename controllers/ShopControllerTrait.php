<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopPageControllerTrait');
/**
 * Description of ShopControllerTrait
 *
 * @author kwlok
 */
trait ShopControllerTrait 
{
    use ShopPageControllerTrait;
    /**
     * Set this property so that can re-use the code inside parent controller
     * @var CModule
     */
    public $module;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        user()->refreshShop();//reload session shop
        $this->parseQueryParams();
        //-----------------
        //set shop assets path alias
        $this->setShopAssetsPathAlias();
    }
}
