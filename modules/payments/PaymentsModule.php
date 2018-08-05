<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.payments.models.PaymentPluginsTrait');
/**
 * Description of PaymentsModule
 *
 * @author kwlok
 */
class PaymentsModule extends SModule 
{
    use PaymentPluginsTrait;
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
                'name'=>'payments',
                'pathAlias'=>'payments.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'common.modules.payments.behaviors.*',
            'common.modules.payments.components.*',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);
        
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tasks'=>[
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ],
            ],
            'views'=>[
                'paypallogo'=>'common.modules.payments.views.logo.paypal',
                'paymentform'=>'application.modules.payments.views.cart.payment',
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ],
            'images'=>[
                'info'=>['common.assets.images'=>'info.png'],
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
                'class'=>'common.services.PaymentMethodManager',
                'model'=>'PaymentMethod',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }    
}