<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ContentController
 *
 * @author kwlok
 */
class ContentController extends SSiteController 
{     
    use WcmContentTrait, WcmLayoutTrait;
    
    protected $defaultFeaturePage;
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
        $this->loadWcmLayout($this);
        $this->defaultFeaturePage = Config::getSystemSetting('features_default_page');  
        $this->checkLanguage();
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
                'actions'=>['index','features'],
                'users'=>['*'],
            ]
        ],parent::accessRules());//parent access rules has to put at last
    }    
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     * 
     * It supports locale setting directly via url
     * E.g. Send in ?lang=zh_cn for chinese
     */
    public function actionIndex()
    {
        $this->enablePageTitleSuffix = false;
        $seoTitle = $this->getPageSeo('landing', 'seoTitle');
        $this->pageTitle = strlen($seoTitle) > 0 ? $seoTitle: app()->name; 
        $this->metaDescription = $this->getPageSeo('landing', 'seoDesc');
        $this->metaKeywords = $this->getPageSeo('landing', 'seoKeywords');
        $this->render('index');
    }    
    /**
     * This shows merchant portal "features" list.
     */
    public function actionFeatures()
    {
        $seoTitle = $this->getPageSeo('features', 'seoTitle');
        $this->pageTitle = strlen($seoTitle) > 0 ? $seoTitle: Sii::t('sii','Features'); 
        $this->metaDescription = $this->getPageSeo('features', 'seoDesc');
        $this->metaKeywords = $this->getPageSeo('features', 'seoKeywords');
        $page = current(array_keys($_GET));//take the first key as page attribute
        if (empty($page))
            $page = $this->defaultFeaturePage;
        $this->render('features',['page'=>'features_'.$page]);
    }
    /**
     * @return array All params readily used in wcm page
     */
    public function getContentParams()
    {
        return [
            'SITE_NAME',
            'CDN_BASE_URL'=>app()->urlManager->createCdnUrl(),
            'SIGNUP_URL'=>app()->urlManager->createMerchantUrl().'/signup',
            'LEARN_MORE_URL'=>hostUrl('features'),
            'DEMO_SHOP_URL'=>Config::getSystemSetting('shop_demo_link'),
            'DEMO_CHATBOT_URL'=>Config::getSystemSetting('chatbot_demo_link'),
            'OPEN_SOURCE_URL'=>Config::getSystemSetting('repo_source_link'),
            'THEME_STORE_URL'=>app()->urlManager->createMerchantUrl().'/themes',
        ];
    }
    /**
     * Render carousel widget
     * @param type $js Javascript to load
     */
    public function renderCarouselWidget($js)
    {
        //Carousel setup 
        // Not displaying Carousel Indicators and Controls
        // As Indicators seems not working, and control click UX is not so good (page jumping and url contains #admin_laptop_carousel
        Helper::registerJs($js);
        echo bootstrap()->Carousel([]);//call this to load bootstrap library        
    }
    /**
     * Generate feature menu widget
     * @param $page
     * @param string $section currently not supported; If value is passed in, the section hyperlink should have css class "active' associated
     * Todo add folowing to support menu item link 'active' class, if $section is known
     * E.g.
     * <pre>
     * 'content' = [
     *   CHtml::link('Multiple shops',WcmFeature::url('highlights#overview'),['class'=>$section=='overview'?'active':'']),
     * ]
     * </pre>
     */
    public function featureMenuWidget($page,$section=null)
    {
        echo bootstrap()->Collapse([
            'items' => [
                $this->getFeatureMenuItem($page,'highlights',WcmFeature::items('highlights')),
                $this->getFeatureMenuItem($page,'website',WcmFeature::items('website')),
                $this->getFeatureMenuItem($page,'cart',WcmFeature::items('cart')),
                $this->getFeatureMenuItem($page,'products',WcmFeature::items('products')),
                $this->getFeatureMenuItem($page,'payments',WcmFeature::items('payments')),
                $this->getFeatureMenuItem($page,'orders',WcmFeature::items('orders')),
                $this->getFeatureMenuItem($page,'marketing',WcmFeature::items('marketing')),
                $this->getFeatureMenuItem($page,'analytics',WcmFeature::items('analytics')),
                $this->getFeatureMenuItem($page,'crm',WcmFeature::items('crm')),
                $this->getFeatureMenuItem($page,'themes',WcmFeature::items('themes')),
                $this->getFeatureMenuItem($page,'chatbots',WcmFeature::items('chatbots')),
                $this->getFeatureMenuItem($page,'hosting',WcmFeature::items('hosting')),
                $this->getFeatureMenuItem($page,'help',WcmFeature::items('help')),
            ],
        ]);
        //note: comment off if to use back side menu
        //remove accordion parent id to allow multiple panels open at one time
//        $script = <<<EOJS
//            $('.column.menu .collapse-toggle').each(function( index, obj ) {
//                $( this ).attr('data-parent','');
//            });
//            $('.list-group-item a').click(function(){
//                $('.list-group-item a').removeClass('active');
//                $(this).addClass("active");
//            });
//EOJS;
//        Helper::registerJs($script);
    }

    protected function getFeatureMenuItem($currentPage,$pageId,$menu)
    {
        return [
            'label' => WcmFeature::title($pageId).'<i class="fa fa-caret-down" style="padding-left: 10px"></i>',
            'encode'=>false,
            'content' => $menu,
            'contentOptions' => $this->getFeatureClass($currentPage,$pageId,'in'), // open its content by default for "in"
            'options' => $this->getFeatureClass($currentPage,$pageId),
        ];
    }

    protected function getFeatureClass($page,$targetPage,$activeClass='active')
    {
        return ['class' => $page=='features_'.$targetPage?$activeClass:''];
    }
    
}
