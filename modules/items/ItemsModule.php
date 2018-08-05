<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ItemsModule
 *
 * @author kwlok
 */
class ItemsModule extends SModule 
{
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
                'name'=>'items',
                'pathAlias'=>'items.assets',
            ],
        ];
    }
        
    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'items.models.*',
            'items.controllers.*',
            'common.modules.items.models.*',
            'common.widgets.SButtonColumn',
            'common.widgets.spagelayout.SPageLayout',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);
         // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'payments'=>[
                    'common.modules.payments.models.PaymentMethod',
                ],
                'tasks'=>[
                    'common.modules.tasks.models.*',
                    'common.modules.tasks.actions.CommentAction',
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.components.TaskBaseController',
                ],
                'comments'=>[
                    'common.modules.comments.models.Comment',
                    'common.modules.comments.models.CommentForm',
                ],
            ],
            'classes'=>[
                'gridview'=>'common.widgets.SGridView',
                'itemcolumn'=>'common.widgets.SItemColumn',
                'listview'=>'common.widgets.SListView',
                'groupview'=>'common.widgets.SGroupView',
            ],
            'views'=> [
                'receiveform'=>'application.modules.items.views.customer._receive',
                'itemview' => 'application.modules.items.views.customer.view',
                'itemviewbody' => 'application.modules.items.views.customer._view_body',
                'itemrecommend'=>'application.modules.items.views.customer._recommend',
                'itemrecent'=>'application.modules.items.views.customer._recent',
                'customeritemlist'=>'application.modules.items.views.customer._item_listview',
                //orders view
                'ordersummary'=>'orders.customersummary',
                'orderpayment'=>'orders.customerpayment',
                //comments view
                'comment'=>'comments.commentview',
                //cart key value view
                'keyvalue'=>'carts.keyvalue',
            ],
            'images'=>[
                'search'=> ['common.assets.images'=>'search.png'],                            
                'datepicker'=> ['common.assets.images'=>'datepicker.gif'],
            ],
            'sii'=>[
                'common.orders',
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
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.ItemManager',
                'model'=>'Item',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
}
