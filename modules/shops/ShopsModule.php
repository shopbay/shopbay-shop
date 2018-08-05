<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopsModule
 *
 * @author kwlok
 */
class ShopsModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'undefined';
    /**
     * parentShopModelClass (model classname) means that products module needs to be attached to shop module 
     * as all products objects creation/update is assuming having shop_id in session 
     * 
     * parentShopModelClass (null or false) means that products module needs to define which shop products objects 
     * belongs to during creation/update 
     * 
     * @see SActiveSession::SHOP_ACTIVE
     * @property boolean whether parentShopModelClass is required.
     */
    public $parentShopModelClass = 'Shop';        
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'shops',
                'pathAlias'=>'shops.assets',
            ],
            'shopresourceBehavior' => [
                'class'=>'common.modules.shops.behaviors.ShopAssetsBehavior',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'shops.models.*',
            'shops.actions.*',
            'shops.components.*',
            'shops.controllers.StorefrontController',
            'common.modules.shops.models.*',
            'common.modules.shops.controllers.ShopParentController',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.widgets.spagemenu.SPageMenu',
            'common.widgets.spagetab.SPageTab',
            'common.widgets.spagelayout.SPageLayout',
            'common.widgets.sloader.SLoader',
            'common.widgets.SButtonColumn',
            'common.widgets.SStateDropdown',
            'common.widgets.soffcanvasmenu.SOffCanvasMenu',
            'common.widgets.simagemanager.SImageManager',
            'common.widgets.simagemanager.models.SingleImageForm',
            'common.services.CartManager',
            'common.extensions.facebook.sharing.FacebookShareButton',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'themes'=>[
                    'common.modules.themes.models.*',
                ],          
                'tasks'=>[
                    'common.modules.tasks.models.*',
                ],          
                'payments'=>[
                    'common.modules.payments.models.PaymentMethod',
                ],     
                'taxes'=>[
                    'common.modules.taxes.models.Tax',
                ],     
                'carts'=>[
                    'common.modules.carts.components.*',
                    'common.modules.carts.models.Cart',
                ],     
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
                'imagecolumn'=>'common.widgets.EImageColumn',
            ],
            'views'=>[
                //common views
                'shopmap'=>'common.modules.shops.views.share._map',
                //comments views
                'commentview'=>'comments.commentquickview',
                'commentform'=>'comments.commentform',
                //questions views
                'questionview'=>'questions.questionquickview',
                'questionform'=>'questions.questionquickform',
                //campaign views
            ],      
            'images'=> [
                'like.png'=>['common.assets.images'=>'like.png'],
                'dislike.png'=>['common.assets.images'=>'dislike.png'],
                'empty'=>['common.modules.carts.assets.images'=>'empty.jpg'],
                'datepicker'=>['common.assets.images'=>'datepicker.gif'],
            ],
            'sii'=>[
                //must follow this format [app-alias.module-name]
                'common.carts',
            ],
        ]);  

        //set shop assets path alias
        $this->setShopAssetsPathAlias();

        $this->defaultController = $this->entryController;

    }
    /**
     * Module display name
     * @param $mode singular or plural, if the language supports, e.g. english
     * @return string the model display name
     */
    public function displayName($mode=Helper::SINGULAR)
    {
        return Sii::t('sii','Shop|Shops',[$mode]);
    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.ShopManager',
                'model'=>'Shop',
                'runMode'=>$this->serviceMode,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
}