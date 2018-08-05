<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.modules.carts.components.CartData");
Yii::import('common.modules.shops.controllers.PreviewControllerTrait');
/**
 * Description of ManagementController
 *
 * @author kwlok
 */
class ManagementController extends AuthenticatedController 
{
    use PreviewControllerTrait;
    
    protected $modelType = 'Cart';
    protected $formType  = 'CartItemForm';
    protected $guestCheckoutActions  = [
        'fillShippingAddress',
        'selectPaymentMethod',
        'confirm',
        'paypalExpressReview',
    ];
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        $this->module->registerScripts();
        $this->pageTitle = Sii::t('sii','Shopping Cart');
        $this->parseQueryParams();//preview controller trait
        //set shop assets path alias
        $this->setShopAssetsPathAlias();
    }    
    /**
     * Behaviors for this controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'rightsfilterbehavior' => [
                'class'=>'common.components.behaviors.RightsFilterBehavior',
                'whitelistActions'=>$this->guestCheckoutActions,
                'whitelistModels'=>['Shop'],
                'whitelistMethod'=>'isGuestCheckoutAllowed',
                'whitelistTasks'=>[
                    'Tasks.Cart.Checkout',
                    'Tasks.Cart.FillShippingAddress',
                    'Tasks.Cart.SelectPaymentMethod',   
                    'Tasks.Order.Purchase',
                    'Tasks.Item.Purchase',
                    'Tasks.Cart.Confirm',
                ],
            ],
            'cartdatabehavior' => [
                'class'=>'CartDataBehavior',
            ],            
            'shopthemebehavior' => [
                'class'=>'common.modules.shops.behaviors.ShopThemeBehavior',
            ],            
            'shopassetsbehavior' => [
                'class'=>'common.modules.shops.behaviors.ShopAssetsBehavior',
            ],            
        ]);
    }     
    /**
     * @return array action filters
     */
    public function filters()
    {
        $this->rightsFilterActionsExclude = ['index','add','checkout','paypalExpressCheckout'];
        foreach (array_keys($this->actions()) as $value)
            $this->rightsFilterActionsExclude[] = $value;
        //perform guest checkout filtering
        $this->checkWhitelist(function(){
            if (Helper::strpos_arr(request()->getRequestUri(), $this->guestCheckoutActions, true)!=false)
                return $this->_parseShopModel();
            else
                return null;
        });
        return parent::filters();
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'buttonget'=>['class'=>'ButtonGetAction'],
            'itemcheckout'=>['class'=>'ItemCheckoutAction'],
            'itempriceget'=>['class'=>'ItemPriceGetAction'],
            'iteminventoryget'=>['class'=>'ItemInventoryGetAction'],
            'itemquantityvalidate'=>['class'=>'ItemQuantityValidateAction'],
            'itemvalidate'=>['class'=>'ItemValidateAction'],
            'itemremove'=>['class'=>'ItemRemoveAction'],
            'promocodeset'=>['class'=>'PromocodeSetAction'],
            'totalget'=>['class'=>'TotalGetAction'],
            'addressget'=>['class'=>'AddressGetAction'],
            'stateget'=>['class'=>'common.components.actions.AddressStateGetAction'],            
        ];
    }  
    /**
     * Cart default page
     */
    public function actionIndex() 
    {
        //The token of the cancelled payment typically used to cancel the payment within own application, if required
        if (isset($_GET['token'])){
            logTrace('Cancel paypal payment token='.$_GET['token']);
            if (YII_DEBUG)
                user()->setFlash('cart',[
                    'message'=>Sii::t('sii','Oops, it seems that you have cancelled checkout with PayPal.'),
                    'type'=>'notice',
                    'title'=>Sii::t('sii','Paypal Express Checkout'),
                ]);
            $this->cart->setPaypalExpressResponse(null);//set to null
        }

        $this->cart->clearSessionVariables();
        
        if (YII_DEBUG) { 
            foreach ($this->cart->getItems() as $item) { 
                logTrace('shopping cart items before checkout',$item->getAttributes()); 
            } 
        }        
        
        $this->render('index');
    }        
    /**
     * Add cart item.
     * @todo add preview support
     * @todo Need to catch extra query params to check if this is preview
     */
    public function actionAdd()
    {
        if (Yii::app()->request->getIsPostRequest()) {

            $form = new $this->formType('addCart');

            if(isset($_POST[$this->formType])) {
                
                $form->assignAttributes($_POST[$this->formType]);
                
                try  {
                    
                    if ($form->validate()) {

                        //important step to setup cart key reference, must do it after set options
                        $form->constructFormData();

                        $form->setCheckout(true);

                        $previousQuantity = $this->cart->existsItem($form->getKey());

                        if ($this->module->serviceManager->add($this->cart,$form)){                            
                            
                            if (is_numeric($previousQuantity)){
                                $message = Sii::t('sii','"{item}" added <span style="color:red">{n}</span> more successfully to existing ',[$form->quantity,'{item}'=>$form->getName()]);
                                $message .= Sii::t('sii','{n} item shopping cart.|{n} items shopping cart.',[$previousQuantity]);
                            }
                            else {
                                $title = Sii::t('sii','"{item}" added to shopping cart successfully',['{item}'=>$form->getName()]);
                            }
                            user()->setFlash('cart',[
                                'message'=>isset($message)?$message:null,
                                'type'=>'success',
                                'title'=>isset($title)?$title:null,
                            ]);
                            //shop cart level count
                            user()->setShopCartCount($form->shop_id,$this->cart->getCount($form->shop_id));
                            //cart level total count
                            user()->setCartCount($this->cart->getCount());
                            //load shop theme to pickup correct cart view
                            $this->loadTheme($form->shopModel->theme,$form->shopModel->themeStyle);        
                            header('Content-type: application/json');
                            echo CJSON::encode([
                                'status'=>'success',
                                'url'=>$this->getCartUrl($form->shop_id),
                                'cart_quickview'=>$this->renderPartial($this->getThemeView('_cart_quickview'),[
                                    'model'=>$form->shopModel,
                                    'queryParams'=>$this->queryParams,//forward all incoming query params
                                ],true),
                                'cart_count'=>user()->getCartCount($form->shop_id),
                                'cart_total_count'=>user()->getCartCount(),
                            ]);
                            Yii::app()->end();
                        }
                     } 
                    else 
                        throw new CException(Helper::htmlErrors($form->getErrors()));

                } catch (CException $e) {
                    logError(__METHOD__.' '.$e->getMessage(),$form->getErrors());
                    user()->setFlash('cart',[
                        'message'=> $e->getMessage(),
                        'type'=>'error',
                        'title'=>null,
                    ]);
                    header('Content-type: application/json');
                    echo CJSON::encode([
                        'status'=>'failure',
                        'message'=>$e->getMessage(),
                        'flash'=>$this->sflashWidget('cart',true),
                    ]);
                    Yii::app()->end();
                }
            }              
        }
        else 
            throwError400(Sii::t('sii','Bad request'));
    }
    /**
     * Checkout cart items.
     */
    public function actionCheckout()
    {
        if (Yii::app()->request->getIsPostRequest()) {

            if(isset($_POST)) {
                
                $checkoutShop = $this->_parseShop($_POST);
                if ($checkoutShop==null)//try to check if session has previous checkout shop
                    $checkoutShop = $this->cart->getCheckoutShop();
            
                $this->_renderFirstStepView($checkoutShop);

            }
            else {
                user()->setFlash('cart',[
                    'message'=>Sii::t('sii','Please select shop'),
                    'type'=>'error',
                    'title'=>null,
                ]);
                header('Content-type: application/json');
                echo CJSON::encode([
                    'flash'=>$this->sflashWidget('cart',true),
                ]);
                Yii::app()->end();
            }
        }
        else {
            $loginRequired = $this->_checkLoginRequired(request()->getHostInfo().'/checkout?'.http_build_query($this->queryParams));//forward all query params
            if (isset($loginRequired['checkoutShop']))
                $this->_renderFirstStepView($loginRequired['checkoutShop'],'html');
            else
                throwError400(Sii::t('sii','Bad request'));
        }
    }
    /**
     * Fill in shipping address.
     */
    public function actionFillShippingAddress()
    {

        if (Yii::app()->request->getIsPostRequest()){

            $addressForm = new CartAddressForm(user()->isGuest?CartAddressForm::GUEST_CHECKOUT:null);
            if (isset($_POST['CartAddressForm']))
                $addressForm->attributes = $_POST['CartAddressForm'];
            else 
                $addressForm = $this->cart->getShippingAddress();

            try {

                if ($this->module->serviceManager->fillShippingAddress(user()->getId(),$this->cart,$addressForm)){
                    $checkoutShop = $this->cart->getCheckoutShop();
                    //prepare form
                    $form = new CartPaymentMethodForm();
                    $form->shop_id = $checkoutShop;
                    $form->amount = $this->cart->getCheckoutGrandTotal($checkoutShop);
                    $form->currency = $this->cart->getShopCurrency($checkoutShop);
                    $this->_renderNextStepView($this->getAction()->getId(),['form'=>$form]);
                }
                else
                    throw new CException(Sii::t('sii','Error in submitting shipping address'));
                

             } catch (Exception $e) {
                user()->setFlash('cart',[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Shopping Cart Error'),
                ]);
                $this->_renderCurrentStepView($this->getAction()->getId(),['form'=>$addressForm]);
             }
        }
        else 
            throwError400(Sii::t('sii','Bad request'));

    }
    /**
     * Payment Methods and information.
     */
    public function actionSelectPaymentMethod()
    {

        if (Yii::app()->request->getIsPostRequest()){

            try {
                $checkoutShop = $this->cart->getCheckoutShop();
                //prepare payment form
                $form = new CartPaymentMethodForm();
                $form->shop_id = $checkoutShop;
                $form->amount = $this->cart->getCheckoutGrandTotal($checkoutShop);
                $form->currency = $this->cart->getShopCurrency($checkoutShop);
                
                if (isset($_POST['CartPaymentMethodForm']['id']) && $_POST['CartPaymentMethodForm']['method']){
                    
                    //continue set payment method
                    $form->id = $_POST['CartPaymentMethodForm']['id'];
                    $form->method = $_POST['CartPaymentMethodForm']['method'];
                    $form->method_desc = $form->getMethodName($form->method);
                    if ($form->requirePaymentData()){
                        $form->fetchPaymentData();
                    }
        
                    if ($this->module->serviceManager->selectPaymentMethod(user()->getId(),$this->cart,$form)){
                        $this->_renderNextStepView($this->getAction()->getId(),[],['label'=>Sii::t('sii','Confirm Order')]);
                    }
                    else
                        throw new CException(Sii::t('sii','Error in submitting payment method'));
                }
                else 
                    throw new CException(Sii::t('sii','Please select payment method'));


            } catch (Exception $e) {
                logError($e->getMessage());
                user()->setFlash('cart',[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>null,
                ]);
                $this->_renderCurrentStepView($this->getAction()->getId(),['form'=>$form]);
            } 
        }
        else 
            throwError400(Sii::t('sii','Bad request'));

    }
    /**
     * Confirm cart items.
     */
    public function actionConfirm()
    {
        if (Yii::app()->request->getIsPostRequest()){

            try {
                
                $paymentMethod = $this->cart->getPaymentMethod()->getPaymentMethodModel();
                if ($paymentMethod===null)
                    throw new CException(Sii::t('sii','Payment method not found'));
                
                $orderNo = $this->module->serviceManager->confirm(user()->getId(),$this->cart);
                //note that checkout items are already removed inside service manager
                user()->setCartCount($this->cart->getCount());
                user()->setShopCartCount($this->cart->getCheckoutShop(),$this->cart->getCount($this->cart->getCheckoutShop()));
                
                $domain = user()->onShopScope()?user()->shopModel->domain:null;
                
                $orderUrl = Order::getAccessUrl($orderNo,user()->isGuest,$domain);

                logTrace(__METHOD__." domain $domain, orderUrl $orderUrl");
                
                $this->_renderLastStepView([
                        'orderNo'=>$orderNo,
                        'paymentMethod'=>$paymentMethod,
                        'orderUrl'=>$orderUrl,
                        'showFlash'=>user()->isAuthenticated,
                    ],$orderUrl);
                
                //empty cart session
                $this->cart->clearSessionVariables();

            } catch (Exception $e) {//use Exception to catch all errors , including Braintree exceptions 
                logError(__METHOD__.' '.$e->getTraceAsString());
                $message = $e->getMessage().'.. '.Sii::t('sii','Please try again.');
                user()->setFlash('cart',[
                    'message'=>$message,
                    'type'=>'error',
                    'title'=>null,
                ]);
                $this->_renderCurrentStepView($this->getAction()->getId());
            } 
        }
        else 
            throwError400(Sii::t('sii','Bad request'));

    }
    /**
     * Perform Paypal express checkout
     * @param type $shop
     * @param type $override
     * @throws CException
     */
    public function actionPaypalExpressCheckout($shop,$override='0')
    {
        $loginRequired = $this->_checkLoginRequired(request()->getHostInfo().'/cart/management/paypalexpresscheckout?shop='.$shop.'&override='.$override);
        /**
         * Sub routine to handled url redirect by json response
         */
        $jsonResponse = function($redirectUrl,$error=null) {
            $response = [
                'redirect'=>true,
                'url'=>$redirectUrl,
            ];
            if (isset($error))
                $response['error'] = $error;
            header('Content-type: application/json');
            echo CJSON::encode($response);
        };
        /**
         * Sub routine to handle checkout result 
         */
        $returnCheckoutResult = function($redirectUrl,$error=null) use($override, $loginRequired, $jsonResponse){
            //condition to redirect; different scenarios
            if ($override==1||
                isset($_GET['loginrequired'])||
                (isset($loginRequired['skipLogin'])&&$loginRequired['skipLogin'])||
                (isset($loginRequired['guestCheckout'])&&$loginRequired['guestCheckout'])){//pass login required check
                $this->redirect($redirectUrl);
            }
            else
               $jsonResponse($redirectUrl,$error);
        };
        
        if ($this->cart->getCheckoutCount()==0){
            user()->setFlash('cart',[
                'message'=>Sii::t('sii','Please checkout items'),
                'type'=>'error',
                'title'=>Sii::t('sii','Shopping Cart Error'),
            ]);
            $jsonResponse(url('cart'));
            Yii::app()->end();
        }

        try {
            $redirectUrl = $this->module->serviceManager->paypalExpressCheckout(user()->getId(),$this->cart,$shop,$override);
            $returnCheckoutResult($redirectUrl);
            
        } catch (Exception $e)  {
            logError(__METHOD__.' error='.$e->getMessage().' '.$e->getTraceAsString());
            user()->setFlash('cart',[
                'message'=>$e->getMessage(),
                'type'=>'error',
                'title'=>Sii::t('sii','System is unable to proceed PayPal Express Checkout.'),
            ]);
            $returnCheckoutResult(url('cart'),$e->getMessage());
        }
    }
    /**
     * Paypal express checkout return url to review cart order
     * Assumption: Checkout items, shipping address, payment methods are already available
     */        
    public function actionPaypalExpressReview()
    {
        try {
            if ($this->module->serviceManager->paypalExpressReview($this->cart,trim($_GET['token']))){
                //show review page 
                user()->setFlash('cart',[
                    'message'=>Sii::t('sii','Next, please confirm your order.'),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Paypal Express Checkout'),
                ]);
                //render confirm page
                $this->_renderNextStepView('selectPaymentMethod',[],['label'=>Sii::t('sii','Confirm Order')],'html');
                Yii::app()->end();
            }
            else
                throw new CException(Sii::t('sii','Paypal Express Review Error'));

        } catch (CException $e)  {
            logError(__METHOD__.' review error='.$e->getMessage());
            user()->setFlash('cart',[
                'message'=>$e->getMessage(),
                'type'=>'error',
                'title'=>Sii::t('sii','Shopping Cart Error'),
            ]);
            $this->redirect('/cart'); 
            Yii::app()->end();
        }
    }        
    
    private function _renderCurrentStepView($action,$itemsParams=[],$btnParams=[],$contentType='json')
    {
        $viewData = $this->_getCurrentStepViewData($action, $itemsParams, $btnParams);
        $this->_renderView($viewData,$contentType);
    }   
    
    private function _renderNextStepView($action,$itemsParams=[],$btnParams=[],$contentType='json')
    {
        $viewData = $this->_getNextStepViewData($action, $itemsParams, $btnParams);
        $this->_renderView($viewData,$contentType);
    }
    
    private function _renderFirstStepView($checkoutShop,$contentType='json')
    {
        try {

            if ($this->module->serviceManager->checkout(user()->getId(),$this->cart,$checkoutShop)){
                //prepare form to return
                if ($this->cart->hasShippingMethodPickupOnly()){//check if shipping method is "PickupOnly"
                    $form = new CartPaymentMethodForm();
                    $form->amount = $this->cart->getCheckoutGrandTotal($checkoutShop);
                    $form->currency = $this->cart->getShopCurrency($checkoutShop);
                    $viewData = $this->_getNextStepViewData('FillShippingAddress',['form'=>$form]);
                }
                else {
                    $form = new CartAddressForm(user()->isGuest?CartAddressForm::GUEST_CHECKOUT:null);
                    if ($this->cart->hasShippingAddress())
                        $form = $this->cart->getShippingAddress();
                    $viewData = $this->_getNextStepViewData(Cart::beginAction(),['form'=>$form]);
                }
                $this->_renderView($viewData,$contentType);
                Yii::app()->end(); 
            }
            else
                throw new CException(Sii::t('sii','Error in checking out cart'));

        } catch (CException $e) {
            logError(__METHOD__.' error',$e->getTraceAsString());
            user()->setFlash('cart',[
                'message'=>$e->getMessage(),
                'type'=>'error',
                'title'=>null,
            ]);
            $this->_renderErrorView($checkoutShop,$contentType);
            Yii::app()->end();
        }
    }
    
    private function _renderLastStepView($itemsParams=[],$redirectRoute,$contentType='json')
    {
        if (isset($redirectRoute)){
            if ($itemsParams['showFlash'])
                user()->setFlash(get_class(Order::model()),[
                    'message'=>$this->renderPartial('_complete',$itemsParams,true),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Thanks for your purchase!'),
                ]);
            header('Content-type: application/json');
            echo CJSON::encode(['redirect'=>$redirectRoute]);
            Yii::app()->end();
        }
        else {//below is not in use
            user()->setFlash('cart',[
                    'message'=>Sii::t('sii','Thanks for buying with us!'),
                    'type'=>'success',
                    'title'=>Sii::t('sii','Order Confirmation'),
            ]);
            if ($this->cart->getNonCheckoutCount()>0)
                user()->setFlash('cart2',[
                    'message'=>Sii::t('sii','You still have {n} item left in shopping cart.|You still have {n} items left in shopping cart.',[$this->cart->getNonCheckoutCount()]),
                    'type'=>'notice',
                    'title'=>null,
                ]);
            $viewData = $this->_getViewData(CartDataBehavior::COMPLETED,null,$itemsParams);
            $this->_renderView($viewData,$contentType);
        }
    }   
    
    private function _renderView($viewData,$contentType='json')
    {
        if ($contentType=='html'){
            $this->render('template',$viewData);
        }
        else{
            header('Content-type: application/json');
            $view = $this->renderPartial('template',$viewData,true);
            echo CJSON::encode(['html'=>$view,'noncheckout_count'=>$this->cart->getNonCheckoutCount($viewData['shop'])]);
        }
        Yii::app()->end();
    }
    
    private function _renderErrorView($checkoutShop,$contentType='json')
    {
        if ($contentType=='html'){
            $this->redirect($this->getCartUrl($checkoutShop));
        }
        else {
            header('Content-type: application/json');
            echo CJSON::encode([
                'redirect'=>true,
                'url'=>$this->getCartUrl($checkoutShop),
            ]);
        }
    }
    
    private function _getCurrentStepViewData($action,$itemsParams=[],$btnParams=[])
    {
        $currentProcessId  = Process::getId(WorkflowManager::getProcessBeforeAction(Cart::model()->tableName(), $action));
        return $this->_getViewData($action, $currentProcessId, $itemsParams, $btnParams);
    }   
    
    private function _getNextStepViewData($action,$itemsParams=[],$btnParams=[])
    {
        $nextAction = WorkflowManager::getNextAction(Cart::model()->tableName(), $action);
        $nextProcessId  = Process::getId(WorkflowManager::getProcessAfterAction(Cart::model()->tableName(), $action));
        return $this->_getViewData($nextAction, $nextProcessId, $itemsParams, $btnParams);
    }
    
    private function _getViewData($action,$step,$itemsParams=[],$btnParams=[])
    {
        $model = $this->cart->getShop($this->cart->getCheckoutShop());
        $itemsParams = array_merge(['shop'=>$this->cart->getCheckoutShop()],$itemsParams);
        $btnParams = array_merge($btnParams, ['shopModel'=>$model]);
        return [
            'shop'=>$this->cart->getCheckoutShop(),
            'flash'=>['cart','cart2'],            
            'step'=>$this->renderPartial('_step',['current'=>$step],true),
            'items'=>$this->renderPartial('_'.strtolower($action),$itemsParams,true),
            'summary'=>$this->renderPartial('_cart_summary',['shop'=>$this->cart->getCheckoutShop(),'buttons'=>$this->renderPartial($this->module->getView('carts.buttons'),['buttons'=>$this->getCheckoutButtons($action,$btnParams)],true),'action'=>$action],true),
            'addonButtons'=>$this->renderPartial($this->module->getView('carts.buttons'),['buttons'=>$this->getAddOnButtons($action,$btnParams)],true),
        ];
    }
    
    protected function isLastStep($current)
    {
        return $current==Cart::endAction();
    }
    
    protected function getSteps($current)
    {
        if ($current==Cart::endAction())
            return [];//last step; no need any more steps
        else
            return [
                Process::CHECKOUT=>['arrow'=>false,'label'=>Sii::t('sii','Fill in Shipping Address'),'visible'=>!$this->cart->hasShippingMethodPickupOnly()],
                Process::CHECKOUT_ADDRESS=>['arrow'=>!$this->cart->hasShippingMethodPickupOnly(),'label'=>Sii::t('sii','Select Payment Method'),'visible'=>true],
                Process::CHECKOUT_PAYMENT=>['arrow'=>true,'label'=>Sii::t('sii','Submit Order'),'visible'=>true],
            ];
    }
    /**
     * Parse shop id based on input $_GET/$_POST parameters
     * Look for first param start with "cart-" and retreive its value (which is the item key)
     * Since each checkout is only for one shop, parse first item key could retreive back shop id
     * 
     * @param type $params
     * @return type
     */
    private function _parseShop($params)
    {
        $shop = null;
        if (is_string($params)) {
            $params = explode(',', urldecode($params));
            array_pop($params);//removing the last entry ','
            foreach ($params as $key => $value){
                $shop = CartData::parseShop($value);
                break;
            }
        }
        else {
            foreach ($params as $key => $value) {
                if (substr( $key, 0, 5 )=='cart-') {
                    $shop = CartData::parseShop($value);
                    break;
                }
            }
        }
        return $shop;
    }
    /**
     * Parse shop model
     * @return type
     */
    private function _parseShopModel()
    {
        if (isset($_GET['items']))
            $checkoutShop = $this->_parseShop($_GET['items']);
        elseif (isset($_GET['shop']))
            $checkoutShop = $_GET['shop'];
        else
            $checkoutShop = $this->cart->getCheckoutShop();
        
        return $this->cart->getShop($checkoutShop);  
    }
    /**
     * Check if shop is allowed with guest checkout
     * @return mixed If allow, shop model will return, else return false
     */
    private function _allowGuestCheckout($shopModel)
    {
        return ($shopModel!=null && $shopModel->isGuestCheckoutAllowed());
    }
    
    private function _checkLoginRequired($returnUrl)
    {
        $checkoutShopModel = $this->_parseShopModel();  
        $checkoutShop = $checkoutShopModel->id;
        $guestCheckout = false;//default to false
        //check if user has guest checkout token, if yes, skip login
        if ($this->cart->getCheckoutToken($checkoutShop)!=null && $this->cart->getCheckoutToken($checkoutShop)==request()->getQuery('checkout')){
            $guestCheckout = true;
            logTrace(__METHOD__.' guest checkout token matched!',request()->getQuery('checkout'));
        }

        //prepare login form if no guest checkout token
        if (user()->isGuest && !$guestCheckout){
            Yii::app()->user->returnUrl = $returnUrl;
            logTrace(__METHOD__.' set returnUrl to '.Yii::app()->user->returnUrl);
            $loginRequired = [
                'status'=>'loginrequired',
                'loginMethod'=>'shoplogin',
                'checkout'=>[
                    'guest'=>false,
                    'returnUrl'=>Yii::app()->user->returnUrl.'&loginrequired=1',
                ],//default no guest checkout
            ];
            if ($this->_allowGuestCheckout($checkoutShopModel)){
                $token = md5($checkoutShop);//use shop id as token
                $this->cart->setCheckoutToken($checkoutShop,$token);
                //shop has guest checkout enabled
                $loginRequired['checkout']['guest'] = true;
                $loginRequired['checkout']['returnUrl'] = Yii::app()->user->returnUrl.'&checkout='.$token;
                logTrace(__METHOD__.' shop has guest checkout enabled, shop=',$checkoutShop);
                if (isset($_GET['override'])&&$_GET['override']==1){//for paypal expresss checkout
                    return [
                        'checkoutShop'=>$checkoutShop,
                        'skipLogin'=>true,
                    ];
                }
            }
            header('Content-type: application/json');
            user()->loginRequiredAjaxResponse = CJSON::encode($loginRequired);
            user()->loginRequired();
            Yii::app()->end();  
        }
        
        return [
            'checkoutShop'=>$checkoutShop,
            'guestCheckout'=>$guestCheckout,
        ];
    }
}
