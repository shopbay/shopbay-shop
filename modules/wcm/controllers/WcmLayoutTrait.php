<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of WcmLayoutTrait
 *
 * @author kwlok
 */
trait WcmLayoutTrait 
{
    public static $colorSchemeWhite = 'white';
    public static $colorSchemeBlack = 'black';
    protected $colorScheme;
    
    public function loadWcmLayout($controller=null)
    {        
        $this->layout = 'wcm.views.layouts.site';
        $this->headerView = 'wcm.views.layouts._site_header';
        $this->footerView = 'wcm.views.layouts._site_footer';
        //load default color scheme if not set
        if (!isset($this->colorScheme))
            $this->colorScheme = Config::getSystemSetting('wcm_color_scheme');
        
        if (isset($controller) && $controller instanceof SController){
            $controller->htmlBodyCssClass .= ' wcm '.$this->colorScheme;
            $controller->registerCssFile('wcm.assets.css','wcm.css');
        }
    }    
    
    public function getInvertedColorScheme()
    {
        if ($this->colorScheme==static::$colorSchemeWhite)
            return static::$colorSchemeBlack;
        else
            return static::$colorSchemeWhite;
            
    }
    /**
     * Set the site logo display pattern
     * If to use image as site logo, please prepare corporate-logo-white.png / corporate-logo-black.png and put under www/assets/images
     * @param mixed $colorScheme If boolean, it will be auto invert; Else put in color scheme
     * @param type $url
     * @param string $img
     * @return type
     */
    public function getSiteLogo($colorScheme=false,$url=null,$img=null)
    {
        if (!isset($url))
            $url = app()->urlManager->getHomeUrl();
        
        
        if (!isset($img)){
            if (is_bool($colorScheme))
                $colorScheme = ($colorScheme==true) ? $this->colorScheme : $this->invertedColorScheme;
                
            $img = 'corporate-logo-'.$colorScheme.'.png';
        }
        
        return $this->hasSiteLogo 
               ? l(CHtml::image(app()->urlManager->createCdnUrl('/images/'.$img), app()->name, ['class'=>'corporate-logo']),$url)
               : l(app()->name,$url);
    }
    
    public function getHasSiteLogo()
    {
        return param('SITE_LOGO')!=null ? param('SITE_LOGO') : false;
    }
    
    public function setColorScheme($color)
    {
        $this->colorScheme = $color;
    }
}
