<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.components.validators.PasswordValidator');
Yii::import('common.modules.customers.models.CustomerFormTrait');
/**
 * RegistrationForm 
 *
 * @author kwlok
 */
class RegistrationForm extends SFormModel
{
    use CustomerFormTrait;
    
    public $shop_id;
    public $email;
    public $password;
    public $confirmPassword;
    public $verify_code;
    /**
     * Constructor.
     * @see getScenario
     */
    public function __construct($shop_id,$scenario='')
    {
        $this->shop_id = $shop_id;
        $this->initAddress();
        parent::__construct($scenario);
    }
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array_merge($this->formRules(),[
            // name, email, password are required
            ['email, shop_id, password, verify_code', 'required'],
            ['password', 'length', 'max'=>64],
            ['password', 'PasswordValidator', 'strength'=>PasswordValidator::WEAK],
            ['email', 'email'],
            ['email', 'length', 'max'=>100],
            
            //for create scenario - user has to fill up sign up form
            ['email', 'ruleEmail','on'=>'create'],
            ['confirmPassword', 'required','on'=>'create'],
            ['confirmPassword', 'compare','compareAttribute'=>'password','operator'=>'=','message'=>Sii::t('sii','Confirm password must be same as Password'),'on'=>'create'],
            // verifyCode needs to be entered correctly
            ['verify_code', 'captcha', 'captchaAction'=>'register/captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'create'],
        ]);
    }
    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array_merge($this->formAttributeLabels(),[
            'shop_id' => Sii::t('sii','Shop'),
            'password' => Sii::t('sii','Password'),
            'confirmPassword' => Sii::t('sii','Confirm Password'),
            'email' => Sii::t('sii','Email Address'),
            'verify_code'=>Sii::t('sii','Verification Code'),
            'acceptTOS'=>Sii::t('sii','By signing up, you agree to the {agreement}',array('{agreement}'=>CHtml::link(Sii::t('sii','Terms of Service'),url('terms')))),
        ]);
    }
    /**
     * Email must be unique per shop
     * @param type $attribute
     * @param type $params
     * @throws CException
     */
    public function ruleEmail($attribute,$params)
    {
        $model = CustomerAccount::model()->findByAttributes(['shop_id'=>$this->shop_id,'email'=>$this->email]);
        if ($model!=null){
            $this->addError('email', Sii::t('sii','Email Address "{email}" has already been taken.',['{email}'=>$this->email]));
        }
    }
    
    public function displayName() 
    {
        return Sii::t('sii','Registration Form');
    }
}
