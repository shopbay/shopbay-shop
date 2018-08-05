<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CartsModule
 *
 * @author kwlok
 */
class CartsModule extends SModule 
{
    /**
     * The module version.
     */    
    public $version = '0.1';
    /**
     * @property string url to redirect when buyer wants to exit cart
     */
    public $exitUrl = '/';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'carts',
                'pathAlias'=>'carts.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'carts.actions.*',
            'common.modules.carts.components.*',
            'common.modules.carts.behaviors.*',
            'common.modules.carts.models.*',
            'common.modules.payments.models.PaymentForm',//it seems PaymentForm has to load here as fast as it can, and not inside dependcies
            'common.services.CartManager',
            'common.modules.products.behaviors.ProductBehavior',
            'common.widgets.SStateDropdown',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'payments'=>[
                   'common.modules.payments.plugins.paypalExpressCheckout.components.PaypalExpressCheckout',
                   'common.modules.payments.models.PaymentMethod',
                ],                           
                'taxes'=>[  
                   'common.modules.taxes.models.Tax',
                ],                             
                'tasks'=>[  
                   'common.modules.tasks.models.*',
                ],                             
                'orders'=>[  
                   'common.modules.orders.models.OrderForm',
                   'common.modules.orders.models.OrderShippingAddressForm',
                ],                             
            ],
            'views'=>[
                'cart'=>'application.modules.carts.views.management._cart',
                'cartsummary'=>'application.modules.carts.views.management._cart_summary',
                'cartheader'=>'application.modules.carts.views.management._cart_header',
                'cartfooter'=>'application.modules.carts.views.management._cart_footer',
                'cartform'=>'application.modules.carts.views.management._cart_form',
                'cartshop'=>'application.modules.carts.views.management._cart_shop',
                'cartshipping'=>'application.modules.carts.views.management._cart_shipping',
                'cartshippingremarks'=>'application.modules.carts.views.management._cart_shipping_remarks',
                'cartshippingtotal'=>'application.modules.carts.views.management._cart_shipping_total',
                'cartitem'=>'application.modules.carts.views.management._cart_item',
                'cartiteminfo'=>'application.modules.carts.views.management._cart_item_info',
                'cartitemprice'=>'application.modules.carts.views.management._cart_item_price',
                'cartactions'=>'application.modules.carts.views.management._cart_actions',
                'cartpromocode'=>'application.modules.carts.views.management._cart_promocode',
                'carttotal'=>'application.modules.carts.views.management._cart_total',
                'confirmaddress'=>'application.modules.carts.views.management._confirm_address',
                'confirmpayment'=>'application.modules.carts.views.management._confirm_payment',
                //common views
                'empty'=>'common.modules.carts.views.base._empty',
                'buttons'=>'common.modules.carts.views.base._buttons',
                'keyvalue'=>'common.modules.carts.views.base._key_value',                
                //payments views
                'paymentform'=>'payments.paymentform',
                //item views
                'itemrecommend'=>'items.itemrecommend',
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',                          
            ],
            'images'=>[
                'info'=>['common.assets.images'=>'info.png'],
                'empty'=>['carts.assets.images'=>'empty.jpg'],
            ],                        
        ]);                 

        $this->defaultController = 'management';

    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        // Set the required components.
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.CartManager',
                'model'=>'Cart',
                'saveTransition'=>false,
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}