<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/*
 * OrdersModule requires 'tasks' module to work - also get some of this assets/js
 *
 * @author kwlok
 */
class OrdersModule extends SModule 
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
                'name'=>'orders',
                'pathAlias'=>'orders.assets',
            ],
        ];
    }
        
    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'orders.models.*',
            'orders.controllers.*',
            'common.modules.orders.models.*',
            'common.widgets.SButtonColumn',
            'common.extensions.xupload.XUpload',
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.widgets.spagefilter.controllers.SPageFilterControllerTrait',
        ]);
         // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tasks'=>[
                    'common.modules.tasks.models.Process',
                    'common.modules.tasks.models.Transition',
                    'common.modules.tasks.components.TaskBaseController',
                ],
                'payments'=>[
                    'common.modules.payments.models.PaymentForm',
                    'common.modules.payments.models.PaymentMethod',
                ],
            ],
            'classes'=>[
                'gridview'=>'common.widgets.SGridView',
                'groupview'=>'common.widgets.SGroupView',
                'itemcolumn'=>'common.widgets.SItemColumn',
                'listview'=>'common.widgets.SListView',
            ],
            'views'=>[
                'customersummary'=>'application.modules.orders.views.customer._summary',
                'customerpayment'=>'application.modules.orders.views.customer._payment',
                'customerorderlist'=>'application.modules.orders.views.customer._order_listview',
                //common views
                'orderproduct'=>'common.modules.orders.views.common._product',
                'merchantaddress'=>'common.modules.orders.views.common._address',
                'merchantshipping'=>'common.modules.orders.views.common._shipping',
                'merchantpayment'=>'common.modules.orders.views.common._payment',
                'merchantattachment'=>'common.modules.orders.views.common._attachments',
                'merchanthistory'=>'common.modules.orders.views.common._history',
                //cart views
                'keyvalue'=>'carts.keyvalue',
            ],
            'images'=>[
                'backbutton'=>['common.modules.tasks.assets.images'=>'back_left32.png'],
                'processgear'=>['common.modules.tasks.assets.images'=>'process_gear.png'],
                'verifypayment'=>['common.modules.tasks.assets.images'=>'verify_payment.png'],
                'refund'=>['common.modules.tasks.assets.images'=>'refund.png'],
                'payorder'=>['common.modules.tasks.assets.images'=>'pay.png'],
                'datepicker'=>['common.assets.images'=>'datepicker.gif'],
            ],
        ]); 

        $this->defaultController = $this->entryController;

        $this->registerScripts();
        $this->registerGridViewCssFile();
        
        $this->publishSUploadAssets();//used by attachment upload (indirectly)
    }

    //tweak: extract from SUpload.publishAssets()
    private function publishSUploadAssets()
    {
        Yii::import('common.extensions.supload.SUpload');
        $supload = new SUpload();
        $supload->publishAssets();
    }        
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.OrderManager',
                'model'=>['Order','ShippingOrder'],
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}
