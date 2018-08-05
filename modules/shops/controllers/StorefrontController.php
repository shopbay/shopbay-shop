<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.shops.controllers.ShopPageControllerTrait');
/**
 * This controller manages shop store front UI/widgets and product catalog displays 
 * 
 * @author kwlok
 */
class StorefrontController extends AuthenticatedController 
{
    use ShopPageControllerTrait;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
    }  
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),$this->storefrontBehaviors());
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'contactpost'=>['class'=>'ContactPostAction'],
            'promoget'=>['class'=>'PromotionGetAction'],
            'css'=> ['class'=>'CssGetAction'],
        ];
    }     
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            [Yii::app()->ctrlManager->pageTitleSuffixFilter,'hideOnShopScope'=>true,'useShopName'=>false],//shop scope page title suffix @see ShopPageTitleSuffixFilter
            'accessControl', // perform access control for CRUD operations
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
                'actions'=>[
                    'page',
                    'prevdata',
                    'catalog',
                    'trend',
                    'promotion',
                    'fbshop',
                    'promoget',
                    'css',
                    '404',
                    'contactpost',
                ],
                'users'=>['*'],
            ],
            ['allow', // allow authenticated user to perform actions
                'actions'=>[],
                'users'=>['@'],
            ],
            ['deny',  // deny all users
                'users'=>['*'],
            ],
        ];
    }  
    /**
     * Direct access shop page
     * Supported url scheme:
     * [1] 'shop/<shop_name>' => 'shops/storefront/page',
     *     e.g. http://localhost/shop/this-is-a-shop-name
     * [2] 'shop/<shop_name>/<page>' => 'shops/storefront/page',
     * [3] 'shop/<shop_name>/<page>/<subpage>' => 'shops/storefront/page',
     *     e.g. http://localhost/shop/this-is-a-shop-name/product/this-is-a-product-name (product modal view) (both AJAX and HTTP GET view)
     *     e.g. http://localhost/shop/this-is-a-shop-name/category/this-is-a-category-name (HTTP GET view)
     *     e.g. http://localhost/shop/this-is-a-shop-name/brand/this-is-a-brand-name (HTTP GET view)
     * [4] 'shop/<shop_name>/<page>/<subpage>/<subsubpage>' => 'shops/storefront/page',
     *     e.g. http://localhost/shop/this-is-a-shop-name/category/this-is-a-category-name/subcategory-name (HTTP GET view)
     *     e.g. http://localhost/shop/this-is-a-shop-name/products/product-name (HTTP GET view)
     *     e.g. http://localhost/shop/this-is-a-shop-name/promotions/offer-name/campaignKey (HTTP GET view)
     * [5] 'product/<product_name>' => 'shops/storefront/page',
     * [6] 'promotions/<campaign_name>/<campaign_key>' => 'shops/storefront/page',
     */
    public function actionPage()
    {
        if (isset($_GET['shop_name']) && !isset($_GET['page'])) {
            $model = $this->_getShopModel(urldecode($_GET['shop_name']));  
            if ($model!=null){
                $this->setCurrentPage();//follow default page
                $this->renderIndexPage($model);
                Yii::app()->end();
            }
            else {
                $this->_render404();
            }
        }
        elseif (isset($_GET['shop_name']) && isset($_GET['page'])) {
            $page = $_GET['page'].'_page';
            $model = $this->_getShopModel(urldecode($_GET['shop_name']));
            if ($model==null)
                $this->_render404();
            
            if (!ShopPage::existsPage($page))
                $this->_render404($model);

            if ($page==ShopPage::SITEMAP_XML)
                $this->_renderSitemapXML($model);

            if (ShopPage::isCustomPage($page)){
                logTrace(__METHOD__.' Request custom page',request()->requestUri);
                $this->_renderCustomPage($model, request()->requestUri);
            }            
            
            $this->setCurrentPage($page);
            logTrace(__METHOD__.' Request shop page '.$this->getCurrentPage());
            //process page
            if ($page==ShopPage::PRODUCT && isset($_GET['subpage'])) {//product modal view, @see ProductPage::getProductUrl()
                logTrace(__METHOD__.' Request product modal page '.$_GET['subpage']);
                $this->_renderProductPage($_GET['subpage'],true);
            }
            elseif ($page==ShopPage::PRODUCTS && isset($_GET['subpage'])) {//direct product view as entry, @see ProductPage::getProductUrl()
                logTrace(__METHOD__.' Request product direct page '.$_GET['subpage']);
                $this->_renderProductPage($_GET['subpage']);//but internally $page is set to ShopPage::PRODUCT
            }
            elseif ($page==ShopPage::PROMOTIONS && isset($_GET['subsubpage'])) {
                //$_GET['subpage'] will contain the campaign name (dummy, for url beautification)
                logTrace(__METHOD__.' Request promotions page '.$_GET['subpage'].' and campaign key '.$_GET['subsubpage']);
                $this->_renderCampaignPage($_GET['subsubpage']);//$_GET['subsubpage'] will contain the campaignKey 
            }
            elseif (request()->getIsAjaxRequest()) { 
                $this->setThemeLayout($model->getTheme());
                header('Content-type: application/json');
                $pagination = false;
                if (isset($_GET['ajax']) && ($page==ShopPage::NEWS||$page==ShopPage::SEARCH)){
                    //this param is sending from javascript storefront.js method news(), including 'News_page' as well
                    $pagination = true;
                }
                echo CJSON::encode($this->getThemePageDataWrapper($model,$this->getCurrentPage(),$pagination));
            }  
            else {
                $this->renderIndexPage($model);
            }
            Yii::app()->end();      
        }
        elseif (isset($_GET['product_name'])) {
            $this->_renderProductPage($_GET['product_name']);
        }
        elseif (isset($_GET['campaign_name']) && isset($_GET['campaign_key'])) {
            //$_GET['campaign_name'] is mainly for url beautification
            $this->_renderCampaignPage($_GET['campaign_key']);    
        }
        else
            $this->_render404();
    }  
    /**
     * Get trend view 
     * Send from storefront.js trendview()
     */
    public function actionTrend()
    {
        if (isset($_GET['shop']) && isset($_GET['topic']) && isset($_GET['view']) && isset($_GET['container']) ) {
            $model = $this->loadModel($_GET['shop'],get_class(Shop::model()),true);
            $page = $this->createViewPage(ShopPage::TRENDS, $model);
            $page->trendTopic = $_GET['topic'];
            $page->trendViewContainer = $_GET['view'];
            if(Yii::app()->request->isAjaxRequest){
                header('Content-type: application/json');
                echo CJSON::encode([
                    'html'=>$page->getPage(),
                    'topic'=>$_GET['topic'],
                    'container'=>$_GET['container'],
                ]);
            }
            else {
                header('Content-type: application/json');
                echo CJSON::encode($page->getPage());
            }
            Yii::app()->end();      
        }
        throwError403(Sii::t('sii','Unauthorized Access')); 
    }
    /**
     * Get previous data (comment/questions)
     */
    public function actionPrevData()
    {
        if(isset($_POST['CommentForm'])) {
            $form = new CommentForm('prevcomment');
            $form->attributes = $_POST['CommentForm'];
            logTrace(__METHOD__.' CommentForm attributes',$form->getAttributes());
        }
        if(isset($_POST['QuestionForm'])) {
            $form = new QuestionForm('prevquestion');
            $form->attributes = $_POST['QuestionForm'];
            logTrace(__METHOD__.' QuestionForm attributes',$form->getAttributes());
        }
        $this->_actionPrevDataInternal($form);
    }
    /**
     * Internal function to support actionPrev<Model>
     * @param CFormModel $form
     */
    private function _actionPrevDataInternal($form)
    {
        $idx = 'undefined';
        switch (get_class($form)) {
            case 'CommentForm':
                $idx = 'comment';
                $model = $this->loadModel($form->target,$form->type,false);
                $dataProvider = $model->searchComments($form->page);
                break;
            case 'QuestionForm':
                $idx = 'question';
                $model = $this->loadModel($form->product_id,get_class(Product::model()),false);
                $dataProvider = $model->searchQuestions($form->page);
                $viewData = ['showAvatar'=>true];
                break;
            default:
                throwError403(Sii::t('sii','Unauthorized Access'));
        }
        
        $form->page += 1;
        if ($dataProvider->pagination->pageSize>=$dataProvider->totalItemCount)
            $prevlink = 'onepage';//question pagination is showing reverse
        else if ($dataProvider->pagination->currentPage==0)
            $prevlink = 'lastpage';//question pagination is showing reverse
        else
            $prevlink = $this->renderPartial($this->getThemeView('_product_'.$idx.'_prev'),[$idx.'Form'=>$form],true);

        header('Content-type: application/json');
        echo CJSON::encode([
            'prevlink'=>$prevlink,
            'prevdata'=>$this->widget('zii.widgets.CListView',[
                    'dataProvider'=>$dataProvider,
                    'template'=>'{items}',
                    'emptyText'=>'',
                    'viewData'=>isset($viewData)?$viewData:null,
                    'itemView'=>$this->module->getView($idx.'s.'.$idx.'quickview'),//query from module view map
                ],true),
         ]);
         Yii::app()->end();        
    }
    /**
     * Get all active shop promotions
     * Mainly to serve promotion pagination
     * Used in storefront.js promotion() and Shoppage::getCampaignDataProvider
     * @param type $shop
     */
    public function actionPromotion($shop)
    {
        if (isset($_GET['shop'])) {        
            $page = ShopPage::PROMOTIONS;
            $model = Shop::model()->findByPk($shop);
            $this->setThemeLayout($model->getTheme());
            header('Content-type: application/json');
            if (isset($_GET['CampaignBga_page'])){//this param is sending from pagination setting
                echo $this->getThemePageDataWrapper($model,$page,true);
            }
            else { //default promotion view in tab
                echo CJSON::encode($this->getThemePageDataWrapper($model,$page));
            }
            Yii::app()->end();      
        }
        throwError404();
    }    
    /**
     * Get product catalog data (ajax only)
     * Mainly to serve catalog pagination
     * If pagination data is not available, redirect to ShopPage::PRODUCTS
     */
    public function actionCatalog()
    {
        if (isset($_GET['shop']) && isset($_GET['Product_page'])) {
            $model = Shop::model()->active()->findByPk($_GET['shop']);  
            $this->setThemeLayout($model->getTheme());
            $pageobj = $this->createViewPage(ShopPage::PRODUCTS, $model);
            if (isset($_GET['browseBy'])){
                $pageobj->setFilter([$_GET['browseBy']=>$_GET['group']]);
            }
            //need a div wrapper for jquery.ias container (pager) to work
            echo CHtml::tag('div',['class'=>'catalog-wrapper'],
                $this->renderPartial($this->getThemeView('_catalog_listing'),[
                    'idx'=>'catalog',
                    'page'=>$pageobj,
                    'dataProvider'=>$pageobj->dataProvider,
                ],true)
            );
            Yii::app()->end();
        }
        else 
            $this->redirect(ShopPage::trimPageId(ShopPage::PRODUCTS));
    }
    /**
     * A public page to display 'shop not found'
     * This is used for shop webapp when shop is not available
     * -> can be offline, no longer valid (no subscription), or deleted etc
     * @see CustomerUser::refreshShop()
     */
    public function action404()
    {
        $this->_render404();
    }
    /**
     * Callback method when shop is clicked at facebook page
     * $_POST['signed_request'] return from facebook:
     *  array (   
     *      'algorithm' => 'HMAC-SHA256'
     *      'issued_at' => 1434462772
     *      'page' => array (
     *           'id' => 'xxxxyyyyyxxxx'
     *           'admin' => true
     *       )
     *       user' => 
     *           array (
     *              'country' => 'sg',
     *              'locale' => 'en_US',
     *              'age' => 
     *               array (
     *                  'min' => 21,
     *               ),
     *           ),
     *      'user_id' => 'xxxxxxxx',
     *  )
     */
    public function actionFbshop()
    {
        if (isset($_POST['signed_request'])){
            $data_signed_request = explode('.',$_POST['signed_request']); // Get the part of the signed_request we need.
            $jsonData = base64_decode($data_signed_request['1']);//base64 decode signed_request making it JSON.
            $objData = json_decode($jsonData,true); //Split the JSON into arrays.
            logTrace(__METHOD__.' signed request data from facebook',$objData);  
            $pageData = $objData['page'];
            $shopSetting = ShopSetting::model()->facebookPage($pageData['id'])->find();
            if ($shopSetting!=null){
                user()->setScope(CustomerUser::SCOPE_SHOP);
                $this->setCurrentPage();//follow default page
                $this->setFacebookMode();//set to facebook page mode
                $this->renderIndexPage($shopSetting->shop);
                Yii::app()->end();
            }
        }
        throwError404(Sii::t('sii','Page not Found.'));
    }    
    /**
     * Render product page by product url (for modal url via ajax and direct url access)
     * @param type $slug
     */
    protected function _renderProductPage($slug,$modal=false)
    {
        $model = $this->getProductModel($slug);  
        if ($model!=null){
            if ($modal){
                $modalView = $this->renderModalPage(ProductPage::MODAL,$model);
                if (request()->getIsAjaxRequest()) { 
                    header('Content-type: application/json');
                    echo CJSON::encode([
                        'modal'=>$modalView,
                        'promotion_buttons'=>$this->getCampaignButtons($model),
                    ]);
                }  
                else {
                    //modal view is overlaying on shop default page
                    $this->renderIndexPage($model->shop, $modalView);
                }
            }
            else {
                $this->setCurrentPage(ShopPage::PRODUCT);
                logTrace(__METHOD__.' Request product page '.$this->getCurrentPage());
                $this->renderIndexPage($model);
            }
            Yii::app()->end();      
        }  
        else
            $this->_render404();
    }
    
    private function _renderCampaignPage($campaignKey) 
    {
        $model = $this->getCampaignModel($campaignKey);
        if ($model==null || $model->hasExpired())
            $this->_render404($model);

        if (request()->getIsAjaxRequest()) {
            header('Content-type: application/json');
            echo CJSON::encode([
                'modal'=>$this->renderModalPage(CampaignPage::MODAL,$model),
                'promotion_buttons'=>$this->getCampaignButtons(['campaign'=>$model,'exclude'=>$model->id]),
            ]);
        }
        else {
            $this->setCurrentPage(ShopPage::CAMPAIGN);
            logTrace(__METHOD__.' campaign page request '.$this->getCurrentPage());
            $this->renderIndexPage($model);
        }
        Yii::app()->end();
    }      
    /**
     * Locate shop model
     * @param string $slug Shop slug or custom domain
     * @return type
     */
    private function _getShopModel($slug)
    {
        if ($this->onCustomDomain()){
            if (user()->shopModel!=null && user()->shopModel->slug==$slug){
                $model = user()->shopModel;
                logTrace(__METHOD__.' Shop model is already loaded from WebUser');
            }
            else
                $model = Shop::model()->findByDomain($slug,$this->onLive());//for preview mode, no need to check shop is active 
        }
        else {
            $finder = Shop::model()->withSlug($slug);
            if ($this->onLive())
                $finder = $finder->active();
            $model = $finder->find(); 
        }
        return $this->isValidModel($model);
    }
    /**
     * Generate sitemap xml file
     */
    private function _renderSitemapXML($model)
    {
        if (!$model->hasSitemap())
            $this->_render404($model);

        $pageobj = new ShopPage($this->getCurrentPage(),$model,$this);
        header('Content-Type: application/xml');
        $this->widget('shopwidgets.shopsitemap.ShopSitemap',$pageobj->getSitemapContent(true,'application/xml'));
        Yii::app()->end();
    }
    /**
     * Render custom page
     * Url pattern can be in "/page/<page_name>" or "/<page_name>"
     */
    private function _renderCustomPage($shop,$uri)
    {
        $slug = null;//default pointing to nothing (no page found)
        $url = Helper::parseUri($uri);
        if (isset($url['path'][1]) && $url['path'][1]!='page')//url pattern: /<page_name>
            $slug = $url['path'][1];
        elseif (isset($url['path'][2]))//url pattern: /page/<page_name>
            $slug = $url['path'][2];
        //logTrace(__METHOD__.' slug',$slug);
        $page = $this->getPageModel($shop,$slug);  
        if ($page!=null){
            $this->setCurrentPage(ShopPage::CUSTOM);
            $this->renderIndexPage($page);
            Yii::app()->end();      
        }
        else
            $this->_render404($shop);
    }     
    /**
     * Render 404 page 
     * Using shop theme page But this works only when shop model is known
     * @param $model
     * @param $message
     */
    private function _render404($model=null,$message=null)
    {
        if (isset($model)){
            $this->renderHtmlPageByFile($model, '404', ['message'=>$message]);
        }
        else {
            $this->render('404');
        }
        Yii::app()->end();
    }        
}
