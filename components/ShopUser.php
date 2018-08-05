<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopUser
 *
 * @author kwlok
 */
class ShopUser extends WebUser 
{
    /*
     * User's shopping scope
     * If all user objects is scoped within a shop;
     * This is used when shop is running own its and not on Marketplace
     */
    CONST SCOPE_SHOP  = 'shop';
    /**
     * Customer's shopping scope; Either on entire platform, a marketplace, or a shop
     * @var type 
     */
    protected $scope;
    private $_s;//shop instance in session
    private $_c;//cart instance in session    
    /**
     * Init
     */
    public function init()
    {
        parent::init();
        $this->currentRole = Role::CUSTOMER;
        $this->scope = self::SCOPE_SHOP;
    }    
    /**
     * After login event
     * @param type $fromCookie
     */
    public function afterLogin($fromCookie)
    {        
        parent::afterLogin($fromCookie);
        $this->setCartCount($this->getCart()->getCount());
        $this->setShopCartCount($this->getShop(),$this->getCart()->getCount($this->getShop()));
    }
    /**
     * Set user scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    /**
     * Check if customer shopping scope
     * @return boolean
     */
    public function onShopScope()
    {
        return $this->scope==self::SCOPE_SHOP;
    }
    /**
     * Get the current visit shop
     * @return null|string Shop id
     */
    public function getShop()
    {
        return $this->getState(SActiveSession::SHOP_VISIT, null);
    }    
    /**
     * Store the current visit shop
     * Each shop (accessed via its subdomain url) will have separate session.
     * Shop accessed via common url path '/shop/<shop_name>' will share this session, and each time user change shop, the session value changes accordingly.
     * @todo Explore session $path to separate each url path '/shop/<shop_name>' cookies
     * 
     * @param integer $shop The shop id
     */
    public function setShop($shop)
    {
        $this->setState(SActiveSession::SHOP_VISIT, $shop);
    }   
    /*
     * Return shop model 
     */
    public function getShopModel()
    {
        if ($this->_s==null && $this->getShop()!=null)
            $this->_s = Shop::model()->findByPk($this->getShop());
        return $this->_s;
    }
    /**
     * This is check if session shop is always available. If session expires, reload
     */
    public function refreshShop()
    {
        //set session shop
        if ($this->getShop()==null && $_SERVER['HTTP_HOST']!=Yii::app()->urlManager->hostDomain){
            $domain = str_replace(app()->urlManager->domain,'',$_SERVER['HTTP_HOST']);
            $shop = Shop::model()->findByDomain($domain);//for active shop only
            //if ($shop!=null && $shop->hasSubscription){//
            if ($shop!=null){//shop subscription verification is done at StorefrontController, else shop preview cannot go through
                $this->setShop($shop->id);
                logInfo(__METHOD__.' Reload shop into session');
            }
            else {
                logError(__METHOD__." Shop by domain $domain not found!");
                Yii::app()->controller->redirect(app()->urlManager->createHostUrl('site/page?id=shop-not-found'));
                Yii::app()->end();
            }
        }      
    }     
    /*
     * Return cart 
     */
    protected function getCart()
    {
        if ($this->_c==null)
            $this->_c = Yii::app()->serviceManager->getCart();
        return $this->_c;
    }
    /**
     * Get the cart url of current visited shop
     * @return null|string The cart url
     */
    public function getCartUrl()
    {
        return $this->getState(SActiveSession::SHOP_CART, null);
    }    
    /**
     * Store the cart url of current visited shop
     * @param string $url The cart url of shop
     */
    public function setCartUrl($url)
    {
        $this->setState(SActiveSession::SHOP_CART, $url);
    }    
    /**
     * Cart count by shop
     * @param type $shop
     * @param type $count
     */
    public function setShopCartCount($shop,$count)
    {
        $this->setState('_cartcount_'.$shop,$count);
    }
    
    public function setCartCount($count)
    {
        $this->setState('_cartcount',$count);
    }
    
    public function getCartCount($shop=null)
    {
        if (isset($shop))
            return $this->getState('_cartcount_'.$shop);
        else 
            return $this->getState('_cartcount');
    }    
    /*
     * Return account 
     */
    protected function getAccount()
    {
        if ($this->_account==null){
            $this->_account=CustomerAccount::model()->findByPk([
                'shop_id'=>$this->getShop(),
                'email'=>$this->getName(),//its the identity id
            ]);
        }
        return $this->_account;
    }    
    /**
     * @inheritdoc
     */
    public function resetEmail($email)
    {
        $this->setEmail($email);
        $this->setName($email);//Shop user name is email itself
    }             
    /**
     * Construct and return profile menu according to role
     * @return array
     */
    public function getProfileMenu()
    {
        return $this->getMenuInternal('getProfileMenuItems');
    }    
    /**
     * Construct and return account menu according to role
     * @return array
     */
    public function getAccountMenu()
    {
        return $this->getMenuInternal('getAccountMenuItems');
    }       
    
    protected function getMenuInternal($menuMethod)
    {
        $loginMenu = new CustomerLoginMenu($this);
        return $loginMenu->{$menuMethod}();
    }      
}

