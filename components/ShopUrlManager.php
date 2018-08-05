<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopUrlManager
 *
 * @author kwlok
 */
class ShopUrlManager extends SUrlManager 
{
    protected $path;
    protected $query;
    /**
     * @property array the wcm rules to be loaded into SUrlManager
     */
    public $wcmRules = [
        'index'=>'wcm/content/index',//default landing page, @see WcmMenu 
        'features'=>'wcm/content/features',
        'features/*'=>'wcm/content/features/*',
        //Below url route has prefix "site/x" to avoid conflict with each in-built shop page url
        'site/about'=>'wcm/site/about',//for static web content page 
        'site/terms'=>'wcm/site/terms',//for static web content page 
        'site/privacy'=>'wcm/site/privacy',//for static web content page 
        'site/careers'=>'wcm/site/careers',//for static web content page
        'site/contact'=>'wcm/site/contact',
    ];    
    /**
     * @property array the customer rules to be loaded
     */
    public $customerRules = [
        'welcome'=>'accounts/welcome/index',
        'cart/paypalexpressreview'=>'carts/management/paypalexpressreview',
        'cart/<controller>/<action:\w+>'=>'carts/<controller>/<action>',
        'cart/<controller>/<action:\w+>/*'=>'carts/<controller>/<action>',
        'checkout'=>'carts/management/checkout',
        'order/view/*'=>'orders/customer/view',
        'order/track/*'=>'orders/customer/track',
        'orders/payment/*'=>'payments/customer/index',
        'item/view/*'=>'items/customer/view',
        'item/track/*'=>'items/customer/track',
        'message/view/*'=>'messages/management/view',
        'message/compose/*'=>'messages/management/compose',
        'message/reply/*'=>'messages/management/reply',
        'messages/sent'=>'messages/management/sent',
        'messages/unread'=>'messages/management/unread',                
        'comment/view/*'=>'comments/management/view',
        'payment/view/*'=>'payments/customer/view',
        'news/view/*'=>'news/customer/view',
        'question/view/*'=>'questions/customer/view',
        'question/ask'=>'questions/customer/ask',
        'ticket/view/*'=>'tickets/management/view',
        'media/assets/*'=>'media/files',
        'file/download/*'=>'media/download/attachment/*',
        'dashboard'=>'analytics/management/index',
        'shop-not-found'=>'site/page?id=shop-not-found',
    ];
    /**
     * Initializes the application component.
     */
    public function init()
    {
        unset($this->defaultRules['about']);//this is required by shop "About Us" page
        unset($this->defaultRules['terms']);//this is required by shop "Terms" page
        unset($this->defaultRules['privacy']);//this is required by shop "privacy" page
        unset($this->defaultRules['account/<controller>/<action:\\w+>']);//shop will have own 'account/activate' path
        unset($this->defaultRules['account/<controller>/<action:\\w+>/*']);//shop will have own 'account/activate' path
        unset($this->defaultRules['account/<controller>/*']);//shop will have own 'account/activate' path
        $this->defaultRules = array_merge($this->defaultRules,$this->customerRules,$this->wcmRules);
        $this->shopDomain = $this->hostDomain;
        parent::init();
    }
    /**
     * Parse shop url by subdomain
     * @see shops/controllers/StorefrontController::actionPage()
     */
    public function parseShopUrl()
    {
        $url = parse_url(request()->requestUri);
        logTrace(__METHOD__.' url',$url);
        $this->path = $url['path'];
        if (isset($url['query'])){
            parse_str($url['query'], $this->query);
            logTrace(__METHOD__.' url query',$this->query);
        }        
        
        $subdomain = str_replace($this->domain,'',$_SERVER['HTTP_HOST']);
        
        if ($this->isCustomShopDomain($subdomain)){
            $route = $this->createShopUrl($subdomain);
            logTrace(__METHOD__.' shop route: ',$_SERVER['HTTP_HOST'].'/'.$route);
            Yii::app()->runController($route);
        }
        else {
            $siteRoute = 'site/page';
            if ($this->path=='/site/locale')
                $siteRoute .= '/id/locale';
            elseif (strpos($this->path, '/site/captcha') !== false)
                $siteRoute .= '/id/captcha';
            //logTrace(__METHOD__.' Non shop domain route: '.$_SERVER['HTTP_HOST'].' site route: '.$siteRoute,request()->requestUri);
            Yii::app()->runController($siteRoute);
        }
    }  
    /**
     * This method create url based on shop domain
     * 
     * @see shops/controllers/StorefrontController::actionPage() for its defintion
     * @see more url rules defined at main.php
     * 
     * @param type $subdomain
     * @return string 
     */
    public function createShopUrl($route=null,$forceSecure=false)
    {
        //default route if any of below is not parsed with results
        $route = 'shops/storefront/page/shop_name/'.$route;
        if ($this->path!='/'){
            $path = explode('/',$this->path,4);
            logTrace(__METHOD__.' url path',$path);
            if (in_array($path[1], ['catalog','trend','prevdata','promotion','promoget','contactpost'])){
                $route = 'shops/storefront/'.$path[1];
                $route = $this->_appendOtherQueryParams($route, $path);
            }
            elseif (in_array($path[1], ['product'])){//@see StorefrontController::actionPage() ShopPage::PRODUCT handling
                if (isset($path[2]) && !empty($path[2]))//Product modal view
                    $route .= '/page/product/subpage/'.urldecode($path[2]);
            }
            elseif (in_array($path[1], ['products'])){//@see StorefrontController::actionPage() ShopPage::PRODUCTS handling 
                if (isset($path[2]) && !empty($path[2]))//direct single product view
                    $route = 'shops/storefront/page/product_name/'.urldecode($path[2]);
                else //list all products
                    $route .= '/page/products';
            }
            elseif (in_array($path[1], ['promotions'])){
                if (isset($path[2]) && !empty($path[2])){//modal promotion view
                    $route = 'shops/storefront/page/campaign_name/'.urldecode($path[2]);
                    if (isset($path[3]) && !empty($path[3]))
                        $route .= '/campaign_key/'.urldecode($path[3]);
                }
                else {//list all promotions 
                    $route .= '/page/promotions';
                }
            }
            elseif (in_array($path[1], ['category'])){//direct category view
                if (isset($path[2]) && !empty($path[2]))
                    $route .= '/page/category/subpage/'.urldecode($path[2]);
                if (isset($path[3]) && !empty($path[3]))
                    $route .= '/subsubpage/'.urldecode($path[3]);
            }
            elseif (in_array($path[1], ['brand'])){//direct brand view
                if (isset($path[2]) && !empty($path[2]))
                    $route .= '/page/brand/subpage/'.urldecode($path[2]);
            }
            elseif (in_array($path[1], ['news']) && isset($this->query['article'])){//news article view
                $route .= '/page/news/article/'.$this->query['article'];//convert to search/query/x
            }
            elseif (in_array($path[1], ['search'])){//direct search view
                $route .= '/page/search/query/'.$this->query['query'];//convert to search/query/x
            }
            elseif (in_array($path[1], ['cart']) && isset($this->query['token'])){//news article view
                $route .= '/page/cart/?token='.$this->query['token'];
            }
            elseif ($path[1]=='site' && isset($path[2]) && $path[2]=='locale'){
                $route = 'site/locale';
            }
            elseif ($path[1]=='shops' && isset($path[2]) && $path[2]=='storefront' && isset($path[3]) && strpos($path[3],'Product_page')!=false){//catalog pagination
                $route = 'shops/storefront/'.$path[3];
            }
            elseif ($path[1]=='custom' && isset($path[2]) && $path[2]=='css'){//custom css request
                $route = 'shops/storefront/'.$path[2];
            }
            else {
                $route .= '/page/'.$path[1];
                $route = $this->_appendOtherQueryParams($route, $path);
            }
        }
        return $route;
    }  
    /**
     * Append other url query params
     * @param string $route
     * @param string $path The url path
     * @return string
     */
    private function _appendOtherQueryParams($route,$path)
    {
        //append other uri params
        if (isset($path[2]) && !empty($path[2]))
           $route .= '/'.$path[2];
        if (isset($path[3]) && !empty($path[3]))
           $route .= '/'.$path[3];
        
        return $route;
    }
    /**
     * Check if the detected subdomain is a valid shop domain
     * Current implementation is use exclude-list method (as long as domain is not in the list are considered valid shop domain)
     * 
     * @param type $subdomain
     * @return boolean
     */
    protected function isCustomShopDomain($subdomain)
    {
        $excludeList = [
            'shopbay.org',//for apex domain 
            'www',//for domain starts with www 
        ];
        return !in_array($subdomain, $excludeList);
    }
}
