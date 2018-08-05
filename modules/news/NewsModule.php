<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of NewsModule
 *
 * @author kwlok
 */
class NewsModule extends SModule 
{
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
     * @property string the default controller.
     */
    public $entryController = 'undefined';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
              'class'=>'common.components.behaviors.AssetLoaderBehavior',
              'name'=>'news',
              'pathAlias'=>'news.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'news.behaviors.*',
            'news.models.*',
            'common.modules.news.models.News',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.modules.shops.controllers.ShopParentController',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
                'itemcolumn'=>'common.widgets.SItemColumn',
            ],
            'views'=>[
                'recent'=>'application.modules.news.views.customer._recent',
            ],
            'images'=>[
                'search'=>['common.assets.images'=>'search.png'],
            ],                        
        ]);             
        
        $this->defaultController = $this->entryController;

        $this->registerScripts();

    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.NewsManager',
                'model'=>'News',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
}