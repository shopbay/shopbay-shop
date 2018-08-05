<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.SUserMenu");
Yii::import("common.widgets.susermenu.components.*");
/**
 * Description of WcmMenu
 *
 * @author kwlok
 */
class WcmMenu extends SUserMenu
{
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if (!isset($this->user))
            throw new CException(__CLASS__." User cannot be null");
        
        $this->renderMenu('WcmMenuContent', $this->offCanvas);
    }  
}
Yii::import("common.widgets.susermenu.components.*");
Yii::import("wcm.models.WcmFeature");
/**
 * Description of WcmMenuContent
 * 
 * @author kwlok
 */
class WcmMenuContent extends UserMenu 
{
    public static $features  = 'features';
    public static $pricing   = 'pricing';
    public static $faq       = 'faq';
    public static $demo      = 'demo';
    public static $community = 'community';
    
    protected $hostInfo;
    /**
     * Constructor
     */
    public function __construct($user,$config=[]) 
    {
        $this->loadConfig($config);
        
        $this->items[static::$features] = new UserMenuItem([
            'id'=> static::$features,
            'label'=>Sii::t('sii','Features'),
            'icon'=>'<i class="fa fa-heart-o"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'iconPlacement'=>$this->iconPlacement,
            'url'=>app()->urlManager->createHostUrl('/features'),
            'cssClass'=>'features',
            'items'=>WcmFeature::menu(),
        ]);
//        $this->items[static::$pricing] = new UserMenuItem([
//            'id'=> static::$pricing,
//            'label'=>Sii::t('sii','Pricing'),
//            'icon'=>'<i class="fa fa-check"></i>',
//            'iconDisplay'=>$this->iconDisplay,
//            'iconPlacement'=>$this->iconPlacement,
//            'url'=>app()->urlManager->createHostUrl('pricing'),
//        ]);        
//        $this->items[static::$faq] = new UserMenuItem([
//            'id'=> static::$faq,
//            'label'=>Sii::t('sii','FAQ'),
//            'icon'=>'<i class="fa fa-check"></i>',
//            'iconDisplay'=>$this->iconDisplay,
//            'iconPlacement'=>$this->iconPlacement,
//            'url'=>app()->urlManager->createHostUrl('index#faq'),
//        ]);        
//        $this->items[static::$demo] = new UserMenuItem([
//            'id'=> static::$demo,
//            'label'=>Sii::t('sii','Demo'),
//            'icon'=>'<i class="fa fa-tv"></i>',
//            'iconDisplay'=>$this->iconDisplay,
//            'iconPlacement'=>$this->iconPlacement,
//            'url'=>app()->urlManager->createHostUrl('index#demo'),
//        ]);        
        $this->items[static::$community] = new UserMenuItem([
            'id'=> static::$community,
            'label'=>Sii::t('sii','Community'),
            'icon'=>'<i class="fa fa-users"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'iconPlacement'=>$this->iconPlacement,
            'url'=>app()->urlManager->createMerchantUrl().'/community',
        ]);        
        
        $siteMenu = new SiteMenu($user, false, [
            'signinScript'=>'redirect("'.app()->urlManager->createMerchantUrl().'/signin");',
            'signupScript'=>'redirect("'.app()->urlManager->createMerchantUrl().'/signup");',
            'iconDisplay'=>$this->iconDisplay,
            'signupLabel'=>Sii::t('sii','Get Started'),
        ]);
        $langMenu = new LangMenu($user);
        $this->items = array_merge($this->items,$siteMenu->items,$langMenu->items);
    }  
    
    public function getMobileButton()
    {
        $button = CHtml::openTag('div',['class'=>'mobile-button mobile-wcm']);
        $button .= CHtml::link('<i class="fa fa-navicon"></i>','javascript:void(0);',['onclick'=>'openoffcanvaswcmmenu();']);
        $button .= CHtml::closeTag('div');
        return $button;        
    }
}
