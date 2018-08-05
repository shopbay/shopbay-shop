<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shop.controllers.ShopControllerTrait');
Yii::import('common.widgets.simagemanager.controllers.ImageControllerTrait');
Yii::import('common.modules.media.models.SessionMedia');
Yii::import('common.widgets.SStateDropdown');
/**
 * Description of ProfileController
 *
 * @author kwlok
 */
class ProfileController extends AuthenticatedController 
{
    use ImageControllerTrait;
    use ShopControllerTrait {
        init as traitInit;
    }    
    
    public function init()
    {
        parent::init();
        $this->traitInit();
        $this->module = Yii::app()->getModule('customers');
        $this->modelType = 'Customer';
        //-----------------
        // @see ImageControllerTrait
        $this->imageStateVariable = SActiveSession::ACCOUNT_IMAGE; 
        $this->sessionActionsExclude = [//customize, keep one action to exclude
            $this->imageUploadAction, 
            'update',//all profile update are done within "update" action, hence session clearing to be exluded
        ];
        //-----------------
        // Exclude following actions from rights filter 
        // @see ImageControllerTrait
        $this->rightsFilterActionsExclude = $this->getRightsFilterImageActionsExclude([
            'captcha',
            'forgotpassword',
            'stateget',
        ]);
        //-----------------//        
    }        
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }            
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @see ImageControllerTrait::runBeforeAction()
     */
    protected function beforeAction($action)
    {
        return $this->runBeforeAction($action);
    } 
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge($this->imageActions(),[
            'index'=>[
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'finderMethod'=>'all',
                'accountAttribute'=>'customer_id'
            ],               
            'update'=>[
                'class'=>'common.components.actions.UpdateAction',
                'model'=>$this->modelType,
                'serviceOwnerAttribute'=>'customer_id',
                'loadModelMethod'=>'prepareModel',
                'loadModelAttribute'=>null,
                'setAttributesMethod'=>'setModelAttributes',
                'redirectUrl'=>url('account/profile'),
                'viewFile'=>'view',
            ], 
            $this->imageUploadAction =>[
                'class'=>'common.widgets.simagemanager.actions.ImageUploadAction',
                'multipleImages'=>false,
                'stateVariable'=> $this->imageStateVariable,
                'secureFileNames'=>true,
                'path'=>Yii::app()->getBasePath()."/www/uploads",
                'publicPath'=>'/uploads',
            ],
            // captcha action renders the CAPTCHA image displayed on the form page
            'captcha'=>[
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                'testLimit'=>1,
            ],
            'stateget'=>[
                'class'=>'common.components.actions.AddressStateGetAction',
            ],         
            'password'=>[
                'class'=>'common.modules.accounts.actions.ChangePasswordAction',
                'accountType'=>'CustomerAccount',
            ],               
            'email'=>[
                'class'=>'common.modules.accounts.actions.ChangeEmailAction',
                'accountType'=>'CustomerAccount',
                'emailCondition'=>['shop_id'=>user()->getShop()],
            ],               
            'forgotpassword'=>[
                'class'=>'common.modules.accounts.actions.ResetPasswordAction',
                'accountType'=>'CustomerAccount',
                'emailCondition'=>['shop_id'=>user()->getShop()],
                'layout'=>$this->layout,
                'appName'=>user()->shopModel->displayLanguageValue('name',user()->getLocale()),
            ],               
            'notifications'=>[
                'class'=>'common.modules.notifications.actions.SubscribeNotificationAction',
                'viewFile'=>'notification',
            ],               
        ]);
    }   
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return CModel
     */
    public function prepareModel()
    {
        $type = $this->modelType;
        $model = $type::model()->mine()->find();
        if($model===null)
            throw new CHttpException(404,Sii::t('sii','The requested page does not exist.'));
        return $model;
    }
    
    public function setModelAttributes($model)
    {
        if(isset($_POST[$this->modelType]) && isset($_POST['CustomerAddressForm'])) {
            $model->attributes = $_POST[$this->modelType];
            $addressForm = $this->getCustomerAddressForm();
            $addressForm->attributes = $_POST['CustomerAddressForm'];
            $addressData = new CustomerAddressData();
            $addressData->fillData($addressForm);
            $model->setAddressData($addressData);
            return $model;
        }
        throwError400(Sii::t('sii','Bad Request'));
    } 
    
    private $_addressForm;
    protected function getCustomerAddressForm($model=null) 
    {
        Yii::import('common.modules.customers.models.CustomerAddressForm');
        if (!isset($this->_addressForm))
            $this->_addressForm = new CustomerAddressForm();
        
        if (isset($model))
            $this->_addressForm->fillForm($model->getAddressData());
        
        $this->_addressForm->validate();//to get error message if any
        return $this->_addressForm;
    }    
    
}