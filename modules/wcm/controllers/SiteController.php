<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SiteController
 *
 * @author kwlok
 */
class SiteController extends SSiteController 
{
    use WcmContentTrait, WcmLayoutTrait;    
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        $this->loadWcmLayout($this);
        $this->enablePageTitleSuffix = false;
        $this->htmlBodyCssClass .= ' site';
        $this->colorScheme = WcmLayoutTrait::$colorSchemeBlack;
        app()->ctrlManager->setColorScheme($this->colorScheme);
        logTrace(__METHOD__.' Use color scheme ',$this->colorScheme);
    }      
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', 
        ];
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
                'actions'=>['about','terms','privacy','careers','contact','captcha'],
                'users'=>['*'],
            ],
            //default deny all users anything not specified       
            ['deny',  
                'users'=>['*'],
            ],
        ];
    }    
    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $this->pageTitle = Sii::t('sii','{page} | {company}',['{page}'=>Sii::t('sii','Contact Us'),'{company}'=>param('ORG_NAME')]);
        $this->registerFormCssFile();
        $model = new ContactForm;

        if(isset($_POST['ContactForm'])){
            try {
                $model->attributes = $_POST['ContactForm'];
                if ($model->validate()){
                    Yii::app()->serviceManager->execute(
                            $model,
                            [ServiceManager::NOTIFICATION=>ServiceManager::EMPTY_PARAMS],
                            ServiceManager::NO_VALIDATION);
                    user()->setFlash(get_class($model),[
                        'message'=>Sii::t('sii','We value your input and will respond to you shortly.'),
                        'type'=>'success',
                        'title'=>Sii::t('sii','Thank you for contacting us!'),
                    ]);               
                    $model->unsetAttributes();
                    unset($_POST);
                }
                
            } catch (CException $e) {
                user()->setFlash(get_class($model),[
                    'message'=>$e->getMessage(),
                    'type'=>'error',
                    'title'=>Sii::t('sii','Submission Error'),
                ]);               
            }
        }
        $this->render('contact',['model'=>$model]);
    }    
    /**
     * This action shows about us page
     */
    public function actionAbout()
    {
        $this->pageTitle = Sii::t('sii','{page} | {company}',['{page}'=>Sii::t('sii','About Us'),'{company}'=>param('ORG_NAME')]);
        $this->render('about',[
            'about'=>'about',
            'values'=>'values',
            'investors'=>'investors',
        ]);
    }
    /**
     * This action shows terms of service page
     * @todo Default to merchant terms only; To include "general" terms need to workout the requirement and logic
     * The diff between general and merchant terms is the clause "Your Products and Services in our Services"
     */
    public function actionTerms()
    {
        $termsFile = $this->action->id.'_merchant';//default to merchant 
        $this->pageTitle = Sii::t('sii','{page} | {company}',['{page}'=>Sii::t('sii','Terms of Service'),'{company}'=>param('ORG_NAME')]);
        $this->_actionInternal($this->action->id,$termsFile);
    }
    /**
     * This action shows privacy policy page
     */
    public function actionPrivacy()
    {
        $this->pageTitle = Sii::t('sii','{page} | {company}',['{page}'=>Sii::t('sii','Privacy Policy'),'{company}'=>param('ORG_NAME')]);
        $this->_actionInternal($this->action->id);
    }
    /**
     * This action shows careers page
     */
    public function actionCareers()
    {
        $this->pageTitle = Sii::t('sii','{page} | {company}',['{page}'=>Sii::t('sii','Careers'),'{company}'=>param('ORG_NAME')]);
        $this->_actionInternal($this->action->id);
    }
    /**
     * This action retrieves web content page
     */
    private function _actionInternal($action,$page=null)
    {
        if (!isset($page))
            $page = $action;
        $this->render($action,['page'=>$page]);
    }    

}
