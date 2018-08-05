<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.widgets.SStateDropdown');
Yii::import('common.modules.accounts.controllers.SignupController');
Yii::import('common.modules.accounts.models.SignupCustomerForm');
Yii::import('shop.models.RegistrationForm');
Yii::import('shop.controllers.ShopControllerTrait');
/**
 * Description of RegisterController
 *
 * @author kwlok
 */
class RegisterController extends SignupController
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
        $this->module = Yii::app()->getModule('customers');
    }
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow',  
                'actions'=>['captcha','index','resend','complete','customer'],
                'users'=>['*'],
            ],
            ['allow', // allow authenticated user to perform actions
                'actions'=>[],//nothing
                'users'=>['@'],
            ],
            ['deny',  // deny all users
                'users'=>['*'],
            ],
        ];
    }    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            //define local customer action again
            'customer' => [
                'class'=>'common.modules.accounts.actions.SignupCustomerAction',
                'formModel'=>'RegisterCustomerForm',
                'formModelParams'=>user()->getShop(),
                'addressFormModel'=>'CustomerAddressForm',
                'accountType'=>'CustomerAccount',
                'emailCondition'=>['shop_id'=>user()->getShop()],
                'service'=>'register',
                'successViewData'=>['modal'=>true],
            ],
            'complete' => [
                'class'=>'common.modules.accounts.actions.SignupCompleteAction',
                'accountType'=>'CustomerAccount',
                'emailCondition'=>['shop_id'=>user()->getShop()],
            ],
        ]);
    }    
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() 
    {        
        $this->setPageTitle(Sii::t('sii','Register Account'));
        
        $form = new RegistrationForm(user()->getShop(),'create');

        if(isset($_POST['ajax']) && $_POST['ajax']==='register_form') {
            //Bugfix: should not validate 'verify_code', else its value will change by internal calls
            echo CActiveForm::validate($form,['name','email','password']);
            Yii::app()->end();
        }

        if (isset($_POST['RegistrationForm'])&&isset($_POST['CustomerAddressForm'])){
            try {
                $form->attributes = $_POST['RegistrationForm'];
                $form->address->attributes = $_POST['CustomerAddressForm'];
                $this->module->serviceManager->register($form);
                header('Content-type: application/json');
                $this->redirect(url('register/complete',['email'=>$form->email]));
                unset ($_POST);
                Yii::app()->end();

            } catch (CException $e) {
                $form->unsetAttributes(['password','confirmPassword']);
                user()->setFlash(get_class($form),[
                        'message'=>$e->getMessage(),
                        'type'=>'error',
                        'title'=>null]);
            }  
        }

        $this->render('index',['form'=>$form]);
    }
    /**
     * Resend activation string
     * @param type $email
     */
    public function actionResend($email=null)
    {
        if (isset($email)){
            
            $this->setPageTitle(Sii::t('sii','Activation Resend'));
            
            try {
                $this->module->serviceManager->resendActivationEmail(user()->getShop(),$email);
                $this->render('resend',['email'=>$email]);
                Yii::app()->end();
                
            } catch (CException $e) {
                //Hit error! redirect back to complete page
                user()->setFlash('complete',[
                        'message'=>$e->getMessage(),
                        'type'=>'error',
                        'title'=>null]);
                $this->render('complete',['email'=>$email]);
                Yii::app()->end();
            }            
        }
        throwError403(Sii::t('sii','Unauthorized Access'));  
    }      
    
}
