<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shop.controllers.ShopControllerTrait');
Yii::import('wcm.controllers.WcmLayoutTrait');
/**
 * Description of SiteController
 *
 * @author kwlok
 */
class SiteController extends SSiteController 
{
    use WcmLayoutTrait;
    use ShopControllerTrait {
        init as traitInit;
    }    
    /**
     * Initializes the controller.
     */
    public function init()
    {
        parent::init();
        $this->traitInit();
    }      
    /**
     * Behaviors for shop controller
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }            
    /**
     * Specifies the local access control rules.
     * @see SSiteController::accessRules()
     * @return array access control rules
     */
    public function accessRules()
    {
        return array_merge([
            ['allow',  
                'actions'=>['index','page','locale','captcha'],
                'users'=>['*'],
            ]
        ],parent::accessRules());//parent access rules has to put at last
    }
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        if (isset($_GET['lang'])){
            $this->setUserLocale($_GET['lang']);
        }
        
        Yii::app()->controller->registerCssFile('application.assets.css','application.css');
        Yii::app()->urlManager->parseShopUrl();
        Yii::app()->end();
    }
    /**
     * This is the default index page when subdomain non-shop subdomain is entered
     * e.g. www.shopbay.org
     */
    public function actionPage($id=null)
    {
        $this->enablePageTitleSuffix = false;
        $this->setPageTitle(Yii::app()->name.' - The Open Source Ecommerce Platform');
        $this->layout = 'shop.views.layouts.site';
        $this->headerView = 'shop.views.layouts._site_header';
        $this->footerView = 'shop.views.layouts._site_footer';
        $this->htmlBodyCssClass .= ' site-page';//append css class

        switch ($id) {
            case 'shop-not-found':
            case 'page-not-found':
                //@see ShopUser::refreshShop() for how this page cound be reached
                $this->render('page',[
                    'heading'=>Sii::t('sii','Sorry, the page is not available.'),
                    'body'=>Sii::t('sii','The link you requested may be broken or may have been removed.'),
                ]);
                break;
            case 'locale'://@see passed from ShopUrlManager::parseShopUrl()
                logTrace(__METHOD__.' Request site locale',request()->requestUri);
                $this->actionLocale();    
                break;
            case 'captcha':
                logTrace(__METHOD__.' Request site captcha',request()->requestUri);
                $this->forward('wcm/content/captcha');    
                break;
            default:
                $this->loadWcmLayout($this);
                $this->forward('wcm/content/index');    
                //$this->render('index');//default index page not use; Use wcm content index page instead
                break;
        }
    }
    /**
     * This action changes the site locale
     */
    public function actionLocale()
    {
        if (isset($_POST['language'])){
            $this->setUserLocale($_POST['language']);
        }
        if (request()->getUrlReferrer()==Yii::app()->urlManager->createHostUrl('shops/storefront/fbshop',true)){
            //for fb shop
            logInfo(__METHOD__.' url referrer',Yii::app()->urlManager->createHostUrl('shops/storefront/fbshop',true));
            $this->redirect('/');
        }
        else
            $this->redirect(request()->getUrlReferrer());
    }
}